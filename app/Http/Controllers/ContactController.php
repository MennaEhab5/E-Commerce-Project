<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    // لو عايزة صفحة contact مستقلة
    public function showForm()
    {
        return view('contact'); // لو فيه صفحة مستقلة
    }

    // ارسال الفورم (Ajax)
    public function send(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // ارسال الايميل
        Mail::to('menna28112005@gmail.com')
            ->send(new ContactFormMail($validated));

        // Response JSON
        return response()->json([
            'message' => '✅ Your message has been sent successfully!'
        ]);
    }
}
