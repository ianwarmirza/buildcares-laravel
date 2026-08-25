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
                {{-- ═══ ONGOING PROJECTS LIVE TICKER (RIGHT TO LEFT MARQUEE) ═══ --}}
                @php
                $dbOngoing = \App\Models\OngoingProject::active()->limit(10)->get();
                if ($dbOngoing->count() > 0) {
                    $tickerList = $dbOngoing;
                } else {
                    $tickerList = collect([
                        (object)['site_address' => '42 High Street, Oxford, UK', 'proposal' => 'Double Storey Rear Extension & Loft Conversion', 'status' => 'In Progress'],
                        (object)['site_address' => '15 Station Road, Reading, UK', 'proposal' => 'Single Storey Wrap-Around Kitchen Extension', 'status' => 'Planning Submission'],
                        (object)['site_address' => '88 Park Lane, Sutton, London, UK', 'proposal' => 'Dormer Loft Conversion & Internal Alterations', 'status' => 'Building Control Review'],
                        (object)['site_address' => '24 Church Street, Cambridge, UK', 'proposal' => 'Garage Conversion to Home Office & Gym Annex', 'status' => 'In Progress'],
                        (object)['site_address' => '7 Victoria Road, Slough, UK', 'proposal' => 'Hip-to-Gable Roof Extension & Skylights', 'status' => 'Drawing Finalisation'],
                        (object)['site_address' => '102 Green Lane, Croydon, London, UK', 'proposal' => 'New Build 4-Bedroom Detached House CAD Package', 'status' => 'Planning Ready'],
                        (object)['site_address' => '31 Mill Road, St Albans, UK', 'proposal' => 'Outbuilding Garden Studio & Permitted Development Set', 'status' => 'In Progress'],
                        (object)['site_address' => '59 Kingsway, Brighton, UK', 'proposal' => 'Structural Internal Wall Removal & Open Plan Living', 'status' => 'Building Control Approval'],
                    ]);
                }
                @endphp

                <div class="mb-8 animate-fadeInUp" style="animation-delay:0.05s">
                    <div class="flex items-center rounded-2xl bg-white/95 backdrop-blur-md border border-blue-200/80 shadow-md p-1.5 overflow-hidden group">
                        {{-- Ticker Badge --}}
                        <div class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-extrabold text-[11px] uppercase tracking-wider flex items-center gap-2 flex-shrink-0 z-20 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            <span>Ongoing Projects</span>
                        </div>

                        {{-- Marquee Right-to-Left Scroll Window --}}
                        <div class="overflow-hidden relative flex-1 ml-3 mask-marquee">
                            <div class="marquee-track flex items-center gap-8 whitespace-nowrap group-hover:[animation-play-state:paused] py-1">
                                {{-- Loop twice for seamless infinite marquee loop --}}
                                @foreach([1, 2] as $loopPass)
                                    @foreach($tickerList as $proj)
                                    <div class="inline-flex items-center gap-2.5 text-xs text-slate-700 font-semibold flex-shrink-0">
                                        <span class="text-blue-600 font-extrabold text-sm">📍</span>
                                        <span class="text-slate-900 font-extrabold">{{ $proj->site_address }}</span>
                                        <span class="text-slate-400 font-normal">•</span>
                                        <span class="text-blue-700 font-bold">{{ $proj->proposal }}</span>
                                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full ml-1">
                                            {{ $proj->status }}
                                        </span>
                                    </div>
                                    <span class="text-slate-300 font-mono text-xs">/</span>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

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


