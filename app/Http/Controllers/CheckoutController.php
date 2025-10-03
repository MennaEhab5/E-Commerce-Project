<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->cart ?? null;
        return view('checkout.index', compact('cart'));
    }

    public function cash()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart || $cart->products->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        $order = new Order();
        $order->user_id = $user->id;
        $order->total_price = $cart->products->sum(function($p) {
            return $p->pivot->quantity * $p->price;
        });
        $order->status = 'pending';
        $order->save();

        foreach ($cart->products as $product) {
            $order->products()->attach($product->id, [
                'quantity' => $product->pivot->quantity,
                'price' => $product->price
            ]);
        }

        $cart->products()->detach();

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }
}
