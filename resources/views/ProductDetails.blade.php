@extends('layouts.app')

@section('content')

@push('head')
<style>
    /* Loading overlay */
    .loading-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
    }

    /* Spinner */
    .spinner {
        border: 4px solid rgba(0,0,0,0.1);
        border-top-color: #3490dc;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    .section {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
</style>
@endpush

<div x-data="productDetailsPage()" x-init="init()" class="px-4 md:px-8 mt-6 space-y-8 my-24">

    <!-- Product Section -->
    <section class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <div class="md:col-span-2"></div>

        <!-- الصورة الكبيرة + Gallery -->
        <div class="md:col-span-4 space-y-4"> 
            <!-- الصورة الكبيرة -->
            <img :src="product.image" class="w-full h-85 object-cover rounded mb-4" 
                onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">

            <!-- Gallery 3 صور مصغرة -->
            <div class="grid grid-cols-4 gap-2">
                <template x-for="(img, index) in product.images" :key="index">
                    <img :src="img.startsWith('http') ? img : 'https://ecommerce.routemisr.com' + img"
                        class="w-full h-22 object-cover rounded cursor-pointer border hover:border-green-500 transition"
                        @click="product.image = img"
                        onerror="this.src='https://via.placeholder.com/150x150?text=No+Image'">
                </template>
            </div>
        </div>

        <div class="md:col-span-5 space-y-4">
            <h2 class="text-2xl font-semibold text-gray-600" x-text="product.title"></h2>
            <h3 class="text-green-500 font-semibold" x-text="product.category?.name"></h3>
            <p class="text-gray-400" x-text="product.description"></p>
            <div class="flex justify-between items-center">
                <span x-text="product.price + ' L.E'"></span>
                <div>
                    <i class="fa-solid fa-star mr-2 text-yellow-500"></i>
                    <span x-text="product.ratingAverage"></span>
                </div>
            </div>
            <button
                @click="addToCart()"
                class="btn bg-green-500 py-1 rounded w-full text-white font-semibold text-lg hover:bg-green-600 transition-colors duration-300 mt-4">
                Add to Cart
            </button>
        </div>
    </section>

    <!-- Related Products -->
    <section>
        <h2 class="text-2xl font-semibold text-gray-600 my-4">Related Products</h2>
        <div class="grid sm:gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
            <template x-for="p in relatedProducts" :key="p._id">
                <div class="border p-2 rounded shadow hover:shadow-lg hover:-translate-y-1 transition transform duration-300 bg-white cursor-pointer relative group">
                    <div class="relative">
                        <img :src="p.image" class="w-full h-48 object-cover rounded mb-2"
                            onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                        <div class="absolute inset-0 flex justify-center items-center gap-4 bg-slate-400 bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="icon cursor-pointer w-8 h-8 rounded-full bg-primary-700 text-white flex justify-center items-center">
                                <i class="fa-solid fa-heart"></i>
                            </div>
                            <div class="icon cursor-pointer w-8 h-8 rounded-full bg-primary-700 text-white flex justify-center items-center">
                                <i class="fa-solid fa-cart-plus"></i>
                            </div>
                            <a :href="'/products/' + p._id" class="icon w-8 h-8 rounded-full bg-primary-700 text-white flex justify-center items-center">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body space-y-3">
                        <header>
                            <h3 class="text-lg text-gray-600 font-semibold line-clamp-1">
                                <a :href="'/products/' + p._id" x-text="p.title"></a>
                            </h3>
                            <h4 class="text-green-500 font-semibold" x-text="p.category?.name"></h4>
                        </header>
                        <p class="text-gray-400 text-sm line-clamp-2" x-text="p.description"></p>
                        <div class="flex justify-between items-center">
                            <span x-text="p.price + ' L.E'"></span>
                            <div>
                                <i class="fa-solid fa-star mr-1 text-yellow-500"></i>
                                <span x-text="p.ratingAverage"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </section>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productDetailsPage', () => ({
        product: {},
        relatedProducts: [],
        productId: "{{ request()->route('product') }}",

        init() {
            this.getProduct();
        },

        getProduct() {
            axios.get(`https://ecommerce.routemisr.com/api/v1/products/${this.productId}`)
                .then(res => {
                    this.product = res.data.data;

                    // الصورة الكبيرة
                    this.product.image = this.product.image || (this.product.imageCover ?
                        (this.product.imageCover.startsWith('http') ? this.product.imageCover : 'https://ecommerce.routemisr.com' + this.product.imageCover) :
                        'https://via.placeholder.com/300x300?text=No+Image');

                    // صور مصغرة
                    this.product.images = this.product.images || [];
                    if(this.product.imageCover && this.product.images.length === 0) {
                        this.product.images = [this.product.imageCover, this.product.imageCover, this.product.imageCover];
                    }

                    this.getRelated();
                })
                .catch(err => console.error(err));
        },

        getRelated() {
            if (!this.product.category?._id) return;
            axios.get(`https://ecommerce.routemisr.com/api/v1/products?category[in]=${this.product.category._id}`)
                .then(res => {
                    this.relatedProducts = res.data.data
                        .filter(p => p._id !== this.product._id)
                        .map(p => ({
                            ...p,
                            image: p.image || (p.imageCover ? (p.imageCover.startsWith('http') ? p.imageCover : 'https://ecommerce.routemisr.com' + p.imageCover) : 'https://via.placeholder.com/300x300?text=No+Image')
                        }));
                })
                .catch(err => console.error(err));
        },

        addToCart() {
            alert('Added to cart: ' + this.product.title); // لاحقاً ممكن تعدليها لتعمل Cart حقيقي
        }

    }));
});
</script>
@endpush

@endsection
