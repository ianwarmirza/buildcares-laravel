@extends('layouts.app')

@section('title', 'Contact — BuildCares')
@section('description', 'Get in touch with BuildCares for a free quote on your architectural drawing project.')

@section('content')

{{-- Page Header --}}
<section class="relative pt-40 pb-20 overflow-hidden" style="background-color:#0f172a;">
    {{-- Dynamic 3D ambient background field --}}
    <div class="hero-ambient-3d absolute inset-0 z-0 pointer-events-none opacity-80" aria-hidden="true"></div>

    <div class="absolute inset-0 opacity-[0.06] pointer-events-none z-0">
        <svg class="w-full h-full" viewBox="0 0 1440 300" preserveAspectRatio="xMidYMid slice">
            <defs>
                <pattern id="cg" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#2563eb" stroke-width="0.6"/></pattern>
                <pattern id="cg-lg" width="200" height="200" patternUnits="userSpaceOnUse"><path d="M 200 0 L 0 0 0 200" fill="none" stroke="#2563eb" stroke-width="1"/></pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#cg)"/>
            <rect width="100%" height="100%" fill="url(#cg-lg)"/>
        </svg>
    </div>
    <div class="absolute top-0 right-0 w-[600px] h-[600px] pointer-events-none z-0" style="background:radial-gradient(circle at 75% 30%, rgba(37,99,235,0.15) 0%, transparent 65%);"></div>
    <div class="absolute top-0 left-0 w-1 h-full" style="background-color:#2563eb;"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px" style="background:linear-gradient(to right, rgba(37,99,235,0.5), rgba(37,99,235,0.1), transparent);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="section-label">Reach Out</div>
        <h1 class="section-title-light">Contact Us</h1>
        <p style="color:#94a3b8;" class="max-w-xl text-base mt-2">Ready to start your project? Get in touch for a free consultation and quote. We respond to all enquiries within a few hours.</p>
    </div>
</section>

