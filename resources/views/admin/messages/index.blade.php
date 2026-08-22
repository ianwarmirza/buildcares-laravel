@extends('layouts.admin')

@section('title', 'Messages')
@section('page-title', 'Contact Messages')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-slate-400 text-sm">
        {{ $messages->total() }} total
        @if($unread = \App\Models\ContactMessage::where('is_read', false)->count())
            · <span class="text-gold">{{ $unread }} unread</span>
        @endif
    </p>
</div>

<div class="card-dark rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-200">
                <th class="text-left py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-medium">From</th>
                <th class="text-left py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-medium hidden md:table-cell">Subject</th>
                <th class="text-left py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-medium hidden lg:table-cell">Service</th>
                <th class="text-left py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-medium hidden sm:table-cell">Received</th>
                <th class="text-right py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse($messages as $msg)
            <tr class="hover:bg-light-warm transition-colors {{ !$msg->is_read ? 'bg-gold/5' : '' }}">
                <td class="py-3 px-4">
                    <div class="flex items-center gap-3">
                        @if(!$msg->is_read)
                        <span class="w-2 h-2 rounded-full bg-gold flex-shrink-0" title="Unread"></span>
                        @else
                        <span class="w-2 h-2 flex-shrink-0"></span>
                        @endif
                        <div>
                            <div class="text-dark-900 font-medium leading-snug">{{ $msg->name }}</div>
                            <div class="text-slate-500 text-xs">{{ $msg->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="py-3 px-4 text-slate-600 hidden md:table-cell max-w-xs">
                    <a href="{{ route('admin.messages.show', $msg) }}" class="hover:text-gold transition-colors">
                        <span class="line-clamp-1 {{ !$msg->is_read ? 'font-semibold text-dark-900' : '' }}">{{ $msg->subject }}</span>
                    </a>
                </td>
                <td class="py-3 px-4 hidden lg:table-cell">
                    @if($msg->service)
                    <span class="text-xs bg-gold/10 text-gold border border-gold/20 px-2 py-0.5 rounded-full">{{ $msg->service }}</span>
                    @else
                    <span class="text-slate-400">—</span>
                    @endif
                </td>
                <td class="py-3 px-4 text-slate-500 hidden sm:table-cell text-xs">{{ $msg->created_at->diffForHumans() }}</td>
                <td class="py-3 px-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.messages.show', $msg) }}"
                           class="p-1.5 text-slate-500 hover:text-gold transition-colors" title="View">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <form action="{{ route('admin.messages.toggleRead', $msg) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="p-1.5 text-slate-500 hover:text-blue-500 transition-colors"
                                    title="{{ $msg->is_read ? 'Mark unread' : 'Mark read' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($msg->is_read)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    @endif
                                </svg>
                            </button>
                        </form>
                        <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-500 hover:text-red-500 transition-colors" title="Delete">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-16 text-center text-slate-500">
                    No messages yet. They will appear here when visitors submit the contact form.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($messages->hasPages())
<div class="mt-6 flex justify-center">
    {{ $messages->links() }}
</div>
@endif

@endsection
