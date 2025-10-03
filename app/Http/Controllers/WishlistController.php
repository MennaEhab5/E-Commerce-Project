<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Auth::user()->wishlist()->with('products')->first();

        if (!$wishlist) {
            $wishlistItems = collect();
        } else {
            $wishlistItems = $wishlist->products;
        }

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function add(Product $product)
    {
        $wishlist = Auth::user()->wishlist()->firstOrCreate([]);

        if (!$wishlist->products()->where('product_id', $product->id)->exists()) {
            $wishlist->products()->attach($product->id);
        }

        return redirect()->back()->with('success', 'Product added to wishlist!');
    }

    public function remove(Product $product)
    {
        $wishlist = Auth::user()->wishlist;
        if ($wishlist) {
            $wishlist->products()->detach($product->id);
        }

        return redirect()->back()->with('success', 'Product removed from wishlist!');
    }
}
