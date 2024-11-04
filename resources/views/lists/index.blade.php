<x-app-layout>
    <div class="bg-white shadow rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">My Lists</h2>
                <a href="{{ route('lists.create') }}" 
                   class="bg-accent hover:bg-accent/90 text-white px-4 py-2 rounded">
                    Create New List
                </a>
            </div>
        </div>

        <div class="p-6">
            @if($lists->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500">You haven't created any lists yet.</p>
                    <p class="text-gray-500">Start by creating your first list!</p>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($lists as $list)
                        <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    {{ $list->title }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $list->size }} items • {{ $list->category }}
                                </p>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-sm text-gray-500">
                                        {{ $list->items->count() }} of {{ $list->size }} items
                                    </span>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('lists.edit', $list) }}" 
                                           class="text-primary hover:text-primary/80">
                                            Edit
                                        </a>
                                        <form action="{{ route('lists.destroy', $list) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this list?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>