<?php

use Betod\Livotec\Models\Orders;
use Betod\Livotec\Models\Product;
use Betod\Livotec\Models\Category;
use Betod\Livotec\Controllers\PayPalController;

Route::group(['prefix' => 'apiProduct'], function () {
    Route::get('products', function () {
        $product = Product::with(['image', 'category'])->get();
        return $product;
    });
    Route::get("allProduct", function () {
        $allProduct = Product::with(['gallery', 'image', 'category.parent', 'post'])->get();

        if ($allProduct->isNotEmpty()) {
            return response()->json(['allProduct' => $allProduct, 'status' => 1]);
        } else {
            return response()->json(['message' => 'No data', 'status' => 0]);
        }
    });


    Route::get('navProducts/{slug}', function ($slug) {
        $category = Category::with(['children'])->where('slug', $slug)->first();

        if ($category) {
            $categoryIds = $category->getAllChildrenAndSelf()->pluck('id');
            $products = Product::with(['image', 'category'])
                ->whereIn('category_id', $categoryIds)
                ->get();
            if ($category && $products) {
                return response()->json([
                    'category' => $category,
                    'products' => $products,
                    'status' => 1
                ]);
            } else {
                return response()->json(['status' => 0, 'message' => 'No data']);
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'No data']);
        }
    });

    Route::get('product/{slug}', function ($slug) {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $products = Product::with(['gallery', 'image', 'category.parent', 'post'])
            ->where('category_id', $category->id)
            ->get();

        return response()->json($products);
    });

    Route::get('detailProduct/{slug}', function ($slug) {
        $product = Product::with(['gallery', 'image', 'category.parent', 'post'])->where('slug', $slug)->first();
        if ($product) {
            return $product;
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    });

});

Route::group(['prefix' => 'apiOrder'], function () {
    Route::post('createOrder', 'Betod\Livotec\Controllers\OrderController@createOrder');
});


Route::group(['prefix' => 'apiCategory'], function () {
    // Route::get('category/{slug1}/{slug2?}', function ($slug1, $slug2 = null) {
    //     // Truy vấn category cha theo slug1
    //     $category = Category::with('image', 'filters')->where('slug', $slug1)->first();

    //     if (!$category) {
    //         return response()->json(['message' => 'Category not found'], 404);
    //     }

    //     // Nếu có slug2, tìm category con theo slug2
    //     if ($slug2) {
    //         $subCategory = $category->children()->with('image', 'filters')->where('slug', $slug2)->first();

    //         if (!$subCategory) {
    //             return response()->json(['message' => 'Sub-category not found'], 404);
    //         }

    //         // Chỉ lấy sản phẩm của category con
    //         $products = Product::with('image')
    //             ->where('category_id', $subCategory->id)
    //             ->get();

    //         return response()->json([
    //             'category' => $subCategory,
    //             'products' => $products,
    //         ]);
    //     }

    //     // Nếu không có slug2, lấy sản phẩm của category cha và các category con
    //     $categoryIds = $category->getAllChildrenAndSelf()->pluck('id');
    //     $products = Product::with('image')
    //         ->whereIn('category_id', $categoryIds)
    //         ->get();

    //     return response()->json([
    //         'category' => $category,
    //         'products' => $products,
    //     ]);
    // });

    Route::get('allCategory', function () {
        $allCategory = Category::with(['image', 'filters', 'children'])->get();

        if ($allCategory->isEmpty()) {
            return response()->json(['message' => 'No categories found', 'status' => 0]);
        }

        // $allCategory->each(function ($category) {
        //     $categoryIds = $category->getAllChildrenAndSelf()->pluck('id');
        //     $products = Product::with('image')
        //         ->whereIn('category_id', $categoryIds)
        //         ->get();

        //     $category->setAttribute('products', $products);
        // });

        return response()->json([
            'allCategory' => $allCategory,
            'status' => 1
        ]);
    });


    Route::get('allCategoryParent', function () {
        $allCategoryParent = Category::whereNull('parent_id')->get();

        if ($allCategoryParent->isNotEmpty()) {
            return response()->json(['allCategoryParent' => $allCategoryParent, 'status' => 1]);
        } else {
            return response()->json(['allCategoryParent' => 'No data', 'status' => 0]);
        }
    });
});

Route::group(['prefix' => 'apiPaypal'], function () {
    // Route để tạo đơn hàng
    Route::post('createOrder', [PayPalController::class, 'createOrder']);

    // Route để xác nhận thanh toán
    Route::post('captureOrder', [PayPalController::class, 'captureOrder']);
});

Route::group(['prefix' => 'apiOrder'], function () {
    Route::get('order/{order_code}', function ($order_code) {
        $data = Orders::with('orderdetail.product')->where('order_code', $order_code)->first();
        return $data;
    });
});

