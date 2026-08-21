@extends('layouts.admin')

@section('title', 'Settings & Profile')
@section('page-title', 'Settings & Profile')

@section('content')

<div class="max-w-5xl mx-auto space-y-8">

    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm space-y-1">
        <div class="font-bold">Please correct the following errors:</div>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Business Contact & Site Info Section --}}
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-200">
            <div class="w-10 h-10 rounded-lg bg-gold/10 text-gold flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h2 class="font-display text-lg font-bold text-slate-900">Basic Contact & Business Information</h2>
                <p class="text-xs text-slate-500">Update phone number, email address, WhatsApp contact, and working hours displayed across your website.</p>
            </div>
        </div>

        <form action="{{ route('admin.settings.site') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="site_email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Public Contact Email</label>
                    <input type="email" id="site_email" name="site_email" value="{{ old('site_email', $siteSettings['site_email']) }}" required
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 text-sm focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Displayed on the website header, footer, contact page, and receives inquiry emails.</p>
                </div>

                <div>
                    <label for="site_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Display Phone Number</label>
                    <input type="text" id="site_phone" name="site_phone" value="{{ old('site_phone', $siteSettings['site_phone']) }}" required
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 text-sm focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all"
                           placeholder="+44 7586 750755">
                    <p class="text-[11px] text-slate-400 mt-1">Formatted phone number shown on the header and contact details.</p>
                </div>

                <div>
                    <label for="whatsapp_number" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">WhatsApp Number (Country Code + Digits)</label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $siteSettings['whatsapp_number']) }}" required
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 text-sm focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all"
                           placeholder="447586750755">
                    <p class="text-[11px] text-slate-400 mt-1">Used to generate WhatsApp chat links (e.g. 447586750755 without spaces or +).</p>
                </div>

                <div>
                    <label for="working_hours" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Working Hours</label>
                    <input type="text" id="working_hours" name="working_hours" value="{{ old('working_hours', $siteSettings['working_hours']) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 text-sm focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all"
                           placeholder="Mon–Sun 9AM – 10PM">
                    <p class="text-[11px] text-slate-400 mt-1">Displayed on the Contact Us page.</p>
                </div>
            </div>

            <div>
                <label for="site_address" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Office Address (Optional)</label>
                <input type="text" id="site_address" name="site_address" value="{{ old('site_address', $siteSettings['site_address']) }}"
                       class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 text-sm focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all"
                       placeholder="Location / Address if applicable">
            </div>

            <div class="pt-3 text-right">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gold hover:bg-gold-600 text-white font-medium text-sm rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Contact Info
                </button>
            </div>
        </form>
    </div>

    {{-- Admin Account Profile Section --}}
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-200">
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-display text-lg font-bold text-slate-900">Admin Account Credentials</h2>
                <p class="text-xs text-slate-500">Update your login account name, email address, or change password.</p>
            </div>
        </div>

        <form action="{{ route('admin.settings.profile') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Admin Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 text-sm focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Admin Login Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 text-sm focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-sm font-semibold text-slate-800 mb-4">Change Password (Leave blank to keep current)</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Current Password</label>
                        <input type="password" id="current_password" name="current_password"
                               class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 text-sm focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">New Password</label>
                        <input type="password" id="new_password" name="new_password"
                               class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 text-sm focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Confirm New Password</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                               class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 text-sm focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class="pt-3 text-right">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-medium text-sm rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Update Account Credentials
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
