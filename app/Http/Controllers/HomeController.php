<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // كل المتودز هتحتاج تسجيل دخول
    public function __construct()
    {
        $this->middleware('auth');
    }

    // صفحة Home
    public function index()
    {
        return view('home'); // resources/views/home.blade.php
    }
}
