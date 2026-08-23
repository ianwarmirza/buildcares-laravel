@extends('layouts.app')

@section('title', $item->title . ' — BuildCares Portfolio')
@section('description', $item->description ?? 'View this ' . $item->category_label . ' project by BuildCares.')

@section('content')

{{-- Page header --}}
<section class="pt-32 pb-8 border-b" style="background-color:#f8fafc; border-color:#e2e8f0;">
    <div class="max-w-7xl mx-auto px-6">
        {{-- Top nav row: Back + Prev/Next --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <a href="{{ route('portfolio.index', ['category' => $item->category]) }}" class="inline-flex items-center gap-2 text-sm transition-colors hover:text-blue-600" style="color:#64748b;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                Back to {{ $item->category_label }}
            </a>

            @if($prev || $next)
            <div class="flex items-center gap-2">
                @if($prev)
                <a id="nav-prev" href="{{ route('portfolio.show', $prev->slug) }}"
                   class="group flex items-center gap-2 px-3 py-2 text-sm border transition-colors hover:border-blue-600 hover:text-blue-600"
                   style="border-color:#e2e8f0; color:#475569; background:#ffffff;" title="{{ $prev->title }}">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span class="hidden sm:inline max-w-[180px] truncate">{{ $prev->title }}</span>
                    <span class="sm:hidden">Prev</span>
                </a>
                @else
                <span class="flex items-center gap-2 px-3 py-2 text-sm border opacity-40 cursor-not-allowed" style="border-color:#e2e8f0; color:#94a3b8; background:#ffffff;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span class="hidden sm:inline">Prev</span>
                </span>
                @endif

                @if($siblings->count() > 1)
                <span class="text-xs tracking-widest uppercase px-2" style="color:#94a3b8;">
                    {{ $siblings->search(fn($p) => $p->id === $item->id) + 1 }} / {{ $siblings->count() }}
                </span>
                @endif

                @if($next)
                <a id="nav-next" href="{{ route('portfolio.show', $next->slug) }}"
                   class="group flex items-center gap-2 px-3 py-2 text-sm border transition-colors hover:border-blue-600 hover:text-blue-600"
                   style="border-color:#e2e8f0; color:#475569; background:#ffffff;" title="{{ $next->title }}">
                    <span class="sm:hidden">Next</span>
                    <span class="hidden sm:inline max-w-[180px] truncate">{{ $next->title }}</span>
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @else
                <span class="flex items-center gap-2 px-3 py-2 text-sm border opacity-40 cursor-not-allowed" style="border-color:#e2e8f0; color:#94a3b8; background:#ffffff;">
                    <span class="hidden sm:inline">Next</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
                @endif
            </div>
            @endif
        </div>
        <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1 mb-4" style="background:#2563eb; color:#ffffff;">{{ $item->category_label }}</span>
        <h1 class="font-bold text-3xl md:text-5xl mb-4" style="color:#0f172a; font-family:'DM Sans',sans-serif; line-height:1.1;">{{ $item->title }}</h1>
        <div class="flex flex-wrap items-center gap-5 text-sm" style="color:#64748b;">
            @if($item->location)
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 flex-shrink-0" style="color:#2563eb;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                {{ $item->location }}
            </span>
            @endif
            @if($item->year)
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 flex-shrink-0" style="color:#2563eb;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                {{ $item->year }}
            </span>
            @endif
            @if($item->client)
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 flex-shrink-0" style="color:#2563eb;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                {{ $item->client }}
            </span>
            @endif
        </div>
    </div>
</section>

{{-- Cover image / PDF Viewer --}}
<div style="background-color:#f8fafc;">
    <div class="max-w-7xl mx-auto px-6 py-8">
        @if($item->is_pdf)
        {{-- PDF Container --}}
        <div class="rounded-xl overflow-hidden border border-slate-300 bg-slate-900 shadow-xl">
            <div class="px-5 py-3.5 bg-slate-950 text-white flex flex-wrap items-center justify-between gap-3 border-b border-slate-800">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-red-600/20 text-red-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-300">PDF Drawing Package</div>
                        <div class="text-sm font-semibold text-white leading-tight truncate max-w-md">{{ $item->title }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ $item->cover_url }}" target="_blank" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 transition-colors flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Open in New Tab
                    </a>
                    <a href="{{ $item->cover_url }}" download class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition-colors flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download PDF
                    </a>
                </div>
            </div>
            <div class="w-full h-[680px] bg-slate-900 relative">
                <iframe src="{{ $item->cover_url }}" class="w-full h-full border-0" title="{{ $item->title }}"></iframe>

                {{-- Floating prev/next chevrons over the PDF --}}
                @if($prev)
                <a href="{{ route('portfolio.show', $prev->slug) }}"
                   class="absolute left-3 sm:left-5 top-1/2 -translate-y-1/2 w-11 h-11 sm:w-12 sm:h-12 flex items-center justify-center transition-all duration-200 opacity-80 hover:opacity-100 hover:scale-105 shadow-xl z-20"
                   style="background:rgba(15,23,42,0.85); color:#ffffff; border:1px solid rgba(255,255,255,0.2);"
                   title="Previous: {{ $prev->title }}" aria-label="Previous project">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>
                @endif

                @if($next)
                <a href="{{ route('portfolio.show', $next->slug) }}"
                   class="absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 w-11 h-11 sm:w-12 sm:h-12 flex items-center justify-center transition-all duration-200 opacity-80 hover:opacity-100 hover:scale-105 shadow-xl z-20"
                   style="background:rgba(15,23,42,0.85); color:#ffffff; border:1px solid rgba(255,255,255,0.2);"
                   title="Next: {{ $next->title }}" aria-label="Next project">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endif
            </div>
        </div>
        @else
        {{-- Image Container --}}
        <div class="relative group">
            <img src="{{ $item->cover_url }}" alt="{{ $item->title }}"
                 class="lightbox-trigger w-full object-contain" style="max-height:620px; border:1px solid #e2e8f0; cursor:zoom-in;"
                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-[400px] bg-slate-900 flex flex-col items-center justify-center p-8 text-center rounded-xl border border-slate-800\'><div class=\'w-16 h-16 rounded-2xl bg-blue-600/20 text-blue-400 flex items-center justify-center mb-3\'><svg class=\'w-8 h-8\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/></svg></div><h3 class=\'text-lg font-bold text-white mb-1\'>{{ e($item->title) }}</h3><p class=\'text-xs text-slate-400 max-w-sm\'>Architectural drawing & specification package for {{ e($item->category_label) }}.</p></div>';">

            {{-- Zoom hint (centred, fades in on hover) --}}
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none" style="background:rgba(0,0,0,0.06);">
                <div class="flex items-center gap-2 px-4 py-2 text-sm font-semibold" style="background:rgba(255,255,255,0.95); color:#0f172a; border:1px solid #e2e8f0;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                    Click to zoom
                </div>
            </div>

            {{-- Floating prev/next chevrons over the image --}}
            @if($prev)
            <a id="cover-prev" href="{{ route('portfolio.show', $prev->slug) }}"
               class="absolute left-3 sm:left-5 top-1/2 -translate-y-1/2 w-11 h-11 sm:w-12 sm:h-12 flex items-center justify-center transition-all duration-200 opacity-80 hover:opacity-100 hover:scale-105 shadow-lg"
               style="background:rgba(255,255,255,0.95); color:#0f172a; border:1px solid #e2e8f0;"
               title="Previous: {{ $prev->title }}" aria-label="Previous project">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
            @endif

            @if($next)
            <a id="cover-next" href="{{ route('portfolio.show', $next->slug) }}"
               class="absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 w-11 h-11 sm:w-12 sm:h-12 flex items-center justify-center transition-all duration-200 opacity-80 hover:opacity-100 hover:scale-105 shadow-lg"
               style="background:rgba(255,255,255,0.95); color:#0f172a; border:1px solid #e2e8f0;"
               title="Next: {{ $next->title }}" aria-label="Next project">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- Lightbox modal --}}
