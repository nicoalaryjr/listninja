<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Create New List</h2>
            </div>

            <form action="{{ route('lists.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- List Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">List Type</label>
                        <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="predefined">Choose from Templates</option>
                            <option value="custom">Create Custom List</option>
                        </select>
                    </div>

                    <!-- List Size -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">List Size</label>
                        <select name="size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="5">Top 5</option>
                            <option value="10">Top 10</option>
                            <option value="20">Top 20</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="movies">Movies</option>
                            <option value="songs">Songs</option>
                            <option value="books">Books</option>
                            <option value="games">Games</option>
                            <option value="food">Food</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>

                    <!-- Title -->
                    <div class="relative"> <!-- Added relative positioning for search results -->
                        <label class="block text-sm font-medium text-gray-700">List Title</label>
                        <input type="text" name="title" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-accent hover:bg-accent/90 text-white px-4 py-2 rounded">
                            Create List
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    // Movie search functionality
    let movieSearchTimeout;

    function initializeMovieSearch() {
        const searchInput = document.querySelector('[name="title"]');
        const resultsContainer = document.createElement('div');
        resultsContainer.className = 'absolute z-10 w-full bg-white shadow-lg rounded-md mt-1 hidden';
        searchInput.parentNode.appendChild(resultsContainer);

        searchInput.addEventListener('input', function() {
            clearTimeout(movieSearchTimeout);
            const query = this.value;

            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                return;
            }

            movieSearchTimeout = setTimeout(() => {
                fetch(`/search/movies?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(movies => {
                        resultsContainer.innerHTML = '';
                        
                        movies.forEach(movie => {
                            const div = document.createElement('div');
                            div.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                            div.innerHTML = `
                                <div class="flex items-center">
                                    ${movie.poster ? `<img src="${movie.poster}" class="w-8 h-12 mr-2">` : ''}
                                    <div>
                                        <div class="font-medium">${movie.title}</div>
                                        <div class="text-sm text-gray-500">${movie.year}</div>
                                    </div>
                                </div>
                            `;
                            div.addEventListener('click', () => {
                                searchInput.value = movie.title;
                                resultsContainer.classList.add('hidden');
                            });
                            resultsContainer.appendChild(div);
                        });
                        
                        resultsContainer.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error searching movies:', error);
                        resultsContainer.innerHTML = '<div class="p-2 text-red-500">Error searching movies</div>';
                    });
            }, 300);
        });

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });
    }

    // Music search functionality
    function initializeMusicSearch() {
        const searchInput = document.querySelector('[name="title"]');
        const resultsContainer = document.createElement('div');
        resultsContainer.className = 'absolute z-10 w-full bg-white shadow-lg rounded-md mt-1 hidden';
        searchInput.parentNode.appendChild(resultsContainer);

        searchInput.addEventListener('input', function() {
            clearTimeout(window.searchTimeout);
            const query = this.value;

            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                return;
            }

            window.searchTimeout = setTimeout(() => {
                fetch(`/search/music?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(tracks => {
                        resultsContainer.innerHTML = '';
                        
                        tracks.forEach(track => {
                            const div = document.createElement('div');
                            div.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                            div.innerHTML = `
                                <div class="flex items-center">
                                    ${track.image ? `<img src="${track.image}" class="w-10 h-10 mr-2">` : ''}
                                    <div>
                                        <div class="font-medium">${track.title}</div>
                                        <div class="text-sm text-gray-500">${track.artist} • ${track.album}</div>
                                    </div>
                                </div>
                            `;
                            div.addEventListener('click', () => {
                                searchInput.value = `${track.title} - ${track.artist}`;
                                resultsContainer.classList.add('hidden');
                            });
                            resultsContainer.appendChild(div);
                        });
                        
                        resultsContainer.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error searching music:', error);
                        resultsContainer.innerHTML = '<div class="p-2 text-red-500">Error searching music</div>';
                    });
            }, 300);
        });

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });
    }

    // Initialize search based on category
    document.querySelector('[name="category"]').addEventListener('change', function() {
        if (this.value === 'movies') {
            initializeMovieSearch();
        } else if (this.value === 'songs') {
            initializeMusicSearch();
        }
    });

    // Initialize movie search by default if movies is selected
    if (document.querySelector('[name="category"]').value === 'movies') {
        initializeMovieSearch();
    }
    </script>
    @endpush
</x-app-layout>