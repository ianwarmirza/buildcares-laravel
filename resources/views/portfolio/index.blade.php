@extends('layouts.app')

@section('title', 'Portfolio — BuildCares')
@section('description', 'Browse our architectural drawing portfolio — garage conversions, loft conversions, extensions, new builds and more.')

@section('content')

{{-- Page Header --}}
<section class="relative pt-40 pb-20 overflow-hidden" style="background-color:#0f172a;">
    {{-- Dynamic 3D ambient background field --}}
    <div class="hero-ambient-3d absolute inset-0 z-0 pointer-events-none opacity-80" aria-hidden="true"></div>

    <div class="absolute inset-0 opacity-[0.06] pointer-events-none z-0">
        <svg class="w-full h-full" viewBox="0 0 1440 300" preserveAspectRatio="xMidYMid slice">
            <defs>
                <pattern id="pg" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#2563eb" stroke-width="0.6"/></pattern>
                <pattern id="pg-lg" width="200" height="200" patternUnits="userSpaceOnUse"><path d="M 200 0 L 0 0 0 200" fill="none" stroke="#2563eb" stroke-width="1"/></pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#pg)"/>
            <rect width="100%" height="100%" fill="url(#pg-lg)"/>
        </svg>
    </div>
    <div class="absolute top-0 right-0 w-[600px] h-[600px] pointer-events-none z-0" style="background:radial-gradient(circle at 75% 30%, rgba(37,99,235,0.15) 0%, transparent 65%);"></div>
    <div class="absolute top-0 left-0 w-1 h-full" style="background-color:#2563eb;"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px" style="background:linear-gradient(to right, rgba(37,99,235,0.5), rgba(37,99,235,0.1), transparent);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="section-label">Our Work</div>
        <h1 class="section-title-light">Portfolio</h1>
    </div>
</section>

{{-- Filter Tabs --}}
<section class="bg-white sticky top-[72px] z-40 border-b shadow-xs" style="border-color:#e2e8f0;">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center gap-2 overflow-x-auto no-scrollbar">
        <a href="{{ route('portfolio.index') }}" class="px-4 py-2 rounded-full text-xs font-extrabold uppercase tracking-widest transition-all duration-300 flex-shrink-0 {{ !$category || $category === 'all' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 border border-blue-600' : 'bg-slate-100 text-slate-600 hover:bg-blue-50 hover:text-blue-600 border border-slate-200' }}">All Projects</a>
        @foreach($categories as $key => $label)
        <a href="{{ route('portfolio.index', ['category' => $key]) }}" class="px-4 py-2 rounded-full text-xs font-extrabold uppercase tracking-widest transition-all duration-300 flex-shrink-0 {{ $category === $key ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 border border-blue-600' : 'bg-slate-100 text-slate-600 hover:bg-blue-50 hover:text-blue-600 border border-slate-200' }}">{{ $label }}</a>
        @endforeach
    </div>
</section>

