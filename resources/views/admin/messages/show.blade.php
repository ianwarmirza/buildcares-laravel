@extends('layouts.admin')

@section('title', 'Message: ' . $message->subject)
@section('page-title', 'Message')

@section('content')

<div class="max-w-3xl">
    <div class="mb-4">
        <a href="{{ route('admin.messages.index') }}" class="text-slate-500 hover:text-gold text-sm flex items-center gap-1.5 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to messages
        </a>
    </div>

    <div class="card-dark rounded-xl p-6">
        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <h2 class="font-display text-xl font-semibold text-dark-900">{{ $message->subject }}</h2>
                <p class="text-slate-500 text-sm mt-1">
                    From <span class="text-dark-900 font-medium">{{ $message->name }}</span>
                    &middot; <a href="mailto:{{ $message->email }}" class="text-gold hover:underline">{{ $message->email }}</a>
                    @if($message->phone)
                    &middot; <a href="tel:{{ $message->phone }}" class="text-gold hover:underline">{{ $message->phone }}</a>
                    @endif
                </p>
                <p class="text-slate-400 text-xs mt-1">{{ $message->created_at->format('M j, Y, g:i a') }} ({{ $message->created_at->diffForHumans() }})</p>
            </div>
            @if($message->service)
            <span class="text-xs bg-gold/10 text-gold border border-gold/20 px-3 py-1 rounded-full">{{ $message->service }}</span>
            @endif
        </div>

        <div class="py-6">
            <div class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $message->message }}</div>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-200">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject) }}"
               class="btn-gold text-xs py-2.5 px-4">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Reply by Email
            </a>

            <form action="{{ route('admin.messages.toggleRead', $message) }}" method="POST" class="inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn-outline-gold text-xs py-2.5 px-4">
                    {{ $message->is_read ? 'Mark Unread' : 'Mark Read' }}
                </button>
            </form>

            <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="inline ml-auto">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 text-xs uppercase tracking-widest font-semibold">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
