<?php
namespace Betod\Livotec\Controllers;

use Betod\Livotec\Models\OrderDetail;
use Betod\Livotec\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Betod\Livotec\Models\Orders;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        // Validate dữ liệu đầu vào
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

        // Tạo mã đơn hàng
        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $propertyData = Arr::except($validatedData, ['items', 'differentaddresschecked', 'terms', 'user_id']);

        // Chuyển dữ liệu thành JSON trước khi lưu vào cột text
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
                'price' => ($item['price'] * $item['quantity']), // Tổng giá của từng sản phẩm
            ]);
            $product = Product::find($item['product_id']);

            if ($product) {
                $product->stock -= $item['quantity']; 
                $product->sold_out += $item['quantity']; 
                $product->save();
            }
        }

        return response()->json([
            'message' => 'Order created successfully!',
            'order_code' => $order->order_code
        ], 201);
    }
}