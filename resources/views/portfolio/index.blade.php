@extends('layouts.app')

@section('title', 'Portfolio — BuildCares')
@section('description', 'Browse our architectural drawing portfolio — garage conversions, loft conversions, extensions, new builds and more.')

@section('content')

{{-- Page Header --}}
<section class="relative pt-40 pb-20 overflow-hidden" style="background-color:#0f172a;">
    <div class="absolute inset-0 opacity-[0.06] pointer-events-none">
        <svg class="w-full h-full" viewBox="0 0 1440 300" preserveAspectRatio="xMidYMid slice">
            <defs>
                <pattern id="pg" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#2563eb" stroke-width="0.6"/></pattern>
                <pattern id="pg-lg" width="200" height="200" patternUnits="userSpaceOnUse"><path d="M 200 0 L 0 0 0 200" fill="none" stroke="#2563eb" stroke-width="1"/></pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#pg)"/>
            <rect width="100%" height="100%" fill="url(#pg-lg)"/>
        </svg>
    </div>
    <div class="absolute top-0 left-0 w-1 h-full" style="background-color:#2563eb;"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px" style="background:linear-gradient(to right, rgba(37,99,235,0.5), rgba(37,99,235,0.1), transparent);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="section-label">Our Work</div>
        <h1 class="section-title-light">Portfolio</h1>
        <p style="color:#64748b;" class="max-w-xl">Explore our range of architectural drawing projects — from planning submissions to building control packages across the UK.</p>
    </div>
</section>

{{-- Filter Tabs --}}
<section class="bg-white sticky top-[72px] z-40 border-b shadow-sm" style="border-color:#e2e8f0;">
    <div class="max-w-7xl mx-auto px-6 py-4 flex flex-wrap gap-2">
        <a href="{{ route('portfolio.index') }}" class="filter-tab {{ !$category || $category === 'all' ? 'active' : '' }}">All</a>
        @foreach($categories as $key => $label)
        <a href="{{ route('portfolio.index', ['category' => $key]) }}" class="filter-tab {{ $category === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>
</section>

{{-- Portfolio Grid --}}
<section class="section-padding" style="background-color:#f8fafc;">
    <div class="max-w-7xl mx-auto px-6">

        @if($portfolioItems->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="portfolio-grid">
            @foreach($portfolioItems as $i => $item)
            <a href="{{ route('portfolio.show', $item->slug) }}"
               class="portfolio-card reveal group relative block overflow-hidden rounded-lg bg-white border border-slate-200 shadow-sm"
               style="animation-delay:{{ ($i % 8) * 0.08 }}s;">
                @if($item->is_pdf)
                <div class="w-full aspect-[4/3] bg-white overflow-hidden relative flex items-center justify-center border-b border-slate-100 group/pdf">
                    <canvas class="pdf-thumbnail-canvas w-full h-full object-contain" data-pdf-url="{{ $item->cover_url }}"></canvas>
                    <div class="pdf-fallback-icon absolute inset-0 bg-slate-900 flex flex-col items-center justify-center p-6 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-red-500/20 text-red-400 flex items-center justify-center mb-2 animate-pulse">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-red-400 bg-red-950/80 px-2 py-0.5 rounded border border-red-800/50">PDF Drawing</span>
                    </div>
                    <div class="absolute top-2 right-2 flex items-center gap-1 text-[9px] font-black uppercase tracking-wider text-red-600 bg-red-50/90 px-1.5 py-0.5 rounded border border-red-200 shadow-sm z-10">
                        <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                        <span>PDF</span>
                    </div>
                </div>
                @else
                <div class="w-full aspect-[4/3] bg-slate-100 overflow-hidden relative">
                    <img src="{{ $item->cover_url }}" alt="{{ $item->title }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full bg-slate-900 flex flex-col items-center justify-center p-6 text-center\'><div class=\'w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center mb-2\'><svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/></svg></div><span class=\'text-[10px] font-bold uppercase tracking-wider text-blue-400\'>CAD Drawing</span></div>';">
                </div>
                @endif
                <div class="overlay">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs px-2 py-0.5 font-bold uppercase tracking-wider" style="background:rgba(37,99,235,0.2); color:#93c5fd; border:1px solid rgba(37,99,235,0.35);">{{ $item->category_label }}</span>
                        @if($item->is_pdf)<span class="text-xs font-bold uppercase px-1.5 py-0.5 bg-red-600 text-white rounded">PDF</span>@endif
                        @if($item->year)<span class="text-xs" style="color:#64748b;">{{ $item->year }}</span>@endif
                    </div>
                    <h3 class="text-white font-bold text-base leading-tight">{{ $item->title }}</h3>
                    @if($item->location)
                    <span class="text-xs mt-1 flex items-center gap-1" style="color:#64748b;">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        {{ $item->location }}
                    </span>
                    @endif
                    <div class="mt-3 flex items-center gap-1 text-xs font-bold uppercase tracking-wider" style="color:#60a5fa;">
                        {{ $item->is_pdf ? 'View PDF Drawing' : 'View Project' }} <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @if($portfolioItems->hasPages())
        <div class="mt-14 flex justify-center">{{ $portfolioItems->appends(request()->query())->links() }}</div>
        @endif

        @else
        <div class="text-center py-24">
            <div class="w-20 h-20 flex items-center justify-center mx-auto mb-6" style="background:#eff6ff; border:1px solid #dbeafe;">
                <svg class="w-10 h-10" style="color:#93c5fd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="font-bold text-xl mb-2 uppercase" style="color:#0f172a;">No projects yet</h3>
            <p class="text-sm mb-6" style="color:#64748b;">
                @if($category && $category !== 'all')
                    No projects in this category. <a href="{{ route('portfolio.index') }}" class="font-bold hover:underline" style="color:#2563eb;">View all projects</a>
                @else
                    Our latest portfolio projects and CAD drawings will appear here soon.
                @endif
            </p>
            @auth
            <a href="{{ route('admin.portfolio.create') }}" class="btn-gold text-sm">Add Portfolio Item</a>
            @endauth
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="py-16" style="background-color:#0f172a; border-top:2px solid #2563eb;">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h2 class="font-bold text-2xl mb-4" style="color:#f8fafc;">Like What You See?</h2>
        <p class="mb-8" style="color:#64748b;">Let's work together on your next project. Get in touch for a free consultation.</p>
        <a href="{{ route('contact') }}" class="btn-gold">Start Your Project</a>
    </div>
</section>

@push('scripts')
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
