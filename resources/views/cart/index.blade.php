@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Your Shopping Cart</h1>

    @if($cartItems->isEmpty())
    <div class="bg-gray-100 p-6 rounded-md text-center flex flex-col items-center justify-center space-y-6 shadow-md">
        <p class="text-gray-700 text-lg font-medium">
            Your cart is empty. Start shopping now!
        </p>

        <a href="{{ route('products.index') }}"
            class="inline-block bg-green-500 text-white font-semibold px-5 py-2 rounded-lg shadow hover:bg-green-600 hover:shadow-lg transition duration-300 ease-in-out">
             Back to Products
        </a>
    </div>

    @else
    <div class="space-y-4">
        @foreach($cartItems as $product)
        <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg">
            <div class="flex items-center gap-4">
                <img src="{{ $product->imageCover }}" alt="{{ $product->title }}" class="w-24 h-24 object-cover rounded">
                <div>
                    <h3 class="text-lg font-semibold">{{ $product->title }}</h3>
                    <p class="text-gray-500">{{ $product->category->name ?? 'No Category' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <form action="{{ route('cart.update', $product->id) }}" method="POST" class="flex items-center gap-1">
                    @csrf
                    <input type="number" name="quantity" value="{{ $product->pivot->quantity }}" min="1" class="w-16 text-center rounded border p-1">
                    <button type="submit" class="btn bg-yellow-500 hover:bg-yellow-600 text-white px-3">Update</button>
                </form>

                <form action="{{ route('cart.remove', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn bg-red-500 hover:bg-red-600 text-white px-3"><i class="fa fa-trash"></i></button>
                </form>
            </div>
            <span class="font-semibold">{{ $product->price * $product->pivot->quantity }} EGP</span>
        </div>
        @endforeach

        <div class="flex justify-between items-center mt-4">
            <h2 class="text-xl font-bold">Total: {{ $cartTotal }} EGP</h2>
            <a href="{{ route('checkout.index') }}" class="btn bg-green-600 hover:bg-green-700 text-white px-6 py-2">Proceed to Checkout</a>
        </div>
    </div>
    @endif
</div>
@endsection