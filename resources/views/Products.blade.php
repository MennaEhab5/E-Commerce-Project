@extends('layouts.app')

@section('content')

@push('head')
<style>
    .section {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
</style>
@endpush

<div x-data="productsPage()" x-init="init()" class="space-y-6 px-4 md:px-8 mt-6 relative mb-24">

    <header class="text-center font-bold text-4xl text-primary-700 mb-6 section">
        All Products
    </header>

    {{-- Search --}}
    <div class="section">
        <input type="text" class="rounded-md border border-transparent focus:border-gray-300 focus:ring-1 focus:ring-gray-500 outline-none p-2 w-full transition" placeholder="Search Products..." x-model="searchTerm" @input="debounceSearch()">
    </div>

    {{-- Products Grid --}}
    <div class="grid sm:gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 section">
        <template x-for="product in products" :key="product._id">
            <div class="border p-2 rounded shadow hover:shadow-lg hover:-translate-y-1 transition transform duration-300 bg-white cursor-pointer relative group">
                <div class="relative">
                    <img :src="product.image" class="w-full h-48 object-cover rounded mb-2">
                    <div class="absolute inset-0 flex justify-center items-center gap-4 bg-slate-400 bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <!-- Wishlist -->
                        <a
                            href="/wishlist"
                            class="icon cursor-pointer w-8 h-8 rounded-full bg-primary-700 text-white flex justify-center items-center hover:bg-red-600 transition ease-in-out duration-300"
                            @click.stop="addToWishlist(product._id)">
                            <i class="fa-solid fa-heart"></i>
                        </a>

                        <!-- Cart -->
                        <a
                            href="/cart"
                            class="icon cursor-pointer w-8 h-8 rounded-full bg-primary-700 text-white flex justify-center items-center hover:bg-green-600 transition ease-in-out duration-300"
                            @click.stop="addToCart(product._id)">
                            <i class="fa-solid fa-cart-plus"></i>
                        </a>


                        <!-- View Details -->
                        <a :href="'/products/' + product._id" class="icon w-8 h-8 rounded-full bg-primary-700 text-white flex justify-center items-center hover:bg-blue-600 transition ease-in-out duration-300">
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

        <template x-if="products.length === 0 && !loading">
            <p class="font-semibold mt-6 col-span-full text-center">No products found.</p>
        </template>
    </div>

    {{-- Pagination --}}
    <div class="pagination flex justify-center items-center gap-4 mt-4 section">
        <button class="w-20 h-10 rounded-full bg-gray-300 font-bold" :disabled="currentPage === 1" @click="prevPage()">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <span class="font-bold text-lg">Page <span x-text="currentPage"></span></span>
        <button class="w-20 h-10 rounded-full bg-gray-300 font-bold" @click="nextPage()">
            <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productsPage', () => ({
            products: [],
            currentPage: 1,
            searchTerm: '',
            debouncedSearch: '',
            timer: null,
            loading: true,

            init() {
                this.getProducts();
            },

            debounceSearch() {
                clearTimeout(this.timer);
                this.timer = setTimeout(() => {
                    this.debouncedSearch = this.searchTerm;
                    this.currentPage = 1;
                    this.getProducts();
                }, 500); // سرعة أفضل للبحث
            },

            getProducts() {
                this.loading = true;
                axios.get(`https://ecommerce.routemisr.com/api/v1/products?page=${this.currentPage}`)
                    .then(res => {
                        let allProducts = res.data.data.map(p => {
                            let image = '';
                            if (p.image) image = p.image.startsWith('http') ? p.image : 'https://ecommerce.routemisr.com' + p.image;
                            else if (p.imageCover) image = p.imageCover.startsWith('http') ? p.imageCover : 'https://ecommerce.routemisr.com' + p.imageCover;
                            return {
                                ...p,
                                image
                            };
                        });

                        // فلترة المنتجات في الفرونت حسب الحروف المكتوبة
                        if (this.debouncedSearch) {
                            const searchLower = this.debouncedSearch.toLowerCase();
                            this.products = allProducts.filter(p => p.title.toLowerCase().includes(searchLower));
                        } else {
                            this.products = allProducts;
                        }
                    })
                    .catch(err => console.error(err))
                    .finally(() => {
                        this.loading = false
                    });
            },


            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.getProducts();
                }
            },

            nextPage() {
                this.currentPage++;
                this.getProducts();
            },

            addToWishlist(productId) {
                const token = localStorage.getItem('userToken'); // أو من session حسب نظامك
                if (!token) {
                    window.location.href = '/login';
                    return;
                }

                axios.post('https://ecommerce.routemisr.com/api/v1/wishlist', {
                        productId
                    }, {
                        headers: {
                            token
                        }
                    })
                    .then(res => {
                        alert('✅ Product added to Wishlist!');
                        console.log(res.data);
                    })
                    .catch(err => {
                        console.error(err);
                        alert('❌ Failed to add to Wishlist');
                    });
            },

            addToCart(productId) {
                const token = localStorage.getItem('userToken');
                if (!token) {
                    window.location.href = '/login';
                    return;
                }

                axios.post('https://ecommerce.routemisr.com/api/v1/cart', {
                        productId
                    }, {
                        headers: {
                            token
                        }
                    })
                    .then(res => {
                        alert('✅ Product added to Cart!');
                        console.log(res.data);
                    })
                    .catch(err => {
                        console.error(err);
                        alert('❌ Failed to add to Cart');
                    });
            },


        }));
    });
</script>
@endpush

@endsection