<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Followers of {{ $user->username }}
                    </h2>
                    <span class="text-gray-500">{{ $followers->count() }} followers</span>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($followers as $follower)
                    <div class="p-6 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <img class="h-12 w-12 rounded-full object-cover" 
                                 src="{{ $follower->avatar ? Storage::url($follower->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($follower->username) }}" 
                                 alt="{{ $follower->username }}">
                            <div>
                                <a href="{{ route('profile.show', $follower) }}" 
                                   class="font-medium text-gray-900 hover:text-accent">
                                    {{ $follower->username }}
                                </a>
                                <p class="text-sm text-gray-500">
                                    Level {{ $follower->level }} • {{ $follower->lists->count() }} lists
                                </p>
                            </div>
                        </div>
                        @if(auth()->id() !== $follower->id)
                            <button onclick="toggleFollow({{ $follower->id }})" 
                                    class="follow-button px-4 py-2 rounded {{ auth()->user()->following->contains($follower->id) ? 'bg-gray-200 hover:bg-gray-300' : 'bg-accent text-white hover:bg-accent/90' }}">
                                {{ auth()->user()->following->contains($follower->id) ? 'Following' : 'Follow' }}
                            </button>
                        @endif
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        No followers yet
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>