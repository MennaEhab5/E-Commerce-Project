{{-- Card Component --}}
@props(['product'])

<div class="border p-2 rounded shadow hover:shadow-lg hover:-translate-y-1 transition transform duration-300 bg-white cursor-pointer relative group">
    <div class="relative">
        <img src="{{ $product['image'] ?? 'https://via.placeholder.com/300x300?text=No+Image' }}"
            class="w-full h-48 object-cover rounded mb-2" alt="">
        <div class="absolute inset-0 flex justify-center items-center gap-4 bg-slate-400 bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            {{-- WishList --}}
            <div class="icon cursor-pointer w-8 h-8 rounded-full bg-primary-700 text-white flex justify-center items-center">
                <i class="fa-solid fa-heart"></i>
            </div>
            {{-- Add to Cart --}}
            <div class="icon cursor-pointer w-8 h-8 rounded-full bg-primary-700 text-white flex justify-center items-center">
                <i class="fa-solid fa-cart-plus"></i>
            </div>
            {{-- View Details --}}
            <Link to={`/productDetails/${id}`} className="icon cursor-pointer w-8 h-8 rounded-full bg-primary-700 text-white flex justify-center items-center">
            <i className='fa-regular fa-eye'></i>
            </Link>

        </div>
    </div>
    <div class="card-body space-y-3">
        <header>
            <h3 class="text-lg text-gray-600 font-semibold line-clamp-1">
                <a href="{{ url('/productDetails/'.$product['id']) }}">{{ $product['title'] }}</a>
            </h3>
            <h4 class="text-primary-700 font-semibold">{{ $product['category']['name'] ?? '' }}</h4>
        </header>
        <p class="text-gray-400 text-sm line-clamp-2">{{ $product['description'] }}</p>
        <div class="flex justify-between items-center">
            <span>{{ $product['price'] }} L.E</span>
            <div>
                <i class="fa-solid fa-star mr-1 text-yellow-500"></i>
                <span>{{ $product['ratingAverage'] ?? '' }}</span>
            </div>
        </div>
    </div>
</div>