<div id="lightbox" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4" style="background:rgba(0,0,0,0.92);"
     data-prev-href="{{ $prev ? route('portfolio.show', $prev->slug) : '' }}"
     data-next-href="{{ $next ? route('portfolio.show', $next->slug) : '' }}">
    <button id="lightbox-close" class="absolute top-5 right-5 w-10 h-10 flex items-center justify-center transition-colors z-10" style="color:#ffffff; background:rgba(255,255,255,0.12);" aria-label="Close">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    {{-- Project label in top-left --}}
    <div class="absolute top-5 left-5 z-10 pointer-events-none">
        <div class="text-[10px] uppercase tracking-[0.18em] font-bold" style="color:rgba(255,255,255,0.5);">{{ $item->category_label }}</div>
        <div class="text-sm font-semibold mt-0.5" style="color:#ffffff;">{{ $item->title }}</div>
    </div>

    {{-- Prev / Next inside the lightbox --}}
    @if($prev)
    <button id="lb-prev" type="button"
            class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center transition-all duration-200 hover:scale-105 z-10"
            style="background:rgba(255,255,255,0.12); color:#ffffff;" title="Previous: {{ $prev->title }}" aria-label="Previous project">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    @endif
    @if($next)
    <button id="lb-next" type="button"
            class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center transition-all duration-200 hover:scale-105 z-10"
            style="background:rgba(255,255,255,0.12); color:#ffffff;" title="Next: {{ $next->title }}" aria-label="Next project">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
    @endif

    <div class="relative w-full h-full flex items-center justify-center overflow-hidden" id="lightbox-inner">
        <img id="lightbox-img" src="" alt="" class="select-none" style="max-width:100%; max-height:100%; object-fit:contain; transform-origin:center; transition:transform 0.2s ease; cursor:grab;">
    </div>
    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex items-center gap-3 z-10">
        <button id="lb-zoom-out" class="w-9 h-9 flex items-center justify-center text-sm font-bold transition-colors" style="background:rgba(255,255,255,0.12); color:#ffffff;">−</button>
        <span id="lb-zoom-label" class="text-xs w-12 text-center" style="color:#94a3b8;">100%</span>
        <button id="lb-zoom-in" class="w-9 h-9 flex items-center justify-center text-sm font-bold transition-colors" style="background:rgba(255,255,255,0.12); color:#ffffff;">+</button>
        <button id="lb-zoom-reset" class="px-3 h-9 flex items-center justify-center text-xs transition-colors" style="background:rgba(255,255,255,0.12); color:#ffffff;">Reset</button>
    </div>
