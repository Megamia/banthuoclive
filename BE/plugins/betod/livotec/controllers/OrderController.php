<?php
namespace Betod\Livotec\Controllers;

use Betod\Livotec\UpdateOrderStatusJob;
use Betod\Livotec\Models\OrderDetail;
use Betod\Livotec\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Betod\Livotec\Models\Orders;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'subdistrict' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'diffname' => 'nullable|string|max:255',
            'diffphone' => 'nullable|string|max:20',
            'diffprovince' => 'nullable|string|max:255',
            'diffdistrict' => 'nullable|string|max:255',
            'diffsubdistrict' => 'nullable|string|max:255',
            'diffaddress' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'terms' => 'required|boolean',
            'paymenttype' => 'required|integer',
            'differentaddresschecked' => 'required|boolean',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $totalPrice = array_reduce($validatedData['items'], function ($sum, $item) {
            return $sum + $item['price'] * $item['quantity'];
        }, 0);

        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $propertyData = Arr::except($validatedData, ['items', 'differentaddresschecked', 'terms', 'user_id']);

        $order = Orders::create([
            'user_id' => $validatedData['user_id'],
            'order_code' => $orderCode,
            'price' => $totalPrice,
            'property' => $propertyData,
        ]);

        foreach ($validatedData['items'] as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => ($item['price'] * $item['quantity']),
            ]);
            $product = Product::find($item['product_id']);

            if ($product) {
                $product->stock -= $item['quantity'];
                $product->sold_out += $item['quantity'];
                $product->save();
            }
        }

        $ghnResponse = $this->createGhnOrder($order);

        if (isset($ghnResponse['code']) && $ghnResponse['code'] === 200) {
            $order->ghn_order_code = $ghnResponse['data']['order_code'] ?? 'DEFAULT_CODE';
            $order->save();
        } else {
            $order->ghn_order_code = 'DEFAULT_CODE';
            $order->save();
        }

        UpdateOrderStatusJob::dispatch($order->id)->delay(now()->addMinutes(5));

        return response()->json([
            'message' => 'Order created successfully!',
            'order_code' => $order->order_code,
            'ghn_order_code' => $ghnResponse['data']['order_code'] ?? 'DEFAULT_CODE',
            'data' => $order
        ], 201);
    }


    private function getProvinceId($provinceName)
    {
        $apiKey = env('GHN_API_KEY');

        $response = Http::withHeaders([
            'Token' => $apiKey,
            'Content-Type' => 'application/json',
        ])->get('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province');

        $provinceList = $response->json();

        if (!isset($provinceList['data']) || !is_array($provinceList['data'])) {
            \Log::error('GHN Province List Fetch Error:', $provinceList);
            return null;
        }

        foreach ($provinceList['data'] as $province) {
            if (isset($province['NameExtension']) && is_array($province['NameExtension'])) {
                foreach ($province['NameExtension'] as $name) {
                    if (strcasecmp(trim($name), trim($provinceName)) === 0) {
                        return $province['ProvinceID'];
                    }
                }
            }

            if (isset($province['ProvinceName']) && strcasecmp(trim($province['ProvinceName']), trim($provinceName)) === 0) {
                return $province['ProvinceID'];
            }
        }

        \Log::error("GHN Province ID not found for: " . $provinceName);
        return null;
    }

    private function getDistrictId($provinceId, $districtName)
    {
        $apiKey = env('GHN_API_KEY');

        $response = Http::withHeaders([
            'Token' => $apiKey,
            'Content-Type' => 'application/json',
        ])->get("https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/district?province_id={$provinceId}");

        $districtList = $response->json();

        if (!isset($districtList['data']) || !is_array($districtList['data'])) {
            \Log::error('GHN District List Fetch Error:', $districtList);
            return null;
        }

        foreach ($districtList['data'] as $district) {
            if (strcasecmp(trim($district['DistrictName']), trim($districtName)) === 0) {
                return $district['DistrictID'];
            }
        }

        \Log::error("GHN District ID not found for: " . $districtName);
        return null;
    }

    private function getWardCode($districtId, $wardName)
    {
        $apiKey = env('GHN_API_KEY');

        $response = Http::withHeaders([
            'Token' => $apiKey,
            'Content-Type' => 'application/json',
        ])->get("https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/ward?district_id={$districtId}");

        $wardList = $response->json();

        if (!isset($wardList['data']) || !is_array($wardList['data'])) {
            \Log::error('GHN Ward List Fetch Error:', $wardList);
            return null;
        }

        foreach ($wardList['data'] as $ward) {
            if (strcasecmp(trim($ward['WardName']), trim($wardName)) === 0) {
                return $ward['WardCode'];
            }
        }

        \Log::error("GHN Ward Code not found for: " . $wardName);
        return null;
    }

    private function createGhnOrder($order)
    {
        \Log::info('Order Data:', $order->toArray());

        $apiKey = env('GHN_API_KEY');
        $property = $order->property;

        $provinceName = trim($property['province']);
        $provinceID = $this->getProvinceId($provinceName);
        $districtName = trim($property['district']);
        $subdistrictName = trim($property['subdistrict']);

        if (!$provinceID) {
            \Log::error("GHN Province ID not found for: " . $provinceName);
            return ['code' => 400, 'message' => 'Invalid province'];
        }

        $districtID = $this->getDistrictId($provinceID, $districtName);
        if (!$districtID) {
            \Log::error("GHN District ID not found for: " . $districtName);
            return ['code' => 400, 'message' => 'Invalid district'];
        }

        $wardCode = $this->getWardCode($districtID, $subdistrictName);
        if (!$wardCode) {
            \Log::error("GHN Ward Code not found for: " . $subdistrictName);
            return ['code' => 400, 'message' => 'Invalid ward'];
        }

        $senderProvinceName = 'Thành phố Hà Nội';
        $senderProvinceID = $this->getProvinceId($senderProvinceName);

        $senderDistrictName = 'Quận Nam Từ Liêm';
        $senderDistrictID = $this->getDistrictId($senderProvinceID, $senderDistrictName);

        $senderWardName = 'Phường Mỹ Đình 1';
        $senderWardCode = $this->getWardCode($senderDistrictID, $senderWardName);

        if (!$senderProvinceID || !$senderDistrictID || !$senderWardCode) {
            \Log::error("Sender's address details are invalid.");
            return ['code' => 400, 'message' => 'Sender address details are invalid.'];
        }

        $orderDetails = OrderDetail::where('order_id', $order->id)->get();
        $items = $orderDetails->map(function ($item) {
            $product = Product::find($item->product_id);

            if ($product) {
                return [
                    'name' => $product->name,  
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }

            return [
                'name' => 'Unknown Product',  
                'quantity' => $item->quantity,
                'price' => $item->price,
            ];
        })->toArray();


        $response = Http::withHeaders([
            'Token' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/create', [
                    'payment_type_id' => 1,
                    'note' => $property['notes'] ?? '',
                    'to_name' => $property['name'],
                    'to_phone' => $property['phone'],
                    'to_address' => $property['address'],
                    'to_ward_code' => $wardCode,
                    'to_district_id' => $districtID,
                    'to_province_id' => $provinceID,
                    'required_note' => 'CHOXEMHANGKHONGTHU',
                    'from_name' => 'Megami Shop',
                    'from_phone' => '0869208950',
                    'from_address' => 'Hà Nội',
                    'from_ward_code' => $senderWardCode,
                    'from_district_id' => $senderDistrictID,
                    'from_province_id' => $senderProvinceID,
                    'client_order_code' => $order->order_code,
                    'cod_amount' => $order->price,
                    'weight' => 500,
                    'length' => 30,
                    'width' => 20,
                    'height' => 10,
                    'service_type_id' => 2,
                    'items' => $items,
                ]);

        $ghnResponse = $response->json();
        \Log::info('GHN API Response:', $ghnResponse);

        return $ghnResponse;
    }

}