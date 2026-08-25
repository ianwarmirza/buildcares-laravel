@extends('layouts.admin')

@php
    $isEdit = isset($teamMember);
    $title = $isEdit ? 'Edit Team Member' : 'Add Team Member';
@endphp

@section('title', $title)
@section('page-title', $title)

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.team.index') }}" class="text-xs font-semibold text-slate-500 hover:text-blue-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Team List
        </a>
    </div>

    <form action="{{ $isEdit ? route('admin.team.update', $teamMember) : route('admin.team.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid sm:grid-cols-2 gap-6">
            {{-- Name --}}
            <div>
                <label for="name" class="block text-xs font-extrabold uppercase tracking-widest text-slate-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required
                       value="{{ old('name', $teamMember->name ?? '') }}"
                       placeholder="e.g. Alex Morgan"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="block text-xs font-extrabold uppercase tracking-widest text-slate-700 mb-2">Role / Title <span class="text-red-500">*</span></label>
                <input type="text" name="role" id="role" required
                       value="{{ old('role', $teamMember->role ?? '') }}"
                       placeholder="e.g. Lead CAD Specialist & BIM Architect"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Photo Upload with Drag and Drop --}}
        <div>
            <label class="block text-xs font-extrabold uppercase tracking-widest text-slate-700 mb-2">Profile Photo (JPG / PNG)</label>
            <div id="drop-zone" class="border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-2xl p-6 text-center transition-colors bg-slate-50 relative cursor-pointer">
                <input type="file" name="photo" id="photo-input" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                
                <div class="flex flex-col items-center justify-center space-y-2">
                    <div id="photo-preview-container" class="w-20 h-20 rounded-full overflow-hidden bg-slate-200 border-2 border-white shadow-md mb-2">
                        <img id="photo-preview" src="{{ $isEdit ? $teamMember->photo_url : 'https://ui-avatars.com/api/?name=User&background=0F172A&color=ffffff&size=512' }}" class="w-full h-full object-cover">
                    </div>
                    <p class="text-xs font-bold text-slate-800">Drag & drop photo here or click to browse</p>
                    <p class="text-[11px] text-slate-500">Square proportions recommended (JPG, PNG, max 8MB)</p>
                </div>
            </div>
            @error('photo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Bio / Description --}}
        <div>
            <label for="bio" class="block text-xs font-extrabold uppercase tracking-widest text-slate-700 mb-2">Bio / Experience Summary</label>
            <textarea name="bio" id="bio" rows="4"
                      placeholder="Brief description of experience, qualifications, and role at BuildCares..."
                      class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('bio', $teamMember->bio ?? '') }}</textarea>
            @error('bio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid sm:grid-cols-3 gap-4">
            {{-- Email --}}
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Email Address</label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $teamMember->email ?? '') }}"
                       placeholder="alex@buildcares.com"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Phone / WhatsApp</label>
                <input type="text" name="phone" id="phone"
                       value="{{ old('phone', $teamMember->phone ?? '') }}"
                       placeholder="+44 7586 750755"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- LinkedIn --}}
            <div>
                <label for="linkedin" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">LinkedIn Profile URL</label>
                <input type="url" name="linkedin" id="linkedin"
                       value="{{ old('linkedin', $teamMember->linkedin ?? '') }}"
                       placeholder="https://linkedin.com/in/..."
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="pt-4 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-6">
                {{-- Sort Order --}}
                <div class="flex items-center gap-2">
                    <label for="sort_order" class="text-xs font-bold text-slate-700">Display Order:</label>
                    <input type="number" name="sort_order" id="sort_order" min="0"
                           value="{{ old('sort_order', $teamMember->sort_order ?? 0) }}"
                           class="w-20 px-3 py-1.5 rounded-lg border border-slate-300 text-slate-900 text-xs font-mono text-center">
                </div>

                {{-- Is Active --}}
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $teamMember->is_active ?? true) ? 'checked' : '' }}
                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                    <span class="text-xs font-bold text-slate-800">Publish Active on Website</span>
                </label>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.team.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">Cancel</a>
                <button type="submit" class="btn-gold text-xs py-2.5 px-6 shadow-sm hover:shadow transition-all">
                    {{ $isEdit ? 'Update Team Member' : 'Save Team Member' }}
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function () {
    const photoInput = document.getElementById('photo-input');
    const photoPreview = document.getElementById('photo-preview');
    const dropZone = document.getElementById('drop-zone');

    photoInput?.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                photoPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone?.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-500', 'bg-blue-50/50');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone?.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-500', 'bg-blue-50/50');
        }, false);
    });
})();
</script>
@endpush

@endsection
