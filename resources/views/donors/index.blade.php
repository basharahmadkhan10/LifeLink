@extends('layouts.main')

@section('content')
<div class="bg-gray-50 dark:bg-black py-12 min-h-[calc(100vh-4rem)] transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ── Page Header ── --}}
        <div class="mb-10 text-center max-w-2xl mx-auto">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">Secure Donor Directory</h1>
            <p class="text-gray-500 dark:text-gray-400">Browse active donors near you. Contact details are always hidden — send a secure inbox request to connect.</p>
        </div>

        @if(session('status'))
            <div class="mb-8 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-sm font-semibold text-green-700 dark:text-green-400 text-center">
                {{ session('status') }}
            </div>
        @endif

        {{-- ── Filter Form ── --}}
        <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-sm border border-gray-100 dark:border-white/5 p-6 mb-10">
            <form method="GET" action="{{ route('donors.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Blood Group</label>
                    <select name="blood_group" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white font-semibold focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                        <option value="">All Types</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                            <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">City</label>
                    <input type="text" name="city" value="{{ request('city') }}" placeholder="Enter city name..."
                        class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white font-semibold focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-md shadow-red-600/20 transition-all h-[46px]">
                        Search
                    </button>
                    @if(request()->hasAny(['blood_group', 'city']))
                        <a href="{{ route('donors.index') }}" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-white/5 dark:hover:bg-white/10 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all h-[46px] flex items-center justify-center">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── Donor Grid ── --}}
        @if($donors->isEmpty())
            <div class="text-center py-20 bg-white dark:bg-[#0a0a0a] rounded-3xl border border-gray-100 dark:border-white/5 shadow-sm">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-white/5 mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No active donors found</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto text-sm">
                    Try adjusting your filters. For life-threatening situations, use the
                    <a href="{{ route('emergency.create') }}" class="text-red-500 font-bold hover:underline">Emergency Broadcast</a>.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($donors as $donor)
                <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-sm hover:shadow-xl dark:hover:shadow-white/5 border border-gray-100 dark:border-white/5 p-6 transition-all duration-300 group">

                    {{-- ── Top Row: Avatar + Name + Active Badge ── --}}
                    <div class="flex items-center justify-between gap-3 mb-5">

                        <div class="flex items-center gap-3 min-w-0">

                            {{-- Blood Group Circle ── solid red ring, bold type --}}
                            <div class="relative shrink-0">
                                <div class="h-12 w-12 rounded-full bg-red-600 dark:bg-red-700 flex items-center justify-center shadow-md shadow-red-600/25">
                                    <span class="text-white font-black text-sm leading-none tracking-tight">{{ $donor->blood_group }}</span>
                                </div>
                                {{-- Active dot anchored to circle --}}
                                <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-green-500 border-2 border-white dark:border-[#0a0a0a]">
                                    <span class="absolute inset-0 rounded-full bg-green-400 animate-ping opacity-75"></span>
                                </span>
                            </div>

                            {{-- Name + City --}}
                            <div class="min-w-0">
                                <h3 class="font-extrabold text-gray-900 dark:text-white text-sm leading-snug truncate">{{ $donor->name }}</h3>
                                <p class="text-xs text-gray-400 dark:text-gray-500 truncate mt-0.5">{{ $donor->city ?? 'Location not set' }}</p>
                            </div>
                        </div>

                        {{-- Active Badge -- matches Exact Match / Verified badge system --}}
                        <div class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800/40">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                            <span class="text-[10px] font-black uppercase tracking-wide text-green-700 dark:text-green-400">Active</span>
                        </div>

                    </div>

                    {{-- ── Footer: Request Button ── --}}
                    <div class="pt-4 border-t border-gray-100 dark:border-white/5 flex items-center justify-end">
                        <form action="{{ route('donation_request.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="to_user_id" value="{{ $donor->id }}">
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white text-xs font-black rounded-xl shadow-sm shadow-red-600/20 transition-all group-hover:-translate-y-0.5 uppercase tracking-wide">
                                Request Help
                            </button>
                        </form>
                    </div>

                </div>
                @endforeach
            </div>

            @if($donors->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $donors->links() }}
                </div>
            @endif
        @endif

    </div>
</div>
@endsection