<x-guest-layout>
    <h2 class="auth-heading">Reset your password</h2>
    <p class="auth-subheading">Enter your email and we'll send a reset link to get you back in.</p>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-sm font-semibold text-green-700 dark:text-green-400">
            ✓ {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        @php $iconStyle = "position:absolute;left:13px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF;pointer-events:none;"; @endphp

        <div class="form-group">
            <label class="auth-label" for="email">Email Address</label>
            <div style="position:relative;">
                <svg style="{{ $iconStyle }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
                <input id="email" class="auth-input" style="padding-left:40px;" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@email.com">
            </div>
            @error('email')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="auth-btn">Send Reset Link</button>
    </form>

    <p class="auth-link-row">
        Remember your password? <a href="{{ route('login') }}" class="auth-link">Back to login</a>
    </p>
</x-guest-layout>
