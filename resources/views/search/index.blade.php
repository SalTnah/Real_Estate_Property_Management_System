<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search Properties') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Search Filter Form -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-8 border border-gray-100">
                <form action="{{ route('search.index') }}" method="GET" class="space-y-4">
                    
                    <!-- Top Row: Keyword and Quick Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label for="keyword" class="block text-sm font-medium text-gray-700 mb-1">Keyword</label>
                            <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}" 
                                   placeholder="City, street address, title..." 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Property Type</label>
                            <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">All Types</option>
                                <option value="Single Family" {{ request('type') == 'Single Family' ? 'selected' : '' }}>Single Family</option>
                                <option value="Apartment" {{ request('type') == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="Condo" {{ request('type') == 'Condo' ? 'selected' : '' }}>Condo</option>
                                <option value="Townhouse" {{ request('type') == 'Townhouse' ? 'selected' : '' }}>Townhouse</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Any Status</option>
                                <option value="For Sale" {{ request('status') == 'For Sale' ? 'selected' : '' }}>For Sale</option>
                                <option value="For Rent" {{ request('status') == 'For Rent' ? 'selected' : '' }}>For Rent</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bottom Row: Numerical Range Filters -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">Min Price ($)</label>
                            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" placeholder="0" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Max Price ($)</label>
                            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" placeholder="Any" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">Min Beds</label>
                            <select name="bedrooms" id="bedrooms" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Any</option>
                                @foreach([1, 2, 3, 4, 5] as $beds)
                                    <option value="{{ $beds }}" {{ request('bedrooms') == $beds ? 'selected' : '' }}>{{ $beds }}+ Beds</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">Min Baths</label>
                            <select name="bathrooms" id="bathrooms" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Any</option>
                                @foreach([1, 2, 3, 4] as $baths)
                                    <option value="{{ $baths }}" {{ request('bathrooms') == $baths ? 'selected' : '' }}>{{ $baths }}+ Baths</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                            <select name="sort" id="sort" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-2">
                        <a href="{{ route('search.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded-md shadow-sm bg-white hover:bg-gray-50">
                            Reset Filters
                        </a>
                        <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md shadow-sm">
                            Apply Search
                        </button>
                    </div>

                </form>
            </div>

            <!-- Property Results Grid -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Search Results ({{ $properties->total() }})
                    </h3>
                </div>

                @if($properties->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($properties as $property)
                            @include('properties._card', ['property' => $property])
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $properties->links() }}
                    </div>
                @else
                    <div class="bg-white p-8 text-center rounded-lg shadow-sm border border-gray-100">
                        <p class="text-gray-500 text-base">No properties found matching your search criteria.</p>
                        <a href="{{ route('search.index') }}" class="mt-3 inline-block text-indigo-600 hover:underline text-sm">Clear all filters</a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>