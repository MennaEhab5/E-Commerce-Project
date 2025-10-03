<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
{
    $cart = auth()->user()->cart()->with('products')->first();

    if (!$cart) {
        $cartItems = collect();
        $cartTotal = 0;
    } else {
        $cartItems = $cart->products;
        $cartTotal = $cartItems->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });
    }

    return view('cart.index', compact('cartItems', 'cartTotal'));
}

    public function add(Product $product)
    {
        $cart = auth()->user()->cart()->firstOrCreate([]);

        // لو المنتج موجود نزود الكمية، لو مش موجود نضيفه
        $existing = $cart->products()->where('product_id', $product->id)->first();
        if ($existing) {
            $cart->products()->updateExistingPivot($product->id, [
                'quantity' => $existing->pivot->quantity + 1
            ]);
        } else {
            $cart->products()->attach($product->id, ['quantity' => 1]);
        }

        return redirect()->back()->with('success', 'Product added to cart');
    }

    public function remove(Product $product)
    {
        $cart = auth()->user()->cart;
        if ($cart) {
            $cart->products()->detach($product->id);
        }

        return redirect()->back()->with('success', 'Product removed from cart');
    }

    public function update(Request $request, Product $product)
    {
        $cart = auth()->user()->cart;
        if ($cart) {
            $cart->products()->updateExistingPivot($product->id, [
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated');
    }
}
