@extends('layouts.main')

@section('content')
<div class="bg-gray-50 dark:bg-black py-12 min-h-[calc(100vh-4rem)] transition-colors duration-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('inbox.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Inbox
            </a>
            
            @php
                $otherUser = $donationRequest->from_user_id === auth()->id() ? $donationRequest->toUser : $donationRequest->fromUser;
            @endphp
            @if($otherUser && $otherUser->phone)
            <a href="tel:{{ $otherUser->phone }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                Call {{ explode(' ', trim($otherUser->name))[0] }}
            </a>
            @endif
        </div>

        {{-- Donation Type + Confirm Received Banner --}}
        @if($donationRequest->status === 'accepted')
        <div class="mb-4 p-3.5 bg-white dark:bg-[#0a0a0a] border border-gray-100 dark:border-white/5 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                @if($donationRequest->donation_type === 'emergency')
                    <span class="px-2.5 py-1 bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 text-[10px] font-black rounded-full uppercase tracking-wider">🚨 Emergency +150 pts</span>
                @else
                    <span class="px-2.5 py-1 bg-blue-500/10 border border-blue-500/20 text-blue-600 dark:text-blue-400 text-[10px] font-black rounded-full uppercase tracking-wider">💉 Normal +20 pts</span>
                @endif
                <span class="text-xs text-gray-500 dark:text-gray-400">Points awarded to donor on confirmation</span>
            </div>

            {{-- Determine who is receiving the blood --}}
            @php
                $isBloodReceiver = false;
                if ($donationRequest->donation_type === 'emergency') {
                    // Donor initiated chat to offer help -> receiver is to_user_id
                    $isBloodReceiver = (auth()->id() === $donationRequest->to_user_id);
                } else {
                    // Patient/Hospital initiated chat to ask for help -> receiver is from_user_id
                    $isBloodReceiver = (auth()->id() === $donationRequest->from_user_id);
                }
            @endphp

            @if($isBloodReceiver)
                @if($donationRequest->confirmed_received)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 text-[10px] font-extrabold rounded-lg uppercase tracking-wider">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Blood Received
                    </span>
                @else
                    <div class="flex items-center gap-2">
                        <form action="{{ route('donation_request.confirm_received', $donationRequest->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="outcome" value="received">
                            <button type="submit"
                                onclick="return confirm('Confirm that you have received the blood? This will close the chat and award points to your donor.')"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-600 hover:bg-green-700 text-white font-extrabold rounded-lg text-[10px] uppercase tracking-wider transition-all shadow-md shadow-green-600/20 hover:-translate-y-0.5 active:translate-y-0">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Received
                            </button>
                        </form>
                        
                        <form action="{{ route('donation_request.confirm_received', $donationRequest->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="outcome" value="not_received">
                            <button type="submit"
                                onclick="return confirm('Cancel this help offer? The chat will be closed and your emergency will stay active.')"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 hover:bg-gray-200 dark:bg-white/10 dark:hover:bg-white/20 text-gray-700 dark:text-gray-300 font-extrabold rounded-lg text-[10px] uppercase tracking-wider transition-all hover:-translate-y-0.5 active:translate-y-0">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Not Received
                            </button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
        @endif

        <div class="bg-white dark:bg-[#0a0a0a] rounded-3xl shadow-xl shadow-red-900/5 border border-gray-100 dark:border-white/5 flex flex-col h-[70vh] min-h-[500px] overflow-hidden transition-colors duration-200">
            <!-- Header -->
            <div class="p-5 border-b border-gray-100 dark:border-white/5 bg-white dark:bg-[#111] flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-500 flex items-center justify-center font-bold text-lg">
                        {{ substr($otherUser->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900 dark:text-white">{{ $otherUser->name ?? 'Unknown User' }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <span class="h-2 w-2 rounded-full bg-green-500"></span> Connected
                        </p>
                    </div>
                </div>
                
                <form action="{{ route('donation_request.destroy', $donationRequest->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Are you sure you want to delete this chat? This will close the connection.')"
                        class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-full transition-colors"
                        title="Delete Chat">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
            
            <!-- Messages -->
            <div id="messages-container" class="flex-1 p-6 overflow-y-auto bg-gray-50/50 dark:bg-[#0a0a0a] flex flex-col space-y-6">
                @forelse ($messages as $msg)
                    <div class="flex w-full {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="rounded-2xl px-5 py-3 max-w-[75%] {{ $msg->sender_id === auth()->id() ? 'bg-red-600 text-white rounded-br-none shadow-md shadow-red-600/20' : 'bg-white dark:bg-[#1a1a1a] text-gray-900 dark:text-gray-100 border border-gray-100 dark:border-white/5 rounded-bl-none shadow-sm' }}">
                            <p class="text-[15px] leading-relaxed">{{ $msg->body }}</p>
                            <span class="text-[10px] {{ $msg->sender_id === auth()->id() ? 'text-red-200' : 'text-gray-400 dark:text-gray-500' }} mt-1 block text-right font-medium">
                                {{ $msg->created_at->format('h:i A') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div id="empty-state" class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-500 space-y-3">
                        <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <p class="font-medium">No messages yet. Send a message to start chatting!</p>
                    </div>
                @endforelse
            </div>

            <!-- Input Area -->
            <div class="p-4 sm:p-5 border-t border-gray-100 dark:border-white/5 bg-white dark:bg-[#111]">
                <form id="chat-form" action="{{ route('chat.store', $donationRequest->id) }}" method="POST" class="flex items-center gap-3">
                    @csrf
                    <input type="text" id="chat-input" name="body" class="flex-1 rounded-full border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-6 py-3.5 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-red-500 focus:ring-red-500 transition-colors duration-200" placeholder="Type your message..." required autocomplete="off">
                    <button type="submit" id="chat-submit" class="h-12 w-12 flex items-center justify-center rounded-full bg-red-600 hover:bg-red-700 text-white transition-all transform hover:scale-105 active:scale-95 shadow-md shadow-red-600/30 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-[#111]">
                        <svg class="h-5 w-5 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messages-container');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const emptyState = document.getElementById('empty-state');
    const myId = "{{ (string)auth()->id() }}";
    const donationRequestId = "{{ (string)$donationRequest->id }}";

    // Scroll to bottom initially
    scrollToBottom();

    function scrollToBottom() {
        container.scrollTop = container.scrollHeight;
    }

    function appendMessage(msg, isMine) {
        if (emptyState) emptyState.remove();

        const timeString = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        const alignmentClass = isMine ? 'justify-end' : 'justify-start';
        const bubbleClass = isMine 
            ? 'bg-red-600 text-white rounded-br-none shadow-md shadow-red-600/20' 
            : 'bg-white dark:bg-[#1a1a1a] text-gray-900 dark:text-gray-100 border border-gray-100 dark:border-white/5 rounded-bl-none shadow-sm';
        const timeClass = isMine ? 'text-red-200' : 'text-gray-400 dark:text-gray-500';

        const html = `
            <div class="flex w-full ${alignmentClass}">
                <div class="rounded-2xl px-5 py-3 max-w-[75%] ${bubbleClass}">
                    <p class="text-[15px] leading-relaxed">${escapeHtml(msg.body)}</p>
                    <span class="text-[10px] ${timeClass} mt-1 block text-right font-medium">
                        ${timeString}
                    </span>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        scrollToBottom();
    }

    function escapeHtml(unsafe) {
        return (unsafe || '').toString()
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }

    // Handle AJAX form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const body = input.value.trim();
        if (!body) return;

        // Optimistic UI update
        const tempMsg = { body: body };
        appendMessage(tempMsg, true);
        input.value = '';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ body: body })
        }).catch(err => console.error('Failed to send message:', err));
    });

    // Listen to WebSockets via Laravel Echo (if configured)
    if (typeof window.Echo !== 'undefined' && window.Echo) {
        window.Echo.private(`chat.${donationRequestId}`)
            .listen('MessageSent', (e) => {
                if (e.message.sender_id !== myId) {
                    appendMessage(e.message, false);
                }
            });
    } else {
        // Fallback polling every 3 seconds if WebSockets aren't active yet
        let seenIds = new Set([
            @foreach($messages as $m)
                "{{ (string)$m->id }}",
            @endforeach
        ]);
        
        setInterval(() => {
            fetch(window.location.href, { headers: { 'Accept': 'application/json' }})
                .then(r => {
                    if (r.status === 404) {
                        window.location.href = '/inbox';
                        return null;
                    }
                    return r.json();
                })
                .then(data => {
                    if (!data) return;
                    
                    if (data.chat_status && data.chat_status !== 'accepted' && data.chat_status !== 'pending') {
                        window.location.href = '/inbox';
                        return;
                    }

                    if(data.messages) {
                        data.messages.forEach(m => {
                            const idStr = m.id || m._id;
                            if(!seenIds.has(idStr) && m.sender_id !== myId) {
                                appendMessage(m, false);
                                seenIds.add(idStr);
                            }
                        });
                    }
                })
                .catch(err => console.warn('Chat polling error:', err));
        }, 3000);
    }
});
</script>
@endsection
