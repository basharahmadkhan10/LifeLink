@extends('layouts.main')

@section('content')
<!-- Hero Section -->
<section class="relative pt-24 pb-32 overflow-hidden bg-gray-50 dark:bg-black transition-colors duration-200">
    <div id="tsparticles" class="absolute inset-0 z-0 pointer-events-none opacity-60 dark:opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center px-4 py-2 rounded-fulldark:bg-red-900/20 text-red-600 dark:text-red-400 font-semibold text-sm mb-8 shadow-sm border border-red-200 dark:border-red-800/30 backdrop-blur-sm">
                <span class="flex h-2 w-2 rounded-full bg-red-500 mr-2 animate-pulse"></span>
                Every drop counts
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-10 leading-[1.15]">
                <span>Your blood donation could </span>
                <br class="hidden md:block"/>
                <span class="bg-gradient-to-r from-red-600 to-red-800 dark:from-red-500 dark:to-red-700 bg-clip-text text-transparent">save someone's life</span>
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-12 max-w-2xl mx-auto leading-relaxed">
                Join our community of heroes. A single donation can save up to three lives. Whether you're here to give or receive, LifeLink connects you instantly.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('emergency.create') }}" class="px-8 py-4 rounded-xl bg-red-600 text-white font-bold text-lg shadow-lg shadow-red-500/30 hover:bg-red-700 hover:shadow-red-500/50 hover:-translate-y-1 transition-all duration-300">
                    Need Blood Now
                </a>
                <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl bg-white dark:bg-white/5 text-gray-900 dark:text-white font-bold text-lg shadow-md border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/10 hover:-translate-y-1 transition-all duration-300">
                    Register as Donor
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-gray-50 dark:bg-black transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="p-6 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 hover:shadow-lg hover:shadow-red-500/5 dark:hover:shadow-red-500/10 transition-all duration-300">
                <div class="text-4xl font-black text-red-600 dark:text-red-500 mb-2">1,240+</div>
                <div class="text-gray-600 dark:text-gray-400 font-medium">Registered Donors</div>
            </div>
            <div class="p-6 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 hover:shadow-lg hover:shadow-red-500/5 dark:hover:shadow-red-500/10 transition-all duration-300">
                <div class="text-4xl font-black text-red-600 dark:text-red-500 mb-2">3,450</div>
                <div class="text-gray-600 dark:text-gray-400 font-medium">Lives Saved</div>
            </div>
            <div class="p-6 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 hover:shadow-lg hover:shadow-red-500/5 dark:hover:shadow-red-500/10 transition-all duration-300">
                <div class="text-4xl font-black text-red-600 dark:text-red-500 mb-2">15</div>
                <div class="text-gray-600 dark:text-gray-400 font-medium">Cities Covered</div>
            </div>
            <div class="p-6 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 hover:shadow-lg hover:shadow-red-500/5 dark:hover:shadow-red-500/10 transition-all duration-300">
                <div class="text-4xl font-black text-red-600 dark:text-red-500 mb-2">24/7</div>
                <div class="text-gray-600 dark:text-gray-400 font-medium">Emergency Support</div>
            </div>
        </div>
    </div>
</section>

