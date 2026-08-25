@extends('layouts.app')

@section('title', 'Architectural Services — BuildCares')
@section('meta_description', 'Professional architectural drawing packages tailored for UK planning applications and building control compliance. Fast turnaround, affordable price, and 100% approval focus.')

@section('content')

{{-- Header Banner --}}
<section class="py-16 sm:py-20 relative overflow-hidden text-white" style="background-color:#0f172a;">
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <svg class="w-full h-full" viewBox="0 0 1000 1000"><defs><pattern id="grid-svc" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#2563eb" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(#grid-svc)"/></svg>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-px" style="background:linear-gradient(to right, rgba(37,99,235,0.5), rgba(37,99,235,0.1), transparent);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="inline-flex items-center gap-2 px-3 py-1 text-xs font-bold uppercase tracking-widest text-blue-400 bg-blue-950/80 border border-blue-800/60 rounded-full mb-4">
            <span>📐</span> Precision CAD & Architectural Drawing Packages
        </div>
        <h1 class="section-title-light text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight">Our Services</h1>
        <p style="color:#94a3b8;" class="max-w-2xl text-base sm:text-lg mt-3 leading-relaxed">
            Professional architectural drawing packages tailored for UK planning applications and building control compliance. Fast turnaround, affordable price, and 100% approval focus.
        </p>
    </div>
</section>

{{-- Dynamic Sticky Service Navigation Pills --}}
<section class="bg-white sticky top-[72px] z-40 border-b shadow-sm" style="border-color:#e2e8f0;">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-2 overflow-x-auto no-scrollbar">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mr-2 flex-shrink-0">Jump To:</span>
        @foreach($services as $s)
        <a href="#{{ $s->slug }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-700 transition-colors flex-shrink-0">
            {{ $s->name }}
        </a>
        @endforeach
    </div>
</section>

{{-- Main Services Showcase --}}
<section class="py-20" style="background-color:#f8fafc;">
    <div class="max-w-7xl mx-auto px-6">
        <div class="space-y-16">
            @foreach($services as $i => $svc)
            @php
                $num = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                $badge = $svc->icon ? strtoupper($svc->icon) : strtoupper($svc->name);
                $imgUrl = $svc->cover_image ? Storage::url($svc->cover_image) : null;
                $features = is_array($svc->features) && count($svc->features) > 0 ? $svc->features : [
                    'Existing & Proposed Floor Plans',
                    'Detailed Front & Rear Elevations',
                    'Building Control Technical Specs',
                    'UK Planning Application Package'
                ];
                $bgColor = ($i % 2 === 1) ? '#eff6ff' : '#ffffff';
            @endphp
            <div id="{{ $svc->slug }}" class="scroll-mt-32 rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-all duration-300" style="background-color: {{ $bgColor }};">
                <div class="grid lg:grid-cols-12 items-center">
                    {{-- Visual Column --}}
                    <div class="lg:col-span-5 relative bg-slate-900 overflow-hidden min-h-[300px] lg:min-h-[440px] flex items-center justify-center p-6 {{ $i % 2 === 1 ? 'lg:order-2' : '' }}">
                        <div class="absolute inset-0 opacity-20 pointer-events-none">
                            <svg class="w-full h-full" viewBox="0 0 400 400"><path d="M0 0h400v400H0z" fill="none"/><path d="M0 40h400M0 80h400M0 120h400M0 160h400M0 200h400M0 240h400M0 280h400M0 320h400M0 360h400M40 0v400M80 0v400M120 0v400M160 0v400M200 0v400M240 0v400M280 0v400M320 0v400M360 0v400" stroke="#3b82f6" stroke-width="0.5"/></svg>
                        </div>
                        <div class="relative z-10 w-full h-full flex flex-col items-center justify-center">
                            @if($imgUrl)
                            <div class="w-full h-64 sm:h-72 rounded-xl overflow-hidden border border-slate-700/80 shadow-2xl relative group">
                                <img src="{{ $imgUrl }}" alt="{{ $svc->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-80"></div>
                                <span class="absolute bottom-3 left-3 text-[10px] font-extrabold uppercase tracking-widest text-blue-400 bg-slate-900/90 px-2.5 py-1 rounded border border-blue-500/40 backdrop-blur">
                                    {{ $badge }} PACKAGE
                                </span>
                            </div>
                            @else
                            <div class="w-20 h-20 rounded-2xl bg-blue-600/20 border border-blue-500/30 text-blue-400 flex items-center justify-center mb-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">{{ $badge }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Text Content Column --}}
                    <div class="lg:col-span-7 p-8 sm:p-10 lg:p-12 {{ $i % 2 === 1 ? 'lg:order-1' : '' }}">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-xs font-extrabold uppercase tracking-widest text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-200/60">
                                {{ $num }} / SERVICE
                            </span>
                            <span class="text-xs font-medium text-slate-400">UK Standard CAD Package</span>
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-4">
                            {{ $svc->name }}
                        </h2>

                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6">
                            {{ $svc->full_description ?: $svc->short_description }}
                        </p>

                        {{-- Features 2-col List --}}
                        <div class="grid sm:grid-cols-2 gap-3 mb-8">
                            @foreach($features as $feat)
                            <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-700 bg-white/80 p-2.5 rounded-lg border border-slate-200/60 shadow-2xs">
                                <div class="w-5 h-5 rounded-full bg-blue-600/10 text-blue-600 flex items-center justify-center flex-shrink-0 font-bold text-xs">✓</div>
                                <span>{{ $feat }}</span>
                            </div>
                            @endforeach
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ route('portfolio.index', ['category' => $svc->slug]) }}" class="btn-gold text-xs py-3 px-5 shadow-sm">
                                Explore {{ $badge }} Examples
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                            <a href="{{ route('contact') }}?service={{ $svc->slug }}" class="btn-outline-gold text-xs py-3 px-5">
                                Get a Quote
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Deliverables Grid --}}
<section class="py-20 bg-white border-t border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
                Complete Deliverables
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-3 tracking-tight">What's Included in Your Drawing Package</h2>
            <p class="text-slate-500 text-sm mt-2">Every project receives a complete, submission-ready set of professional architectural documents.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['Planning Application Set', 'Site plans, location block plans, existing & proposed floor plans, section views, and key elevations.', 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
                ['Building Control Technical Set', 'Full construction details, insulation & damp proofing specs, drainage layouts, and structural notes.', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ['3D Concept Visuals', 'Realistic 3D CAD visualization models to visualize space, light, and proportions before building.', 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
                ['Revision Guarantee', 'We make minor design tweaks and revisions to ensure your drawings match your vision exactly.', 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15']
            ] as $i => [$title, $desc, $icon])
            <div class="p-6 bg-slate-50 rounded-xl border border-slate-200/80 hover:border-blue-500/50 hover:bg-blue-50/20 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"/></svg>
                </div>
                <h3 class="font-bold text-base text-slate-900 mb-2">{{ $title }}</h3>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-20" style="background-color:#0f172a; border-top:2px solid #2563eb;">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h2 class="font-bold text-3xl sm:text-4xl mb-4 text-white">Ready to Start Your Project?</h2>
        <p class="mb-8 text-slate-400 text-sm sm:text-base leading-relaxed">Discuss your project with our architectural team today. Fast turnaround, 100% compliance guaranteed.</p>
        <a href="{{ route('contact') }}" class="btn-gold px-8 py-4 text-sm font-bold shadow-lg">Get a Free Quote</a>
    </div>
</section>

@endsection
