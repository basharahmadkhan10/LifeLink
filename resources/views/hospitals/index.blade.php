@extends('layouts.main')

@section('content')
<div class="bg-gray-50 dark:bg-black py-12 min-h-[calc(100vh-4rem)] transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 text-center max-w-2xl mx-auto">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">Nearby Hospitals</h1>
            <p class="text-gray-600 dark:text-gray-400">View real-time blood availability at hospitals in your city. Plan ahead for your donation or emergency needs.</p>
        </div>

        <!-- Search Form -->
        <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-xl shadow-red-900/5 border border-gray-100 dark:border-white/5 p-4 mb-8">
            <form method="GET" action="{{ route('hospitals.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <input type="text" name="city" value="{{ request('city', $city) }}" placeholder="Search by city (e.g. New Delhi)" 
                           class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:text-white transition-colors duration-200">
                </div>
                <button type="submit" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors duration-200 shadow-md shadow-red-600/20">
                    Search
                </button>
            </form>
        </div>

        <!-- Hospitals Grid -->
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
            <span class="inline-block w-1.5 h-6 bg-red-600 rounded-full"></span>
            Partner Hospitals
        </h2>
        <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-white/5 px-3 py-1.5 rounded-full">{{ $hospitals->count() }} Hospitals</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($hospitals as $hospital)
            @php
                // Get inventory for this specific hospital
                $hospitalInventory = $inventories->get($hospital->id, collect());
                // Create an organized map of blood types to units for easy display
                $stockMap = $hospitalInventory->pluck('units', 'blood_group')->toArray();
                $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
            @endphp

            <div class="bg-white dark:bg-[#0a0a0a] rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-white/5 overflow-hidden group">
                <!-- Hospital Header -->
                <div class="p-5 border-b border-gray-100 dark:border-white/10 bg-gradient-to-r from-red-50/20 to-transparent dark:from-red-900/5 dark:to-transparent">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex gap-3 flex-1 min-w-0">
                            <div class="h-12 w-12 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center flex-shrink-0">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-extrabold text-gray-900 dark:text-white group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors truncate">
                                    {{ $hospital->name }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                                    <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="truncate">{{ $hospital->city }}</span>
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 flex-shrink-0">
                            Verified
                        </span>
                    </div>
                </div>

                <div class="p-5">
                    <!-- Blood Stock Section -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Live Blood Stock</h4>
                            <span class="text-[10px] text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-500"></span>
                                </span>
                                Real-time
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($bloodGroups as $bg)
                                @php
                                    $units = $stockMap[$bg] ?? 0;
                                    $hasStock = $units > 0;
                                @endphp
                                <div class="relative overflow-hidden rounded-xl border p-2.5 flex flex-col items-center justify-center transition-all
                                    {{ $hasStock 
                                        ? 'bg-red-50/50 dark:bg-red-900/10 border-red-200 dark:border-red-500/20' 
                                        : 'bg-gray-50 dark:bg-white/5 border-gray-100 dark:border-white/10 opacity-60' }}">
                                    
                                    <span class="font-black text-sm {{ $hasStock ? 'text-red-600 dark:text-red-400' : 'text-gray-400 dark:text-gray-500' }}">
                                        {{ $bg }}
                                    </span>
                                    
                                    <span class="text-xs font-bold mt-1 {{ $hasStock ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}">
                                        <span id="stock-{{ $hospital->id }}-{{ $bg }}">{{ $units }}</span> <span class="text-[9px] uppercase tracking-wider font-semibold opacity-70">u</span>
                                    </span>
                                    
                                    @if($hasStock)
                                        <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-500 to-red-600"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-5 pt-4 border-t border-gray-100 dark:border-white/10 flex gap-3">
                        <a href="tel:{{ $hospital->phone }}" class="flex-1 flex items-center justify-center px-3 py-2.5 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl transition-all duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Call
                        </a>
                        <a href="{{ route('donors.index') }}?city={{ urlencode($hospital->city) }}" class="flex-1 flex items-center justify-center px-3 py-2.5 bg-gray-50 dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/10 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 text-sm font-bold rounded-xl transition-all duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Find Donors
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white dark:bg-[#0a0a0a] rounded-2xl shadow-sm border border-gray-100 dark:border-white/5">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-red-50 dark:bg-red-900/20 mb-4">
                    <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No hospitals found</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">We couldn't find any hospitals registered in {{ $city ?? 'this area' }}.</p>
            </div>
        @endforelse
    </div>
</div>

        <div class="mt-8">
            {{ $hospitals->withQueryString()->links() }}
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.Echo !== 'undefined') {
            window.Echo.channel('blood-inventory')
                .listen('BloodInventoryUpdated', (e) => {
                    // e.hospitalId, e.bloodGroup, e.units
                    const stockElement = document.getElementById(`stock-${e.hospitalId}-${e.bloodGroup}`);
                    if (stockElement) {
                        stockElement.innerText = e.units;
                        
                        // Add flash animation
                        const container = stockElement.closest('.relative');
                        if (container) {
                            const originalClasses = container.className;
                            container.className = "relative overflow-hidden rounded-xl border p-2.5 flex flex-col items-center justify-center transition-all bg-green-500/20 border-green-500 transform scale-110";
                            
                            setTimeout(() => {
                                // We check if it has stock now to apply correct class
                                const hasStock = parseInt(e.units) > 0;
                                const newClasses = hasStock 
                                    ? 'relative overflow-hidden rounded-xl border p-2.5 flex flex-col items-center justify-center transition-all bg-red-50/50 dark:bg-red-900/10 border-red-200 dark:border-red-500/20'
                                    : 'relative overflow-hidden rounded-xl border p-2.5 flex flex-col items-center justify-center transition-all bg-gray-50 dark:bg-white/5 border-gray-100 dark:border-white/10 opacity-60';
                                container.className = newClasses;
                            }, 500);
                        }
                    }
                });
        }
    });
</script>
@endpush
@endsection
