<div>
    <nav class="bg-slate-100 shadow-sm top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="text-xl font-bold">
                <img src="{{ asset('assets/images/freshcart-logo.svg') }}" alt="FreshCart Logo" class="w-full max-w-[150px]">
            </a>

            {{-- القائمة الكبيرة (Desktop) --}}
            <ul class="hidden md:flex items-center gap-6 ms-10">
                @php
                $desktopLinks = [
                ['route' => 'home', 'label' => 'Home'],
                ['route' => 'products.index', 'label' => 'Products'],
                ['route' => 'categories.index', 'label' => 'Categories'],
                ['route' => 'brands.index', 'label' => 'Brands'],
                ['route' => 'orders.index', 'label' => 'Orders'],
                ['route' => 'wishlist.index', 'label' => 'Wishlist'],
                ['route' => 'cart.index', 'label' => 'Cart'], // لو عندك كارت
                ];
                @endphp


                @foreach($desktopLinks as $link)
                <li>
                    <a href="{{ route($link['route']) }}"
                        class="relative text-black text-lg group font-normal
            {{ Route::currentRouteName() == $link['route'] ? 'font-bold' : '' }}">
                        {{ $link['label'] }}
                        <span class="absolute bottom-0 left-0 h-[2px] w-0 bg-green-500 transition-all duration-300 ease-in-out group-hover:w-full
            {{ Route::currentRouteName() == $link['route'] ? 'w-full' : '' }}"></span>
                    </a>
                </li>
                @endforeach
            </ul>


            {{-- أيقونة السلة --}}
            <a href="{{ route('cart.index') }}" class="relative hidden md:block">
                <i class="fa-solid fa-cart-shopping text-lg"></i>
                <div class="absolute right-0 top-0 h-5 w-5 bg-green-500 text-white rounded-full flex justify-center items-center translate-x-1/2 -translate-y-1/2">
                    <span class="text-sm font-semibold">0</span>
                </div>
            </a>

            {{-- روابط التواصل (Desktop) --}}
            <div class="flex items-center gap-6">
                <ul class="hidden md:flex items-center gap-4">
                    @php
                    $socials = [
                    ['url'=>'https://instagram.com','icon'=>'fa-instagram','hover'=>'hover:text-pink-500'],
                    ['url'=>'https://facebook.com','icon'=>'fa-facebook','hover'=>'hover:text-blue-600'],
                    ['url'=>'https://tiktok.com','icon'=>'fa-tiktok','hover'=>'hover:text-black'],
                    ['url'=>'https://twitter.com','icon'=>'fa-twitter','hover'=>'hover:text-sky-500'],
                    ['url'=>'https://linkedin.com','icon'=>'fa-linkedin','hover'=>'hover:text-blue-700'],
                    ['url'=>'https://youtube.com','icon'=>'fa-youtube','hover'=>'hover:text-red-600'],
                    ];
                    @endphp
                    @foreach($socials as $social)
                    <li>
                        <a href="{{ $social['url'] }}" target="_blank" class="text-black transition-colors duration-300 {{ $social['hover'] }}">
                            <i class="fa-brands {{ $social['icon'] }} text-sm"></i>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Login / Logout --}}
            @auth
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-black hover:text-red-600 text-sm transition-colors duration-300 ease-in-out">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="text-black font-semibold text-lg">Login</a>
            @endauth

            {{-- زر القائمة للموبايل --}}
            <button class="md:hidden text-2xl" id="menu-btn">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        {{-- القائمة الجانبية (Mobile) --}}
        <div id="mobile-menu" class="hidden md:hidden bg-slate-200 p-4 space-y-4">
            @foreach($desktopLinks as $link)
            <a href="{{ route($link['route']) }}" class="block text-black text-lg font-semibold relative group">
                {{ $link['label'] }}
                <span class="absolute bottom-0 left-0 h-[2px] w-0 bg-green-500 transition-all duration-300 ease-in-out group-hover:w-full"></span>
            </a>
            @endforeach

            <a href="{{ route('cart.index') }}" class="block text-black text-lg relative group">
                Cart
                <span class="absolute bottom-0 left-0 h-[2px] w-0 bg-green-500 transition-all duration-300 ease-in-out group-hover:w-full"></span>
            </a>

            <div class="flex gap-3 mt-3">
                @foreach($socials as $social)
                <a href="{{ $social['url'] }}" target="_blank" class="text-black transition-colors duration-300 {{ $social['hover'] }} cursor-pointer">
                    <i class="fa-brands {{ $social['icon'] }} text-xs"></i>
                </a>
                @endforeach
            </div>

            @auth
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="text-red-600 font-semibold text-lg">Logout</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="text-black font-semibold text-lg block mt-3">Login</a>
            @endauth
        </div>
    </nav>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const menuBtn = document.getElementById("menu-btn");
            const mobileMenu = document.getElementById("mobile-menu");
            menuBtn.addEventListener("click", () => {
                mobileMenu.classList.toggle("hidden");
            });
        });
    </script>
</div>