</div>

{{-- Content --}}
<section class="section-padding" style="background-color:#ffffff;">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-3 gap-12">
            {{-- Main --}}
            <div class="lg:col-span-2">
                @if($item->description)
                <div class="mb-10">
                    <h2 class="font-bold text-xl mb-4" style="color:#0f172a;">Project Overview</h2>
                    <div class="leading-relaxed space-y-4" style="color:#475569;">
                        {!! nl2br(e($item->description)) !!}
                    </div>
                </div>
                @endif

                {{-- Gallery --}}
                @if($item->gallery_images && count($item->gallery_images))
                <div>
                    <h2 class="font-bold text-xl mb-6" style="color:#0f172a;">Project Gallery</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($item->gallery_images as $img)
                        <div class="lightbox-trigger portfolio-card rounded-md overflow-hidden" data-src="{{ Storage::url($img) }}" style="cursor:zoom-in;">
                            <img src="{{ Storage::url($img) }}" alt="Gallery image" loading="lazy">
                            <div class="overlay">
                                <div class="flex items-center justify-center h-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Project details --}}
                <div class="p-6 border" style="background:#f8fafc; border-color:#e2e8f0;">
                    <h3 class="font-semibold text-lg mb-5" style="color:#0f172a;">Project Details</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs uppercase tracking-widest mb-1" style="color:#94a3b8;">Type</dt>
                            <dd class="text-sm font-medium" style="color:#1e293b;">{{ $item->category_label }}</dd>
                        </div>
                        @if($item->location)
                        <div>
                            <dt class="text-xs uppercase tracking-widest mb-1" style="color:#94a3b8;">Location</dt>
                            <dd class="text-sm font-medium" style="color:#1e293b;">{{ $item->location }}</dd>
                        </div>
                        @endif
                        @if($item->year)
                        <div>
                            <dt class="text-xs uppercase tracking-widest mb-1" style="color:#94a3b8;">Year</dt>
                            <dd class="text-sm font-medium" style="color:#1e293b;">{{ $item->year }}</dd>
                        </div>
                        @endif
                        @if($item->client)
                        <div>
                            <dt class="text-xs uppercase tracking-widest mb-1" style="color:#94a3b8;">Client</dt>
                            <dd class="text-sm font-medium" style="color:#1e293b;">{{ $item->client }}</dd>
                        </div>
                        @endif
                        @if($item->tags && count($item->tags))
                        <div>
                            <dt class="text-xs uppercase tracking-widest mb-2" style="color:#94a3b8;">Tags</dt>
                            <dd class="flex flex-wrap gap-2">
                                @foreach($item->tags as $tag)
                                <span class="text-xs px-2 py-1 border" style="background:#ffffff; border-color:#e2e8f0; color:#475569;">{{ $tag }}</span>
                                @endforeach
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>

                {{-- CTA --}}
                <div class="p-6 border" style="background:#eff6ff; border-color:#bfdbfe;">
                    <h3 class="font-semibold mb-3" style="color:#0f172a;">Similar Project?</h3>
                    <p class="text-sm mb-5" style="color:#475569;">Get in touch to discuss your {{ $item->category_label }} project and receive a free quote.</p>
                    <a href="{{ route('contact') }}?service={{ $item->category }}" class="btn-gold w-full justify-center text-sm">Get a Quote</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Sibling slider — all projects in this category --}}
