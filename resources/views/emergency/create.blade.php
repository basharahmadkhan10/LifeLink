@extends('layouts.main')

@section('content')
<div class="bg-gray-50 dark:bg-black py-12 min-h-[calc(100vh-4rem)] transition-colors duration-200">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-red-600 dark:text-red-500 mb-8 tracking-tight text-center">Emergency Blood Broadcast</h1>

        <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-xl shadow-red-900/5 border border-gray-100 dark:border-white/5 transition-all p-6 sm:p-8">
            <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 text-sm font-bold text-center">
                ⚠️ Fill this form only for critical emergencies. This will instantly notify the nearest active donors with matching blood groups.
            </div>

            <form method="POST" action="{{ route('emergency.store') }}" class="space-y-6">
                @csrf
                
                <div>
                    <x-input-label for="patient_name" value="Patient Name" />
                    <x-text-input id="patient_name" class="block mt-1 w-full" type="text" name="patient_name" required autofocus />
                </div>

                <div>
                    <x-input-label for="blood_group" value="Blood Group Needed" />
                    <select id="blood_group" name="blood_group" class="block mt-1 w-full border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 text-gray-900 dark:text-gray-100 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm transition-colors duration-200" required>
                        <option value="" class="dark:bg-black">Select Blood Group</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                            <option value="{{ $bg }}" class="dark:bg-black">{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="units_needed" value="Units Needed" />
                    <x-text-input id="units_needed" class="block mt-1 w-full" type="number" name="units_needed" min="1" required />
                </div>

                <div>
                    <x-input-label for="hospital_name" value="Hospital Name" />
                    <x-text-input id="hospital_name" class="block mt-1 w-full" type="text" name="hospital_name" required />
                </div>

                <div>
                    <x-input-label for="city" value="City" />
                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" required />
                </div>

                <div>
                    <x-input-label for="address" value="Detailed Hospital Address" />
                    <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 text-gray-900 dark:text-gray-100 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm transition-colors duration-200" required></textarea>
                </div>

                <!-- Geolocation for hospital -->
                <input type="hidden" id="lat" name="lat">
                <input type="hidden" id="lng" name="lng">
                <p class="text-xs text-gray-500 dark:text-gray-400" id="geo-status">We will try to get your location to find nearby donors...</p>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-all transform hover:scale-105 active:scale-95 shadow-md shadow-red-600/30 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-black">
                        Broadcast Emergency
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.addEventListener('load', () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                document.getElementById('lat').value = position.coords.latitude;
                document.getElementById('lng').value = position.coords.longitude;
                document.getElementById('geo-status').innerText = 'Location acquired. Ready to find nearest donors.';
                document.getElementById('geo-status').classList.remove('text-gray-500');
                document.getElementById('geo-status').classList.add('text-green-600', 'dark:text-green-400');
            }, () => {
                document.getElementById('geo-status').innerText = 'Failed to acquire location. Proceeding with city match.';
            });
        }
    });
</script>
@endsection
