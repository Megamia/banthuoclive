<?php
namespace Betod\Livotec\Controllers;

use Betod\Livotec\UpdateOrderStatusJob;
use Betod\Livotec\Models\OrderDetail;
use Betod\Livotec\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Betod\Livotec\Models\Orders;
use Illuminate\Http\JsonResponse;
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

        if (is_array($ghnResponse) && isset($ghnResponse['code']) && $ghnResponse['code'] === 200) {
            $order->ghn_order_code = $ghnResponse['data']['order_code'] ?? 'DEFAULT_CODE';
            $order->save();
        } elseif ($ghnResponse instanceof JsonResponse) {
            $responseData = $ghnResponse->getData(true);
            if (isset($responseData['message'])) {
                return response()->json(['code' => 400, 'message' => $responseData['message']], 400);
            }
        } else {
            return response()->json(['code' => 400, 'message' => 'Tạo đơn hàng thất bại. Vui lòng thử lại sau.'], 400);
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

    private function isValidShippingArea($provinceName, $districtName, $subdistrictName)
    {
        $provinceID = $this->getProvinceId($provinceName);
        if (!$provinceID) {
            \Log::error("Province not found: " . $provinceName);
            return false;
        }

        $districtID = $this->getDistrictId($provinceID, $districtName);
        if (!$districtID) {
            \Log::error("District not found: " . $districtName);
            return false;
        }

        $wardCode = $this->getWardCode($districtID, $subdistrictName);
        if (!$wardCode) {
            \Log::error("Ward not found: " . $subdistrictName);
            return false;
        }

        return true;
    }

    private function createGhnOrder($order)
    {
        \Log::info('Order Data:', $order->toArray());

        $apiKey = env('GHN_API_KEY');
        $property = $order->property;

        $provinceName = trim($property['province']);
        $districtName = trim($property['district']);
        $subdistrictName = trim($property['subdistrict']);

        if (!$this->isValidShippingArea($provinceName, $districtName, $subdistrictName)) {
            \Log::error("Invalid shipping area for order: " . $order->order_code);
            return response()->json(['code' => 400, 'message' => 'Invalid shipping area'], 400);
        }

        $provinceID = $this->getProvinceId($provinceName);
        if (!$provinceID) {
            \Log::error("GHN Province ID not found for: " . $provinceName);
            return response()->json(['code' => 400, 'message' => 'Invalid province'], 400);
        }

        $districtID = $this->getDistrictId($provinceID, $districtName);
        if (!$districtID) {
            \Log::error("GHN District ID not found for: " . $districtName);
            return response()->json(['code' => 400, 'message' => 'Invalid district'], 400);
        }

        $wardCode = $this->getWardCode($districtID, $subdistrictName);
        if (!$wardCode) {
            \Log::error("GHN Ward Code not found for: " . $subdistrictName);
            return response()->json(['code' => 400, 'message' => 'Invalid ward'], 400);
        }

        $senderProvinceName = 'Thành phố Hà Nội';
        $senderProvinceID = $this->getProvinceId($senderProvinceName);
        if (!$senderProvinceID) {
            \Log::error("Sender Province ID not found: " . $senderProvinceName);
            return response()->json(['code' => 400, 'message' => 'Sender province invalid'], 400);
        }

        $senderDistrictName = 'Quận Nam Từ Liêm';
        $senderDistrictID = $this->getDistrictId($senderProvinceID, $senderDistrictName);
        if (!$senderDistrictID) {
            \Log::error("Sender District ID not found: " . $senderDistrictName);
            return response()->json(['code' => 400, 'message' => 'Sender district invalid'], 400);
        }

        $senderWardName = 'Phường Mỹ Đình 1';
        $senderWardCode = $this->getWardCode($senderDistrictID, $senderWardName);
        if (!$senderWardCode) {
            \Log::error("Sender Ward Code not found: " . $senderWardName);
            return response()->json(['code' => 400, 'message' => 'Sender ward invalid'], 400);
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
                    'payment_type_id' => $order->property['paymenttype'],
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
        \Log::error('GHN API response: ' . $response);

        if ($response->failed()) {
            \Log::error('GHN API response error: ' . $response->body());
            return response()->json(['code' => 400, 'message' => 'Khu vực này hiện tại đang quá tải không thể tạo đơn, mong quý khách thông cảm và tạo lại sau!'], 400);
        } else {
            \Log::info('GHN order created successfully: ' . $response->json());
            return $response->json();
        }
    }



}