@if($siblings->count() > 1)
<section class="py-14 border-t" style="background-color:#f8fafc; border-color:#e2e8f0;">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-6 gap-4">
            <div>
                <div class="text-xs font-bold uppercase tracking-widest mb-1" style="color:#2563eb;">More from this collection</div>
                <h2 class="font-bold text-2xl" style="color:#0f172a;">{{ $item->category_label }} Projects</h2>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button type="button" id="sibling-prev"
                        class="w-10 h-10 flex items-center justify-center border transition-colors hover:border-blue-600 hover:text-blue-600 disabled:opacity-30 disabled:cursor-not-allowed"
                        style="border-color:#e2e8f0; color:#475569; background:#ffffff;" aria-label="Scroll left">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" id="sibling-next"
                        class="w-10 h-10 flex items-center justify-center border transition-colors hover:border-blue-600 hover:text-blue-600 disabled:opacity-30 disabled:cursor-not-allowed"
                        style="border-color:#e2e8f0; color:#475569; background:#ffffff;" aria-label="Scroll right">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <div class="relative">
            {{-- Fade edges --}}
            <div class="absolute left-0 top-0 bottom-3 w-8 z-10 pointer-events-none" style="background:linear-gradient(to right,#f8fafc,transparent);"></div>
            <div class="absolute right-0 top-0 bottom-3 w-8 z-10 pointer-events-none" style="background:linear-gradient(to left,#f8fafc,transparent);"></div>

            <div id="sibling-track" class="flex gap-4 overflow-x-auto scroll-smooth pb-3" style="scroll-snap-type:x mandatory; scrollbar-width:thin; scrollbar-color:#cbd5e1 transparent;">
                @foreach($siblings as $sib)
                @php $isCurrent = $sib->id === $item->id; @endphp
                <a href="{{ route('portfolio.show', $sib->slug) }}"
                   data-current="{{ $isCurrent ? '1' : '0' }}"
                   class="sibling-card group flex-shrink-0 w-64 sm:w-72 block transition-all duration-200"
                   style="scroll-snap-align:start; {{ $isCurrent ? 'outline:2px solid #2563eb; outline-offset:3px;' : '' }}">
                    <div class="aspect-[4/3] overflow-hidden border relative flex items-center justify-center" style="border-color:#e2e8f0; background:#ffffff;">
                        @if($sib->is_pdf)
                        <canvas class="pdf-thumbnail-canvas w-full h-full object-contain" data-pdf-url="{{ $sib->cover_url }}"></canvas>
                        <div class="pdf-fallback-icon absolute inset-0 bg-slate-900 flex flex-col items-center justify-center p-3 text-center">
                            <svg class="w-6 h-6 text-red-500 mb-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                            <span class="text-[9px] font-bold text-red-400">PDF</span>
                        </div>
                        @else
                        <img src="{{ $sib->cover_url }}" alt="{{ $sib->title }}" loading="lazy"
                             class="w-full h-full object-cover transition-transform duration-500 {{ $isCurrent ? '' : 'group-hover:scale-105' }}"
                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full bg-slate-900 flex flex-col items-center justify-center text-center p-4\'><svg class=\'w-6 h-6 text-slate-400 mb-1\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/></svg><span class=\'text-[9px] font-bold text-slate-400 uppercase\'>CAD Drawing</span></div>';">
                        @endif
                    </div>
                    <div class="pt-3">
                        <div class="flex items-center gap-2 mb-1">
                            @if($isCurrent)
                            <span class="text-[10px] font-bold uppercase tracking-widest px-1.5 py-0.5" style="background:#2563eb; color:#ffffff;">Viewing</span>
                            @endif
                            @if($sib->year)
                            <span class="text-xs" style="color:#94a3b8;">{{ $sib->year }}</span>
                            @endif
                        </div>
                        <h3 class="font-semibold text-sm leading-snug line-clamp-2" style="color:#0f172a;">{{ $sib->title }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

@push('scripts')
<script>
(function () {
    const lightbox  = document.getElementById('lightbox');
    const lbImg     = document.getElementById('lightbox-img');
    const lbInner   = document.getElementById('lightbox-inner');
    const lbClose   = document.getElementById('lightbox-close');
    const lbZoomIn  = document.getElementById('lb-zoom-in');
    const lbZoomOut = document.getElementById('lb-zoom-out');
    const lbReset   = document.getElementById('lb-zoom-reset');
    const lbLabel   = document.getElementById('lb-zoom-label');

    let scale = 1, tx = 0, ty = 0;
    let isDragging = false, dragX, dragY, dragTx, dragTy;
    let lastTouchDist = null, isTouchPan = false, touchPanX, touchPanY, touchPanTx, touchPanTy;
    const STEP = 0.3, MIN = 0.5, MAX = 8;

    function containerCenter() {
        const r = lbInner.getBoundingClientRect();
        return { x: r.left + r.width / 2, y: r.top + r.height / 2 };
    }

    function applyTransform(animated) {
        lbImg.style.transition = animated ? 'transform 0.18s ease' : 'none';
        lbImg.style.transform = `translate(${tx}px, ${ty}px) scale(${scale})`;
        lbLabel.textContent = Math.round(scale * 100) + '%';
        if (!isDragging) lbImg.style.cursor = scale > 1 ? 'grab' : 'zoom-in';
    }

    // Zoom toward an arbitrary screen point (mx, my)
    function zoomAt(mx, my, newScale) {
        newScale = Math.min(MAX, Math.max(MIN, newScale));
        const c = containerCenter();
        const ratio = newScale / scale;
        tx = (mx - c.x) * (1 - ratio) + tx * ratio;
        ty = (my - c.y) * (1 - ratio) + ty * ratio;
        scale = newScale;
        applyTransform(false);
    }

    function reset(animated) {
        scale = 1; tx = 0; ty = 0;
        applyTransform(animated !== false);
    }

    function openLightbox(src) {
        lbImg.src = src;
        reset(false);
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        document.body.style.overflow = '';
        lbImg.src = '';
    }

    // Open triggers
    document.querySelectorAll('.lightbox-trigger').forEach(el => {
        el.addEventListener('click', () => {
            const src = el.dataset.src || el.src || el.querySelector('img')?.src;
            if (src) openLightbox(src);
        });
    });

    lbClose.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', e => { if (e.target === lightbox) closeLightbox(); });

    // Prev/Next inside lightbox — navigate to the prev/next project and re-open zoomed
    function navigateZoomed(href) {
        if (!href) return;
        const url = new URL(href, location.origin);
        url.searchParams.set('z', '1');
        location.href = url.toString();
    }
    document.getElementById('lb-prev')?.addEventListener('click', (e) => {
        e.stopPropagation();
        navigateZoomed(lightbox.dataset.prevHref);
    });
    document.getElementById('lb-next')?.addEventListener('click', (e) => {
        e.stopPropagation();
        navigateZoomed(lightbox.dataset.nextHref);
    });

    // Auto-open the lightbox on page load if arriving from a "zoom-in next" click
    if (new URLSearchParams(location.search).get('z') === '1') {
        const cover = document.querySelector('.lightbox-trigger');
        const src = cover?.dataset.src || cover?.src;
        if (src) openLightbox(src);
        // Clean the URL so a refresh doesn't keep re-opening
        const clean = new URL(location.href);
        clean.searchParams.delete('z');
        history.replaceState(null, '', clean.toString());
    }

    // Mouse wheel — zoom toward cursor
    lbInner.addEventListener('wheel', e => {
        e.preventDefault();
        zoomAt(e.clientX, e.clientY, scale + (e.deltaY < 0 ? STEP : -STEP));
    }, { passive: false });

    // Double-click — zoom in at point, or reset if already zoomed
    lbImg.addEventListener('dblclick', e => {
        scale >= 2.5 ? reset() : zoomAt(e.clientX, e.clientY, scale * 2);
    });

    // Mouse drag to pan when zoomed
    lbImg.addEventListener('mousedown', e => {
        if (scale <= 1) return;
        isDragging = true;
        dragX = e.clientX; dragY = e.clientY;
        dragTx = tx; dragTy = ty;
        lbImg.style.cursor = 'grabbing';
        e.preventDefault();
    });
    document.addEventListener('mousemove', e => {
        if (!isDragging) return;
        tx = dragTx + (e.clientX - dragX);
        ty = dragTy + (e.clientY - dragY);
        applyTransform(false);
    });
    document.addEventListener('mouseup', () => {
        if (isDragging) { isDragging = false; lbImg.style.cursor = scale > 1 ? 'grab' : 'zoom-in'; }
    });

    // Touch: pinch-to-zoom + single-finger pan
    lbInner.addEventListener('touchstart', e => {
        if (e.touches.length === 2) {
            lastTouchDist = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            isTouchPan = false;
        } else if (e.touches.length === 1 && scale > 1) {
            isTouchPan = true;
            touchPanX = e.touches[0].clientX; touchPanY = e.touches[0].clientY;
            touchPanTx = tx; touchPanTy = ty;
        }
    }, { passive: true });

    lbInner.addEventListener('touchmove', e => {
        e.preventDefault();
        if (e.touches.length === 2 && lastTouchDist) {
            const newDist = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            const mx = (e.touches[0].clientX + e.touches[1].clientX) / 2;
            const my = (e.touches[0].clientY + e.touches[1].clientY) / 2;
            zoomAt(mx, my, scale * (newDist / lastTouchDist));
            lastTouchDist = newDist;
        } else if (e.touches.length === 1 && isTouchPan) {
            tx = touchPanTx + (e.touches[0].clientX - touchPanX);
            ty = touchPanTy + (e.touches[0].clientY - touchPanY);
            applyTransform(false);
        }
    }, { passive: false });

    lbInner.addEventListener('touchend', () => { lastTouchDist = null; isTouchPan = false; });

    // Buttons — zoom toward center
    lbZoomIn.addEventListener('click',  () => { const c = containerCenter(); zoomAt(c.x, c.y, scale + STEP); });
    lbZoomOut.addEventListener('click', () => { const c = containerCenter(); zoomAt(c.x, c.y, scale - STEP); });
    lbReset.addEventListener('click',   () => reset());

    // Keyboard
    document.addEventListener('keydown', e => {
        if (!lightbox.classList.contains('flex')) return;
        const c = containerCenter();
        if (e.key === 'Escape')              closeLightbox();
        if (e.key === '+' || e.key === '=')  zoomAt(c.x, c.y, scale + STEP);
        if (e.key === '-')                   zoomAt(c.x, c.y, scale - STEP);
        if (e.key === '0')                   reset();
        if (e.key === 'ArrowLeft')           navigateZoomed(lightbox.dataset.prevHref);
        if (e.key === 'ArrowRight')          navigateZoomed(lightbox.dataset.nextHref);
    });
})();

// Sibling project slider
(function () {
    const track = document.getElementById('sibling-track');
    if (!track) return;
    const btnPrev = document.getElementById('sibling-prev');
    const btnNext = document.getElementById('sibling-next');

    function step() {
        const card = track.querySelector('.sibling-card');
        return card ? card.getBoundingClientRect().width + 16 /* gap-4 */ : 280;
    }

    function updateButtons() {
        const max = track.scrollWidth - track.clientWidth - 1;
        if (btnPrev) btnPrev.disabled = track.scrollLeft <= 1;
        if (btnNext) btnNext.disabled = track.scrollLeft >= max;
    }

    btnPrev?.addEventListener('click', () => track.scrollBy({ left: -step() * 2, behavior: 'smooth' }));
    btnNext?.addEventListener('click', () => track.scrollBy({ left:  step() * 2, behavior: 'smooth' }));
    track.addEventListener('scroll', updateButtons, { passive: true });

    // Center the current project in the strip on load
    const current = track.querySelector('[data-current="1"]');
    if (current) {
        const c = current.offsetLeft - (track.clientWidth - current.offsetWidth) / 2;
        track.scrollLeft = Math.max(0, c);
    }
    updateButtons();

    // Keyboard left/right arrows navigate to prev/next project
    document.addEventListener('keydown', (e) => {
        if (document.getElementById('lightbox')?.classList.contains('hidden') === false) return;
        if (e.target.matches('input, textarea, [contenteditable]')) return;
        const prev = document.getElementById('nav-prev');
        const next = document.getElementById('nav-next');
        if (e.key === 'ArrowLeft'  && prev) { e.preventDefault(); window.location.href = prev.href; }
        if (e.key === 'ArrowRight' && next) { e.preventDefault(); window.location.href = next.href; }
    });
})();
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const pdfCanvases = document.querySelectorAll('.pdf-thumbnail-canvas');
        pdfCanvases.forEach(canvas => {
            const url = canvas.dataset.pdfUrl;
            if (!url) return;

            pdfjsLib.getDocument(url).promise.then(pdf => {
                return pdf.getPage(1);
            }).then(page => {
                const viewport = page.getViewport({ scale: 1.2 });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const ctx = canvas.getContext('2d');
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                return page.render(renderContext).promise;
            }).then(() => {
                const fallback = canvas.parentElement.querySelector('.pdf-fallback-icon');
                if (fallback) fallback.classList.add('hidden');
            }).catch(err => {
                console.error('PDF thumbnail render error:', err);
            });
        });
    }
});
</script>
@endpush

@endsection