<!-- Gamification Section -->
<section class="py-24 bg-white dark:bg-[#050505] transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            <!-- Gamification Text -->
            <div>
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-red-50 dark:bg-red-950/20 text-red-700 dark:text-red-400 font-bold text-sm mb-6 shadow-sm border border-red-200/50 dark:border-red-900/30">
                    Rewards Program
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-6 leading-tight">
                    Save Lives. <br>
                    <span class="text-gradient">Earn Real Rewards.</span>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                    We believe heroes deserve recognition. When you respond to a blood request, you don't just save a life—you earn points, climb our massive national Leaderboard, and unlock exclusive physical rewards from our partners.
                </p>
                
                <div class="space-y-6 mb-10">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-500 font-black border border-red-200 dark:border-red-800/50">
                                +50
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Normal Donation</h3>
                            <p class="mt-1 text-gray-500 dark:text-gray-400">Earn 50 points for every standard blood request you fulfill.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-500 font-black border border-yellow-200 dark:border-yellow-800/50">
                                +100
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Emergency Support</h3>
                            <p class="mt-1 text-gray-500 dark:text-gray-400">Earn double points (100 pts) for responding to high-priority emergencies.</p>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('leaderboard.index') }}" class="inline-flex items-center gap-2 font-bold text-red-600 dark:text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors">
                    View the Leaderboard <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <!-- Gamification Visual -->
            <div class="relative">
                <div class="absolute -inset-4 bg-gradient-to-r from-red-600 to-yellow-500 opacity-20 dark:opacity-10 blur-2xl rounded-[3rem]"></div>
                <div class="relative bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-[2rem] p-8 shadow-2xl overflow-hidden">
                    
                    <h4 class="text-center font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm mb-6">Current Top Heroes</h4>
                    
                    <div class="space-y-4">
                        <div class="bg-white dark:bg-[#1a1a1a] border border-gray-100 dark:border-white/5 rounded-2xl p-4 flex items-center shadow-sm">
                            <div class="h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center text-md text-yellow-600 dark:text-yellow-500 font-black">1</div>
                            <div class="ml-4 flex-1">
                                <div class="font-bold text-gray-900 dark:text-white">Sarah Jenkins</div>
                                <div class="text-xs text-gray-500">O+ • New York</div>
                            </div>
                            <div class="font-black text-yellow-600 dark:text-yellow-500">1,250 pts</div>
                        </div>
                        
                        <div class="bg-white dark:bg-[#1a1a1a] border border-gray-100 dark:border-white/5 rounded-2xl p-4 flex items-center shadow-sm">
                            <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center text-xl text-gray-600 dark:text-gray-400 font-black">2</div>
                            <div class="ml-4 flex-1">
                                <div class="font-bold text-gray-900 dark:text-white">David Miller</div>
                                <div class="text-xs text-gray-500">A- • Los Angeles</div>
                            </div>
                            <div class="font-black text-gray-700 dark:text-gray-300">850 pts</div>
                        </div>
                        
                        <div class="bg-white dark:bg-[#1a1a1a] border border-gray-100 dark:border-white/5 rounded-2xl p-4 flex items-center shadow-sm">
                            <div class="h-12 w-12 rounded-full bg-orange-100 dark:bg-orange-900/20 flex items-center justify-center text-xl text-orange-600 font-black">3</div>
                            <div class="ml-4 flex-1">
                                <div class="font-bold text-gray-900 dark:text-white">Amina Patel</div>
                                <div class="text-xs text-gray-500">B+ • Chicago</div>
                            </div>
                            <div class="font-black text-orange-700 dark:text-orange-500">550 pts</div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-24 relative z-10 bg-gray-50 dark:bg-black transition-colors duration-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-red-600 to-red-700 dark:from-[#0a0a0a] dark:to-[#0f0f0f] rounded-3xl p-10 md:p-16 text-center relative overflow-hidden shadow-2xl border border-red-500/20 dark:border-white/5">
            
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6 relative z-10 tracking-tight">Ready to save lives?</h2>
            <p class="text-red-100 dark:text-gray-300 text-lg md:text-xl mb-10 max-w-xl mx-auto relative z-10">Registration takes less than two minutes. Once registered, you will appear in our search database and can be contacted during emergencies.</p>
            
            <a href="{{ route('register') }}" class="inline-block px-10 py-4 bg-white text-red-600 dark:bg-red-600 dark:text-white font-bold text-lg rounded-xl hover:bg-gray-100 dark:hover:bg-red-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1 relative z-10">
                Become a Donor Today
            </a>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/tsparticles-engine"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles/tsparticles.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if(typeof tsParticles !== 'undefined') {
            tsParticles.load("tsparticles", {
                particles: {
                    number: { value: 40 },
                    color: { value: "#e11d48" },
                    shape: { type: "circle" },
                    opacity: { value: 0.3, random: true },
                    size: { value: 3, random: true },
                    move: { enable: true, speed: 1, direction: "top", outModes: "out" }
                }
            });
        }
    });
</script>
@endsection