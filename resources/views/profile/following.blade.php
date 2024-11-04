<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $user->username }} is Following
                    </h2>
                    <span class="text-gray-500">Following {{ $following->count() }} users</span>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($following as $followed)
                    <div class="p-6 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <img class="h-12 w-12 rounded-full object-cover" 
                                 src="{{ $followed->avatar ? Storage::url($followed->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($followed->username) }}" 
                                 alt="{{ $followed->username }}">
                            <div>
                                <a href="{{ route('profile.show', $followed) }}" 
                                   class="font-medium text-gray-900 hover:text-accent">
                                    {{ $followed->username }}
                                </a>
                                <p class="text-sm text-gray-500">
                                    Level {{ $followed->level }} • {{ $followed->lists->count() }} lists
                                </p>
                            </div>
                        </div>
                        @if(auth()->id() !== $followed->id)
                            <button onclick="toggleFollow({{ $followed->id }})" 
                                    class="follow-button px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                                Following
                            </button>
                        @endif
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        Not following anyone yet
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>