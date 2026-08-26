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

    <form id="team-form" action="{{ $isEdit ? route('admin.team.update', $teamMember) : route('admin.team.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm relative">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid sm:grid-cols-3 gap-6">
            {{-- Name --}}
            <div>
                <label for="name" class="block text-xs font-extrabold uppercase tracking-widest text-slate-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required
                       value="{{ old('name', $teamMember->name ?? '') }}"
                       placeholder="e.g. Abaid Ullah Mansoor"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-semibold">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="block text-xs font-extrabold uppercase tracking-widest text-slate-700 mb-2">Role / Title <span class="text-red-500">*</span></label>
                <input type="text" name="role" id="role" required
                       value="{{ old('role', $teamMember->role ?? '') }}"
                       placeholder="e.g. Lead CAD Draftsman"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-semibold">
                @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Gender --}}
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-widest text-slate-700 mb-2">Gender / Avatar Icon <span class="text-red-500">*</span></label>
                @php
                    $currentGender = old('gender', $teamMember->gender ?? 'male');
                @endphp
                <div class="grid grid-cols-2 gap-2">
                    <label id="gender-btn-male" class="flex items-center justify-center gap-2 py-3 px-3 rounded-xl border cursor-pointer font-bold text-xs transition-all {{ $currentGender === 'male' ? 'border-blue-600 bg-blue-50 text-blue-700 ring-2 ring-blue-500/20 shadow-xs' : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                        <input type="radio" name="gender" id="gender-male" value="male" class="hidden" {{ $currentGender === 'male' ? 'checked' : '' }}>
                        <span>👨 Male</span>
                    </label>
                    <label id="gender-btn-female" class="flex items-center justify-center gap-2 py-3 px-3 rounded-xl border cursor-pointer font-bold text-xs transition-all {{ $currentGender === 'female' ? 'border-purple-600 bg-purple-50 text-purple-700 ring-2 ring-purple-500/20 shadow-xs' : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                        <input type="radio" name="gender" id="gender-female" value="female" class="hidden" {{ $currentGender === 'female' ? 'checked' : '' }}>
                        <span>👩 Female</span>
                    </label>
                </div>
                @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Profile Photo Upload (Identical to Portfolio Form Workflow) --}}
        <div class="border border-blue-100 rounded-2xl p-6 bg-gradient-to-br from-slate-50 via-blue-50/20 to-slate-50 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-slate-900 flex items-center gap-2">
                        <span>📸</span> Profile Photo Upload
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Upload a photo for this team member (saved standard file upload, same as Portfolio).</p>
                </div>
            </div>

            {{-- Direct Upload Input --}}
            <div id="drop-zone" class="border-2 border-dashed border-blue-300 hover:border-blue-600 rounded-xl p-5 text-center transition-all bg-white cursor-pointer relative group">
                <input type="file" name="photo" id="photo-input" accept="image/jpeg,image/png,image/jpg,image/webp,image/gif" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30">
                <div class="flex flex-col items-center justify-center space-y-2.5 pointer-events-none">
                    <div class="w-12 h-12 rounded-full bg-blue-100/80 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-blue-600 group-hover:underline">Click to Select Photo (JPG / PNG) or Drag & Drop File</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Supports JPG, PNG, WEBP (Max 10MB)</p>
                    </div>
                    <div id="file-info-badge" class="hidden px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold items-center gap-1.5 mt-1">
                        <span>✅ Selected:</span> <span id="file-name-text"></span>
                    </div>
                </div>
            </div>
            @error('photo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

            {{-- Photo Preview & Alignment --}}
            <div class="grid md:grid-cols-12 gap-6 items-center pt-2">
                {{-- Left: Circle Preview --}}
                <div class="md:col-span-5 flex flex-col items-center justify-center text-center">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Live Website Circle Preview</span>
                    <div id="circle-preview-wrapper" class="w-36 h-36 rounded-full overflow-hidden bg-slate-900 border-4 border-blue-600 shadow-xl relative select-none">
                        <img id="photo-preview" 
                             src="{{ $isEdit ? $teamMember->photo_url : 'https://ui-avatars.com/api/?name=User&background=0F172A&color=ffffff&size=512' }}" 
                             alt="Avatar Preview" 
                             class="w-full h-full object-cover pointer-events-none"
                             style="object-position: {{ old('photo_position_x', $teamMember->photo_position_x ?? 50) }}% {{ old('photo_position_y', $teamMember->photo_position_y ?? 50) }}%; transform: scale({{ (old('photo_zoom', $teamMember->photo_zoom ?? 100)) / 100 }});">
                    </div>
                </div>

                {{-- Right: Adjuster Sliders --}}
                <div class="md:col-span-7 space-y-4 bg-white p-5 rounded-xl border border-slate-200">
                    <input type="hidden" name="photo_position_x" id="photo_position_x" value="{{ old('photo_position_x', $teamMember->photo_position_x ?? 50) }}">
                    <input type="hidden" name="photo_position_y" id="photo_position_y" value="{{ old('photo_position_y', $teamMember->photo_position_y ?? 50) }}">
                    <input type="hidden" name="photo_zoom" id="photo_zoom" value="{{ old('photo_zoom', $teamMember->photo_zoom ?? 100) }}">

                    {{-- Vertical Position (Up / Down) --}}
                    <div>
                        <div class="flex justify-between items-center text-xs font-extrabold text-slate-700 mb-1">
                            <span>↕ Vertical Position (Up / Down)</span>
                            <span id="val-pos-y" class="font-mono text-blue-600">{{ old('photo_position_y', $teamMember->photo_position_y ?? 50) }}%</span>
                        </div>
                        <input type="range" id="slider-pos-y" min="0" max="100" value="{{ old('photo_position_y', $teamMember->photo_position_y ?? 50) }}" class="w-full accent-blue-600 cursor-pointer">
                    </div>

                    {{-- Horizontal Position (Left / Right) --}}
                    <div>
                        <div class="flex justify-between items-center text-xs font-extrabold text-slate-700 mb-1">
                            <span>↔ Horizontal Position (Left / Right)</span>
                            <span id="val-pos-x" class="font-mono text-blue-600">{{ old('photo_position_x', $teamMember->photo_position_x ?? 50) }}%</span>
                        </div>
                        <input type="range" id="slider-pos-x" min="0" max="100" value="{{ old('photo_position_x', $teamMember->photo_position_x ?? 50) }}" class="w-full accent-blue-600 cursor-pointer">
                    </div>

                    {{-- Zoom Level --}}
                    <div>
                        <div class="flex justify-between items-center text-xs font-extrabold text-slate-700 mb-1">
                            <span>🔍 Zoom Level</span>
                            <span id="val-zoom" class="font-mono text-blue-600">{{ old('photo_zoom', $teamMember->photo_zoom ?? 100) }}%</span>
                        </div>
                        <input type="range" id="slider-zoom" min="100" max="200" value="{{ old('photo_zoom', $teamMember->photo_zoom ?? 100) }}" class="w-full accent-blue-600 cursor-pointer">
                    </div>
                </div>
            </div>
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
                <button type="submit" id="submit-btn" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs uppercase tracking-wider transition-all shadow-md shadow-blue-600/30 flex items-center gap-2">
                    <span>{{ $isEdit ? 'Update Team Member' : 'Save Team Member' }}</span>
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
    const fileBadge = document.getElementById('file-info-badge');
    const fileNameText = document.getElementById('file-name-text');

    const sliderX = document.getElementById('slider-pos-x');
    const sliderY = document.getElementById('slider-pos-y');
    const sliderZoom = document.getElementById('slider-zoom');

    const inputX = document.getElementById('photo_position_x');
    const inputY = document.getElementById('photo_position_y');
    const inputZoom = document.getElementById('photo_zoom');

    const valX = document.getElementById('val-pos-x');
    const valY = document.getElementById('val-pos-y');
    const valZoom = document.getElementById('val-zoom');

    const maleAvatarSvg = "{!! \App\Models\TeamMember::getMaleAvatarSvg() !!}";
    const femaleAvatarSvg = "{!! \App\Models\TeamMember::getFemaleAvatarSvg() !!}";
    let isUserFileUploaded = {{ isset($teamMember) && $teamMember->has_photo ? 'true' : 'false' }};

    const genderMaleRadio = document.getElementById('gender-male');
    const genderFemaleRadio = document.getElementById('gender-female');
    const genderBtnMale = document.getElementById('gender-btn-male');
    const genderBtnFemale = document.getElementById('gender-btn-female');

    function updateGenderUI(gender) {
        if (gender === 'female') {
            if (genderMaleRadio) genderMaleRadio.checked = false;
            if (genderFemaleRadio) genderFemaleRadio.checked = true;
            if (genderBtnMale) genderBtnMale.className = "flex items-center justify-center gap-2 py-3 px-3 rounded-xl border cursor-pointer font-bold text-xs transition-all border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100";
            if (genderBtnFemale) genderBtnFemale.className = "flex items-center justify-center gap-2 py-3 px-3 rounded-xl border cursor-pointer font-bold text-xs transition-all border-purple-600 bg-purple-50 text-purple-700 ring-2 ring-purple-500/20 shadow-xs";
            if (!isUserFileUploaded && photoPreview) {
                photoPreview.src = femaleAvatarSvg;
            }
        } else {
            if (genderMaleRadio) genderMaleRadio.checked = true;
            if (genderFemaleRadio) genderFemaleRadio.checked = false;
            if (genderBtnFemale) genderBtnFemale.className = "flex items-center justify-center gap-2 py-3 px-3 rounded-xl border cursor-pointer font-bold text-xs transition-all border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100";
            if (genderBtnMale) genderBtnMale.className = "flex items-center justify-center gap-2 py-3 px-3 rounded-xl border cursor-pointer font-bold text-xs transition-all border-blue-600 bg-blue-50 text-blue-700 ring-2 ring-blue-500/20 shadow-xs";
            if (!isUserFileUploaded && photoPreview) {
                photoPreview.src = maleAvatarSvg;
            }
        }
    }

    genderBtnMale?.addEventListener('click', () => updateGenderUI('male'));
    genderBtnFemale?.addEventListener('click', () => updateGenderUI('female'));

    function updatePreview() {
        if (!sliderX || !sliderY || !sliderZoom || !photoPreview) return;

        const posX = parseInt(sliderX.value, 10) || 50;
        const posY = parseInt(sliderY.value, 10) || 50;
        const zoom = parseInt(sliderZoom.value, 10) || 100;

        if (inputX) inputX.value = posX;
        if (inputY) inputY.value = posY;
        if (inputZoom) inputZoom.value = zoom;

        if (valX) valX.textContent = posX + '%';
        if (valY) valY.textContent = posY + '%';
        if (valZoom) valZoom.textContent = zoom + '%';

        const transX = posX - 50;
        const transY = posY - 50;
        const scaleVal = zoom / 100;

        photoPreview.style.transformOrigin = 'center center';
        photoPreview.style.transform = `translate(${transX}%, ${transY}%) scale(${scaleVal})`;
    }

    [sliderX, sliderY, sliderZoom].forEach(slider => {
        if (slider) {
            slider.addEventListener('input', updatePreview);
            slider.addEventListener('change', updatePreview);
        }
    });

    updatePreview();

    function handleFile(file) {
        if (!file) return;
        isUserFileUploaded = true;
        
        if (fileNameText && fileBadge) {
            const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
            fileNameText.textContent = file.name + ' (' + sizeMb + ' MB)';
            fileBadge.classList.remove('hidden');
            fileBadge.classList.add('inline-flex');
        }

        try {
            const objectUrl = URL.createObjectURL(file);
            photoPreview.src = objectUrl;
        } catch (err) {
            const reader = new FileReader();
            reader.onload = function (e) {
                photoPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    photoInput?.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            handleFile(this.files[0]);
        }
    });
})();
</script>
@endpush

@endsection
