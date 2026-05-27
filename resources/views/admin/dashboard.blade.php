@extends('layouts.main')

@section('content')
<div class="bg-gray-50 dark:bg-black py-8 min-h-[calc(100vh-4rem)] transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Admin Dashboard</h1>
            <div class="flex space-x-4">
                <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-900 dark:text-white font-bold rounded-xl transition">
                    User Management
                </a>
                <a href="{{ route('admin.rewards') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2">
                    🎁 Reward Manager
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-[#0a0a0a] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 transition-colors">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-wider mb-2">Total Donors</div>
                <div class="text-4xl font-black text-gray-900 dark:text-white">{{ $stats['total_donors'] ?? 0 }}</div>
            </div>
            <div class="bg-white dark:bg-[#0a0a0a] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 border-l-4 border-l-blue-500 dark:border-l-blue-500 transition-colors">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-wider mb-2">Total Requests</div>
                <div class="text-4xl font-black text-gray-900 dark:text-white">{{ $stats['total_requests'] ?? 0 }}</div>
            </div>
            <div class="bg-white dark:bg-[#0a0a0a] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 border-l-4 border-l-yellow-500 dark:border-l-yellow-500 transition-colors">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-wider mb-2">Active Requests</div>
                <div class="text-4xl font-black text-gray-900 dark:text-white">{{ $stats['active_requests'] ?? 0 }}</div>
            </div>
            <div class="bg-white dark:bg-[#0a0a0a] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 border-l-4 border-l-red-600 dark:border-l-red-600 transition-colors">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-wider mb-2">Emergency Requests</div>
                <div class="text-4xl font-black text-red-600 dark:text-red-500">{{ $stats['emergency_requests'] ?? 0 }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Donors -->
            <div class="bg-white dark:bg-[#0a0a0a] rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 overflow-hidden transition-colors">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02] flex justify-between items-center">
                    <h2 class="font-bold text-lg text-gray-900 dark:text-white">Recently Registered Donors</h2>
                    <a href="{{ route('admin.users') }}" class="text-sm font-bold text-red-600 dark:text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors">View All &rarr;</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse($recent_donors ?? [] as $donor)
                        <div class="p-5 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors group">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-500 flex items-center justify-center font-black text-lg border border-red-100 dark:border-red-900/50 mr-4 group-hover:scale-110 transition-transform">
                                    {{ $donor->blood_group ?? '?' }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $donor->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $donor->city ?? 'No city provided' }}</div>
                                </div>
                            </div>
                            <div class="text-sm font-medium text-gray-400 dark:text-gray-500">
                                {{ $donor->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">No donors found.</div>
                     @endforelse
                </div>
            </div>

            <!-- Recent Requests -->
            <div class="bg-white dark:bg-[#0a0a0a] rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 overflow-hidden transition-colors">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02] flex justify-between items-center">
                    <h2 class="font-bold text-lg text-gray-900 dark:text-white">Recent Blood Requests</h2>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse($recent_requests ?? [] as $req)
                        <div class="p-5 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                            <div>
                                <div class="font-bold text-gray-900 dark:text-white">
                                    <span class="text-red-600 dark:text-red-500 mr-1">{{ $req->blood_group }}</span>
                                    for {{ $req->patient_name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $req->hospital_name }} • {{ $req->city }}</div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $req->emergency_level == 'Critical' || $req->emergency_level == 'High' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' : 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400' }}">
                                {{ $req->emergency_level }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">No requests found.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
