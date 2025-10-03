<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index()
    {
        $response = Http::get('https://ecommerce.routemisr.com/api/v1/products');
        $products = $response->json()['data'] ?? [];
        return view('products', compact('products'));
    }

    public function show($id)
    {
        $response = Http::get("https://ecommerce.routemisr.com/api/v1/products/{$id}");
        $product = $response->json()['data'] ?? [];

        $relatedProducts = [];
        if(!empty($product)) {
            $relatedResponse = Http::get("https://ecommerce.routemisr.com/api/v1/products?category[in]={$product['category']['_id']}");
            $relatedProducts = $relatedResponse->json()['data'] ?? [];
        }

        return view('productDetails', compact('product', 'relatedProducts'));
    }
}
