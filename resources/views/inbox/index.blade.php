@extends('layouts.main')

@section('content')
<div class="bg-gray-50 dark:bg-black py-12 min-h-[calc(100vh-4rem)] transition-colors duration-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8 tracking-tight">Inbox & Requests</h1>

        <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-sm border border-gray-100 dark:border-white/5 overflow-hidden transition-colors duration-200">
            <!-- Tabs -->
            <div class="flex border-b border-gray-200 dark:border-white/10">
                <button class="flex-1 py-4 px-6 text-center font-bold text-red-600 dark:text-red-500 border-b-2 border-red-600 dark:border-red-500 bg-red-50/50 dark:bg-red-900/10 focus:outline-none">
                    Pending Requests
                    @if($pendingRequests->count() > 0)
                        <span class="ml-2 inline-flex items-center justify-center h-5 w-5 rounded-full bg-red-600 text-white text-xs">{{ $pendingRequests->count() }}</span>
                    @endif
                </button>
                <button class="flex-1 py-4 px-6 text-center font-bold text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none" onclick="document.getElementById('chats-section').scrollIntoView({behavior: 'smooth'})">
                    Active Chats
                </button>
            </div>

            <!-- Pending Requests Content -->
            <div class="p-6 sm:p-8">
                @if($pendingRequests->isEmpty())
                    <div class="text-center py-10">
                        <div class="inline-flex h-16 w-16 rounded-full bg-gray-100 dark:bg-[#111] items-center justify-center mb-4 text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">No pending requests</h3>
                        <p class="text-gray-500 dark:text-gray-400">You're all caught up!</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($pendingRequests as $req)
                            <div class="p-5 border border-gray-100 dark:border-white/5 rounded-2xl bg-gray-50 dark:bg-[#111] flex flex-col sm:flex-row sm:items-center justify-between gap-4 group hover:border-red-100 dark:hover:border-red-900/50 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-lg border border-blue-200 dark:border-blue-800/50">
                                        {{ substr($req->fromUser->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 dark:text-white">{{ $req->fromUser->name ?? 'Unknown User' }} requested your help</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $req->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('donation_request.respond', $req->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors shadow-sm shadow-green-600/20">Accept</button>
                                    </form>
                                    <form action="{{ route('donation_request.respond', $req->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="declined">
                                        <button type="submit" class="px-5 py-2.5 bg-gray-200 dark:bg-white/10 hover:bg-gray-300 dark:hover:bg-white/20 text-gray-800 dark:text-gray-200 font-bold rounded-xl transition-colors">Decline</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div id="chats-section" class="mt-12 mb-4">
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Active Chats</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Connect with matched donors or patients.</p>
        </div>

        <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-sm border border-gray-100 dark:border-white/5 overflow-hidden transition-colors duration-200 p-6 sm:p-8">
            @if($activeChats->isEmpty())
                <div class="text-center py-10">
                    <div class="inline-flex h-16 w-16 rounded-full bg-gray-100 dark:bg-[#111] items-center justify-center mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">No active chats</h3>
                    <p class="text-gray-500 dark:text-gray-400">Accept a request or wait for someone to accept yours to start chatting.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($activeChats as $chat)
                        @php
                            $otherUser = $chat->from_user_id === auth()->id() ? $chat->toUser : $chat->fromUser;
                            $unreadMsgCount = \App\Models\Message::where('donation_request_id', $chat->id)
                                ->where('receiver_id', auth()->id())
                                ->whereNull('read_at')
                                ->count();
                        @endphp
                        @if($otherUser)
                        <div class="p-5 border border-gray-100 dark:border-white/5 rounded-2xl bg-gray-50 dark:bg-[#111] hover:shadow-lg dark:hover:shadow-white/5 transition-all group">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center font-bold">
                                        {{ substr($otherUser->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $otherUser->name }}</h3>
                                            @if($unreadMsgCount > 0)
                                                <span class="inline-flex items-center justify-center h-5 px-2 rounded-full bg-red-600 text-white text-[10px] font-bold">{{ $unreadMsgCount }} new</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $otherUser->blood_group ?? 'Donor' }} • {{ $otherUser->city ?? 'Location hidden' }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex gap-3">
                                <a href="{{ route('chat.index', $chat->id) }}" class="flex-1 flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors text-sm shadow-sm">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    Chat
                                </a>
                                @if($otherUser->phone)
                                <a href="tel:{{ $otherUser->phone }}" class="flex items-center justify-center px-4 py-2.5 bg-gray-200 dark:bg-white/10 hover:bg-gray-300 dark:hover:bg-white/20 text-gray-800 dark:text-gray-200 font-bold rounded-xl transition-colors text-sm">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    Call
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
