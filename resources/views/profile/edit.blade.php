@extends('layouts.main')

@section('content')
<div class="bg-gray-50 dark:bg-black py-12 min-h-[calc(100vh-4rem)] transition-colors duration-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8 tracking-tight">Profile Settings</h1>

        <div class="space-y-8">
            <div class="p-6 sm:p-8 bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-xl shadow-red-900/5 border border-gray-100 dark:border-white/5 transition-all">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-xl shadow-red-900/5 border border-gray-100 dark:border-white/5 transition-all">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-xl shadow-red-900/5 border border-gray-100 dark:border-white/5 transition-all">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
