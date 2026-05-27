<x-guest-layout>
    <x-slot name="isRegister">true</x-slot>

    <h2 class="auth-heading">Create your account</h2>
    <p class="auth-subheading">Join LifeLink and start saving lives today.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Hidden GPS fields -->
        <input type="hidden" name="lat" id="lat" value="">
        <input type="hidden" name="lng" id="lng" value="">

        @php $iconStyle = "position:absolute;left:13px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF;pointer-events:none;"; @endphp

        <!-- Name -->
        <div class="form-group">
            <label class="auth-label" for="name">Full Name</label>
            <div style="position:relative;">
                <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                <input id="name" class="auth-input" style="padding-left:40px;" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
            </div>
            @error('name')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <!-- Email -->
        <div class="form-group">
            <label class="auth-label" for="email">Email Address</label>
            <div style="position:relative; display: flex; gap: 8px;">
                <div style="position:relative; flex-grow: 1;">
                    <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                    <input id="email" class="auth-input text-gray-900 dark:text-white" style="padding-left:40px; width: 100%;" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@email.com" oninput="resetVerification('email')">
                </div>
                <button type="button" id="btn-verify-email" onclick="sendOtp('email')" class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-[10px] uppercase tracking-widest transition ease-in-out duration-150">Verify</button>
            </div>
            <p id="email-status" class="text-sm mt-1 hidden"></p>
            @error('email')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <!-- Phone -->
        <div class="form-group">
            <label class="auth-label" for="phone">Phone Number</label>
            <div style="position:relative; display: flex; gap: 8px;">
                <div style="position:relative; flex-grow: 1;">
                    <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                    <input id="phone" class="auth-input text-gray-900 dark:text-white" style="padding-left:40px; width: 100%;" type="text" name="phone" value="{{ old('phone') }}" required placeholder="+91 98765 43210" oninput="resetVerification('phone')">
                </div>
                <button type="button" id="btn-verify-phone" onclick="sendOtp('phone')" class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-[10px] uppercase tracking-widest transition ease-in-out duration-150">Verify</button>
            </div>
            <p id="phone-status" class="text-sm mt-1 hidden"></p>
            @error('phone')<p class="field-error">{{ $message }}</p>@enderror
        </div>



        <!-- City & Address -->
        <div class="form-row">
            <div class="form-group">
                <label class="auth-label" for="city">City</label>
                <div style="position:relative;">
                    <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" /></svg>
                    <input id="city" class="auth-input" style="padding-left:40px;" type="text" name="city" value="{{ old('city') }}" required placeholder="E.g. New Delhi">
                </div>
                @error('city')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="auth-label" for="address">Full Address</label>
                <div style="position:relative;">
                    <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                    <input id="address" class="auth-input" style="padding-left:40px;" type="text" name="address" value="{{ old('address') }}" required placeholder="123 Main St, Area...">
                </div>
                @error('address')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Register As (RBAC) -->
        <div class="form-group">
            <span class="radio-group-label">Register As</span>
            <div class="radio-options">
                <label class="radio-option {{ old('role') == 'user' || !old('role') ? 'selected' : '' }}">
                    <input type="radio" name="role" value="user" onclick="toggleFields(); updateRadioStyles(this)" {{ old('role') == 'user' || !old('role') ? 'checked' : '' }} required>
                    <svg style="width:17px;height:17px;flex-shrink:0;color:#9CA3AF;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    <span>Donor / Patient</span>
                </label>
                <label class="radio-option {{ old('role') == 'hospital' ? 'selected' : '' }}">
                    <input type="radio" name="role" value="hospital" onclick="toggleFields(); updateRadioStyles(this)" {{ old('role') == 'hospital' ? 'checked' : '' }} required>
                    <svg style="width:17px;height:17px;flex-shrink:0;color:#9CA3AF;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                    <span>Hospital / Blood Bank</span>
                </label>
            </div>
            @error('role')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <!-- Donor-only fields -->
        <div id="donor_fields">
            <!-- Age & Gender -->
            <div class="form-row">
                <div class="form-group">
                    <label class="auth-label" for="age">Age</label>
                    <div style="position:relative;">
                        <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <input id="age" class="auth-input" style="padding-left:40px;" type="number" name="age" value="{{ old('age') }}" min="18" max="65" placeholder="E.g. 25">
                    </div>
                    @error('age')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="auth-label" for="gender">Gender</label>
                    <div style="position:relative;">
                        <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                        <select id="gender" name="gender" class="auth-input" style="padding-left:40px;">
                            <option value="" class="bg-white dark:bg-[#0a0a0a] dark:text-white">Select Gender</option>
                            <option value="Male" class="bg-white dark:bg-[#0a0a0a] dark:text-white" @selected(old('gender')=='Male')>Male</option>
                            <option value="Female" class="bg-white dark:bg-[#0a0a0a] dark:text-white" @selected(old('gender')=='Female')>Female</option>
                            <option value="Other" class="bg-white dark:bg-[#0a0a0a] dark:text-white" @selected(old('gender')=='Other')>Other</option>
                        </select>
                    </div>
                    @error('gender')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="auth-label" for="blood_group">Blood Group</label>
                <div style="position:relative;">
                    <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    <select id="blood_group" name="blood_group" class="auth-input" style="padding-left:40px;">
                        <option value="" class="bg-white dark:bg-[#0a0a0a] dark:text-white">Select Blood Group (Optional for Hospitals)</option>
                        <option value="A+" class="bg-white dark:bg-[#0a0a0a] dark:text-white" @selected(old('blood_group')=='A+')>A+</option>
                        <option value="A-" class="bg-white dark:bg-[#0a0a0a] dark:text-white" @selected(old('blood_group')=='A-')>A-</option>
                        <option value="B+" class="bg-white dark:bg-[#0a0a0a] dark:text-white" @selected(old('blood_group')=='B+')>B+</option>
                        <option value="B-" class="bg-white dark:bg-[#0a0a0a] dark:text-white" @selected(old('blood_group')=='B-')>B-</option>
                        <option value="AB+" class="bg-white dark:bg-[#0a0a0a] dark:text-white" @selected(old('blood_group')=='AB+')>AB+</option>
                        <option value="AB-" class="bg-white dark:bg-[#0a0a0a] dark:text-white" @selected(old('blood_group')=='AB-')>AB-</option>
                        <option value="O+" class="bg-white dark:bg-[#0a0a0a] dark:text-white" @selected(old('blood_group')=='O+')>O+</option>
                        <option value="O-" class="bg-white dark:bg-[#0a0a0a] dark:text-white" @selected(old('blood_group')=='O-')>O-</option>
                    </select>
                </div>
                @error('blood_group')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Hospital-only fields -->
        <div id="hospital_fields" style="display:none;">
            <div class="form-group">
                <label class="auth-label" for="license_number">Hospital License Number</label>
                <div style="position:relative;">
                    <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.673 4.673a2.25 2.25 0 01-3.182 0l-3.369-3.369a2.25 2.25 0 010-3.182l4.673-4.673M15.75 11.25V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.328M15.75 11.25v2.25M15.75 11.25H21M8.25 5.578v1.097" /></svg>
                    <input id="license_number" class="auth-input" style="padding-left:40px;" type="text" name="license_number" value="{{ old('license_number') }}" placeholder="E.g. HOS-12345-DL">
                </div>
                @error('license_number')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Password & Confirm -->
        <div class="form-row">
            <div class="form-group">
                <label class="auth-label" for="password">Password</label>
                <div style="position:relative;">
                    <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                    <input id="password" class="auth-input" style="padding-left:40px;" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                </div>
                @error('password')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="auth-label" for="password_confirmation">Confirm Password</label>
                <div style="position:relative;">
                    <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                    <input id="password_confirmation" class="auth-input" style="padding-left:40px;" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                </div>
                @error('password_confirmation')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Terms & GPS Status -->
        <div class="form-group" style="margin-bottom:6px;">
            <label class="auth-checkbox-row">
                <input type="checkbox" required class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                <span>I agree to the <a href="#" class="auth-link">Terms of Service</a> and <a href="#" class="auth-link">Privacy Policy</a>. Data handled with medical-grade confidentiality.</span>
            </label>
            <div id="gps-status" class="mt-2 text-xs flex items-center gap-1 text-gray-500 dark:text-gray-400 font-semibold bg-gray-50 dark:bg-white/5 p-2 rounded-lg border border-gray-100 dark:border-white/5 w-fit">
                <svg class="animate-spin h-3 w-3 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Detecting location for nearby matching...
            </div>
        </div>
        <div class="mt-8 flex items-center justify-between">
            <a class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 font-medium transition-colors" href="{{ route('login') }}">
                Already registered?
            </a>

            <button type="submit" id="btn-register" disabled class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-sm opacity-50 cursor-not-allowed">
                Create Account
            </button>
        </div>
    </form>

    <!-- OTP Modal -->
    <div id="otpModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
        <div class="bg-white dark:bg-[#111111] p-6 rounded-2xl w-full max-w-sm border border-gray-100 dark:border-white/10 shadow-xl">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2" id="otpModalTitle">Verify</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4" id="otpModalDesc">Enter the 6-digit code sent to you.</p>
            
            <input type="hidden" id="currentVerifyType" value="">
            <input type="text" id="otp_input" class="w-full bg-gray-50 dark:bg-[#222] border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white text-center text-2xl tracking-[0.5em] font-mono mb-4 focus:ring-red-500 focus:border-red-500" maxlength="6" placeholder="000000">
            
            <div class="flex gap-2">
                <button type="button" onclick="closeOtpModal()" class="flex-1 px-4 py-2 bg-gray-100 dark:bg-white/5 rounded-xl font-bold text-gray-700 dark:text-gray-300">Cancel</button>
                <button type="button" onclick="verifyOtp()" id="btn-verify-submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold">Verify</button>
            </div>
        </div>
    </div>

    <p class="auth-link-row">
        Already have an account? <a href="{{ route('login') }}" class="auth-link">Log in here</a>
    </p>

    <script>
        function toggleFields() {
            let isUser = true;
            document.querySelectorAll('input[name="role"]').forEach(r => {
                if(r.checked && r.value !== 'user') isUser = false;
            });
            document.getElementById('donor_fields').style.display = isUser ? 'block' : 'none';
            document.getElementById('hospital_fields').style.display = isUser ? 'none' : 'block';
            if(!isUser) {
                document.getElementById('blood_group').value = '';
                document.getElementById('age').value = '';
                document.getElementById('gender').value = '';
            } else {
                document.getElementById('license_number').value = '';
            }
        }
        function updateRadioStyles(radio) {
            document.querySelectorAll('.radio-option').forEach(el => {
                el.classList.remove('selected');
                el.querySelector('svg').style.color = '#9CA3AF'; // reset icon color
            });
            radio.closest('.radio-option').classList.add('selected');
        }

        function initGPS() {
            const statusEl = document.getElementById('gps-status');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        document.getElementById('lat').value = position.coords.latitude;
                        document.getElementById('lng').value = position.coords.longitude;
                        statusEl.innerHTML = `<svg class="h-3 w-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <span class="text-green-600 dark:text-green-400">Location secured securely.</span>`;
                    },
                    function(error) {
                        statusEl.innerHTML = `<svg class="h-3 w-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> <span class="text-yellow-600 dark:text-yellow-500">Location access denied. Matching may be less accurate.</span>`;
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                statusEl.innerHTML = 'Geolocation is not supported by this browser.';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleFields();
            initGPS();
        });
        // OTP Verification Logic
        let verifiedStates = { 
            email: {{ session('verified_email') && session('verified_email') == old('email') ? 'true' : 'false' }}, 
            phone: {{ session('verified_phone') && session('verified_phone') == old('phone') ? 'true' : 'false' }} 
        };

        // On load, if already verified (due to validation error reload), update UI
        window.addEventListener('DOMContentLoaded', () => {
            if (verifiedStates.email) {
                let btn = document.getElementById('btn-verify-email');
                btn.innerHTML = '✓ Verified';
                btn.className = "inline-flex items-center justify-center px-3 py-1.5 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl font-bold text-green-700 dark:text-green-400 uppercase tracking-widest text-[10px] transition";
                btn.disabled = true;
            }
            if (verifiedStates.phone) {
                let btn = document.getElementById('btn-verify-phone');
                btn.innerHTML = '✓ Verified';
                btn.className = "inline-flex items-center justify-center px-3 py-1.5 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl font-bold text-green-700 dark:text-green-400 uppercase tracking-widest text-[10px] transition";
                btn.disabled = true;
            }
            checkRegisterButton();
        });

        function resetVerification(type) {
            verifiedStates[type] = false;
            let btn = document.getElementById(`btn-verify-${type}`);
            btn.innerHTML = 'Verify';
            btn.className = "inline-flex items-center justify-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-[10px] uppercase tracking-widest transition ease-in-out duration-150";
            btn.disabled = false;
            
            let status = document.getElementById(`${type}-status`);
            status.classList.add('hidden');
            
            checkRegisterButton();
        }

        function checkRegisterButton() {
            let btn = document.getElementById('btn-register');
            if (verifiedStates.email && verifiedStates.phone) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        async function sendOtp(type) {
            const input = document.getElementById(type);
            const value = input.value.trim();
            if (!value) {
                alert(`Please enter a valid ${type} first.`);
                return;
            }

            const btn = document.getElementById(`btn-verify-${type}`);
            btn.innerHTML = 'Sending...';
            btn.disabled = true;

            const endpoint = type === 'email' ? '/otp/email/send' : '/otp/send';
            // For phone OTP, also pass the typed email so the backend can deliver via Mailtrap
            const emailVal = document.getElementById('email')?.value?.trim() || '';
            const payload = type === 'email' ? { email: value } : { phone: value, email: emailVal };

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                
                if (response.ok) {
                    document.getElementById('currentVerifyType').value = type;
                    document.getElementById('otpModalTitle').innerText = `Verify ${type === 'email' ? 'Email' : 'Phone'}`;
                    document.getElementById('otpModalDesc').innerText = `Enter the 6-digit code sent to ${value}.`;
                    document.getElementById('otp_input').value = '';
                    document.getElementById('otpModal').classList.remove('hidden');
                    btn.innerHTML = 'Sent';
                } else {
                    alert(data.message || 'Failed to send OTP.');
                    btn.innerHTML = 'Verify';
                    btn.disabled = false;
                }
            } catch (error) {
                alert('Network error occurred.');
                btn.innerHTML = 'Verify';
                btn.disabled = false;
            }
        }

        function closeOtpModal() {
            document.getElementById('otpModal').classList.add('hidden');
            const type = document.getElementById('currentVerifyType').value;
            if (!verifiedStates[type]) {
                const btn = document.getElementById(`btn-verify-${type}`);
                btn.innerHTML = 'Verify';
                btn.disabled = false;
            }
        }

        async function verifyOtp() {
            const type = document.getElementById('currentVerifyType').value;
            const otpCode = document.getElementById('otp_input').value.trim();
            const inputVal = document.getElementById(type).value.trim();
            const btnSubmit = document.getElementById('btn-verify-submit');

            if (otpCode.length !== 6) {
                alert('Please enter a valid 6-digit OTP.');
                return;
            }

            btnSubmit.innerHTML = 'Verifying...';
            btnSubmit.disabled = true;

            const endpoint = type === 'email' ? '/otp/email/verify' : '/otp/verify';
            const payload = type === 'email' 
                ? { email: inputVal, otp_code: otpCode } 
                : { phone: inputVal, otp_code: otpCode };

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                
                if (response.ok) {
                    verifiedStates[type] = true;
                    closeOtpModal();
                    
                    const btn = document.getElementById(`btn-verify-${type}`);
                    btn.innerHTML = '✓ Verified';
                    btn.className = "inline-flex items-center justify-center px-3 py-1.5 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl font-bold text-green-700 dark:text-green-400 uppercase tracking-widest text-[10px] transition";
                    btn.disabled = true;
                    
                    const status = document.getElementById(`${type}-status`);
                    status.innerHTML = `<span class="text-green-600 dark:text-green-400">✓ Verified</span>`;
                    status.classList.remove('hidden');

                    checkRegisterButton();
                } else {
                    alert(data.message || 'Invalid OTP.');
                }
            } catch (error) {
                alert('Network error occurred.');
            } finally {
                btnSubmit.innerHTML = 'Verify';
                btnSubmit.disabled = false;
            }
        }
    </script>
</x-guest-layout>
