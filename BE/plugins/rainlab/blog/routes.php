<?php

use RainLab\Blog\Models\Category;
use RainLab\Blog\Models\Post;

Route::group(['prefix' => 'apiPost'], function () {
    Route::get('allPost', function () {
        $allPost = Post::all();
        if ($allPost) {
            return response()->json(['data' => $allPost, 'status' => 1]);
        } else {
            return response()->json(['data' => 'No data', 'status' => 0]);
        }
    });

    Route::get('post/{slug}', function ($slug) {
        $post = Post::with(['categories', 'user'])->where('slug', $slug)->first();
        return $post;
    });
    Route::get('hotNews/{slugCategory}', function ($slugCategory) {
        $hotNews = Post::with(['featured_images', 'categories'])
            ->whereHas('categories', function ($query) use ($slugCategory) {
                $query->where('slug', $slugCategory);
            })
            ->get();

        return response()->json([
            'data' => $hotNews,
            'images' => $hotNews->map(function ($post) {
                return $post->featured_images ?? null; 
            }),
        ]);
    });

    Route::get('allPostCategory', function () {
        $allPostCategory = Category::all();
        if ($allPostCategory) {
            return response()->json(['data' => $allPostCategory, 'status' => 1]);
        } else {
            return response()->json(['data' => 'No data', 'status' => 0]);
        }
    });
});