{{-- Contact section --}}
<section class="section-padding" style="background-color:#f8fafc;">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-3 gap-12">

            {{-- Info --}}
            <div class="space-y-6">
                <div>
                    <div class="section-label">Get in Touch</div>
                    <h2 class="section-title" style="font-size:1.75rem; margin-bottom:1rem;">We're Here to Help</h2>
                    <p class="text-sm leading-relaxed" style="color:#64748b;">Describe your project and we'll come back with a tailored quote and timeline. No obligation, completely free.</p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start gap-4 bg-white p-4 border" style="border-color:#e2e8f0; border-left:2px solid #2563eb;">
                        <div class="w-9 h-9 flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                            <svg class="w-4 h-4" style="color:#2563eb;" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold uppercase tracking-widest mb-1" style="color:#94a3b8;">Email</div>
                            <a href="mailto:{{ \App\Models\Setting::get('site_email', 'anwar@buildcares.com') }}" class="text-sm font-semibold transition-colors hover:text-blue-600" style="color:#1e293b;">{{ \App\Models\Setting::get('site_email', 'anwar@buildcares.com') }}</a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 bg-white p-4 border" style="border-color:#e2e8f0; border-left:2px solid #2563eb;">
                        <div class="w-9 h-9 flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                            <svg class="w-4 h-4" style="color:#25D366;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.52 3.48A11.85 11.85 0 0012.07 0C5.4 0 .01 5.37 0 12.02c0 2.12.56 4.19 1.62 6l-1.7 6.21 6.38-1.67a12.02 12.02 0 005.75 1.47h.01c6.65 0 12.04-5.38 12.05-12.02a11.92 11.92 0 00-3.59-8.53zM12.06 21.3h-.01a9.95 9.95 0 01-5.07-1.39l-.36-.21-3.79 1 1.01-3.69-.24-.38a9.92 9.92 0 01-1.53-5.31c0-5.5 4.48-9.97 9.98-9.97a9.86 9.86 0 016.99 2.9 9.9 9.9 0 012.91 7.08c0 5.5-4.48 9.97-9.99 9.97zm5.7-7.34c-.31-.16-1.84-.91-2.13-1.02-.29-.11-.5-.16-.7.16-.2.31-.81 1.02-.99 1.23-.18.2-.37.23-.68.08-.31-.16-1.29-.48-2.46-1.53-.91-.81-1.52-1.82-1.7-2.13-.18-.31-.02-.48.14-.64.15-.15.31-.37.47-.55.16-.18.21-.31.32-.52.11-.2.05-.39-.02-.55-.08-.16-.7-1.7-.96-2.32-.25-.6-.5-.52-.7-.53h-.59c-.2 0-.52.08-.79.39-.27.31-1.02 1-1.02 2.46 0 1.45 1.05 2.86 1.2 3.06.16.2 2.08 3.18 5.04 4.46.71.31 1.26.49 1.69.62.71.23 1.35.2 1.86.12.57-.08 1.84-.75 2.1-1.48.26-.73.26-1.35.18-1.48-.08-.13-.29-.2-.61-.36z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold uppercase tracking-widest mb-1" style="color:#94a3b8;">Phone</div>
                            <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number', config('contact.whatsapp_number')) }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold transition-colors hover:text-green-600" style="color:#1e293b;">{{ \App\Models\Setting::get('site_phone', '+44 7586 750755') }}</a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 bg-white p-4 border" style="border-color:#e2e8f0; border-left:2px solid #2563eb;">
                        <div class="w-9 h-9 flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                            <svg class="w-4 h-4" style="color:#2563eb;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold uppercase tracking-widest mb-1" style="color:#94a3b8;">Working Hours</div>
                            <span class="text-sm font-semibold" style="color:#1e293b;">{{ \App\Models\Setting::get('working_hours', 'Mon–Sun 9AM – 10PM') }}</span>
                        </div>
                    </div>
                    @if(\App\Models\Setting::get('site_address', 'Pakistan'))
                    <div class="flex items-start gap-4 bg-white p-4 border" style="border-color:#e2e8f0; border-left:2px solid #2563eb;">
                        <div class="w-9 h-9 flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                            <svg class="w-4 h-4" style="color:#2563eb;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold uppercase tracking-widest mb-1" style="color:#94a3b8;">Location</div>
                            <span class="text-sm font-semibold" style="color:#1e293b;">{{ \App\Models\Setting::get('site_address', 'Pakistan') }}</span>
                        </div>
                    </div>
                    @endif
                </div>

            </div>

            {{-- Form --}}
            <div class="lg:col-span-2">
                @if(session('success'))
                <div class="mb-6 p-4 flex items-start gap-3" style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534;">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <div><div class="font-bold mb-1">Message Sent!</div><div class="text-sm opacity-80">{{ session('success') }}</div></div>
                </div>
                @endif

                <div class="bg-white p-8 border" style="border-color:#e2e8f0; border-top:2px solid #2563eb;">
                    <h2 class="font-bold text-2xl mb-6" style="color:#0f172a;">Send a Message</h2>

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:#94a3b8;">Full Name <span style="color:#2563eb;">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="input-dark @error('name') !border-blue-400 @enderror" placeholder="John Smith">
                                @error('name')<p class="text-xs mt-1" style="color:#2563eb;">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:#94a3b8;">Email <span style="color:#2563eb;">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="input-dark @error('email') !border-blue-400 @enderror" placeholder="john@example.com">
                                @error('email')<p class="text-xs mt-1" style="color:#2563eb;">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label for="phone" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:#94a3b8;">Phone</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="input-dark" placeholder="+44 7700 900000">
                            </div>
                            <div>
                                <label for="service" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:#94a3b8;">Service</label>
                                <select id="service" name="service" class="input-dark">
                                    <option value="">Select a service...</option>
                                    <option value="garage-conversion" {{ old('service', request('service')) === 'garage-conversion' ? 'selected' : '' }}>Garage Conversion</option>
                                    <option value="loft-conversion" {{ old('service', request('service')) === 'loft-conversion' ? 'selected' : '' }}>Loft Conversion</option>
                                    <option value="extension" {{ old('service', request('service')) === 'extension' ? 'selected' : '' }}>Extension</option>
                                    <option value="new-build" {{ old('service', request('service')) === 'new-build' ? 'selected' : '' }}>New Build</option>
                                    <option value="outbuilding" {{ old('service', request('service')) === 'outbuilding' ? 'selected' : '' }}>Outbuilding</option>
                                    <option value="internal-changes" {{ old('service', request('service')) === 'internal-changes' ? 'selected' : '' }}>Internal Changes</option>
                                    <option value="other" {{ old('service', request('service')) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="subject" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:#94a3b8;">Subject <span style="color:#2563eb;">*</span></label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="input-dark @error('subject') !border-blue-400 @enderror" placeholder="Planning drawings for rear extension">
                            @error('subject')<p class="text-xs mt-1" style="color:#2563eb;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="message" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:#94a3b8;">Message <span style="color:#2563eb;">*</span></label>
                            <textarea id="message" name="message" rows="6" class="input-dark resize-none @error('message') !border-blue-400 @enderror" placeholder="Please describe your project — type of property, location, approximate size, and what drawings you need...">{{ old('message') }}</textarea>
                            @error('message')<p class="text-xs mt-1" style="color:#2563eb;">{{ $message }}</p>@enderror
                        </div>

                        {{-- Anti-Bot Honeypot Field (Invisible to real humans) --}}
                        <div style="position: absolute; left: -9999px; top: -9999px;" aria-hidden="true">
                            <input type="text" name="website_url_hp" tabindex="-1" autocomplete="off" value="">
                        </div>

                        {{-- Verify Human Security Verification --}}
                        <div class="p-4 rounded-lg bg-blue-50/60 border border-blue-200/80 my-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <label for="security_answer" class="text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Verify You Are Human <span class="text-blue-600">*</span>
                                </label>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <div class="px-3.5 py-2 bg-white border border-slate-300 rounded font-bold text-sm text-slate-800 tracking-wide select-none shadow-sm flex items-center gap-2">
                                    <span class="text-base">🛡️</span>
                                    <span>What is {{ $num1 ?? 5 }} + {{ $num2 ?? 3 }} ?</span>
                                </div>
                                <input type="number" id="security_answer" name="security_answer" required
                                       class="w-24 px-3.5 py-2 bg-white border border-slate-300 rounded text-sm text-slate-800 font-bold focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600"
                                       placeholder="Answer">
                            </div>
                            @error('security_answer')
                                <p class="text-xs mt-1.5 font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid sm:grid-cols-2 gap-3">
                            <button type="submit" class="btn-gold w-full justify-center py-4 text-sm">
                                Send Message
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                            <button type="button" id="whatsapp-send"
                                    class="w-full justify-center py-4 text-sm inline-flex items-center gap-2 uppercase tracking-widest font-semibold transition-all"
                                    style="background:#25D366; color:#ffffff; letter-spacing:0.06em; font-size:0.8125rem;">
                                Send via WhatsApp
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            </button>
                        </div>
                        <p class="text-xs text-center" style="color:#94a3b8;">We typically respond within a few hours during working hours.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Process --}}
