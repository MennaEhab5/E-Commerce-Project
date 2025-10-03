@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Checkout</h1>

    <form action="{{ route('checkout.cash') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block font-semibold">City</label>
                <input type="text" name="city" value="{{ old('city') }}" required class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block font-semibold">Phone</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block font-semibold">Address Details</label>
                <textarea name="details" rows="3" required class="w-full p-2 border rounded">{{ old('details') }}</textarea>
            </div>
        </div>

        <div class="mt-4 flex gap-4">
            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white px-6 py-2">Cash Order</button>
        </div>
    </form>

    <form action="{{ route('checkout.stripe') }}" method="POST" class="mt-4">
        @csrf
        <input type="hidden" name="city" value="{{ old('city') }}">
        <input type="hidden" name="phone" value="{{ old('phone') }}">
        <input type="hidden" name="details" value="{{ old('details') }}">
        <button type="submit" class="btn bg-green-600 hover:bg-green-700 text-white px-6 py-2">Pay Online</button>
    </form>
</div>
@endsection
