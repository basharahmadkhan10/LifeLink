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
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
        
    
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white dark:bg-black transition-colors duration-200">
        
        <div class="min-h-screen flex">
            <!-- Left Side: Form Area -->
            <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:w-1/2 lg:px-20 xl:px-24">
                
                <!-- Back & Theme Controls -->
                <div class="absolute top-0 left-0 p-6 flex items-center gap-4 w-full lg:w-1/2 justify-between">
                    <a href="{{ route('welcome') }}" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back to Home
                    </a>
                    
                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-sm p-2 transition">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    </button>
                </div>

                <div class="mx-auto w-full max-w-sm lg:max-w-md mt-8">
                    <div>
                        <a href="/" class="flex flex-col items-center group mb-8">
                            <div class="relative mb-6 transform transition-transform group-hover:scale-105 duration-300">
                                <!-- Glow effect -->
                                <div class="absolute -inset-4 bg-red-500/20 dark:bg-red-500/10 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <img src="{{ asset('images/logo.png') }}" alt="LifeLink Logo" class="w-20 h-20 object-contain drop-shadow-xl relative z-10">
                            </div>
                            <span class="font-extrabold text-3xl tracking-tight text-gray-900 dark:text-white">LifeLink</span>
                        </a>
                        
                        {{ $slot }}
                        
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Graphic/Gradient -->
            <div class="hidden lg:block relative w-0 flex-1 bg-gray-900 overflow-hidden">
                <div class="absolute inset-0 h-full w-full object-cover bg-gradient-to-br from-red-600 to-gray-900 flex items-center justify-center p-20 relative">
                    <!-- Particles effect container -->
                    <div id="tsparticles-auth" class="absolute inset-0 z-0 opacity-40"></div>
                    
                    <div class="relative z-10 max-w-lg text-white">
                        @if(isset($isRegister) && $isRegister == 'true')
                            <h2 class="text-4xl font-extrabold mb-6 leading-tight">A single drop of your blood could mean <span class="text-red-300">the world</span> to someone else.</h2>
                            <p class="text-xl text-red-100 opacity-90">Join thousands of donors making a difference every single day.</p>
                        @else
                            <h2 class="text-4xl font-extrabold mb-6 leading-tight">Welcome back to the community of <span class="text-red-300">heroes</span>.</h2>
                            <p class="text-xl text-red-100 opacity-90">Every time you log in, you are one step closer to saving a life.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/tsparticles-engine"></script>
        <script src="https://cdn.jsdelivr.net/npm/tsparticles/tsparticles.bundle.min.js"></script>
        <script>
            // Dark mode toggle logic
            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            var themeToggleBtn = document.getElementById('theme-toggle');

            themeToggleBtn.addEventListener('click', function() {
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

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
            
            // Particles for auth page right side
            document.addEventListener("DOMContentLoaded", function () {
                if(typeof tsParticles !== 'undefined' && document.getElementById('tsparticles-auth')) {
                    tsParticles.load("tsparticles-auth", {
                        particles: {
                            number: { value: 60 },
                            color: { value: "#ffffff" },
                            shape: { type: "circle" },
                            opacity: { value: 0.5, random: true },
                            size: { value: 3, random: true },
                            move: { enable: true, speed: 1.5, direction: "top", outModes: "out" }
                        }
                    });
                }
            });
        </script>
    </body>
</html>
