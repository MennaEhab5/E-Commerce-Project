<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function carts()
    {
        return $this->belongsToMany(Cart::class)->withPivot('quantity');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price');
    }
    public function wishlists()
    {
        return $this->belongsToMany(Wishlist::class);
    }
}
