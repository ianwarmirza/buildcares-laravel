@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['Portfolio Items', $stats['portfolio_count'], 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', '#2563eb'],
        ['Total Messages', $stats['messages_count'], 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', '#0ea5e9'],
        ['Unread Messages', $stats['unread_messages'], 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', '#f59e0b'],
        ['Services', $stats['services_count'], 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', '#10b981'],
    ] as [$label, $value, $icon, $color])
    <div class="card-dark rounded-xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color:{{ $color }}1a">
            <svg class="w-6 h-6" style="color:{{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"/>
            </svg>
        </div>
        <div>
            <div class="text-2xl font-bold font-display" style="color:{{ $color }}">{{ $value }}</div>
            <div class="text-slate-500 text-xs uppercase tracking-wider">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-2 gap-6">
    {{-- Quick actions --}}
    <div class="card-dark rounded-xl p-6">
        <h2 class="font-display font-semibold text-dark-900 mb-4">Quick Actions</h2>
        <div class="space-y-3">
            <button type="button" onclick="openQuickUploadModal()" class="w-full flex items-center gap-3 p-3 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-medium transition-all text-sm shadow-sm">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                ⚡ Quick Upload (Photo or PDF)
            </button>
            <a href="{{ route('admin.portfolio.create') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors text-sm text-slate-700 hover:text-dark-900">
                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Detailed Portfolio Item
            </a>
            <a href="{{ route('admin.services.create') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors text-sm text-slate-700 hover:text-dark-900">
                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Service
            </a>
            <a href="{{ route('admin.team.create') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors text-sm text-slate-700 hover:text-dark-900">
                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Add Team Member
            </a>
            <a href="{{ route('admin.messages.index') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors text-sm text-slate-700 hover:text-dark-900">
                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                View Messages
            </a>
            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors text-sm text-slate-700 hover:text-dark-900">
                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Website
            </a>
        </div>
    </div>

@include('admin.portfolio._quick_upload_modal')

    {{-- Recent messages --}}
    <div class="card-dark rounded-xl p-6">
        <h2 class="font-display font-semibold text-dark-900 mb-4">Recent Messages</h2>
        @if($recentMessages->count())
        <div class="space-y-3">
            @foreach($recentMessages as $msg)
            <a href="{{ route('admin.messages.show', $msg) }}" class="flex items-start gap-3 p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors {{ !$msg->is_read ? 'border-l-2 border-gold' : '' }}">
                <div class="w-8 h-8 rounded-full bg-gold/15 flex items-center justify-center flex-shrink-0 text-gold text-sm font-bold">
                    {{ strtoupper(substr($msg->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-dark-900 text-sm font-medium truncate">{{ $msg->name }}</span>
                        <span class="text-slate-500 text-xs flex-shrink-0">{{ $msg->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-slate-500 text-xs truncate">{{ $msg->subject }}</div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <p class="text-slate-500 text-sm">No messages yet.</p>
        @endif
    </div>
</div>

@endsection
