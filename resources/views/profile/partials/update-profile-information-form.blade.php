<section>
    <header>
        <h2 class="text-lg font-extrabold text-gray-900 dark:text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="blood_group" :value="__('Blood Group')" />
            <select id="blood_group" name="blood_group" class="mt-1 block w-full border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 text-gray-900 dark:text-gray-100 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm transition-colors duration-200">
                <option value="" class="dark:bg-black">Select Blood Group</option>
                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                    <option value="{{ $bg }}" {{ old('blood_group', $user->blood_group) == $bg ? 'selected' : '' }} class="dark:bg-black">{{ $bg }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('blood_group')" />
        </div>

        <div>
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 text-gray-900 dark:text-gray-100 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm transition-colors duration-200">
                <option value="" class="dark:bg-black">Select Gender</option>
                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }} class="dark:bg-black">Male</option>
                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }} class="dark:bg-black">Female</option>
                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }} class="dark:bg-black">Other</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
        </div>

        <div>
            <x-input-label for="age" :value="__('Age')" />
            <x-text-input id="age" name="age" type="number" class="mt-1 block w-full" :value="old('age', $user->age)" min="18" max="65" />
            <x-input-error class="mt-2" :messages="$errors->get('age')" />
        </div>

        <div>
            <x-input-label for="city" :value="__('City')" />
            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $user->city)" />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <div>
            <x-input-label for="address" :value="__('Address')" />
            <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 text-gray-900 dark:text-gray-100 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm transition-colors duration-200">{{ old('address', $user->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <!-- Hidden fields for Geolocation -->
        <input type="hidden" name="location[type]" value="Point">
        <input type="hidden" id="lng" name="location[coordinates][0]" value="{{ old('location.coordinates.0', $user->location['coordinates'][0] ?? '') }}">
        <input type="hidden" id="lat" name="location[coordinates][1]" value="{{ old('location.coordinates.1', $user->location['coordinates'][1] ?? '') }}">
        
        <div>
            <button type="button" id="getLocationBtn" class="inline-flex items-center px-4 py-2 bg-white dark:bg-white/10 border border-gray-300 dark:border-white/10 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-black disabled:opacity-25 transition ease-in-out duration-150">
                Update My Location
            </button>
            <span id="locationStatus" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                @if(isset($user->location['coordinates']))
                    Location saved.
                @else
                    No location saved.
                @endif
            </span>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        document.getElementById('getLocationBtn').addEventListener('click', function() {
            const status = document.getElementById('locationStatus');
            status.textContent = 'Locating...';
            
            if (!navigator.geolocation) {
                status.textContent = 'Geolocation is not supported by your browser';
            } else {
                navigator.geolocation.getCurrentPosition((position) => {
                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('lng').value = position.coords.longitude;
                    status.textContent = 'Location updated successfully (Don\'t forget to save)';
                }, () => {
                    status.textContent = 'Unable to retrieve your location';
                });
            }
        });
    </script>
</section>