{{-- ═══ ABOUT US (MOVED ABOVE SERVICES WITH VIBRANT ROYAL BLUE THEME) ═══ --}}
<section class="py-24 relative overflow-hidden bg-[#0b172c] text-white border-t border-b border-blue-900/60">
    {{-- Blueprint Micro-Grid SVG & Ambient Radial Glows --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.06]">
        <svg class="w-full h-full" viewBox="0 0 1440 600" preserveAspectRatio="xMidYMid slice">
            <defs><pattern id="about-blue-grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#3b82f6" stroke-width="0.8"/></pattern></defs>
            <rect width="100%" height="100%" fill="url(#about-blue-grid)"/>
        </svg>
    </div>
    <div class="absolute top-0 left-1/4 w-[600px] h-[400px] pointer-events-none" style="background:radial-gradient(circle, rgba(37,99,235,0.22) 0%, transparent 70%);"></div>
    <div class="absolute bottom-0 right-1/4 w-[600px] h-[400px] pointer-events-none" style="background:radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 70%);"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">

            {{-- Left Column: Interactive CAD Technical Card --}}
            <div class="lg:col-span-5 relative">
                <div class="relative rounded-3xl p-7 bg-slate-900/90 border border-blue-500/30 shadow-2xl backdrop-blur-xl overflow-hidden group">
                    {{-- Top Accent Glow Line --}}
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-sky-400 to-indigo-500"></div>

                    {{-- Background Blueprint Icon --}}
                    <div class="absolute -right-8 -bottom-8 opacity-10 text-blue-400 pointer-events-none group-hover:opacity-20 transition-opacity">
                        <svg class="w-48 h-48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>

                    {{-- Card Header --}}
                    <div class="flex items-center justify-between gap-3 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-black text-lg flex items-center justify-center shadow-lg shadow-blue-600/40">
                                B
                            </div>
                            <div>
                                <h4 class="font-extrabold text-sm text-white">BuildCares Studio</h4>
                                <p class="text-[11px] text-blue-400 font-semibold">CAD & Architectural Technicians</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-400 bg-emerald-950/80 border border-emerald-500/40 px-2.5 py-1 rounded-full flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> UK Standard
                        </span>
                    </div>

                    {{-- CAD Capability Metrics --}}
                    <div class="space-y-4 mb-6 relative z-10">
                        @php
                        $capabilities = [
                            ['name' => 'UK Planning Applications', 'perc' => 98],
                            ['name' => 'Building Control Drawings', 'perc' => 96],
                            ['name' => '3D SketchUp & Revit Models', 'perc' => 94],
                            ['name' => 'Photorealistic Renders', 'perc' => 92],
                        ];
                        @endphp

                        @foreach($capabilities as $cap)
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1.5">
                                <span class="text-slate-200">{{ $cap['name'] }}</span>
                                <span class="text-blue-400">{{ $cap['perc'] }}%</span>
                            </div>
                            <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden p-0.5 border border-slate-700/60">
                                <div class="h-full bg-gradient-to-r from-blue-600 via-sky-400 to-indigo-500 rounded-full transition-all duration-1000" style="width: {{ $cap['perc'] }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Software Badges --}}
                    <div class="pt-5 border-t border-slate-800 flex flex-wrap items-center justify-between gap-2 text-xs">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Software Suite:</span>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="px-2 py-0.5 rounded bg-blue-950/90 text-blue-300 border border-blue-800/60 text-[10px] font-extrabold">AutoCAD</span>
                            <span class="px-2 py-0.5 rounded bg-blue-950/90 text-blue-300 border border-blue-800/60 text-[10px] font-extrabold">Revit</span>
                            <span class="px-2 py-0.5 rounded bg-blue-950/90 text-blue-300 border border-blue-800/60 text-[10px] font-extrabold">SketchUp</span>
                            <span class="px-2 py-0.5 rounded bg-blue-950/90 text-blue-300 border border-blue-800/60 text-[10px] font-extrabold">Photoshop</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: About Us Content --}}
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-950/90 border border-blue-500/30 text-blue-400 text-xs font-extrabold uppercase tracking-widest mb-4">
                    <span>🏢</span> About BuildCares
                </div>

                <h2 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight mb-5">
                    Architectural Designer & <br class="hidden sm:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-sky-300 to-indigo-300">CAD Technician</span>
                </h2>

                <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-4">
                    BuildCares is a dedicated freelance architectural drawing service specialising in <strong class="text-white">UK residential construction projects</strong>. We act as a trusted, highly skilled subcontractor — working directly with architects, property developers, builders, and homeowners.
                </p>

                <p class="text-slate-400 text-xs sm:text-sm leading-relaxed mb-8">
                    Using industry-leading software — <span class="text-blue-300 font-semibold">AutoCAD, Revit, SketchUp, and Photoshop</span> — we produce precise, professional drawings formatted to meet strict UK council planning and building control regulations, delivered with fast turnaround times.
                </p>

                {{-- Feature Bullet Grid --}}
                <div class="grid sm:grid-cols-2 gap-3 mb-10">
                    @php
                    $points = [
                        'Technical drawings for planning applications',
                        'Building regulations compliance drawings',
                        '3D visualisations & photorealistic renders',
                        'Fast 3–5 day turnaround & clear communication',
                        'Lowest rates with no compromise on quality',
                        'Subcontractor support for UK architects & builders'
                    ];
                    @endphp

                    @foreach($points as $point)
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-900/60 border border-blue-900/40 text-xs text-slate-200">
                        <span class="w-5 h-5 rounded-full bg-blue-600/30 text-blue-400 flex items-center justify-center font-bold text-xs flex-shrink-0">✓</span>
                        <span>{{ $point }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- CTAs --}}
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('contact') }}" class="px-7 py-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-extrabold text-sm shadow-lg shadow-blue-600/30 border border-blue-400/30 transition-all transform hover:-translate-y-0.5 inline-flex items-center gap-2">
                        <span>Start a Project</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('portfolio.index') }}" class="px-7 py-3.5 rounded-xl bg-slate-900/80 hover:bg-slate-800 text-blue-200 hover:text-white border border-blue-500/40 font-extrabold text-sm transition-all inline-flex items-center gap-2">
                        <span>See Portfolio</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ═══ SERVICES ═══ --}}
<section class="py-24 relative overflow-hidden bg-slate-50 border-t border-b border-slate-200">
    {{-- Background Blueprint Grid Overlay --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.04]">
        <svg class="w-full h-full" viewBox="0 0 1440 600" preserveAspectRatio="xMidYMid slice">
            <defs><pattern id="services-grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#2563eb" stroke-width="0.8"/></pattern></defs>
            <rect width="100%" height="100%" fill="url(#services-grid-pattern)"/>
        </svg>
    </div>
    <div class="absolute top-0 right-1/4 w-[700px] h-[400px] pointer-events-none" style="background:radial-gradient(circle, rgba(37,99,235,0.06) 0%, transparent 70%);"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-50 border border-blue-200/80 text-blue-700 text-xs font-extrabold uppercase tracking-widest mb-3">
                <span>🛠️</span> Our Expertise
            </div>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">Services We Offer</h2>
            <p class="text-slate-500 text-sm sm:text-base mt-3 leading-relaxed">Comprehensive architectural drawing and CAD services tailored for UK planning and building control requirements.</p>
        </div>

        @php
        $services = [
            [
                'img' => 'portfolio/cat-garage-conversion.jpg',
                'title' => 'Garage Conversion',
                'badge' => 'Permitted Dev & Regs',
                'desc' => 'Transform your garage into valuable living space with detailed planning and building control drawings.',
                'slug' => 'garage-conversion'
            ],
            [
                'img' => 'portfolio/cat-loft-conversion.jpg',
                'title' => 'Loft Conversion',
                'badge' => 'Dormer & Hip-to-Gable',
                'desc' => 'Unlock your loft\'s potential with dormer or hip-to-gable conversions, fully drawn to approval standard.',
                'slug' => 'loft-conversion'
            ],
            [
                'img' => 'portfolio/cat-extension.jpg',
                'title' => 'Extensions',
                'badge' => 'Single & Double Storey',
                'desc' => 'Single, double or wrap-around extensions designed to maximise space and comply with planning regulations.',
                'slug' => 'extension'
            ],
            [
                'img' => 'portfolio/cat-new-build.jpg',
                'title' => 'New Build',
                'badge' => 'Full CAD Package',
                'desc' => 'Complete architectural packages for new residential builds from concept through to planning submission.',
                'slug' => 'new-build'
            ],
            [
                'img' => 'portfolio/cat-outbuilding.jpg',
                'title' => 'Outbuilding',
                'badge' => 'Garden Rooms & Studios',
                'desc' => 'Garden rooms, home offices, studios and annexes — full drawings for permitted development or planning.',
                'slug' => 'outbuilding'
            ],
            [
                'img' => 'portfolio/cat-internal-changes.jpg',
                'title' => 'Internal Changes',
                'badge' => 'Structural & Layouts',
                'desc' => 'Structural internal alterations, wall removals and reconfigurations with precise building control drawings.',
                'slug' => 'internal-changes'
            ],
        ];
        @endphp

        {{-- Distinct Card Grid with Generous 32px Gaps --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $i => $svc)
            <a href="{{ route('portfolio.index', ['category' => $svc['slug']]) }}" class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-2xl hover:border-blue-500/80 hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                {{-- Top Blue Gradient Accent Line --}}
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-600 via-sky-400 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity z-20"></div>

                <div>
                    {{-- Dedicated Top Card Header Strip (ABOVE PHOTO) --}}
                    <div class="px-5 pt-4 pb-3 bg-gradient-to-r from-slate-50 to-blue-50/50 border-b border-slate-100 flex items-center justify-between z-30 relative">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> CAD Package
                        </span>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-100/90 border border-blue-200/90 px-2.5 py-1 rounded-full shadow-xs">
                            {{ $svc['badge'] }}
                        </span>
                    </div>

                    {{-- CAD Drawing Image Showcase Box (BELOW HEADER) --}}
                    <div class="relative h-48 bg-gradient-to-br from-white via-slate-50 to-blue-50/20 p-5 flex items-center justify-center border-b border-slate-100 overflow-hidden">
                        {{-- Micro Blueprint Grid SVG --}}
                        <div class="absolute inset-0 pointer-events-none opacity-[0.04]">
                            <svg class="w-full h-full" viewBox="0 0 400 200" preserveAspectRatio="xMidYMid slice">
                                <pattern id="card-bp-grid-{{ $i }}" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="#2563eb" stroke-width="0.6"/></pattern>
                                <rect width="100%" height="100%" fill="url(#card-bp-grid-{{ $i }})"/>
                            </svg>
                        </div>

                        {{-- 3D Isometric CAD House Image --}}
                        <img src="{{ Storage::url($svc['img']) }}" alt="{{ $svc['title'] }}" class="h-full w-auto max-w-full object-contain filter drop-shadow-md group-hover:scale-108 transition-transform duration-500 z-10">
                    </div>

                    {{-- Content Details --}}
                    <div class="p-6">
                        <h3 class="font-extrabold text-xl text-slate-900 mb-2 group-hover:text-blue-600 transition-colors tracking-tight">
                            {{ $svc['title'] }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            {{ $svc['desc'] }}
                        </p>
                    </div>
                </div>

                {{-- Interactive Bottom Button Strip --}}
                <div class="px-6 pb-6 pt-2">
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between font-bold text-xs text-blue-600 group-hover:text-blue-700">
                        <span>View Projects</span>
                        <span class="w-7 h-7 rounded-full bg-blue-50 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                    </div>
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
                        <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Lowest Rates & Fast Turnaround</span>
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
<section class="py-24 relative bg-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 px-3 py-1 text-xs font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 rounded-full border border-blue-200/80 mb-3">
                <span>⭐</span> Our Advantage
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Why Choose BuildCares</h2>
            <p class="text-slate-500 text-sm sm:text-base mt-2">Built on precision, compliance, and fast turnaround for UK architectural projects.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
            $advantages = [
                [
                    'title' => 'Expertise & Precision',
                    'stat' => '100% Accuracy',
                    'desc' => 'CAD excellence with meticulous attention to every dimension, wall thickness, beam calculation, and scale bar.',
                    'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
                ],
                [
                    'title' => 'Cost-Effective',
                    'stat' => 'Lowest Rates',
                    'desc' => 'Competitive quotes with lowest rates and no hidden fees. Professional architectural drawing sets at fair, transparent pricing.',
                    'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                ],
                [
                    'title' => 'Full Service Range',
                    'stat' => 'All-in-One',
                    'desc' => '2D planning drawings, 3D SketchUp models, Photoshop renders, and Revit BIM packages — everything under one roof.',
                    'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'
                ],
                [
                    'title' => 'Fast Turnaround',
                    'stat' => '3–5 Days',
                    'desc' => 'Most project drawing packages delivered ready for submission within 3–5 working days. Urgent deadlines accommodated.',
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                ],
            ];
            @endphp

            @foreach($advantages as $i => $item)
            <div class="bg-slate-50 p-7 rounded-2xl border border-slate-200/90 hover:bg-white hover:border-blue-500/80 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 relative group overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div>
                    <div class="flex items-center justify-between gap-2 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2 py-0.5 rounded-full">
                            {{ $item['stat'] }}
                        </span>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">{{ $item['title'] }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $item['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Bottom Trust Strip --}}
        <div class="mt-14 p-4 rounded-xl bg-slate-900 text-white flex flex-wrap items-center justify-around gap-4 text-xs font-semibold shadow-md border border-slate-800 text-center">
            <span class="flex items-center gap-2"><span class="text-emerald-400">✓</span> Lowest Rates Guarantee</span>
            <span class="hidden sm:inline text-slate-700">•</span>
            <span class="flex items-center gap-2"><span class="text-emerald-400">✓</span> Minor Revisions Included</span>
            <span class="hidden sm:inline text-slate-700">•</span>
            <span class="flex items-center gap-2"><span class="text-emerald-400">✓</span> 100% Planning Approval Focus</span>
            <span class="hidden sm:inline text-slate-700">•</span>
            <span class="flex items-center gap-2"><span class="text-emerald-400">✓</span> UK Building Regs Compliant</span>
        </div>
    </div>
</section>





{{-- ═══ MEET OUR TEAM ═══ --}}
<section class="py-24 relative overflow-hidden bg-slate-50 border-t border-slate-200">
    {{-- Blueprint micro-grid SVG background --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.04]">
        <svg class="w-full h-full" viewBox="0 0 1440 600" preserveAspectRatio="xMidYMid slice">
            <defs><pattern id="team-grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#2563eb" stroke-width="0.8"/></pattern></defs>
            <rect width="100%" height="100%" fill="url(#team-grid)"/>
        </svg>
    </div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[450px] pointer-events-none" style="background:radial-gradient(circle at 50% 20%, rgba(37,99,235,0.08) 0%, transparent 70%);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-50 border border-blue-200/80 text-blue-700 text-xs font-extrabold uppercase tracking-widest mb-3">
                <span>👥</span> Our Experts
            </div>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">Meet Our Team</h2>
            <p class="text-slate-500 text-sm sm:text-base mt-3">The experienced architectural technologists, CAD specialists, and BIM visualisers powering BuildCares.</p>
        </div>

        @php
        $dbMembers = \App\Models\TeamMember::active()->orderBy('sort_order')->orderBy('id')->get();
        if ($dbMembers->count() > 0) {
            $homepageTeam = $dbMembers->map(function($m) {
                return [
                    'name'  => $m->name,
                    'role'  => $m->role,
                    'bio'   => $m->bio,
                    'photo' => $m->photo_url,
                ];
            })->all();
        } else {
            $homepageTeam = [
                [
                    'name'  => 'M Tauseeq Nasir ACIAT',
                    'role'  => 'FOUNDER & ARCHITECTURAL TECHNOLOGIST',
                    'bio'   => 'Founder of ArckiDraw Ltd, Muhammad Tauseeq Nasir is an Architectural Technologist with 9+ years of experience specialising in UK planning, building regulations, and residential projects. He has extensive experience working with architects, developers, and homeowners.',
                    'photo' => 'https://ui-avatars.com/api/?name=M+Tauseeq+Nasir&background=0F172A&color=2563EB&size=512',
                ],
                [
                    'name'  => 'Muhammad Anwar Mirza',
                    'role'  => 'CO-FOUNDER & ARCHITECT',
                    'bio'   => 'Muhammad Anwar is a graduate of the University of Gujrat and a Co-founder of Arckidraw. He has extensive experience in preparing Planning and Building Regulations drawings for UK-based residential projects.',
                    'photo' => 'https://ui-avatars.com/api/?name=Muhammad+Anwar+Mirza&background=0F172A&color=2563EB&size=512',
                ],
                [
                    'name'  => 'Iqra Shehzadi',
                    'role'  => 'JUNIOR ARCHITECT',
                    'bio'   => 'Iqra joined ArckiDraw Ltd in 2020 after completing her Bachelor of Architecture (B.Arch). She specialises in planning drawings and has developed strong experience in preparing detailed drawings for residential extensions.',
                    'photo' => 'https://ui-avatars.com/api/?name=Iqra+Shehzadi&background=0F172A&color=2563EB&size=512',
                ],
                [
                    'name'  => 'M Ali',
                    'role'  => 'SENIOR CGI ARTIST',
                    'bio'   => 'M. Ali is a highly experienced CGI Artist with more than 15 years of experience in creating high-end, photorealistic renders and animations for both residential and commercial projects across the UK and UAE.',
                    'photo' => 'https://ui-avatars.com/api/?name=M+Ali&background=0F172A&color=2563EB&size=512',
                ],
                [
                    'name'  => 'Ubaid Mirza',
                    'role'  => 'TRAINEE ARCHITECTURAL DRAFTSMAN',
                    'bio'   => 'After completing college, Ubaid joined ArckiDraw as a Trainee Architectural Draftsman. He primarily works with AutoCAD and supports the team in preparing accurate existing-condition drawings based on survey notes.',
                    'photo' => 'https://ui-avatars.com/api/?name=Ubaid+Mirza&background=0F172A&color=2563EB&size=512',
                ],
            ];
        }
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($homepageTeam as $member)
            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-blue-500/80 hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                {{-- Top Blue Gradient Accent Line on Hover --}}
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                <div>
                    {{-- Circular Photo Avatar --}}
                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full overflow-hidden border-4 border-blue-50 p-1 bg-white shadow-md mx-auto mb-5 group-hover:scale-105 group-hover:border-blue-100 transition-all duration-300 flex-shrink-0">
                        <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover rounded-full">
                    </div>

                    {{-- Name --}}
                    <h3 class="font-extrabold text-base text-slate-900 tracking-tight mb-1 text-center group-hover:text-blue-600 transition-colors">
                        {{ $member['name'] }}
                    </h3>

                    {{-- Role Badge --}}
                    <div class="text-[10px] sm:text-[11px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50/80 border border-blue-200/80 px-2.5 py-1 rounded-full mb-3 text-center min-h-[2.4rem] flex items-center justify-center">
                        {{ $member['role'] }}
                    </div>

                    {{-- Bio Description --}}
                    <p class="text-[11px] sm:text-xs text-slate-500 leading-relaxed line-clamp-4 text-center mb-5">
                        {{ $member['bio'] }}
                    </p>
                </div>

                {{-- Action Link Button --}}
                <div class="pt-2 border-t border-slate-100">
                    <a href="{{ route('team.index') }}" class="w-full text-center py-2 px-3 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-700 group-hover:text-white font-bold text-xs transition-colors flex items-center justify-center gap-1.5 border border-slate-200 group-hover:border-blue-600 shadow-xs">
                        <span>View Profile</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
