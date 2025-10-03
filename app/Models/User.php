<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name','email','password'];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // علاقة الكارت باليوزر
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    // علاقة الويشليست باليوزر
    public function wishlist()
    {
        return $this->hasOne(Wishlist::class);
    }

    // علاقة الاوردرز باليوزر
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
