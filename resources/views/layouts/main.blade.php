<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LifeLink') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Dark Mode Script -->
        <script>
            // On page load or when changing themes, best to add inline in `head` to avoid FOUC
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
        
        <style>
            .glass {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }
            .dark .glass {
                background: rgba(6, 6, 10, 0.85);
            }
            .text-gradient {
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-image: linear-gradient(to right, #e11d48, #9f1239);
            }
            .dark .text-gradient {
                background-image: linear-gradient(to right, #fb7185, #e11d48);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-white dark:bg-black text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="min-h-screen flex flex-col">
            
            <!-- Navbar -->
            <nav class="glass sticky top-0 z-50 border-b border-gray-200 dark:border-white/5 transition-colors duration-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ route('welcome') }}" class="flex items-center space-x-2">
                                <img src="{{ asset('images/logo.png') }}" alt="LifeLink" class="w-8 h-8 object-contain drop-shadow-sm">
                                <span class="font-bold text-xl tracking-tight">LifeLink</span>
                            </a>
                            
                            <div class="hidden sm:-my-px sm:ml-10 sm:flex space-x-8">
                                <a href="{{ route('donors.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 transition duration-150 ease-in-out">
                                    Find Donors
                                </a>
                                <a href="{{ route('leaderboard.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:border-red-500 transition duration-150 ease-in-out">
                                    Leaderboard
                                </a>
                                <a href="{{ route('hospitals.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 transition duration-150 ease-in-out">
                                    Nearby Hospitals
                                </a>
                                @auth
                                <a href="{{ route('inbox.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 transition duration-150 ease-in-out">
                                    Inbox
                                    @php
                                        $unreadRequests = \App\Models\DonationRequest::where('to_user_id', auth()->id())->where('status', 'pending')->count();
                                        $activeRequestIds = \App\Models\DonationRequest::where(function ($query) {
                                                $query->where('to_user_id', auth()->id())
                                                      ->orWhere('from_user_id', auth()->id());
                                            })
                                            ->where('status', 'accepted')
                                            ->pluck('id');
                                        $unreadMessages = \App\Models\Message::where('receiver_id', auth()->id())
                                            ->whereIn('donation_request_id', $activeRequestIds)
                                            ->whereNull('read_at')
                                            ->count();
                                        $unreadCount = $unreadRequests + $unreadMessages;
                                    @endphp
                                    <span id="nav-notification-badge" class="{{ $unreadCount > 0 ? 'inline-flex' : 'hidden' }} ml-1.5 items-center justify-center h-4 w-4 rounded-full bg-red-600 text-white text-[10px] font-bold">{{ $unreadCount }}</span>
                                </a>
                                @endauth
                                <a href="{{ route('emergency.create') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-red-600 dark:text-red-500 hover:text-red-800 dark:hover:text-red-400 hover:border-red-300 transition duration-150 ease-in-out">
                                    Emergency
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Dark Mode Toggle -->
                            <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 transition">
                                <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                                <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                            </button>

                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ route('dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition">Log in</a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="ml-4 font-medium px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition shadow-sm">Register</a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-grow">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-[#050505] border-t border-gray-200 dark:border-white/5 mt-auto transition-colors duration-200">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-gray-500 dark:text-gray-400 text-sm">
                        &copy; {{ date('Y') }} LifeLink Blood Donor Platform. Built with ❤️ for humanity.
                    </p>
                </div>
            </footer>
        </div>

        @yield('scripts')

        <script>
            // Dark mode toggle logic
            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Change the icons inside the button based on previous settings
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            var themeToggleBtn = document.getElementById('theme-toggle');

            themeToggleBtn.addEventListener('click', function() {
                // toggle icons inside button
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                // if set via local storage previously
                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });
        </script>

        @auth
        <script>
            // Periodically check for new notifications (unread requests + unread messages)
            document.addEventListener('DOMContentLoaded', function() {
                const badge = document.getElementById('nav-notification-badge');
                if (badge) {
                    setInterval(function() {
                        fetch('{{ route('notifications.count') }}')
                            .then(response => {
                                if (response.ok) {
                                    return response.json();
                                }
                                throw new Error('Failed to fetch notifications');
                            })
                            .then(data => {
                                if (typeof data.count !== 'undefined') {
                                    const count = data.count;
                                    badge.textContent = count;
                                    if (count > 0) {
                                        badge.classList.remove('hidden');
                                        badge.classList.add('inline-flex');
                                    } else {
                                        badge.classList.remove('inline-flex');
                                        badge.classList.add('hidden');
                                    }
                                }
                            })
                            .catch(error => {
                                console.warn('Notification update warning:', error);
                            });
                    }, 5000); // Check every 5 seconds
                }
            });
        </script>
        @endauth
    </body>
</html>
