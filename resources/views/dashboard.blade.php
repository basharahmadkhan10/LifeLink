@extends('layouts.main')

@section('content')
<div class="bg-gray-50 dark:bg-black py-12 min-h-[calc(100vh-4rem)] transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">My Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Welcome back, <span class="font-semibold text-gray-900 dark:text-gray-200">{{ auth()->user()->name }}</span>!</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 dark:border-white/10 rounded-xl shadow-sm text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Edit Profile
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Info Card -->
            <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-sm hover:shadow-xl dark:hover:shadow-white/5 border border-gray-100 dark:border-white/5 p-8 lg:col-span-1 h-fit relative overflow-hidden transition-all duration-300">

                <!-- Availability Toggle (top-right) -->
                @php
                    $inCooldown = auth()->user()->last_donated_at && auth()->user()->last_donated_at->diffInDays(now()) < 90;
                    $displayStatus = $inCooldown ? 'unavailable' : auth()->user()->availability_status;
                @endphp
                <div class="absolute top-0 right-0 p-4">
                    <div class="flex items-center gap-2.5">
                        <span id="toggle-label" class="text-xs font-extrabold tracking-widest uppercase transition-colors duration-300 {{ $displayStatus === 'available' ? 'text-green-500' : 'text-gray-400 dark:text-gray-500' }}">
                            {{ $displayStatus === 'available' ? 'ACTIVE' : 'INACTIVE' }}
                        </span>
                        {{-- Toggle switch --}}
                        <button
                            id="availability-toggle"
                            type="button"
                            role="switch"
                            {{ $inCooldown ? 'disabled' : '' }}
                            aria-checked="{{ $displayStatus === 'available' ? 'true' : 'false' }}"
                            data-status="{{ $displayStatus }}"
                            class="relative inline-flex h-7 w-14 shrink-0 items-center rounded-full border-2 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-[#0a0a0a]
                                {{ $inCooldown ? 'opacity-50 cursor-not-allowed' : '' }}
                                {{ $displayStatus === 'available'
                                    ? 'bg-green-500 border-green-500'
                                    : 'bg-gray-200 border-gray-300 dark:bg-white/10 dark:border-white/20' }}"
                        >
                            <span
                                id="toggle-knob"
                                class="inline-block h-4.5 w-4.5 h-[18px] w-[18px] transform rounded-full bg-white shadow-md transition-transform duration-300
                                    {{ $displayStatus === 'available' ? 'translate-x-[30px]' : 'translate-x-[3px]' }}"
                            ></span>
                        </button>
                    </div>
                </div>

                @if(auth()->user()->last_donated_at && auth()->user()->last_donated_at->diffInDays(now()) < 90)
                    <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-xl text-[10px] font-bold text-red-600 dark:text-red-400 text-center leading-relaxed">
                        <span class="block uppercase tracking-wider mb-0.5">Medical Cooldown Active</span>
                        You can donate again on <br>
                        <span class="text-xs font-black">{{ auth()->user()->last_donated_at->addDays(90)->format('M d, Y') }}</span>
                    </div>
                @endif

                <!-- Avatar -->
                <div class="flex items-center justify-center mb-8 mt-4">
                    <div class="h-28 w-28 rounded-full bg-gradient-to-br from-red-100 to-red-50 dark:from-red-900/30 dark:to-red-900/10 flex items-center justify-center text-red-600 dark:text-red-400 font-black text-4xl border-4 border-white dark:border-[#0a0a0a] shadow-xl shadow-red-500/10 relative">
                        {{ auth()->user()->blood_group ?? '?' }}
                        <div id="status-dot" class="absolute bottom-0 right-0 h-6 w-6 rounded-full border-2 border-white dark:border-[#0a0a0a] transition-colors duration-300 {{ $displayStatus === 'available' ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 dark:bg-white/5 rounded-2xl p-4 border border-gray-100 dark:border-white/5">
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Contact Number</div>
                        <div class="font-semibold text-gray-900 dark:text-white">{{ auth()->user()->phone ?? 'Not provided' }}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-white/5 rounded-2xl p-4 border border-gray-100 dark:border-white/5">
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">City/Location</div>
                        <div class="font-semibold text-gray-900 dark:text-white">{{ auth()->user()->city ?? 'Location not set' }}</div>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/10 rounded-2xl p-4 border border-red-100 dark:border-red-900/20 flex justify-between items-center">
                        <div>
                            <div class="text-xs font-bold text-red-500 uppercase tracking-wider mb-1">Reward Points</div>
                            <div class="font-black text-xl text-red-700 dark:text-red-400">{{ \App\Models\Point::where('user_id', auth()->id())->sum('amount') }} PTS</div>
                        </div>
                        <div class="h-10 w-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center text-red-600 dark:text-red-400">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100 dark:border-white/5">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center px-4 py-3 bg-gray-50 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 rounded-xl transition-colors duration-200 font-bold border border-gray-200 dark:border-white/10">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>

            <!-- Activity Area -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Emergency Action -->
                <div class="bg-gradient-to-r from-red-600 to-red-900 rounded-3xl p-8 shadow-xl shadow-red-600/20 text-white relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-5 rounded-full transform translate-x-1/2 -translate-y-1/2 group-hover:scale-110 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div>
                            <h2 class="text-2xl font-extrabold mb-2">Emergency Broadcast</h2>
                            <p class="text-red-100 opacity-90 max-w-md">Instantly alert matching donors in your city about a critical blood requirement.</p>
                        </div>
                        <a href="{{ route('emergency.create') }}" class="shrink-0 inline-flex items-center justify-center px-8 py-4 bg-white text-red-600 font-black rounded-xl hover:bg-gray-50 transition-colors shadow-lg">
                            <svg class="w-6 h-6 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            BROADCAST
                        </a>
                    </div>
                </div>

                <!-- Active Emergencies Container -->
                <div id="active-emergencies-container" class="space-y-4 ">
                    @if($emergencies->isNotEmpty())
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ auth()->user()->blood_group }} Needed Near You
                            <span class="ml-1 text-xs font-semibold text-gray-400 dark:text-gray-500 normal-case">in {{ auth()->user()->city ?? 'your area' }}</span>
                        </h2>
                        
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($emergencies as $emergency)
                                <div data-emergency-id="{{ $emergency->id }}" class="group bg-white dark:bg-[#0a0a0a] border border-red-200/60 dark:border-red-500/20 rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                                    <div class="p-4 flex items-center justify-between gap-3">
                                        <!-- Left content: compact info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-md font-black uppercase tracking-wide bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                                    {{ $emergency->blood_group }}
                                                </span>
                                                @php
                                                    $user = auth()->user();
                                                    $isExactMatch = $emergency->blood_group === $user->blood_group && strtolower(trim($emergency->city)) === strtolower(trim($user->city));
                                                @endphp
                                                @if($isExactMatch)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wide bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                                        Exact Match
                                                    </span>
                                                @endif
                                                @if($emergency->is_hospital_verified)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wide bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400" title="Verified by a registered hospital">
                                                        <svg class="h-3 w-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                        Verified
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                                    {{ $emergency->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <div class="mt-1">
                                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                                    {{ $emergency->patient_name }}
                                                </p>
                                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                    <span class="truncate">{{ $emergency->hospital_name }}</span>
                                                    <span>•</span>
                                                    <span class="truncate">{{ $emergency->city }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right: action button -->
                                        <div class="shrink-0">
                                            <form action="{{ route('donation_request.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="to_user_id" value="{{ $emergency->user_id }}">
                                                <button type="submit" class="inline-flex items-center px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-extrabold rounded-lg shadow-sm transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                                                    Help
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- My Blood Requests -->
<div class="space-y-4">
    <h2 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">My Requests</h2>
    
    @if($requests->isEmpty())
        <div class="p-6 text-center bg-white dark:bg-[#0a0a0a] border border-gray-100 dark:border-white/5 rounded-2xl text-gray-500 dark:text-gray-400 text-sm">
            You have not posted any blood requests yet.
        </div>
    @else
        <div class="grid grid-cols-1 gap-3">
            @foreach($requests as $req)
                <div class="group bg-white dark:bg-[#0a0a0a] border border-gray-200/60 dark:border-white/10 rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="p-4">
                        <!-- Top row: status and date -->
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-black uppercase tracking-wide 
                                    {{ $req->status === 'Pending' 
                                        ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' 
                                        : ($req->status === 'Completed' 
                                            ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                                            : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400') }}">
                                    {{ $req->status }}
                                </span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $req->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-black uppercase tracking-wide bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                {{ $req->blood_group }}
                            </span>
                        </div>

                        <!-- Patient and hospital info -->
                        <div class="mt-1 mb-3">
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                {{ $req->patient_name }}
                            </p>
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                <span class="truncate">{{ $req->hospital_name }}</span>
                                <span>•</span>
                                <span class="truncate">{{ $req->city }}</span>
                            </div>
                        </div>

                        <!-- Action buttons - compact -->
                        @if($req->status === 'Pending')
                            @php
                                $hasHelp = in_array((string)$req->id, $helpOfferedRequestIds ?? []);
                            @endphp
                            <div id="my-req-actions-{{ $req->id }}" class="flex items-center gap-2 justify-end" data-has-help="{{ $hasHelp ? 'true' : 'false' }}">
                                @if($hasHelp)
                                    <a href="{{ route('inbox.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold rounded-md shadow-sm transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                                        <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                        Check Inbox
                                    </a>
                                    <form action="{{ route('emergency.status', $req->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Cancelled">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md border bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/15 text-gray-700 dark:text-gray-300 text-xs font-extrabold border-gray-200 dark:border-white/10 transition-all">
                                            <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Cancel
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-500 text-[11px] font-extrabold rounded-md uppercase tracking-wider">
                                        Waiting for donors...
                                    </span>
                                    <form action="{{ route('emergency.status', $req->id) }}" method="POST" class="ml-1">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Cancelled">
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md border bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/15 text-gray-700 dark:text-gray-300 text-[11px] font-extrabold border-gray-200 dark:border-white/10 transition-all uppercase tracking-wider">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const btn       = document.getElementById('availability-toggle');
    const knob      = document.getElementById('toggle-knob');
    const label     = document.getElementById('toggle-label');
    const statusDot = document.getElementById('status-dot');

    if (!btn) return;

    btn.addEventListener('click', function () {
        const isNowAvailable = btn.dataset.status !== 'available';

        // Toggle pill background + border
        if (isNowAvailable) {
            btn.className = btn.className.replace(/bg-gray-200|border-gray-300|dark:bg-white\/10|dark:border-white\/20/g, '');
            btn.classList.add('bg-green-500', 'border-green-500');
        } else {
            btn.className = btn.className.replace(/bg-green-500|border-green-500/g, '');
            btn.classList.add('bg-gray-200', 'border-gray-300');
        }

        // Slide knob
        knob.classList.toggle('translate-x-[30px]', isNowAvailable);
        knob.classList.toggle('translate-x-[3px]',  !isNowAvailable);

        // Label text & colour
        label.textContent = isNowAvailable ? 'ACTIVE' : 'INACTIVE';
        label.classList.toggle('text-green-500', isNowAvailable);
        label.classList.toggle('text-gray-400',  !isNowAvailable);

        // Avatar status dot
        statusDot.classList.toggle('bg-green-500', isNowAvailable);
        statusDot.classList.toggle('bg-gray-300',  !isNowAvailable);

        btn.dataset.status = isNowAvailable ? 'available' : 'unavailable';
        btn.setAttribute('aria-checked', String(isNowAvailable));

        // AJAX call
        fetch('{{ route('donor.toggle') }}', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status !== 'success') revert(!isNowAvailable);
        })
        .catch(() => revert(!isNowAvailable));
    });

    function revert(wasAvailable) {
        btn.dataset.status = wasAvailable ? 'available' : 'unavailable';
        knob.classList.toggle('translate-x-[30px]', wasAvailable);
        knob.classList.toggle('translate-x-[3px]',  !wasAvailable);
        label.textContent = wasAvailable ? 'ACTIVE' : 'INACTIVE';
        label.classList.toggle('text-green-500', wasAvailable);
        label.classList.toggle('text-gray-400',  !wasAvailable);
        statusDot.classList.toggle('bg-green-500', wasAvailable);
        statusDot.classList.toggle('bg-gray-300',  !wasAvailable);
    }
})();

// Real-time Active Emergencies update polling
(function() {
    const container = document.getElementById('active-emergencies-container');
    if (!container) return;

    let currentIds = new Set(Array.from(container.querySelectorAll('[data-emergency-id]')).map(el => el.dataset.emergencyId));
    
    setInterval(function() {
        fetch('{{ route('emergencies.active') }}')
            .then(r => r.json())
            .then(data => {
                const newEmergencies = data.emergencies || [];
                const newIds = new Set(newEmergencies.map(e => e._id || e.id));
                
                // Compare IDs to see if the list changed
                let changed = currentIds.size !== newIds.size;
                if (!changed) {
                    for (let id of newIds) {
                        if (!currentIds.has(id)) {
                            changed = true;
                            break;
                        }
                    }
                }
                
                if (changed) {
                    currentIds = newIds;
                    if (newEmergencies.length === 0) {
                        container.innerHTML = '';
                        return;
                    }
                    
                    let html = `
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            ${escapeHtml(data.user_blood_group)} Needed Near You
                            <span class="ml-1 text-xs font-semibold text-gray-400 dark:text-gray-500 normal-case">in ${escapeHtml(data.user_city || 'your area')}</span>
                        </h2>
                        <div class="grid grid-cols-1 gap-3 mt-4">
                    `;
                    
                    newEmergencies.forEach(e => {
                        const id = e._id || e.id;
                        const exactMatchBadge = (e.blood_group === data.user_blood_group && (e.city || '').toLowerCase().trim() === (data.user_city || '').toLowerCase().trim()) 
                            ? `<span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wide bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">Exact Match</span>`
                            : '';
                            
                        const verifiedBadge = e.is_hospital_verified 
                            ? `<span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wide bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400" title="Verified by a registered hospital">
                                <svg class="h-3 w-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Verified
                               </span>`
                            : '';
                        
                        html += `
                            <div data-emergency-id="${id}" class="group bg-white dark:bg-[#0a0a0a] border border-red-200/60 dark:border-red-500/20 rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                                <div class="p-4 flex items-center justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-black uppercase tracking-wide bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                                ${escapeHtml(e.blood_group)}
                                            </span>
                                            ${exactMatchBadge}
                                            ${verifiedBadge}
                                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                                ${escapeHtml(e.created_at_diff || 'Just updated')}
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                                ${escapeHtml(e.patient_name)}
                                            </p>
                                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                <span class="truncate">${escapeHtml(e.hospital_name)}</span>
                                                <span>•</span>
                                                <span class="truncate">${escapeHtml(e.city)}</span>
                                            </div>
                                            <div class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                <span class="truncate">${escapeHtml(e.address)}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <form action="${data.donation_request_store_url}" method="POST">
                                            <input type="hidden" name="_token" value="${data.csrf_token}">
                                            <input type="hidden" name="to_user_id" value="${escapeHtml(e.user_id)}">
                                            <button type="submit" class="inline-flex items-center px-3.5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-extrabold rounded-lg shadow-sm transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                                                Help
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += `</div>`;
                    container.innerHTML = html;
                }

                // Also update "My Requests" waiting labels in real-time
                if (data.my_requests && data.help_offered_request_ids) {
                    data.my_requests.forEach(req => {
                        if (req.status !== 'Pending') return;
                        
                        const id = req._id || req.id;
                        const actionContainer = document.getElementById('my-req-actions-' + id);
                        
                        if (actionContainer) {
                            const hasHelpNow = data.help_offered_request_ids.includes(id.toString());
                            const hasHelpWas = actionContainer.dataset.hasHelp === 'true';
                            
                            if (hasHelpNow && !hasHelpWas) {
                                // Swap out the waiting label for the Check Inbox / Cancel buttons
                                actionContainer.dataset.hasHelp = 'true';
                                actionContainer.innerHTML = `
                                    <a href="/inbox" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold rounded-md shadow-sm transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                                        <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                        Check Inbox
                                    </a>
                                    <form action="/emergency/${id}/status" method="POST">
                                        <input type="hidden" name="_token" value="${data.csrf_token}">
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="hidden" name="status" value="Cancelled">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md border bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/15 text-gray-700 dark:text-gray-300 text-xs font-extrabold border-gray-200 dark:border-white/10 transition-all">
                                            <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Cancel
                                        </button>
                                    </form>
                                `;
                            }
                        }
                    });
                }
            })
            .catch(err => console.warn('Active emergencies fetch warning:', err));
    }, 5000);
})();

function escapeHtml(unsafe) {
    return (unsafe || '').toString()
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
</script>
@endsection