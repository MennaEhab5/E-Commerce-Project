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
    .section {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
</style>
@endpush

<div x-data="categoriesPage()" x-init="init()" class="space-y-6 px-4 md:px-8 mt-6 relative mb-24">

    <header class="text-center font-bold text-4xl text-primary-700 mb-6 section">
        All Categories
    </header>

    <div x-show="loading" x-transition class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <div class="grid sm:gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 section">
        <template x-for="category in categories" :key="category._id || category.id">
            <div class="border p-2 rounded shadow hover:shadow-lg hover:-translate-y-1 transition transform duration-300 bg-white cursor-pointer text-center">
                <img :src="getImageUrl(category.image)" class="w-full h-60 object-cover rounded mb-2" alt="">
                <h3 class="font-semibold text-lg text-black" x-text="category.name || category.title"></h3>
            </div>
        </template>

        <template x-if="categories.length === 0 && !loading">
            <p class="font-semibold mt-6 col-span-full text-center">No categories found.</p>
        </template>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('categoriesPage', () => ({
        categories: [],
        loading: true,

        init() {
            this.getCategories();
        },

        getCategories() {
            this.loading = true;
            axios.get('https://ecommerce.routemisr.com/api/v1/categories')
                .then(res => {
                    if(res.data && res.data.data) {
                        this.categories = res.data.data.map(cat => ({
                            ...cat,
                            image: cat.image && cat.image.length > 0
                                ? (cat.image.startsWith('http') ? cat.image : 'https://ecommerce.routemisr.com' + (cat.image.startsWith('/') ? cat.image : '/' + cat.image))
                                : 'https://via.placeholder.com/300x300?text=No+Image'
                        }));
                    } else {
                        this.categories = [];
                    }
                })
                .catch(err => console.error(err))
                .finally(() => { this.loading = false });
        },

        getImageUrl(img) {
            if (!img) return 'https://via.placeholder.com/300x300?text=No+Image';
            return img.startsWith('http') ? img : 'https://ecommerce.routemisr.com' + (img.startsWith('/') ? img : '/' + img);
        }

    }));
});
</script>
@endpush

@endsection
