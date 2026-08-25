<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — BuildCares</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light-alt text-slate-700 antialiased">

@php
    $unreadMessages = \App\Models\ContactMessage::where('is_read', false)->count();
@endphp

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside class="w-64 bg-white border-r border-slate-200 flex-shrink-0 hidden lg:flex flex-col">
        <div class="p-6 border-b border-slate-200">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="font-display text-lg font-bold text-dark-900">Build<span class="text-gold">Cares</span></div>
            </a>
            <div class="text-xs uppercase tracking-widest text-slate-500 mt-0.5">Admin Panel</div>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gold/10 text-gold' : 'text-slate-600 hover:text-dark-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <a href="{{ route('admin.portfolio.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.portfolio.*') ? 'bg-gold/10 text-gold' : 'text-slate-600 hover:text-dark-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Portfolio
            </a>

            <a href="{{ route('admin.services.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.services.*') ? 'bg-gold/10 text-gold' : 'text-slate-600 hover:text-dark-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Services
            </a>

            <a href="{{ route('admin.team.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.team.*') ? 'bg-gold/10 text-gold' : 'text-slate-600 hover:text-dark-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Meet Our Team
            </a>

            <a href="{{ route('admin.messages.index') }}"
               class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.messages.*') ? 'bg-gold/10 text-gold' : 'text-slate-600 hover:text-dark-900 hover:bg-slate-100' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Messages
                </span>
                @if($unreadMessages > 0)
                <span class="bg-gold text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $unreadMessages }}</span>
                @endif
            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-gold/10 text-gold' : 'text-slate-600 hover:text-dark-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>
        </nav>

        <div class="p-4 border-t border-slate-200 space-y-2">
            @auth
            <div class="px-3 py-2 text-xs text-slate-500">
                Signed in as <span class="text-dark-900 font-medium">{{ auth()->user()->name }}</span>
            </div>
            @endauth

            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-slate-500 hover:text-gold text-sm transition-colors px-3 py-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Site
            </a>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 text-slate-500 hover:text-red-500 text-sm transition-colors px-3 py-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Top bar --}}
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
            <h1 class="font-display text-lg font-semibold text-dark-900">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                @if(session('success'))
                <span class="text-green-600 text-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </span>
                @endif

                {{-- Mobile menu shortcut links --}}
                <div class="lg:hidden flex items-center gap-3 text-xs">
                    <a href="{{ route('admin.dashboard') }}" class="text-slate-500 hover:text-gold {{ request()->routeIs('admin.dashboard') ? 'text-gold' : '' }}">Dash</a>
                    <a href="{{ route('admin.portfolio.index') }}" class="text-slate-500 hover:text-gold {{ request()->routeIs('admin.portfolio.*') ? 'text-gold' : '' }}">Portfolio</a>
                    <a href="{{ route('admin.services.index') }}" class="text-slate-500 hover:text-gold {{ request()->routeIs('admin.services.*') ? 'text-gold' : '' }}">Services</a>
                    <a href="{{ route('admin.messages.index') }}" class="text-slate-500 hover:text-gold {{ request()->routeIs('admin.messages.*') ? 'text-gold' : '' }}">
                        Messages @if($unreadMessages > 0)<span class="bg-gold text-white text-[10px] px-1.5 rounded-full">{{ $unreadMessages }}</span>@endif
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="text-slate-500 hover:text-gold {{ request()->routeIs('admin.settings.*') ? 'text-gold' : '' }}">Settings</a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-slate-500 hover:text-red-500">Out</button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
