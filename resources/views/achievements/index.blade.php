<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Current Level Status -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Ninja Training Progress</h1>
                        <p class="mt-2 text-gray-600">
                            Level {{ auth()->user()->level }} • {{ auth()->user()->points }} points
                        </p>
                    </div>
                    <div class="text-4xl">
                        @if($achievements->isNotEmpty())
                            {{ $achievements->last()->icon }}
                        @else
                            🥋
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievements Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($achievements as $achievement)
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center space-x-4">
                        <div class="text-4xl">{{ $achievement->icon }}</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $achievement->name }}
                            </h3>
                            <p class="text-sm text-gray-500">{{ $achievement->description }}</p>
                            <div class="mt-2 text-accent">
                                Unlocked {{ $achievement->pivot->unlocked_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @foreach($nextAchievements as $achievement)
                <div class="bg-gray-50 shadow rounded-lg p-6 opacity-75">
                    <div class="flex items-center space-x-4">
                        <div class="text-4xl opacity-50">{{ $achievement->icon }}</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-700">
                                {{ $achievement->name }}
                            </h3>
                            <p class="text-sm text-gray-500">{{ $achievement->description }}</p>
                            <div class="mt-2 text-gray-600">
                                Requires: {{ $achievement->required_points }} points
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>