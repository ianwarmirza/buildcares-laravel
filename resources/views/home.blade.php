@extends('layouts.app')
@section('title', 'BuildCares — Architectural Design & CAD Services')
@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative min-h-screen flex items-center overflow-hidden" style="background-color:#f0f7ff;">
    {{-- Static background: blueprint grid + radial glow --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <svg class="absolute inset-0 w-full h-full opacity-[0.05]" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice">
            <defs>
                <pattern id="grid-sm" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#2563eb" stroke-width="0.5"/>
                </pattern>
                <pattern id="grid-lg" width="200" height="200" patternUnits="userSpaceOnUse">
                    <path d="M 200 0 L 0 0 0 200" fill="none" stroke="#2563eb" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-sm)"/>
            <rect width="100%" height="100%" fill="url(#grid-lg)"/>
        </svg>
        <div class="absolute top-0 right-0 w-[900px] h-[900px] pointer-events-none" style="background:radial-gradient(circle at 75% 30%, rgba(37,99,235,0.10) 0%, transparent 65%);"></div>
    </div>

    {{-- Three.js ambient field: subtle drifting particles + wireframe shapes across the whole hero --}}
    <div id="hero-ambient-3d" class="absolute inset-0 z-[1] pointer-events-none" aria-hidden="true"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 pt-36 pb-24 w-full">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="animate-fadeInUp" style="animation-delay:0.1s; font-family:'DM Sans',sans-serif; font-size:0.65rem; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; color:#2563eb; display:flex; align-items:center; gap:0.625rem; margin-bottom:1.75rem;">
                    <span style="display:block;width:1.5rem;height:2px;background:#2563eb;flex-shrink:0;"></span>
                    Freelance-Based Subcontractor
                </div>

                <h1 class="animate-fadeInUp" style="animation-delay:0.2s; font-family:'DM Sans',sans-serif; font-size:clamp(2.75rem,5.5vw,4.5rem); font-weight:800; line-height:1.08; letter-spacing:-0.02em; color:#0f172a; margin-bottom:1.5rem;">
                    Thoughtful Design,<br>
                    <span style="color:#2563eb;">Lasting Care.</span>
                </h1>

                <p class="text-lg leading-relaxed max-w-md mb-10 animate-fadeInUp" style="animation-delay:0.3s; color:#64748b;">
                    Precision architectural drawings and CAD services for UK construction. From planning applications to building control — we deliver quality that gets approved.
                </p>

                <div class="flex flex-wrap gap-4 animate-fadeInUp" style="animation-delay:0.4s">
                    <a href="{{ route('portfolio.index') }}" class="btn-gold">
                        View Our Work
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('contact') }}" class="btn-outline-gold">Get a Quote</a>
                </div>

                <div class="flex flex-wrap items-center gap-6 mt-12 animate-fadeInUp" style="animation-delay:0.5s">
                    @foreach(['Planning Approved','3–5 Day Turnaround','UK Standards'] as $badge)
                    <div class="flex items-center gap-2 text-sm" style="color:#64748b;">
                        <svg class="w-4 h-4 flex-shrink-0" style="color:#2563eb;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ $badge }}
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 3D wireframe house — Three.js scene --}}
            <div class="relative hidden lg:block animate-fadeInUp" style="animation-delay:0.35s">
                <div class="relative">
                    {{-- Blueprint canvas frame (matches the rest of the design language) --}}
                    <div class="relative border shadow-xl" style="background:#ffffff; border-color:#dbeafe;">
                        <div class="p-2">
                            <div class="aspect-[4/3] relative overflow-hidden" style="background:linear-gradient(180deg,#f0f7ff 0%,#e6f0ff 100%);">
                                {{-- Subtle blueprint grid behind the 3D scene --}}
                                <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 400 300" preserveAspectRatio="none">
                                    <defs>
                                        <pattern id="bp-grid" width="20" height="20" patternUnits="userSpaceOnUse">
                                            <path d="M 20 0 L 0 0 0 20" fill="none" stroke="#dbeafe" stroke-width="0.8"/>
                                        </pattern>
                                    </defs>
                                    <rect width="400" height="300" fill="url(#bp-grid)"/>
                                </svg>

                                {{-- Three.js canvas mounts here --}}
                                <div id="hero-house-3d" class="absolute inset-0"></div>

                                {{-- Drawing-style labels on top --}}
                                <div class="absolute top-3 left-3 right-3 flex items-start justify-between pointer-events-none">
                                    <div class="text-[10px] uppercase tracking-[0.2em] font-bold" style="color:#2563eb;">3D Concept</div>
                                    <div class="text-right">
                                        <div class="text-[10px] font-bold" style="color:#60a5fa;">BuildCares</div>
                                        <div class="text-[9px]" style="color:#93c5fd;">Drawing No: BC-001</div>
                                    </div>
                                </div>
                                <div class="absolute bottom-3 left-3 right-3 flex items-end justify-between pointer-events-none">
                                    <div class="text-[10px] uppercase tracking-[0.2em]" style="color:#93c5fd;">Scale 1:50</div>
                                    <div class="text-[10px] uppercase tracking-[0.2em]" style="color:#93c5fd;">Rev. A</div>
                                </div>

                                {{-- Corner crops --}}
                                <div class="absolute top-3 left-3 w-5 h-5 border-l border-t pointer-events-none" style="border-color:#bfdbfe;"></div>
                                <div class="absolute top-3 right-3 w-5 h-5 border-r border-t pointer-events-none" style="border-color:#bfdbfe;"></div>
                                <div class="absolute bottom-3 left-3 w-5 h-5 border-l border-b pointer-events-none" style="border-color:#bfdbfe;"></div>
                                <div class="absolute bottom-3 right-3 w-5 h-5 border-r border-b pointer-events-none" style="border-color:#bfdbfe;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating badges --}}
                    <div class="absolute -top-4 -right-4 px-4 py-3 text-sm font-semibold flex items-center gap-2 shadow-lg border" style="background:#ffffff; border-color:#dbeafe; color:#1e293b;">
                        <svg class="w-4 h-4" style="color:#2563eb;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Planning Approved
                    </div>
                    <div class="absolute -bottom-4 -left-4 px-4 py-3 shadow-lg border" style="background:#ffffff; border-color:#dbeafe;">
                        <div class="text-xs uppercase tracking-wider font-bold" style="color:#94a3b8;">Turnaround</div>
                        <div class="font-bold text-xl" style="color:#2563eb;">3–5 Days</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-bounce">
        <span class="text-xs tracking-widest uppercase" style="color:#94a3b8;">Scroll</span>
        <svg class="w-4 h-4" style="color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
</section>


{{-- ═══ SERVICES ═══ --}}
<section class="section-padding" style="background-color:#f8fafc;">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <div class="section-label justify-center reveal">Our Expertise</div>
            <h2 class="section-title reveal">Services We Offer</h2>
            <p class="leading-relaxed max-w-xl mx-auto reveal" style="color:#64748b;">Comprehensive architectural drawing and CAD services tailored for UK planning and building control requirements.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-px" style="background-color:#e2e8f0;">
            @php
            $services = [
                ['img'=>'portfolio/cat-garage-conversion.jpg','title'=>'Garage Conversion','desc'=>'Transform your garage into valuable living space with detailed planning and building control drawings.','slug'=>'garage-conversion'],
                ['img'=>'portfolio/cat-loft-conversion.jpg','title'=>'Loft Conversion','desc'=>'Unlock your loft\'s potential with dormer or hip-to-gable conversions, fully drawn to approval standard.','slug'=>'loft-conversion'],
                ['img'=>'portfolio/cat-extension.jpg','title'=>'Extensions','desc'=>'Single, double or wrap-around extensions designed to maximise space and comply with planning regulations.','slug'=>'extension'],
                ['img'=>'portfolio/cat-new-build.jpg','title'=>'New Build','desc'=>'Complete architectural packages for new residential builds from concept through to planning submission.','slug'=>'new-build'],
                ['img'=>'portfolio/cat-outbuilding.jpg','title'=>'Outbuilding','desc'=>'Garden rooms, home offices, studios and annexes — full drawings for permitted development or planning.','slug'=>'outbuilding'],
                ['img'=>'portfolio/cat-internal-changes.jpg','title'=>'Internal Changes','desc'=>'Structural internal alterations, wall removals and reconfigurations with precise building control drawings.','slug'=>'internal-changes'],
            ];
            @endphp

            @foreach($services as $i => $svc)
            <a href="{{ route('portfolio.index', ['category' => $svc['slug']]) }}" class="group reveal bg-white block overflow-hidden" style="animation-delay:{{ $i * 0.07 }}s;">
                <div class="overflow-hidden" style="height:200px;">
                    <img src="{{ Storage::url($svc['img']) }}" alt="{{ $svc['title'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-base mb-2" style="color:#0f172a;">{{ $svc['title'] }}</h3>
                    <p class="text-sm leading-relaxed mb-4" style="color:#64748b;">{{ $svc['desc'] }}</p>
                    <span class="text-xs font-bold uppercase tracking-widest flex items-center gap-2 group-hover:gap-3 transition-all" style="color:#2563eb;">
                        View Projects
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>



{{-- Featured projects removed as requested. --}}


{{-- ═══ WHAT WE DELIVER & SOFTWARE WE USE ═══ --}}
<section class="py-24 relative overflow-hidden" style="background-color:#f8fafc; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;">
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-start">

            {{-- Left Column: What We Deliver --}}
            <div class="lg:col-span-7 space-y-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 text-xs font-bold uppercase tracking-widest text-blue-700 bg-blue-100/80 rounded-full mb-3">
                        <span>📋</span> Specialist Architectural Packages
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">What We Deliver</h2>
                    <p class="text-slate-600 text-sm sm:text-base mt-2 leading-relaxed max-w-xl">
                        As specialist CAD technicians and architectural designers, we produce drawing packages that UK planners, building inspectors, and contractors can rely on.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 gap-4 pt-2">
                    @php
                    $deliverables = [
                        [
                            'title' => 'Planning Drawings',
                            'badge' => 'Planning Ready',
                            'desc' => 'Full planning application packages including location site plans, block plans, floor layouts, elevations and sections.',
                            'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'
                        ],
                        [
                            'title' => 'Building Control',
                            'badge' => 'Regs Compliant',
                            'desc' => 'Detailed technical construction drawings meeting UK building regulations for structural, insulation & drainage sign-off.',
                            'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
                        ],
                        [
                            'title' => '3D Visualisation',
                            'badge' => 'BIM & Renders',
                            'desc' => 'Realistic SketchUp models, Revit BIM representations, and Photoshop renders for client presentations and council reviews.',
                            'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'
                        ],
                        [
                            'title' => 'Design Modifications',
                            'badge' => 'Fast Revisions',
                            'desc' => 'Continuous design iteration and updates — we collaborate with you until your architectural vision is realized.',
                            'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
                        ],
                    ];
                    @endphp

                    @foreach($deliverables as $i => $item)
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-500/60 hover:-translate-y-1 transition-all group duration-300 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/></svg>
                            </div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                {{ $item['badge'] }}
                            </span>
                        </div>
                        <h3 class="font-bold text-base text-slate-900 mb-1.5 group-hover:text-blue-600 transition-colors">{{ $item['title'] }}</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Right Column: Software We Use & Professional Standards --}}
            <div class="lg:col-span-5 space-y-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 text-xs font-bold uppercase tracking-widest text-slate-700 bg-slate-200/80 rounded-full mb-3">
                        <span>🛠️</span> Professional CAD Suite
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Software We Use</h3>
                    <p class="text-slate-600 text-sm mt-1">Industry-standard tools for maximum precision and compatibility.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @php
                    $softwareList = [
                        [
                            'name' => 'SketchUp Pro',
                            'type' => '3D Spatial Modelling',
                            'color' => '#0097D4',
                            'short' => 'SKP'
                        ],
                        [
                            'name' => 'Autodesk Revit',
                            'type' => 'BIM & Structural 3D',
                            'color' => '#0696D7',
                            'short' => 'RVT'
                        ],
                        [
                            'name' => 'AutoCAD Architecture',
                            'type' => '2D Technical Drafting',
                            'color' => '#D2232A',
                            'short' => 'CAD'
                        ],
                        [
                            'name' => 'Adobe Photoshop',
                            'type' => 'Photorealistic Renders',
                            'color' => '#31A8FF',
                            'short' => 'PSD'
                        ],
                    ];
                    @endphp

                    @foreach($softwareList as $sw)
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3.5 hover:border-slate-300 transition-all">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 text-white font-extrabold text-xs shadow-sm" style="background-color: {{ $sw['color'] }};">
                            {{ $sw['short'] }}
                        </div>
                        <div>
                            <div class="font-bold text-xs sm:text-sm text-slate-900 leading-snug">{{ $sw['name'] }}</div>
                            <div class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $sw['type'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Highlights Card --}}
                <div class="p-6 rounded-xl bg-slate-900 text-white shadow-lg relative overflow-hidden border border-slate-800">
                    <div class="absolute -right-6 -bottom-6 opacity-10 text-blue-400 pointer-events-none">
                        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.57l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.57l7-10a1 1 0 011.12-.384z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Fixed Rates & Fast Turnaround</span>
                    </div>
                    <h4 class="font-bold text-base text-white mb-1">UK Planning & Building Regs Ready</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Every drawing is formatted to exact UK council standards (scale bars, block plans, notes, and key dimensions) ensuring smooth approval.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ═══ WHY CHOOSE US ═══ --}}
<section class="section-padding bg-white border-t" style="border-color:#e2e8f0;">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <div class="section-label justify-center reveal">Our Advantage</div>
            <h2 class="section-title reveal">Why Choose BuildCares</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['Expertise & Precision','CAD excellence with meticulous attention to every measurement and detail.','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ['Cost-Effective','Competitive pricing without compromising quality — professional results at fair rates.','M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Full Service Range','2D drawings, 3D models, Photoshop renders, Revit BIM — everything under one roof.','M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                ['Fast Turnaround','Most projects delivered in 3–5 working days. Urgent submissions catered for.','M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ] as $i => [$title, $desc, $icon])
            <div class="card-light p-6 reveal" style="animation-delay:{{ $i * 0.1 }}s">
                <div class="w-12 h-12 flex items-center justify-center mb-5" style="background:#eff6ff; border:1px solid #dbeafe;">
                    <svg class="w-5 h-5" style="color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"/>
                    </svg>
                </div>
                <h3 class="font-bold text-sm mb-2" style="color:#0f172a;">{{ $title }}</h3>
                <p class="text-sm leading-relaxed" style="color:#64748b;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══ ABOUT ═══ --}}
<section class="section-padding" style="background-color:#0f172a;">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="relative reveal">
                <div class="p-6 border" style="background:rgba(30,41,59,0.8); border-color:rgba(37,99,235,0.2);">
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div class="aspect-video flex items-center justify-center border" style="background:#080f1e; border-color:rgba(255,255,255,0.05);">
                            <svg class="w-10 h-10" style="color:rgba(37,99,235,0.3);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div class="aspect-video flex items-center justify-center border" style="background:#080f1e; border-color:rgba(255,255,255,0.05);">
                            <svg class="w-10 h-10" style="color:rgba(37,99,235,0.3);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </div>
                    </div>
                    <div class="p-4 border" style="background:#080f1e; border-color:rgba(255,255,255,0.05);">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 flex items-center justify-center text-white font-bold text-sm flex-shrink-0" style="background:#2563eb;">A</div>
                            <div>
                                <div class="text-sm font-semibold" style="color:#f8fafc;">Anwar</div>
                                <div class="text-xs" style="color:#475569;">Architectural Designer & CAD Technician</div>
                            </div>
                        </div>
                        <div class="space-y-2.5">
                            @foreach(['Technical Drawings','Design Modifications','3D Modelling','Documentation'] as $skill)
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-1.5 overflow-hidden" style="background:#1e293b;">
                                    <div class="h-full" style="background:linear-gradient(to right,#1d4ed8,#2563eb);width:{{ rand(78,95) }}%"></div>
                                </div>
                                <span class="text-xs w-28 text-right" style="color:#475569;">{{ $skill }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="section-label reveal">About Us</div>
                <h2 class="section-title-light reveal">Architectural Designer & CAD Technician</h2>
                <p class="leading-relaxed mb-6 reveal" style="color:#64748b;">
                    BuildCares is a Pakistan-based freelance architectural drawing service specialising in UK residential projects. We act as a skilled subcontractor, working directly with architects, builders and homeowners.
                </p>
                <p class="leading-relaxed mb-8 reveal" style="color:#64748b;">
                    Using industry-leading software — AutoCAD, Revit, SketchUp and Photoshop — we produce precise, professional drawings that meet UK planning and building control standards, delivered fast.
                </p>
                <ul class="space-y-3 mb-10 reveal">
                    @foreach(['Technical drawings for planning applications','Building regulations compliance drawings','3D visualisations and photorealistic renders','Fast turnaround with clear communication','Competitive rates with no compromise on quality'] as $point)
                    <li class="flex items-center gap-3 text-sm" style="color:#94a3b8;">
                        <span class="w-1.5 h-1.5 flex-shrink-0 rounded-full" style="background:#2563eb;"></span>
                        {{ $point }}
                    </li>
                    @endforeach
                </ul>
                <div class="flex gap-4 reveal">
                    <a href="{{ route('contact') }}" class="btn-gold">Start a Project</a>
                    <a href="{{ route('portfolio.index') }}" class="btn-outline-gold-dark">See Portfolio</a>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ═══ CTA ═══ --}}
<section class="relative py-20 overflow-hidden border-t" style="background-color:#f8fafc; border-color:#e2e8f0;">
    <div class="absolute inset-0 pointer-events-none opacity-[0.04]">
        <svg class="w-full h-full" viewBox="0 0 1440 300" preserveAspectRatio="xMidYMid slice">
            <defs><pattern id="cta-grid" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M 50 0 L 0 0 0 50" fill="none" stroke="#2563eb" stroke-width="0.8"/></pattern></defs>
            <rect width="100%" height="100%" fill="url(#cta-grid)"/>
        </svg>
    </div>
    <div class="absolute left-0 top-0 bottom-0 w-1" style="background:#2563eb;"></div>
    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <div class="section-label justify-center reveal">Ready to Build?</div>
        <h2 class="section-title reveal">Have a Project in Mind?</h2>
        <p class="text-lg mb-10 reveal max-w-xl mx-auto" style="color:#64748b;">
            Get in touch today for a free consultation and quote. Most enquiries receive a response within a few hours.
        </p>
        <div class="flex flex-wrap gap-4 justify-center reveal">
            <a href="{{ route('contact') }}" class="btn-gold text-sm px-8 py-4">
                Get a Free Quote
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

@endsection