{{-- Portfolio Grid --}}
<section class="py-20 relative overflow-hidden bg-slate-50 border-t border-slate-200">
    {{-- Blueprint Micro-Grid Background --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.04]">
        <svg class="w-full h-full" viewBox="0 0 1440 600" preserveAspectRatio="xMidYMid slice">
            <defs><pattern id="port-grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#2563eb" stroke-width="0.8"/></pattern></defs>
            <rect width="100%" height="100%" fill="url(#port-grid)"/>
        </svg>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">

        @if($portfolioItems->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="portfolio-grid">
            @foreach($portfolioItems as $i => $item)
            <a href="{{ route('portfolio.show', $item->slug) }}"
               class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-2xl hover:border-blue-500/80 hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden"
               style="animation-delay:{{ ($i % 8) * 0.08 }}s;">
                
                {{-- Top Blue Gradient Accent Line on Hover --}}
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-600 via-sky-400 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity z-20"></div>

                <div>
                    {{-- Card Top Header Bar (Category Badge & Drawing Format) --}}
                    <div class="px-4 pt-3.5 pb-2.5 bg-gradient-to-r from-slate-50 to-blue-50/40 border-b border-slate-100 flex items-center justify-between z-10 relative">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-100/90 border border-blue-200/90 px-2.5 py-1 rounded-full shadow-xs">
                            {{ $item->category_label }}
                        </span>
                        @if($item->is_pdf)
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-red-600 bg-red-50 border border-red-200 px-2 py-0.5 rounded-md flex items-center gap-1">
                            <span>📄</span> PDF
                        </span>
                        @else
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md flex items-center gap-1">
                            <span>📐</span> CAD
                        </span>
                        @endif
                    </div>

                    {{-- Image / PDF Drawing Thumbnail Container --}}
                    @if($item->is_pdf)
                    <div class="w-full aspect-[4/3] bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 overflow-hidden relative flex items-center justify-center border-b border-slate-100 p-2">
                        <canvas class="pdf-canvas w-full h-full object-contain filter drop-shadow-md opacity-0 transition-opacity duration-500 group-hover:scale-105" data-pdf-url="{{ $item->cover_url }}"></canvas>
                        <div class="pdf-loading-fallback absolute inset-0 bg-slate-900 flex flex-col items-center justify-center p-6 text-center group-hover:bg-slate-800 transition-colors">
                            <div class="w-12 h-12 rounded-2xl bg-red-500/20 text-red-400 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-md">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-red-400 bg-red-950/80 px-2.5 py-1 rounded border border-red-800/50">Technical PDF</span>
                            <span class="text-xs font-semibold text-slate-300 mt-2 line-clamp-1 max-w-[90%]">{{ $item->title }}</span>
                        </div>
                    </div>
                    @else
                    <div class="w-full aspect-[4/3] bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 overflow-hidden relative flex items-center justify-center border-b border-slate-100 p-2">
                        <img src="{{ $item->cover_url }}" alt="{{ $item->title }}" loading="lazy" class="w-full h-full object-contain filter drop-shadow-md transition-transform duration-500 group-hover:scale-105"
                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full bg-slate-900 flex flex-col items-center justify-center p-6 text-center\'><div class=\'w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center mb-2\'><svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/></svg></div><span class=\'text-[10px] font-bold uppercase tracking-wider text-blue-400\'>CAD Drawing</span></div>';">
                    </div>
                    @endif

                    {{-- Visible Info Footer --}}
                    <div class="p-5">
                        <h3 class="font-extrabold text-base text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1 mb-2 tracking-tight">
                            {{ $item->title }}
                        </h3>

                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-2">
                            @if($item->location)
                            <span class="flex items-center gap-1 truncate">
                                <svg class="w-3.5 h-3.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                {{ $item->location }}
                            </span>
                            @endif

                            @if($item->year)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                                {{ $item->year }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Interactive Bottom Action Strip --}}
                <div class="px-5 pb-5 pt-0">
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between font-bold text-xs text-blue-600 group-hover:text-blue-700">
                        <span>{{ $item->is_pdf ? 'View PDF Package' : 'View Drawing Details' }}</span>
                        <span class="w-7 h-7 rounded-full bg-blue-50 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @if($portfolioItems->hasPages())
        <div class="mt-14 flex justify-center">{{ $portfolioItems->appends(request()->query())->links() }}</div>
        @endif

        @else
        <div class="text-center py-24 bg-white rounded-3xl border border-slate-200 shadow-sm max-w-2xl mx-auto p-8">
            <div class="w-20 h-20 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-5 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="font-extrabold text-xl text-slate-900 mb-2">No Projects Found</h3>
            <p class="text-sm text-slate-500 mb-6 max-w-md mx-auto">
                @if($category && $category !== 'all')
                    No projects found in this category. <a href="{{ route('portfolio.index') }}" class="font-bold text-blue-600 hover:underline">View all projects</a>
                @else
                    Our latest portfolio projects and CAD drawings will appear here soon.
                @endif
            </p>
            @auth
            <a href="{{ route('admin.portfolio.create') }}" class="px-6 py-3 rounded-xl bg-blue-600 text-white font-extrabold text-xs uppercase tracking-wider hover:bg-blue-700 transition-colors shadow-md">Add Portfolio Item</a>
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
</section>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        document.querySelectorAll('.pdf-canvas').forEach(function(canvas) {
            const url = canvas.dataset.pdfUrl;
            if (!url) return;

            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                return pdf.getPage(1);
            }).then(function(page) {
                const viewport = page.getViewport({ scale: 1.5 });
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                const ctx = canvas.getContext('2d');
                return page.render({ canvasContext: ctx, viewport: viewport }).promise;
            }).then(function() {
                const fallback = canvas.parentElement.querySelector('.pdf-loading-fallback');
                if (fallback) fallback.classList.add('hidden');
                canvas.classList.remove('opacity-0');
            }).catch(function(err) {
                console.warn('PDF.js render fallback active:', err);
            });
        });
    }
});
</script>
@endpush

@endsection
