<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List Ninja - Share Your Top Lists</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-primary">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-2xl">🥷</span>
                        <span class="ml-2 text-white font-bold">List Ninja</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-white hover:text-accent">Log in</a>
                        <a href="{{ route('register') }}" class="bg-accent hover:bg-accent/90 text-white px-4 py-2 rounded-lg">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:w-full lg:pb-28 xl:pb-32">
                    <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                        <div class="text-center">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block">Share Your Top Lists</span>
                                <span class="block text-accent mt-3">Become a List Ninja</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl">
                                Create and share lists of your favorite things. Movies, music, books - anything! 
                                Join the community and discover what others love.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center">
                                <div class="rounded-md shadow">
                                    <a href="{{ route('register') }}" 
                                       class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-accent hover:bg-accent/90 md:py-4 md:text-lg md:px-10">
                                        Start Creating Lists
                                    </a>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        Why Join List Ninja?
                    </h2>
                </div>

                <div class="mt-10">
                    <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-accent text-white text-2xl">
                                📝
                            </div>
                            <h3 class="mt-6 text-xl font-medium text-gray-900">Create Lists</h3>
                            <p class="mt-2 text-center text-gray-500">
                                Share your top picks in any category. Movies, music, books - you name it!
                            </p>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-accent text-white text-2xl">
                                🏆
                            </div>
                            <h3 class="mt-6 text-xl font-medium text-gray-900">Earn Achievements</h3>
                            <p class="mt-2 text-center text-gray-500">
                                Level up and unlock ninja achievements as you create and share more lists.
                            </p>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-accent text-white text-2xl">
                                🤝
                            </div>
                            <h3 class="mt-6 text-xl font-medium text-gray-900">Join the Community</h3>
                            <p class="mt-2 text-center text-gray-500">
                                Connect with others, discover new favorites, and share your interests.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
</body>
</html>