@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">My Wishlist</h1>

    @if($wishlistItems->isEmpty())
        <div class="bg-gray-100 p-6 rounded-md text-center">
            <p>Your wishlist is empty. Start adding products you love!</p>
            <a href="{{ route('products.index') }}" class="btn bg-blue-600 text-white mt-3">Back to Products</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($wishlistItems as $item)
            <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg">
                <div class="flex items-center gap-4">
                    <img src="{{ $item->product->imageCover }}" alt="{{ $item->product->title }}" class="w-24 h-24 object-cover rounded">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $item->product->title }}</h3>
                        <p class="text-gray-500">{{ $item->product->category->name ?? 'No Category' }}</p>
                        <span class="font-semibold">{{ $item->product->price }} EGP</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('cart.add', $item->product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn bg-green-600 hover:bg-green-700 text-white px-4 py-2">Add to Cart</button>
                    </form>

                    <form action="{{ route('wishlist.remove', $item->product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn bg-red-500 hover:bg-red-600 text-white px-4 py-2"><i class="fa fa-trash"></i> Remove</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
