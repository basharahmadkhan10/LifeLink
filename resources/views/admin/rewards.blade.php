@extends('layouts.main')

@section('content')
<div class="bg-gray-50 dark:bg-black py-8 min-h-[calc(100vh-4rem)] transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Reward Manager
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Identify your top donors and distribute physical rewards.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-900 dark:hover:text-white font-bold">&larr; Back to Dashboard</a>
        </div>

        <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-sm border border-gray-100 dark:border-white/5 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-white/5 bg-red-50/50 dark:bg-red-900/10 flex justify-between items-center">
                <form action="{{ route('admin.rewards') }}" method="GET" class="flex gap-4 max-w-md w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search top donors..." class="w-full bg-white dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm focus:ring-red-500 focus:border-red-500 dark:text-white transition shadow-sm">
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-sm">Filter</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-white/[0.02] text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-white/5">
                            <th class="p-4 px-6 w-20 text-center">Rank</th>
                            <th class="p-4 px-6">Donor Info</th>
                            <th class="p-4 px-6 text-center">Total Points</th>
                            <th class="p-4 px-6 text-center">Milestone Status</th>
                            <th class="p-4 px-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($donors as $index => $donor)
                        @php
                            $rank = ($donors->currentPage() - 1) * $donors->perPage() + $index + 1;
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition">
                            <td class="p-4 px-6 text-center font-black text-gray-400 dark:text-gray-600">
                                #{{ $rank }}
                            </td>
                            <td class="p-4 px-6">
                                <div class="font-bold text-gray-900 dark:text-white">
                                    {{ $donor->name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $donor->phone }} • {{ $donor->city }}</div>
                            </td>
                            <td class="p-4 px-6 text-center">
                                <span class="font-black text-xl text-gray-900 dark:text-white">{{ number_format($donor->points) }}</span>
                            </td>
                            <td class="p-4 px-6 text-center">
                                @if($donor->points >= 1000)
                                    <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 font-bold rounded-full text-xs border border-yellow-200 dark:border-yellow-800">Gold Hero (1000+)</span>
                                @elseif($donor->points >= 500)
                                    <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-full text-xs border border-gray-300 dark:border-gray-600">Silver Savior (500+)</span>
                                @elseif($donor->points >= 200)
                                    <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400 font-bold rounded-full text-xs border border-orange-200 dark:border-orange-800">Bronze Donor (200+)</span>
                                @else
                                    <span class="text-xs font-semibold text-gray-400">Needs 200 pts</span>
                                @endif
                            </td>
                            <td class="p-4 px-6 text-right">
                                <button onclick="alert('In a live environment, this would mark a physical reward as dispatched via API!')" class="text-xs font-bold px-4 py-2 rounded-lg bg-green-50 hover:bg-green-100 text-green-700 border border-green-200 dark:bg-green-900/20 dark:hover:bg-green-900/40 dark:text-green-400 dark:border-green-800 transition">
                                    Mark Rewarded
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($donors->hasPages())
                <div class="p-6 border-t border-gray-100 dark:border-white/5">
                    {{ $donors->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection