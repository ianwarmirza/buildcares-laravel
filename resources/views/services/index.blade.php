@extends('layouts.app')

@section('title', 'Services — BuildCares')
@section('description', 'Architectural drawing and CAD services: planning drawings, building control drawings, garage conversions, loft conversions, extensions and more.')

@section('content')

{{-- Hero Section --}}
<section class="relative pt-40 pb-20 overflow-hidden" style="background-color:#0f172a;">
    {{-- Dynamic 3D ambient background field --}}
    <div class="hero-ambient-3d absolute inset-0 z-0 pointer-events-none opacity-80" aria-hidden="true"></div>

    <div class="absolute inset-0 opacity-[0.06] pointer-events-none z-0">
        <svg class="w-full h-full" viewBox="0 0 1440 300" preserveAspectRatio="xMidYMid slice">
            <defs>
                <pattern id="sg" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#2563eb" stroke-width="0.6"/></pattern>
                <pattern id="sg-lg" width="200" height="200" patternUnits="userSpaceOnUse"><path d="M 200 0 L 0 0 0 200" fill="none" stroke="#2563eb" stroke-width="1"/></pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#sg)"/>
            <rect width="100%" height="100%" fill="url(#sg-lg)"/>
        </svg>
    </div>
    <div class="absolute top-0 right-0 w-[600px] h-[600px] pointer-events-none z-0" style="background:radial-gradient(circle at 75% 30%, rgba(37,99,235,0.15) 0%, transparent 65%);"></div>
    <div class="absolute top-0 left-0 w-1 h-full" style="background-color:#2563eb;"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px" style="background:linear-gradient(to right, rgba(37,99,235,0.5), rgba(37,99,235,0.1), transparent);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="inline-flex items-center gap-2 px-3 py-1 text-xs font-bold uppercase tracking-widest text-blue-400 bg-blue-950/80 border border-blue-800/60 rounded-full mb-4">
            <span>📐</span> Precision CAD & Architectural Drawing Packages
        </div>
        <h1 class="section-title-light text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight">Our Services</h1>
        <p style="color:#94a3b8;" class="max-w-2xl text-base sm:text-lg mt-3 leading-relaxed">
            Professional architectural drawing packages tailored for UK planning applications and building control compliance. Fast turnaround, fixed pricing, and 100% approval focus.
        </p>
    </div>
</section>

{{-- Sticky Service Navigation Pills --}}
<section class="bg-white sticky top-[72px] z-40 border-b shadow-sm" style="border-color:#e2e8f0;">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-2 overflow-x-auto no-scrollbar">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mr-2 flex-shrink-0">Jump To:</span>
        @php
        $navServices = [
            'garage-conversion' => 'Garage Conversion',
            'loft-conversion'   => 'Loft Conversion',
            'extension'         => 'Extensions',
            'new-build'          => 'New Build',
            'outbuilding'       => 'Outbuilding',
            'internal-changes'  => 'Internal Changes',
        ];
        @endphp
        @foreach($navServices as $slug => $label)
        <a href="#{{ $slug }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-700 transition-colors flex-shrink-0">
            {{ $label }}
        </a>
        @endforeach
    </div>
</section>

{{-- Main Services Showcase --}}
<section class="py-20" style="background-color:#f8fafc;">
    <div class="max-w-7xl mx-auto px-6">
        @php
        $serviceData = [
            [
                'slug' => 'garage-conversion',
                'number' => '01',
                'title' => 'Garage Conversion Drawings',
                'badge' => 'GARAGE CONVERSION',
                'img' => 'portfolio/cat-garage-conversion.jpg',
                'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                'desc' => 'Transform your underutilized garage into a high-value living space, home office, or extra bedroom. We produce comprehensive planning and building regulations drawing packages with full insulation, ventilation, and structural specifications.',
                'features' => [
                    'Existing & Proposed Floor Plans',
                    'Detailed Front & Rear Elevations',
                    'Building Control Technical Specs',
                    'Structural Steel Beam Details',
                    'Insulation & Damp Proofing Specs',
                    'Drainage & Ventilation Layouts'
                ]
            ],
            [
                'slug' => 'loft-conversion',
                'number' => '02',
                'title' => 'Loft Conversion Packages',
                'badge' => 'LOFT CONVERSION',
                'img' => 'portfolio/cat-loft-conversion.jpg',
                'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                'desc' => 'Maximize roof space with precision CAD drawings for rear box dormers, hip-to-gable extensions, mansard lofts, or Velux skylights. Designed to strict UK headroom and fire safety standards.',
                'features' => [
                    'Dormer & Hip-to-Gable Plans',
                    'Cross-Section & Headroom Height Checks',
                    'Staircase Placement & Layout',
                    'Fire Safety & Escape Specs',
                    'Thermal Performance Calculations',
                    'Planning Application Full Set'
                ]
            ],
            [
                'slug' => 'extension',
                'number' => '03',
                'title' => 'Home Extensions (Single & Double Storey)',
                'badge' => 'EXTENSIONS',
                'img' => 'portfolio/cat-extension.jpg',
                'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z',
                'desc' => 'Single-storey rear extensions, double-storey side extensions, or wrap-around open plan living. We draft complete drawing sets that satisfy council planning requirements and local building inspectors.',
                'features' => [
                    'Existing & Proposed Elevations',
                    'Open-Plan Kitchen Layouts',
                    'Bi-Fold & Skylight Details',
                    'Location & Ordnance Block Plans',
                    'Full Building Control Package',
                    'Drainage & Foundation Details'
                ]
            ],
            [
                'slug' => 'new-build',
                'number' => '04',
                'title' => 'New Build Residential Drawings',
                'badge' => 'NEW BUILD',
                'img' => 'portfolio/cat-new-build.jpg',
                'icon' => 'M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z',
                'desc' => 'Complete architectural drawing suites for new residential developments, bespoke houses, or infill plots. From outline planning proposals to detailed construction working drawings.',
                'features' => [
                    'Full Planning Application Package',
                    'Site Masterplan & Access Design',
                    'Detailed Floor Plans & Elevations',
                    'Building Control Specification',
                    'Structural Engineer Coordination',
                    'SAP Thermal Compliance Support'
                ]
            ],
            [
                'slug' => 'outbuilding',
                'number' => '05',
                'title' => 'Garden Outbuildings & Annexe',
                'badge' => 'OUTBUILDINGS',
                'img' => 'portfolio/cat-outbuilding.jpg',
                'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z',
                'desc' => 'Garden offices, workshops, granny annexes, and timber outbuildings. We verify permitted development rights or draft full planning permission packages.',
                'features' => [
                    'Garden Office & Studio Plans',
                    'Granny Annexe Drawing Sets',
                    'Permitted Development Checks',
                    'Foundations & Timber Frame Details',
                    'Utility Connections Coordination',
                    'Material Schedule & Elevation Specs'
                ]
            ],
            [
                'slug' => 'internal-changes',
                'number' => '06',
                'title' => 'Internal Alterations & Wall Removals',
                'badge' => 'INTERNAL CHANGES',
                'img' => 'portfolio/cat-internal-changes.jpg',
                'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
                'desc' => 'Load-bearing wall removals, open-plan kitchen integration, and room reconfigurations. We prepare existing vs. proposed plans for building control sign-off.',
                'features' => [
                    'Before & After Layout Drawings',
                    'Load-Bearing Wall Assessment',
                    'RSJ Steel Beam Placement',
                    'Fire Safety Door Specifications',
                    'Building Control Submission Set',
                    'Electric & Plumbing Layout Schemes'
                ]
            ],
        ];
        @endphp

        <div class="space-y-16">
            @foreach($serviceData as $i => $svc)
            <div id="{{ $svc['slug'] }}" class="scroll-mt-32 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <div class="grid lg:grid-cols-12 items-center">
                    {{-- Visual Column --}}
                    <div class="lg:col-span-5 relative bg-slate-900 overflow-hidden min-h-[300px] lg:min-h-[440px] flex items-center justify-center p-6 {{ $i % 2 === 1 ? 'lg:order-2' : '' }}">
                        <div class="absolute inset-0 opacity-20 pointer-events-none">
                            <svg class="w-full h-full" viewBox="0 0 400 400"><path d="M0 0h400v400H0z" fill="none"/><path d="M0 40h400M0 80h400M0 120h400M0 160h400M0 200h400M0 240h400M0 280h400M0 320h400M0 360h400M40 0v400M80 0v400M120 0v400M160 0v400M200 0v400M240 0v400M280 0v400M320 0v400M360 0v400" stroke="#3b82f6" stroke-width="0.5"/></svg>
                        </div>
                        <div class="relative z-10 w-full h-full flex flex-col items-center justify-center">
                            @if(!empty($svc['img']))
                            <div class="w-full h-64 sm:h-72 rounded-xl overflow-hidden border border-slate-700/80 shadow-2xl relative group">
                                <img src="{{ Storage::url($svc['img']) }}" alt="{{ $svc['title'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-80"></div>
                                <span class="absolute bottom-3 left-3 text-[10px] font-extrabold uppercase tracking-widest text-blue-400 bg-slate-900/90 px-2.5 py-1 rounded border border-blue-500/40 backdrop-blur">
                                    {{ $svc['badge'] }} PACKAGE
                                </span>
                            </div>
                            @else
                            <div class="w-20 h-20 rounded-2xl bg-blue-600/20 border border-blue-500/30 text-blue-400 flex items-center justify-center mb-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $svc['icon'] }}"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">{{ $svc['badge'] }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Text Content Column --}}
                    <div class="lg:col-span-7 p-8 sm:p-10 lg:p-12 {{ $i % 2 === 1 ? 'lg:order-1' : '' }}">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-xs font-extrabold uppercase tracking-widest text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-200/60">
                                {{ $svc['number'] }} / SERVICE
                            </span>
                            <span class="text-xs font-medium text-slate-400">UK Standard CAD Package</span>
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-4">
                            {{ $svc['title'] }}
                        </h2>

                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6">
                            {{ $svc['desc'] }}
                        </p>

                        {{-- Features 2-col List --}}
                        <div class="grid sm:grid-cols-2 gap-3 mb-8">
                            @foreach($svc['features'] as $feat)
                            <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-700 bg-slate-50 p-2.5 rounded-lg border border-slate-200/60">
                                <div class="w-5 h-5 rounded-full bg-blue-600/10 text-blue-600 flex items-center justify-center flex-shrink-0 font-bold text-xs">✓</div>
                                <span>{{ $feat }}</span>
                            </div>
                            @endforeach
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ route('portfolio.index', ['category' => $svc['slug']]) }}" class="btn-gold text-xs py-3 px-5 shadow-sm">
                                Explore {{ $svc['badge'] }} Examples
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                            <a href="{{ route('contact') }}?service={{ $svc['slug'] }}" class="btn-outline-gold text-xs py-3 px-5">
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
