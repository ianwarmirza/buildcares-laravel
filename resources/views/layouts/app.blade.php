<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BuildCares') — Thoughtful Design, Lasting Care</title>
    <link rel="icon" type="image/jpeg" href="/images/logo.jpeg">
    <meta name="description" content="@yield('description', 'BuildCares is a freelance architectural design and CAD subcontractor specialising in planning drawings, loft conversions, extensions, garage conversions, and new builds for UK clients.')">

    <!-- Fonts: DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400;1,9..40,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="antialiased" style="background-color:#ffffff; color:#1e293b;">

    <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-400">
        {{-- Top bar --}}
        <div id="topbar" class="border-b transition-all duration-400" style="background-color:#f8fafc; border-color:#e2e8f0;">
            <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-10 text-xs" style="color:#64748b;">
                <div class="hidden md:flex items-center gap-6">
                    <a href="mailto:{{ \App\Models\Setting::get('site_email', 'anwar@buildcares.com') }}" class="flex items-center gap-2 transition-colors hover:text-blue-600">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                        {{ \App\Models\Setting::get('site_email', 'anwar@buildcares.com') }}
                    </a>
                    <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number', config('contact.whatsapp_number')) }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 transition-colors hover:text-green-600" style="color:#25D366;">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M20.52 3.48A11.85 11.85 0 0012.07 0C5.4 0 .01 5.37 0 12.02c0 2.12.56 4.19 1.62 6l-1.7 6.21 6.38-1.67a12.02 12.02 0 005.75 1.47h.01c6.65 0 12.04-5.38 12.05-12.02a11.92 11.92 0 00-3.59-8.53zM12.06 21.3h-.01a9.95 9.95 0 01-5.07-1.39l-.36-.21-3.79 1 1.01-3.69-.24-.38a9.92 9.92 0 01-1.53-5.31c0-5.5 4.48-9.97 9.98-9.97a9.86 9.86 0 016.99 2.9 9.9 9.9 0 012.91 7.08c0 5.5-4.48 9.97-9.99 9.97zm5.7-7.34c-.31-.16-1.84-.91-2.13-1.02-.29-.11-.5-.16-.7.16-.2.31-.81 1.02-.99 1.23-.18.2-.37.23-.68.08-.31-.16-1.29-.48-2.46-1.53-.91-.81-1.52-1.82-1.7-2.13-.18-.31-.02-.48.14-.64.15-.15.31-.37.47-.55.16-.18.21-.31.32-.52.11-.2.05-.39-.02-.55-.08-.16-.7-1.7-.96-2.32-.25-.6-.5-.52-.7-.53h-.59c-.2 0-.52.08-.79.39-.27.31-1.02 1-1.02 2.46 0 1.45 1.05 2.86 1.2 3.06.16.2 2.08 3.18 5.04 4.46.71.31 1.26.49 1.69.62.71.23 1.35.2 1.86.12.57-.08 1.84-.75 2.1-1.48.26-.73.26-1.35.18-1.48-.08-.13-.29-.2-.61-.36z"/>
                        </svg>
                        {{ \App\Models\Setting::get('site_phone', '+44 7586 750755') }}
                    </a>
                    @if(\App\Models\Setting::get('site_address', 'Pakistan'))
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" style="color:#2563eb;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        <span>{{ \App\Models\Setting::get('site_address', 'Pakistan') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Main nav --}}
        <nav id="main-nav" class="border-b transition-all duration-400" style="background-color:rgba(255,255,255,0.98); border-color:#e2e8f0; backdrop-filter:blur(12px);">
            <div class="max-w-7xl mx-auto px-6 flex items-center justify-between py-4">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="/images/logo.jpeg" alt="BuildCares" class="h-12 w-auto">
                </a>

                <div class="hidden lg:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">Services</a>
                    <a href="{{ route('portfolio.index') }}" class="nav-link {{ request()->routeIs('portfolio.*') ? 'active' : '' }}">Portfolio</a>
                    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact*') ? 'active' : '' }}">Contact</a>
                </div>

                <div class="hidden lg:flex items-center gap-3">
                    <a href="{{ route('contact') }}" class="btn-gold text-xs py-2.5 px-5">
                        Get a Quote
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                <button id="mobile-menu-btn" class="lg:hidden p-2 transition-colors" style="color:#475569;">
                    <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div id="mobile-menu" class="hidden lg:hidden border-t" style="background-color:#f8fafc; border-color:#e2e8f0;">
                <div class="px-6 py-4 space-y-1">
                    <a href="{{ route('home') }}" class="block py-3 text-xs font-semibold uppercase tracking-widest border-b transition-colors hover:text-blue-600" style="color:#475569; border-color:#e2e8f0;">Home</a>
                    <a href="{{ route('services.index') }}" class="block py-3 text-xs font-semibold uppercase tracking-widest border-b transition-colors hover:text-blue-600" style="color:#475569; border-color:#e2e8f0;">Services</a>
                    <a href="{{ route('portfolio.index') }}" class="block py-3 text-xs font-semibold uppercase tracking-widest border-b transition-colors hover:text-blue-600" style="color:#475569; border-color:#e2e8f0;">Portfolio</a>
                    <a href="{{ route('contact') }}" class="block py-3 text-xs font-semibold uppercase tracking-widest transition-colors hover:text-blue-600" style="color:#475569;">Contact</a>
                    <div class="pt-4"><a href="{{ route('contact') }}" class="btn-gold w-full text-center justify-center text-xs py-3">Get a Quote</a></div>
                </div>
            </div>
        </nav>
    </header>

    <main>@yield('content')</main>

    {{-- Footer --}}
    <footer class="relative text-slate-300 overflow-hidden" style="background: linear-gradient(180deg, #0b1329 0%, #070c1a 100%);">
        {{-- Top Accent Line --}}
        <div class="h-1 w-full" style="background: linear-gradient(90deg, #2563eb 0%, #3b82f6 50%, #60a5fa 100%);"></div>

        {{-- Subtle Background Architectural Grid Pattern --}}
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 1440 400" preserveAspectRatio="xMidYMid slice">
                <defs>
                    <pattern id="footer-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#60a5fa" stroke-width="0.8"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#footer-grid)"/>
            </svg>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 pt-16 pb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                {{-- Column 1: Brand & Contact Info --}}
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <div class="inline-block p-2 bg-white rounded-xl shadow-md border border-slate-200/20 mb-4">
                            <img src="/images/logo.jpeg" alt="BuildCares" class="h-11 w-auto">
                        </div>
                        <p class="text-sm leading-relaxed max-w-md" style="color:#94a3b8;">
                            Freelance architectural design and CAD subcontractor delivering precision drawings for UK construction projects.
                        </p>
                    </div>

                    {{-- Contact Badges Grid --}}
                    <div class="grid sm:grid-cols-2 gap-3 pt-2">
                        {{-- Email Badge --}}
                        <a href="mailto:{{ \App\Models\Setting::get('site_email', 'anwar@buildcares.com') }}" 
                           class="group flex items-center gap-3 p-3 rounded-xl bg-slate-900/80 border border-slate-800 hover:border-blue-500/50 hover:bg-slate-800/90 transition-all duration-300 shadow-sm">
                            <div class="w-9 h-9 rounded-lg bg-blue-600/15 border border-blue-500/20 flex items-center justify-center flex-shrink-0 text-blue-400 group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Email</div>
                                <div class="text-xs font-medium text-slate-200 truncate group-hover:text-blue-400 transition-colors">
                                    {{ \App\Models\Setting::get('site_email', 'anwar@buildcares.com') }}
                                </div>
                            </div>
                        </a>

                        {{-- Phone / WhatsApp Badge --}}
                        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number', config('contact.whatsapp_number')) }}" 
                           target="_blank" rel="noopener noreferrer" 
                           class="group flex items-center gap-3 p-3 rounded-xl bg-slate-900/80 border border-slate-800 hover:border-emerald-500/50 hover:bg-slate-800/90 transition-all duration-300 shadow-sm">
                            <div class="w-9 h-9 rounded-lg bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center flex-shrink-0 text-emerald-400 group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.52 3.48A11.85 11.85 0 0012.07 0C5.4 0 .01 5.37 0 12.02c0 2.12.56 4.19 1.62 6l-1.7 6.21 6.38-1.67a12.02 12.02 0 005.75 1.47h.01c6.65 0 12.04-5.38 12.05-12.02a11.92 11.92 0 00-3.59-8.53zM12.06 21.3h-.01a9.95 9.95 0 01-5.07-1.39l-.36-.21-3.79 1 1.01-3.69-.24-.38a9.92 9.92 0 01-1.53-5.31c0-5.5 4.48-9.97 9.98-9.97a9.86 9.86 0 016.99 2.9 9.9 9.9 0 012.91 7.08c0 5.5-4.48 9.97-9.99 9.97zm5.7-7.34c-.31-.16-1.84-.91-2.13-1.02-.29-.11-.5-.16-.7.16-.2.31-.81 1.02-.99 1.23-.18.2-.37.23-.68.08-.31-.16-1.29-.48-2.46-1.53-.91-.81-1.52-1.82-1.7-2.13-.18-.31-.02-.48.14-.64.15-.15.31-.37.47-.55.16-.18.21-.31.32-.52.11-.2.05-.39-.02-.55-.08-.16-.7-1.7-.96-2.32-.25-.6-.5-.52-.7-.53h-.59c-.2 0-.52.08-.79.39-.27.31-1.02 1-1.02 2.46 0 1.45 1.05 2.86 1.2 3.06.16.2 2.08 3.18 5.04 4.46.71.31 1.26.49 1.69.62.71.23 1.35.2 1.86.12.57-.08 1.84-.75 2.1-1.48.26-.73.26-1.35.18-1.48-.08-.13-.29-.2-.61-.36z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Phone / WhatsApp</div>
                                <div class="text-xs font-medium text-slate-200 truncate group-hover:text-emerald-400 transition-colors">
                                    {{ \App\Models\Setting::get('site_phone', '+44 7586 750755') }}
                                </div>
                            </div>
                        </a>

                        {{-- Working Hours Badge --}}
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-900/80 border border-slate-800">
                            <div class="w-9 h-9 rounded-lg bg-blue-600/15 border border-blue-500/20 flex items-center justify-center flex-shrink-0 text-blue-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Working Hours</div>
                                <div class="text-xs font-medium text-slate-200 truncate">
                                    {{ \App\Models\Setting::get('working_hours', 'Mon–Sun 9AM – 10PM') }}
                                </div>
                            </div>
                        </div>

                        {{-- Location Badge --}}
                        @if(\App\Models\Setting::get('site_address', 'Pakistan'))
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-900/80 border border-slate-800">
                            <div class="w-9 h-9 rounded-lg bg-blue-600/15 border border-blue-500/20 flex items-center justify-center flex-shrink-0 text-blue-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Office Location</div>
                                <div class="text-xs font-medium text-slate-200 truncate">
                                    {{ \App\Models\Setting::get('site_address', 'Pakistan') }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Column 2: Services --}}
                <div class="space-y-4">
                    <h4 class="font-bold text-xs tracking-widest uppercase text-white border-b-2 border-blue-600 pb-2 inline-block">
                        Services
                    </h4>
                    <ul class="space-y-2.5 pt-1">
                        @foreach(['Garage Conversion','Loft Conversion','Extensions','New Build','Outbuilding','Internal Changes'] as $svc)
                        <li>
                            <a href="{{ route('services.index') }}" class="group inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-all">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 group-hover:w-3 group-hover:bg-blue-400 transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">{{ $svc }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Column 3: Quick Links --}}
                <div class="space-y-4">
                    <h4 class="font-bold text-xs tracking-widest uppercase text-white border-b-2 border-blue-600 pb-2 inline-block">
                        Quick Links
                    </h4>
                    <ul class="space-y-2.5 pt-1">
                        @foreach([['Home',route('home')],['Portfolio',route('portfolio.index')],['Services',route('services.index')],['Contact',route('contact')]] as [$label,$href])
                        <li>
                            <a href="{{ $href }}" class="group inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-all">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 group-hover:w-3 group-hover:bg-blue-400 transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">{{ $label }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Bottom copyright bar --}}
            <div class="pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500">
                    © {{ date('Y') }} <span class="text-slate-300 font-medium">BuildCares</span>. All rights reserved. Precision Architectural Drawings.
                </p>
                <div class="flex items-center gap-6 text-xs text-slate-500">
                    <a href="{{ route('contact') }}" class="hover:text-blue-400 transition-colors">Free Consultation</a>
                    <span>•</span>
                    <a href="{{ route('services.index') }}" class="hover:text-blue-400 transition-colors">CAD Drafting</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Back to Top Floating Button --}}
    <button id="back-to-top" class="fixed bottom-8 right-8 z-40 w-11 h-11 rounded-xl bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-900/40 border border-blue-400/30 transition-all duration-300 opacity-0 translate-y-4 flex items-center justify-center hover:scale-105 active:scale-95" aria-label="Back to top">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
    </button>

    @stack('scripts')
    <script>
        const topbar = document.getElementById('topbar');
        const mainNav = document.getElementById('main-nav');
        const backToTop = document.getElementById('back-to-top');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 60) {
                topbar.style.height = '0'; topbar.style.overflow = 'hidden'; topbar.style.opacity = '0';
                mainNav.style.boxShadow = '0 2px 16px rgba(15,23,42,0.1)';
                backToTop.classList.remove('opacity-0','translate-y-4');
                backToTop.classList.add('opacity-100','translate-y-0');
            } else {
                topbar.style.height = ''; topbar.style.overflow = ''; topbar.style.opacity = '1';
                mainNav.style.boxShadow = '';
                backToTop.classList.add('opacity-0','translate-y-4');
                backToTop.classList.remove('opacity-100','translate-y-0');
            }
        });
        backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        mobileBtn.addEventListener('click', () => {
            const isOpen = !mobileMenu.classList.contains('hidden');
            mobileMenu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden', !isOpen);
            closeIcon.classList.toggle('hidden', isOpen);
        });
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); } });
        }, { threshold: 0.08 });
        reveals.forEach(el => observer.observe(el));
    </script>
</body>
</html>
