@extends('layouts.app')

@section('content')

@push('head')
<style>
    .loading-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
    }

    .spinner {
        border: 4px solid rgba(0,0,0,0.1);
        border-top-color: #3490dc;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .section { margin-top: 2rem; margin-bottom: 2rem; }
</style>
@endpush

<div x-data="brandsPage()" x-init="init()" class="space-y-6 px-4 md:px-8 mt-6 relative mb-24">

    <header class="text-center text-green-600 text-5xl text-primary-700 mb-12 section">
        All Brands
    </header>

    <div x-show="loading" x-transition class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <div class="grid sm:gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4 section">
        <template x-for="brand in brands" :key="brand._id">
            <div class="text-center border p-2 rounded shadow hover:shadow-lg hover:-translate-y-1 transition transform duration-300 bg-white cursor-pointer">
                <img :src="resolveImage(brand.image)" class="w-full h-58 object-cover rounded mb-2" alt="">
                <h3 class="font-semibold text-lg text-green-600 mx-auto" x-text="brand.name"></h3>
            </div>
        </template>

        <template x-if="brands.length === 0 && !loading">
            <p class="font-semibold mt-6 col-span-full text-center">No brands found.</p>
        </template>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('brandsPage', () => ({
        brands: [],
        loading: true,

        init() {
            this.getBrands();
        },

        getBrands() {
            this.loading = true;
            axios.get('https://ecommerce.routemisr.com/api/v1/brands')
                .then(res => {
                    if(res.data && res.data.data){
                        this.brands = res.data.data.map(b => ({
                            ...b,
                            image: this.resolveImage(b.image)
                        }));
                    } else {
                        this.brands = [];
                    }
                })
                .catch(err => console.error(err))
                .finally(() => this.loading = false);
        },

        resolveImage(img) {
            if(!img || img.length === 0) return 'https://via.placeholder.com/300x300?text=No+Image';
            if(Array.isArray(img)) img = img[0];
            if(!img.startsWith('http')) img = 'https://ecommerce.routemisr.com' + (img.startsWith('/') ? img : '/' + img);
            return img;
        }

    }));
});
</script>
@endpush

@endsection
