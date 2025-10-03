@extends('layouts.app')

@section('title', 'Orders Page')

@section('content')
<div class="container mx-auto p-6 space-y-4">
    <h1 class="text-2xl font-bold mb-4">My Orders</h1>

    @if($orders->isEmpty())
        <div class="bg-gray-100 p-6 rounded-md text-center flex flex-col items-center justify-center space-y-6 shadow-md">
            <p class="text-gray-600">You have no orders yet</p>
            <a href="{{ route('products.index') }}" class="mt-3 inline-block bg-green-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-green-600 transition">
                Browse Products
            </a>
        </div>
    @else
        @foreach($orders as $order)
            <div class="order p-4 border-2 border-gray-300 rounded-lg">
                <header class="flex justify-between items-center">
                    <div>
                        <h2 class="text-gray-500 text-sm">Order ID</h2>
                        <span class="text-lg font-semibold text-gray-700">#{{ $order->id }}</span>
                    </div>
                    <div class="space-x-2">
                        @if($order->is_paid)
                            <span class="inline-block px-3 py-1 bg-lime-500 text-white font-semibold rounded-full">تم الدفع</span>
                        @else
                            <span class="inline-block px-3 py-1 bg-red-500 text-white font-semibold rounded-full">غير مدفوع</span>
                        @endif

                        @if($order->is_delivered)
                            <span class="inline-block px-3 py-1 bg-lime-500 text-white font-semibold rounded-full">تم الإستلام</span>
                        @else
                            <span class="inline-block px-3 py-1 bg-blue-500 text-white font-semibold rounded-full">قيد التوصيل</span>
                        @endif
                    </div>
                </header>

                <div class="mt-4 grid md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    @foreach($order->cartItems as $item)
                        <div class="product-item border-2 border-gray-300 rounded-lg overflow-hidden">
                            <img src="{{ $item->product->image_cover }}" alt="{{ $item->product->title }}" class="w-full h-40 object-cover">
                            <div class="p-2">
                                <h3 class="text-sm font-semibold line-clamp-2">
                                    <a href="{{ route('products.show', $item->product->id) }}">
                                        {{ $item->product->title }}
                                    </a>
                                </h3>
                                <div class="flex justify-between items-center mt-2 text-sm">
                                    <p><span class="font-bold underline mr-1">Count:</span> {{ $item->count }}</p>
                                    <p class="font-semibold">
                                        <span class="text-green-600 font-bold mr-1">{{ $item->price }}</span>L.E
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="text-lg mt-4">Your Total Order Price is 
                    <span class="mx-1 font-bold text-green-600">{{ $order->total_order_price }}</span> L.E
                </p>
            </div>
        @endforeach
    @endif
</div>
@endsection
