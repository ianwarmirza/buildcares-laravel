@extends('layouts.app')

@section('title', 'Meet Our Team — BuildCares')
@section('description', 'Meet the experienced architectural designers, CAD specialists, and BIM engineers powering BuildCares.')

@section('content')

{{-- Page Header with Dynamic 3D Ambient Background --}}
<section class="relative pt-40 pb-20 overflow-hidden" style="background-color:#0f172a;">
    <div class="hero-ambient-3d absolute inset-0 z-0 pointer-events-none opacity-80" aria-hidden="true"></div>

    <div class="absolute inset-0 opacity-[0.06] pointer-events-none z-0">
        <svg class="w-full h-full" viewBox="0 0 1440 300" preserveAspectRatio="xMidYMid slice">
            <defs>
                <pattern id="tg" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#2563eb" stroke-width="0.6"/></pattern>
                <pattern id="tg-lg" width="200" height="200" patternUnits="userSpaceOnUse"><path d="M 200 0 L 0 0 0 200" fill="none" stroke="#2563eb" stroke-width="1"/></pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#tg)"/>
            <rect width="100%" height="100%" fill="url(#tg-lg)"/>
        </svg>
    </div>
    <div class="absolute top-0 right-0 w-[600px] h-[600px] pointer-events-none z-0" style="background:radial-gradient(circle at 75% 30%, rgba(37,99,235,0.15) 0%, transparent 65%);"></div>
    <div class="absolute top-0 left-0 w-1 h-full" style="background-color:#2563eb;"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px" style="background:linear-gradient(to right, rgba(37,99,235,0.5), rgba(37,99,235,0.1), transparent);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="inline-flex items-center gap-2 px-3 py-1 text-xs font-bold uppercase tracking-widest text-blue-400 bg-blue-950/80 border border-blue-800/60 rounded-full mb-4">
            <span>👥</span> Architectural CAD Experts
        </div>
        <h1 class="section-title-light text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight">Meet Our Team</h1>
        <p style="color:#94a3b8;" class="max-w-2xl text-base sm:text-lg mt-3 leading-relaxed">
            The dedicated architectural designers, CAD draftsmen, and 3D visualisers bringing accuracy, speed, and UK planning compliance to every project.
        </p>
    </div>
</section>

{{-- Team Showcase Section --}}
<section class="py-20 bg-slate-50 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-6">
        
        @if($teamMembers->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($teamMembers as $member)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-blue-500/60 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                <div>
                    {{-- Photo Header --}}
                    <div class="relative aspect-square overflow-hidden bg-slate-100 border-b border-slate-100">
                        <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" style="{{ $member->photo_style }}" onerror="this.onerror=null; this.src='{{ $member->gender === 'female' ? \App\Models\TeamMember::getFemaleAvatarSvg() : \App\Models\TeamMember::getMaleAvatarSvg() }}';">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>

                    {{-- Member Info --}}
                    <div class="p-6">
                        <div class="inline-block text-[11px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-200/80 mb-3">
                            {{ $member->role }}
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">{{ $member->name }}</h3>
                        
                        @if($member->bio)
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-4">{{ $member->bio }}</p>
                        @endif
                    </div>
                </div>

                {{-- Footer / Social Links --}}
                @if($member->email || $member->phone || $member->linkedin)
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3">
                        @if($member->email)
                        <a href="mailto:{{ $member->email }}" class="text-slate-500 hover:text-blue-600 transition-colors" title="Send Email">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </a>
                        @endif

                        @if($member->phone)
                        <a href="tel:{{ $member->phone }}" class="text-slate-500 hover:text-green-600 transition-colors" title="Call / WhatsApp">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </a>
                        @endif

                        @if($member->linkedin)
                        <a href="{{ $member->linkedin }}" target="_blank" rel="noopener" class="text-slate-500 hover:text-blue-700 transition-colors" title="LinkedIn Profile">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.25V10.9H6.46M7.86 6.75a1.4 1.4 0 1 0 0 2.8 1.4 1.4 0 0 0 0-2.8z"/></svg>
                        </a>
                        @endif
                    </div>

                    <a href="{{ route('contact') }}" class="text-[11px] font-bold text-blue-600 hover:underline">Get in Touch →</a>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        {{-- Default Showcase Team Members when database table is fresh --}}
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center">
                <div class="w-24 h-24 rounded-full bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center mx-auto mb-4 font-extrabold text-2xl">
                    BC
                </div>
                <div class="text-xs font-extrabold uppercase tracking-widest text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-200 inline-block mb-3">
                    Lead Architectural Designer
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 mb-2">Senior CAD & Planning Specialist</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                    Specializing in UK planning drawings, building control compliance, extensions, loft conversions, and 3D architectural visualization.
                </p>
                @auth
                <a href="{{ route('admin.team.create') }}" class="btn-gold text-xs py-2 px-4">
                    + Add Team Member in Admin
                </a>
                @endauth
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center">
                <div class="w-24 h-24 rounded-full bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center mx-auto mb-4 font-extrabold text-2xl">
                    3D
                </div>
                <div class="text-xs font-extrabold uppercase tracking-widest text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-200 inline-block mb-3">
                    BIM & 3D Specialist
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 mb-2">Revit & SketchUp Modeller</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                    Expert in building information modeling (BIM), 3D spatial renders, photorealistic Photoshop visualization, and structural drafting.
                </p>
                @auth
                <a href="{{ route('admin.team.create') }}" class="btn-gold text-xs py-2 px-4">
                    + Add Team Member in Admin
                </a>
                @endauth
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center">
                <div class="w-24 h-24 rounded-full bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center mx-auto mb-4 font-extrabold text-2xl">
                    UK
                </div>
                <div class="text-xs font-extrabold uppercase tracking-widest text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-200 inline-block mb-3">
                    Building Control Consultant
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 mb-2">Technical Regulations Lead</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                    Ensuring all structural notes, insulation specifications, scale bars, and council submission guidelines are 100% compliant.
                </p>
                @auth
                <a href="{{ route('admin.team.create') }}" class="btn-gold text-xs py-2 px-4">
                    + Add Team Member in Admin
                </a>
                @endauth
            </div>
        </div>
        @endif

        {{-- Contact CTA Banner --}}
        <div class="mt-16 bg-slate-900 text-white rounded-3xl p-8 sm:p-12 border border-slate-800 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden shadow-xl">
            <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none text-blue-400">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div class="max-w-xl relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-600/20 text-blue-400 border border-blue-500/30 text-xs font-bold uppercase tracking-widest mb-3">
                    <span>⚡</span> Work Directly with Our Experts
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">Need CAD Drawings for Your Project?</h2>
                <p class="text-slate-400 text-sm mt-2">Get in touch today for a free quote on planning drawings, building control packages, or 3D renders.</p>
            </div>
            <div class="relative z-10 flex flex-wrap gap-4 flex-shrink-0">
                <a href="{{ route('contact') }}" class="btn-gold py-3 px-6 text-xs shadow-lg">Get a Quote</a>
                <a href="{{ route('portfolio.index') }}" class="btn-outline-gold py-3 px-6 text-xs">View Our Portfolio</a>
            </div>
        </div>

    </div>
</section>

@endsection
