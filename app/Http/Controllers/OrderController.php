<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // يجيب ID المستخدم الحالي

        // لو عندك موديل Order مرتبط بالـ user
        $orders = Order::with(['cartItems.product'])->where('user_id', $userId)->get();

        return view('orders.index', compact('orders'));
    }
}
