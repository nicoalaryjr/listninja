<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800">Edit List</h2>
                    <div class="text-sm bg-gray-100 rounded-full px-3 py-1">
                        {{ $list->size }} items max
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- List Details Form -->
                <form action="{{ route('lists.update', $list) }}" method="POST" class="mb-8">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">List Title</label>
                            <input type="text" name="title" value="{{ $list->title }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <input type="text" value="{{ ucfirst($list->category) }}" disabled
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded">
                                Update List Details
                            </button>
                        </div>
                    </div>
                </form>

                <!-- List Items Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">List Items</h3>
                    
                    <div class="space-y-2" id="list-items">
                        @foreach($list->items->sortBy('position') as $item)
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg" 
                                 data-item-id="{{ $item->id }}">
                                <div class="cursor-move text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-500 font-medium">{{ $item->position }}.</span>
                                <div class="flex-grow">
                                    <input type="text" value="{{ $item->title }}"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                           data-action="update-item"
                                           data-item-id="{{ $item->id }}">
                                </div>
                                <button type="button" 
                                        class="text-red-600 hover:text-red-800"
                                        onclick="deleteItem({{ $item->id }})">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    @if($list->items->count() < $list->size)
                        <div class="mt-4">
                            <div class="relative">
                                <input type="text" id="new-item-title"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                       placeholder="Add new item...">
                                <button onclick="addItem()"
                                        class="absolute right-2 top-2 bg-accent text-white px-4 py-1 rounded text-sm hover:bg-accent/90">
                                    Add
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        // Initialize drag and drop
        new Sortable(document.getElementById('list-items'), {
            handle: '.cursor-move',
            animation: 150,
            onEnd: function(evt) {
                const items = Array.from(evt.to.children).map((el, index) => ({
                    id: el.dataset.itemId,
                    position: index + 1
                }));

                fetch(`/lists/{{ $list->id }}/reorder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ items })
                }).then(() => {
                    // Update visible position numbers
                    document.querySelectorAll('[data-item-id]').forEach((el, index) => {
                        el.querySelector('.text-gray-500').textContent = (index + 1) + '.';
                    });
                });
            }
        });

        // Add new item
        function addItem() {
            const title = document.getElementById('new-item-title').value;
            if (!title) return;

            fetch(`/lists/{{ $list->id }}/items`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ title })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        // Delete item
        function deleteItem(itemId) {
            if (!confirm('Are you sure you want to delete this item?')) return;

            fetch(`/lists/items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        // Auto-save item updates
        let updateTimeout;
        document.querySelectorAll('[data-action="update-item"]').forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(updateTimeout);
                updateTimeout = setTimeout(() => {
                    const itemId = this.dataset.itemId;
                    const title = this.value;

                    fetch(`/lists/items/${itemId}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ title })
                    });
                }, 500);
            });
        });
    </script>
    @endpush
</x-app-layout>