<section class="py-16 border-t bg-white" style="border-color:#e2e8f0;">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <div class="section-label justify-center">How It Works</div>
            <h2 class="section-title">Our Simple Process</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach([['Send Enquiry','Fill in the form above or WhatsApp us with your project details.','1'],['Free Consultation','We discuss your requirements and provide a fixed-price quote.','2'],['Drawing Production','Our team produces your drawings to UK planning/building control standards.','3'],['Delivery','Receive your completed drawings ready for submission, typically in 3–5 days.','4']] as [$title, $desc, $num])
            <div class="text-center reveal">
                <div class="w-14 h-14 flex items-center justify-center mx-auto mb-4 relative" style="border:2px solid #2563eb; background:#eff6ff;">
                    <span class="font-bold text-xl" style="color:#2563eb;">{{ $num }}</span>
                    <div class="absolute -top-1.5 -right-1.5 w-3.5 h-3.5" style="background-color:#2563eb;"></div>
                </div>
                <h3 class="font-bold text-sm mb-2 uppercase tracking-tight" style="color:#0f172a;">{{ $title }}</h3>
                <p class="text-sm leading-relaxed" style="color:#64748b;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
(function () {
    const btn = document.getElementById('whatsapp-send');
    if (!btn) return;

    const WA_NUMBER = '{{ \App\Models\Setting::get('whatsapp_number', config('contact.whatsapp_number')) }}';

    btn.addEventListener('click', () => {
        const v = (id) => (document.getElementById(id)?.value || '').trim();
        const name    = v('name');
        const email   = v('email');
        const phone   = v('phone');
        const service = v('service');
        const subject = v('subject');
        const message = v('message');

        // Lightweight client-side validation so the WhatsApp draft is useful
        if (!name || !email || !subject || !message) {
            alert('Please fill in your name, email, subject and message before sending via WhatsApp.');
            return;
        }

        const niceService = service
            ? service.replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase())
            : '';

        const lines = [
            'Hi BuildCares,',
            '',
            'I would like to enquire about a project.',
            '',
            `Name: ${name}`,
            `Email: ${email}`,
            phone   ? `Phone: ${phone}`     : null,
            niceService ? `Service: ${niceService}` : null,
            `Subject: ${subject}`,
            '',
            'Message:',
            message,
        ].filter(Boolean);

        const text = encodeURIComponent(lines.join('\n'));
        const url = `https://wa.me/${WA_NUMBER}?text=${text}`;
        window.open(url, '_blank', 'noopener');
    });
})();
</script>
@endpush

@endsection
