@extends('layouts.app')

@section('content')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<style>
    #home-swiper,
    #category-swiper {
        height: 520px;
    }
</style>
@endpush

<div x-data="homePage()" x-init="init()" class="space-y-8 relative px-4 md:px-8 mt-6 mb-24">

    {{-- Home Slider --}}
    <section class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="col-span-12 md:col-span-8">
            <div id="home-swiper" class="swiper rounded">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/images/slider-image-1.jpeg') }}" class="w-full h-full object-cover rounded" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/images/slider-image-2.jpeg') }}" class="w-full h-full object-cover rounded" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/images/slider-image-3.jpeg') }}" class="w-full h-full object-cover rounded" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 md:col-span-4 flex flex-col md:gap-2">
            <img src="{{ asset('assets/images/slider-image-1.jpeg') }}" class="w-full h-full object-cover rounded mb-2" alt="">
            <img src="{{ asset('assets/images/slider-image-2.jpeg') }}" class="w-full h-full object-cover rounded" alt="">
        </div>
    </section>

    {{-- Category Slider --}}
    <section class="my-0 gap-0">
        <h2 class="mb-5 text-lg text-gray-600 font-semibold">Shop Popular Categories</h2>
        <div id="category-swiper" class="swiper">
            <div class="swiper-wrapper" id="category-wrapper">
                {{-- JS سيضيف الكاتيجوري هنا --}}
            </div>
        </div>

        <h2 class="mb-5 text-lg text-gray-600 font-semibold">Popular Products</h2>
        {{-- Products Grid --}}
        <div class="grid sm:gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
            <template x-for="product in products" :key="product._id">
                <div class="border p-2 rounded shadow hover:shadow-lg hover:-translate-y-1 transition transform duration-300 bg-white cursor-pointer relative group">
                    <div class="relative">
                        <img :src="product.image" class="w-full h-48 object-cover rounded mb-2">
                                            <div class="absolute inset-0 flex justify-center items-center gap-4 bg-slate-400 bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300">

                            <a
                                href="/wishlist"
                                class="icon cursor-pointer w-8 h-8 rounded-full bg-primary-700 text-white flex justify-center items-center hover:bg-red-600 transition ease-in-out duration-400"
                                @click.stop="addToWishlist(product._id)">
                                <i class="fa-solid fa-heart"></i>
                            </a>

                            <!-- Cart -->
                            <a
                                href="/cart"
                                class="icon cursor-pointer w-8 h-8 rounded-full bg-primary-700 text-white flex justify-center items-center hover:bg-green-600 transition ease-in-out duration-400"
                                @click.stop="addToCart(product._id)">
                                <i class="fa-solid fa-cart-plus"></i>
                            </a>


                            <!-- View Details -->
                            <a :href="'/products/' + product._id" class="icon w-8 h-8 rounded-full bg-primary-700 text-white flex justify-center items-center hover:bg-blue-600 transition ease-in-out duration-400">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body space-y-3">
                        <header>
                            <h3 class="text-lg text-gray-600 font-semibold line-clamp-1">
                                <a :href="'/products/' + product._id" x-text="product.title"></a>
                            </h3>
                            <h4 class="text-green-500 font-semibold" x-text="product.category?.name"></h4>
                        </header>
                        <p class="text-gray-400 text-sm line-clamp-2" x-text="product.description"></p>
                        <div class="flex justify-between items-center">
                            <span x-text="product.price + ' L.E'"></span>
                            <div>
                                <i class="fa-solid fa-star mr-1 text-yellow-500"></i>
                                <span x-text="product.ratingAverage"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="products.length === 0">
                <p class="font-semibold mt-6 col-span-full text-center">No products found.</p>
            </template>
        </div>
    </section>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('homePage', () => ({
            categories: [],
            products: [],
            searchTerm: '',
            debouncedSearch: '',
            timer: null,

            init() {
                this.getCategories();
                this.getProducts();
                this.initHomeSwiper();
            },

            // Categories
            getCategories() {
                axios.get('https://ecommerce.routemisr.com/api/v1/categories')
                    .then(res => {
                        this.categories = res.data.data.map(cat => ({
                            ...cat,
                            image: cat.image.startsWith('http') ? cat.image : 'https://ecommerce.routemisr.com' + cat.image
                        }));
                        this.renderCategories();
                    })
                    .catch(err => console.error(err));
            },

            renderCategories() {
                const wrapper = document.getElementById('category-wrapper');
                wrapper.innerHTML = '';
                this.categories.forEach(cat => {
                    const slide = document.createElement('div');
                    slide.className = 'swiper-slide text-center';
                    slide.innerHTML = `
                    <div class="h-64">
                        <img class="w-full h-full object-cover rounded" src="${cat.image}" alt="">
                    </div>
                    <h3 class="mt-2 font-medium">${cat.name}</h3>
                `;
                    wrapper.appendChild(slide);
                });
                this.initCategorySwiper();
            },

            initHomeSwiper() {
                new Swiper('#home-swiper', {
                    slidesPerView: 1,
                    loop: true,
                    autoplay: {
                        delay: 3000
                    },
                });
            },

            initCategorySwiper() {
                new Swiper('#category-swiper', {
                    slidesPerView: 2,
                    loop: true,
                    autoplay: {
                        delay: 2500
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 4
                        },
                        1024: {
                            slidesPerView: 6
                        }
                    },
                });
            },

            getProducts() {
                let url = 'https://ecommerce.routemisr.com/api/v1/products?page=1';
                if (this.debouncedSearch) {
                    url = `https://ecommerce.routemisr.com/api/v1/products?title[regex]=${this.debouncedSearch}&title[options]=i`;
                }
                axios.get(url)
                    .then(res => {
                        this.products = res.data.data.map(p => {
                            let image = '';
                            if (p.image) image = p.image.startsWith('http') ? p.image : 'https://ecommerce.routemisr.com' + p.image;
                            else if (p.imageCover) image = p.imageCover.startsWith('http') ? p.imageCover : 'https://ecommerce.routemisr.com' + p.imageCover;
                            return {
                                ...p,
                                image
                            };
                        });
                    })
                    .catch(err => console.error(err));
            }

        }));
    });
</script>
@endpush

@endsection