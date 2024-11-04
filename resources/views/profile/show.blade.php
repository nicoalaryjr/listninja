<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Profile Header -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="p-6">
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <img class="h-24 w-24 rounded-full object-cover" 
                             src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->username) }}" 
                             alt="{{ $user->username }}">
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center justify-between">
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ $user->username }}
                                @if($user->achievements->isNotEmpty())
                                    <span class="ml-2">{{ $user->achievements->last()->icon }}</span>
                                @endif
                            </h1>
                            @if(auth()->id() !== $user->id)
                                <button onclick="toggleFollow({{ $user->id }})" 
                                        class="follow-button px-4 py-2 rounded {{ $isFollowing ? 'bg-gray-200 hover:bg-gray-300' : 'bg-accent text-white hover:bg-accent/90' }}">
                                    {{ $isFollowing ? 'Following' : 'Follow' }}
                                </button>
                            @else
                                <a href="{{ route('profile.edit') }}" 
                                   class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded">
                                    Edit Profile
                                </a>
                            @endif
                        </div>
                        <div class="mt-2 text-gray-600">{{ $user->bio }}</div>
                        <div class="mt-4 flex space-x-6">
                            <a href="{{ route('profile.following', $user) }}" class="text-gray-600 hover:text-gray-900">
                                <span class="font-bold">{{ $user->following->count() }}</span> Following
                            </a>
                            <a href="{{ route('profile.followers', $user) }}" class="text-gray-600 hover:text-gray-900">
                                <span class="font-bold">{{ $user->followers->count() }}</span> Followers
                            </a>
                            <div class="text-gray-600">
                                <span class="font-bold">{{ $user->lists->count() }}</span> Lists
                            </div>
                            <div class="text-gray-600">
                                <span class="font-bold">Level {{ $user->level }}</span>
                                ({{ $user->points }} points)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lists Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($lists as $list)
                <div class="bg-white shadow rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $list->title }}</h2>
                        <p class="text-sm text-gray-500 mb-4">
                            {{ $list->size }} items • {{ ucfirst($list->category) }}
                        </p>
                        <div class="space-y-2">
                            @foreach($list->items->take(3) as $item)
                                <div class="text-gray-700">
                                    {{ $item->position }}. {{ $item->title }}
                                </div>
                            @endforeach
                            @if($list->items->count() > 3)
                                <div class="text-accent hover:text-accent/80">
                                    + {{ $list->items->count() - 3 }} more items...
                                </div>
                            @endif
                        </div>
                        <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                            <div>{{ $list->created_at->diffForHumans() }}</div>
                            <div>{{ $list->likes_count }} likes</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleFollow(userId) {
            fetch(`/profile/${userId}/follow`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const button = document.querySelector('.follow-button');
                    if (data.isFollowing) {
                        button.textContent = 'Following';
                        button.classList.remove('bg-accent', 'text-white', 'hover:bg-accent/90');
                        button.classList.add('bg-gray-200', 'hover:bg-gray-300');
                    } else {
                        button.textContent = 'Follow';
                        button.classList.remove('bg-gray-200', 'hover:bg-gray-300');
                        button.classList.add('bg-accent', 'text-white', 'hover:bg-accent/90');
                    }
                }
            });
        }
    </script>
    @endpush
</x-app-layout>