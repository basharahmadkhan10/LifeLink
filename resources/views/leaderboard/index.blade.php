@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-10">
    
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight mb-4">
            Heroes <span class="text-gradient">Leaderboard</span>
        </h1>
        <p class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">
            Recognizing the incredible individuals who consistently step up to save lives. Earn points by fulfilling blood requests and climb the ranks!
        </p>
    </div>

    <!-- City Filter Only -->
    <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-xl shadow-red-900/5 border border-gray-100 dark:border-white/5 p-4 md:p-6 mb-12 relative z-10">
        <form method="GET" action="{{ route('leaderboard.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Filter by City</label>
                <input type="text" name="city" value="{{ request('city') }}" placeholder="e.g. New Delhi, Mumbai..."
                    class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white font-semibold focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-md shadow-red-600/20 transition h-[46px]">
                    View City Ranks
                </button>
                @if(request()->filled('city'))
                    <a href="{{ route('leaderboard.index') }}" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-white/5 dark:hover:bg-white/10 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition h-[46px] flex items-center justify-center">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Top 3 Podium -->
    @if($topThree->count() >= 3)
    <div class="flex flex-col md:flex-row justify-center items-end gap-4 md:gap-8 mb-16 pt-10">
        
        <!-- Rank 2 (Silver) -->
        @php $silver = $topThree[1]; @endphp
        <div class="w-full md:w-1/4 flex flex-col items-center transform md:translate-y-8 order-2 md:order-1">
            <div class="relative mb-4 group">
                <div class="absolute -inset-4 bg-gray-300/30 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative h-24 w-24 bg-gradient-to-br from-gray-200 to-gray-400 rounded-full border-4 border-white dark:border-[#0a0a0a] shadow-lg flex items-center justify-center text-3xl font-black text-white">
                    {{ substr($silver->name, 0, 1) }}
                </div>
                <div class="absolute -bottom-2 -right-2 h-8 w-8 bg-gray-300 rounded-full border-2 border-white dark:border-[#0a0a0a] flex items-center justify-center font-black text-white shadow-sm text-sm">2</div>
            </div>
            <h3 class="font-extrabold text-lg text-gray-900 dark:text-white text-center">{{ $silver->name }}</h3>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-2">{{ $silver->city ?? 'Unknown' }}</p>
            <span class="px-4 py-1.5 bg-gray-100 dark:bg-white/5 rounded-full text-sm font-black text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-white/10">
                {{ number_format($silver->points) }} pts
            </span>
        </div>

        <!-- Rank 1 (Gold) -->
        @php $gold = $topThree[0]; @endphp
        <div class="w-full md:w-1/3 flex flex-col items-center order-1 md:order-2 mb-8 md:mb-0 z-10">
            <div class="relative mb-4 group">
                <div class="absolute -inset-6 bg-yellow-400/30 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition duration-500 animate-pulse"></div>
                <div class="relative h-32 w-32 bg-gradient-to-br from-yellow-300 to-yellow-600 rounded-full border-4 border-white dark:border-[#0a0a0a] shadow-2xl flex items-center justify-center text-5xl font-black text-white">
                    {{ substr($gold->name, 0, 1) }}
                </div>
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 text-4xl">👑</div>
                <div class="absolute -bottom-2 -right-2 h-10 w-10 bg-yellow-500 rounded-full border-2 border-white dark:border-[#0a0a0a] flex items-center justify-center font-black text-white shadow-sm text-lg">1</div>
            </div>
            <h3 class="font-black text-2xl text-gray-900 dark:text-white text-center">{{ $gold->name }}</h3>
            <p class="text-md font-bold text-yellow-600 dark:text-yellow-500 mb-3">{{ $gold->city ?? 'Unknown' }}</p>
            <span class="px-6 py-2 bg-yellow-50 dark:bg-yellow-900/20 rounded-full text-md font-black text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-700 shadow-sm">
                {{ number_format($gold->points) }} pts
            </span>
        </div>

        <!-- Rank 3 (Bronze) -->
        @php $bronze = $topThree[2]; @endphp
        <div class="w-full md:w-1/4 flex flex-col items-center transform md:translate-y-12 order-3">
            <div class="relative mb-4 group">
                <div class="absolute -inset-4 bg-orange-700/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative h-20 w-20 bg-gradient-to-br from-orange-300 to-orange-700 rounded-full border-4 border-white dark:border-[#0a0a0a] shadow-md flex items-center justify-center text-2xl font-black text-white">
                    {{ substr($bronze->name, 0, 1) }}
                </div>
                <div class="absolute -bottom-2 -right-2 h-7 w-7 bg-orange-600 rounded-full border-2 border-white dark:border-[#0a0a0a] flex items-center justify-center font-black text-white shadow-sm text-xs">3</div>
            </div>
            <h3 class="font-bold text-md text-gray-900 dark:text-white text-center">{{ $bronze->name }}</h3>
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2">{{ $bronze->city ?? 'Unknown' }}</p>
            <span class="px-3 py-1 bg-orange-50 dark:bg-orange-900/20 rounded-full text-xs font-black text-orange-800 dark:text-orange-400 border border-orange-200 dark:border-orange-800/50">
                {{ number_format($bronze->points) }} pts
            </span>
        </div>

    </div>
    @endif

    <!-- Leaderboard Table -->
    <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-xl shadow-red-900/5 border border-gray-100 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-[#111] border-b border-gray-100 dark:border-white/5 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                        <th class="py-4 px-6 text-center w-20">Rank</th>
                        <th class="py-4 px-6">Donor Hero</th>
                        <th class="py-4 px-6 text-center">Blood Group</th>
                        <th class="py-4 px-6">Location</th>
                        <th class="py-4 px-6 text-right">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse($donors as $index => $donor)
                        @php
                            // Calculate global rank based on pagination
                            $rank = ($donors->currentPage() - 1) * $donors->perPage() + $index + 1;
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors {{ auth()->check() && auth()->id() === $donor->id ? 'bg-red-50/50 dark:bg-red-900/10' : '' }}">
                            <td class="py-4 px-6 text-center font-black text-gray-400 dark:text-gray-600">
                                #{{ $rank }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/30 dark:to-red-800/30 flex items-center justify-center font-bold text-red-600 dark:text-red-400">
                                        {{ substr($donor->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                            {{ $donor->name }}
                                            @if(auth()->check() && auth()->id() === $donor->id)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest bg-red-600 text-white">You</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-xs font-black bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/50">
                                    {{ $donor->blood_group }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm font-semibold text-gray-600 dark:text-gray-400">
                                {{ $donor->city ?? 'Unknown' }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <span class="font-black text-lg text-gray-900 dark:text-white">{{ number_format($donor->points) }}</span>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">pts</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-white/5 mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">No heroes found</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Be the first to step up and earn points!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($donors->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5">
            {{ $donors->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
