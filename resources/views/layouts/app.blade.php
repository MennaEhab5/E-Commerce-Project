<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
    
    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Extra head --}}
    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-100">

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- محتوى الصفحات --}}
    <main class="max-w-7xl mx-auto mt-6 px-4">
         @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Scripts --}}
    @stack('scripts')
</body>
</html>
