@extends('layouts.main')

@section('content')
<div class="bg-gray-50 dark:bg-black py-8 min-h-[calc(100vh-4rem)] transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">User Management</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Manage all registered users, ban fraudulent accounts.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-900 dark:hover:text-white font-bold">&larr; Back to Dashboard</a>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-sm font-semibold text-green-700 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-sm border border-gray-100 dark:border-white/5 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-white/5">
                <form action="{{ route('admin.users') }}" method="GET" class="flex gap-4 max-w-md">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, or phone..." class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm focus:ring-red-500 focus:border-red-500 dark:text-white transition">
                    <button type="submit" class="px-6 py-2 bg-gray-900 hover:bg-black dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-bold rounded-xl transition">Search</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-white/[0.02] text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-white/5">
                            <th class="p-4 px-6">Account Info</th>
                            <th class="p-4 px-6 text-center">Role</th>
                            <th class="p-4 px-6 text-center">Blood</th>
                            <th class="p-4 px-6">Joined</th>
                            <th class="p-4 px-6 text-center">Status</th>
                            <th class="p-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition">
                            <td class="p-4 px-6">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}<br>{{ $user->phone }}</div>
                            </td>
                            <td class="p-4 px-6 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                    {{ $user->role === 'hospital' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400' : 'bg-gray-100 dark:bg-white/5 text-gray-800 dark:text-gray-300' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="p-4 px-6 text-center">
                                @if($user->role === 'hospital')
                                    <span class="text-gray-400 dark:text-gray-600 font-semibold text-xs">-</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-black rounded text-xs">{{ $user->blood_group }}</span>
                                @endif
                            </td>
                            <td class="p-4 px-6 text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="p-4 px-6 text-center">
                                @if($user->is_banned)
                                    <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-bold rounded text-xs">Banned</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-bold rounded text-xs">Active</span>
                                @endif
                            </td>
                            <td class="p-4 px-6 text-right">
                                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs font-bold px-4 py-2 rounded-lg border transition-colors
                                        {{ $user->is_banned ? 'border-green-300 text-green-600 hover:bg-green-50 dark:border-green-800 dark:text-green-400 dark:hover:bg-green-900/20' : 'border-red-300 text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20' }}">
                                        {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
                <div class="p-6 border-t border-gray-100 dark:border-white/5">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
