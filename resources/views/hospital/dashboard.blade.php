@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-16">
    
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Hospital Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your blood inventory and verify emergency requests.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Verified Institution
            </span>
        </div>
    </div>

    @if(session('status'))
    <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-sm font-semibold text-green-700 dark:text-green-400">
        {{ session('status') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Inventory Section -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-xl shadow-red-900/5 border border-gray-100 dark:border-white/5 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Blood Inventory
                    </h2>
                    <span class="text-xs font-semibold text-gray-400">Real-time units tracking</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach($inventory as $item)
                        <div class="relative bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/5 rounded-2xl p-4 flex flex-col items-center justify-center group hover:border-red-500/30 transition-colors">
                            <span class="text-2xl font-black text-red-600 dark:text-red-500 mb-2">{{ $item->blood_group }}</span>
                            
                            <div class="flex items-center gap-3">
                                <form action="{{ route('hospital.inventory.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="inventory_id" value="{{ $item->id }}">
                                    <input type="hidden" name="action" value="decrement">
                                    <button type="submit" class="h-8 w-8 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-500/20 dark:hover:text-red-400 transition-colors font-bold text-lg">-</button>
                                </form>
                                
                                <span class="text-xl font-extrabold text-gray-900 dark:text-white w-8 text-center" id="units-{{ $item->id }}">{{ $item->units }}</span>
                                
                                <form action="{{ route('hospital.inventory.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="inventory_id" value="{{ $item->id }}">
                                    <input type="hidden" name="action" value="increment">
                                    <button type="submit" class="h-8 w-8 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-green-100 hover:text-green-600 dark:hover:bg-green-500/20 dark:hover:text-green-400 transition-colors font-bold text-lg">+</button>
                                </form>
                            </div>
                            
                            <span class="text-[10px] text-gray-400 uppercase tracking-widest mt-3 font-semibold">Units</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Active Patient Requests Section -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-xl shadow-red-900/5 border border-gray-100 dark:border-white/5 p-6 h-full">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Patient Emergencies
                    </h2>
                    <a href="{{ route('emergency.create') }}" class="text-xs font-bold text-red-600 dark:text-red-400 hover:text-red-700 bg-red-50 dark:bg-red-500/10 px-3 py-1.5 rounded-lg transition-colors">
                        + New Request
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($activeRequests as $req)
                        <div class="p-4 rounded-2xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111]">
                            <div class="flex justify-between items-start mb-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-black uppercase tracking-wide bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                    {{ $req->blood_group }}
                                </span>
                                <span class="text-[10px] font-semibold text-gray-400">{{ $req->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $req->patient_name }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Needed by: {{ \Carbon\Carbon::parse($req->needed_by_date)->format('M d, Y') }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-sm font-medium text-gray-500">No active patient requests.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- Registered Donors Management Section -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Add New Donor Form -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-xl shadow-red-900/5 border border-gray-100 dark:border-white/10 p-6 h-full">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Register Donor
                    </h2>
                </div>

                <form action="{{ route('hospital.donors.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-xl focus:ring-red-500 focus:border-red-500 dark:text-white text-sm transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                        <input type="text" name="phone" required class="w-full px-4 py-2 bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-xl focus:ring-red-500 focus:border-red-500 dark:text-white text-sm transition-colors">
                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Blood Group</label>
                            <select name="blood_group" required class="w-full px-4 py-2 bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-xl focus:ring-red-500 focus:border-red-500 dark:text-white text-sm transition-colors">
                                <option value="" class="bg-white dark:bg-[#0a0a0a] dark:text-white">Select</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                    <option value="{{ $bg }}" class="bg-white dark:bg-[#0a0a0a] dark:text-white">{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                            <select name="gender" required class="w-full px-4 py-2 bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-xl focus:ring-red-500 focus:border-red-500 dark:text-white text-sm transition-colors">
                                <option value="" class="bg-white dark:bg-[#0a0a0a] dark:text-white">Select</option>
                                <option value="Male" class="bg-white dark:bg-[#0a0a0a] dark:text-white">Male</option>
                                <option value="Female" class="bg-white dark:bg-[#0a0a0a] dark:text-white">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Age</label>
                            <input type="number" name="age" min="18" max="65" required class="w-full px-4 py-2 bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-xl focus:ring-red-500 focus:border-red-500 dark:text-white text-sm transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">City</label>
                            <input type="text" name="city" required value="{{ $hospital->city }}" class="w-full px-4 py-2 bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-xl focus:ring-red-500 focus:border-red-500 dark:text-white text-sm transition-colors">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors shadow-md mt-4">
                        Register Verified Donor
                    </button>
                </form>
            </div>
        </div>

        <!-- Registered Donors List -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-xl shadow-red-900/5 border border-gray-100 dark:border-white/10 p-6 h-full overflow-hidden flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Your Registered Donors
                    </h2>
                    <span class="text-xs font-semibold text-gray-400 bg-gray-100 dark:bg-white/5 px-3 py-1 rounded-full">{{ $hospitalDonors->count() }} Total</span>
                </div>

                <div class="flex-1 overflow-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-white/10 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <th class="pb-3 px-4">Name</th>
                                <th class="pb-3 px-4">Blood</th>
                                <th class="pb-3 px-4">Phone</th>
                                <th class="pb-3 px-4">Status</th>
                                <th class="pb-3 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @forelse($hospitalDonors as $donor)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <td class="py-3 px-4 font-bold text-gray-900 dark:text-white">{{ $donor->name }}</td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-black bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                            {{ $donor->blood_group }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-500 dark:text-gray-400">{{ $donor->phone }}</td>
                                    <td class="py-3 px-4">
                                        @if($donor->availability_status === 'available')
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-bold bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <form action="{{ route('hospital.donors.toggle', $donor->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-lg border transition-colors
                                                {{ $donor->availability_status === 'available' 
                                                    ? 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' 
                                                    : 'border-green-300 dark:border-green-700/50 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20' }}">
                                                {{ $donor->availability_status === 'available' ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                                        You haven't registered any donors yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // AJAX for updating inventory smoothly
    document.querySelectorAll('form[action="{{ route("hospital.inventory.update") }}"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if(data.status === 'success') {
                    const id = formData.get('inventory_id');
                    document.getElementById('units-' + id).innerText = data.units;
                }
            })
            .catch(err => console.error('Error updating inventory', err));
        });
    });
</script>
@endsection
