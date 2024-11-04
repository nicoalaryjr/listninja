<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'List Ninja') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-primary border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center text-white">
                            <span class="text-2xl">🥷</span>
                            <span class="ml-2 font-bold">List Ninja</span>
                        </a>
                    </div>

                    @auth
                    <!-- Desktop Navigation -->
                    <div class="hidden sm:flex sm:items-center sm:space-x-8">
                        <a href="{{ route('dashboard') }}" class="text-white hover:text-accent">
                            Dashboard
                        </a>
                        <a href="{{ route('lists.index') }}" class="text-white hover:text-accent">
                            My Lists
                        </a>
                        <a href="{{ route('achievements.index') }}" class="text-white hover:text-accent">
                            Achievements
                        </a>

                        <!-- Notifications Dropdown -->
                        <x-notification-dropdown />

                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-white">
                                <img class="h-8 w-8 rounded-full object-cover" 
                                     src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->username) }}" 
                                     alt="{{ auth()->user()->username }}">
                                <span class="ml-2">{{ auth()->user()->username }}</span>
                                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.show', auth()->user()) }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile
                                </a>
                                <a href="{{ route('profile.edit') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Navigation Button -->
                    <div class="flex items-center sm:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                                class="text-white hover:text-accent">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                    @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-white hover:text-accent">Login</a>
                        <a href="{{ route('register') }}" class="bg-accent hover:bg-accent/90 text-white px-4 py-2 rounded">
                            Get Started
                        </a>
                    </div>
                    @endauth
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            @auth
            <div x-show="mobileMenuOpen" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="block px-3 py-2 text-white hover:bg-accent">
                        Dashboard
                    </a>
                    <a href="{{ route('lists.index') }}" 
                       class="block px-3 py-2 text-white hover:bg-accent">
                        My Lists
                    </a>
                    <a href="{{ route('achievements.index') }}" 
                       class="block px-3 py-2 text-white hover:bg-accent">
                        Achievements
                    </a>
                    <a href="{{ route('profile.show', auth()->user()) }}" 
                       class="block px-3 py-2 text-white hover:bg-accent">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="block w-full text-left px-3 py-2 text-white hover:bg-accent">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </nav>

        <!-- Page Content -->
        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <span class="text-xl">🥷</span>
                        <span class="text-gray-600">List Ninja</span>
                    </div>
                    <div class="text-gray-500 text-sm">
                        © {{ date('Y') }} List Ninja. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>