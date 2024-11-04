<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->username }}! 🥷</h1>
                        <p class="mt-1 text-gray-600">Level {{ auth()->user()->level }} • {{ auth()->user()->points }} points</p>
                    </div>
                    <a href="{{ route('lists.create') }}" 
                       class="bg-accent hover:bg-accent/90 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                        <span>Create New List</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Feed -->
            <div class="lg:col-span-2 space-y-6">
                @forelse($lists as $list)
                    <div class="bg-white shadow rounded-lg">
                        <!-- List Header -->
                        <div class="p-4 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <img class="h-10 w-10 rounded-full object-cover" 
                                     src="{{ $list->user->avatar ? Storage::url($list->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($list->user->username) }}" 
                                     alt="{{ $list->user->username }}">
                                <div>
                                    <a href="{{ route('profile.show', $list->user) }}" 
                                       class="font-medium text-gray-900 hover:text-accent">
                                        {{ $list->user->username }}
                                    </a>
                                    <p class="text-sm text-gray-500">{{ $list->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- List Content -->
                        <div class="p-4">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $list->title }}</h2>
                            
                            <div class="space-y-2">
                                @foreach($list->items->sortBy('position')->take(5) as $item)
                                    <div class="flex items-center space-x-2">
                                        <span class="text-gray-500">{{ $item->position }}.</span>
                                        <span class="text-gray-900">{{ $item->title }}</span>
                                    </div>
                                @endforeach
                                
                                @if($list->items->count() > 5)
                                    <a href="{{ route('lists.show', $list) }}" 
                                       class="text-accent hover:text-accent/80 text-sm">
                                        Show {{ $list->items->count() - 5 }} more items...
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="p-4 border-t border-gray-100">
                            <div class="flex items-center space-x-4">
                                <button onclick="toggleLike({{ $list->id }})" 
                                        class="flex items-center space-x-1 text-gray-500 hover:text-accent"
                                        data-list-id="{{ $list->id }}"
                                        data-liked="{{ $list->isLikedBy(auth()->user()) ? 'true' : 'false' }}">
                                    <svg class="w-5 h-5" fill="{{ $list->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" 
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                    <span class="likes-count">{{ $list->likes()->count() }}</span>
                                </button>

                                <button onclick="toggleComments({{ $list->id }})" 
                                        class="flex items-center space-x-1 text-gray-500 hover:text-accent">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    <span>{{ $list->comments()->count() }}</span>
                                </button>

                                <a href="{{ route('lists.show', $list) }}" 
                                   class="flex items-center space-x-1 text-gray-500 hover:text-accent ml-auto">
                                    <span>View Full List</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                </a>
                            </div>

                            <!-- Comments Section -->
                            <div id="comments-{{ $list->id }}" class="hidden mt-4">
                                <div class="space-y-4">
                                    @foreach($list->comments()->latest()->take(3)->get() as $comment)
                                        <div class="flex items-start space-x-3">
                                            <img class="h-8 w-8 rounded-full object-cover" 
                                                 src="{{ $comment->user->avatar ? Storage::url($comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->username) }}" 
                                                 alt="{{ $comment->user->username }}">
                                            <div class="flex-grow">
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <a href="{{ route('profile.show', $comment->user) }}" 
                                                       class="font-medium text-gray-900 hover:text-accent">
                                                        {{ $comment->user->username }}
                                                    </a>
                                                    <p class="text-gray-600">{{ $comment->content }}</p>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <form onsubmit="postComment(event, {{ $list->id }})" class="mt-4">
                                    <div class="flex items-center space-x-2">
                                        <input type="text" 
                                               class="flex-grow rounded-lg border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50"
                                               placeholder="Add a comment..."
                                               id="comment-input-{{ $list->id }}">
                                        <button type="submit" 
                                                class="bg-accent hover:bg-accent/90 text-white px-4 py-2 rounded-lg">
                                            Post
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow rounded-lg p-6 text-center">
                        <h3 class="text-lg font-medium text-gray-900">Welcome to List Ninja!</h3>
                        <p class="mt-1 text-gray-500">Follow other users to see their lists here.</p>
                        <a href="{{ route('lists.create') }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 bg-accent hover:bg-accent/90 text-white rounded-lg">
                            Create Your First List
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Stats</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Lists Created</p>
                            <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->lists()->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Followers</p>
                            <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->followers()->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Following</p>
                            <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->following()->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Latest Achievement -->
                @if($latestAchievement = auth()->user()->achievements()->latest()->first())
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Latest Achievement</h2>
                        <div class="flex items-center space-x-3">