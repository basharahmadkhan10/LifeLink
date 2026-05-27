<x-guest-layout>
    <h2 class="auth-heading">Welcome back</h2>
    <p class="auth-subheading">Sign in to manage your appointments and donations.</p>

    @if(session('status'))
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        @php $iconStyle = "position:absolute;left:13px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF;pointer-events:none;"; @endphp

        <!-- Email -->
        <div class="form-group">
            <label class="auth-label" for="email">Email Address</label>
            <div style="position:relative;">
                <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
                <input id="email" class="auth-input" style="padding-left:40px;" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@email.com">
            </div>
            @error('email')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <div class="label-row">
                <label class="auth-label" for="password">Password</label>
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <div style="position:relative;">
                <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                <input id="password" class="auth-input" style="padding-left:40px;" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>
            @error('password')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <!-- Remember Me -->
        <div class="form-group" style="margin-bottom: 6px;">
            <label class="auth-checkbox-row">
                <input type="checkbox" name="remember" id="remember_me" class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                <span>Remember me for 30 days</span>
            </label>
        </div>

        <button type="submit" class="auth-btn">Log In</button>
    </form>

    <p class="auth-link-row">
        Don't have an account? <a href="{{ route('register') }}" class="auth-link">Register now</a>
    </p>
</x-guest-layout>
