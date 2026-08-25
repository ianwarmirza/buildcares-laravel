@extends('layouts.admin')

@php
    $isEdit = isset($service);
    $title = $isEdit ? 'Edit Service' : 'Add New Service';
@endphp

@section('title', $title)
@section('page-title', $title)

@section('content')

<div class="max-w-6xl mx-auto space-y-6">
    {{-- Header Bar --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.services.index') }}" class="text-xs font-semibold text-slate-500 hover:text-blue-600 flex items-center gap-1.5 mb-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to All Services
            </a>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $title }}</h2>
            <p class="text-xs text-slate-500 mt-1">Configure service information, CAD drawing showcase image, and features displayed on the website.</p>
        </div>
    </div>

    <form id="service-form" action="{{ $isEdit ? route('admin.services.update', $service) : route('admin.services.store') }}"
          method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid lg:grid-cols-12 gap-8 items-start">
            
            {{-- Left Column: Form Controls (7 cols) --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- Card 1: Core Details --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">1</span>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Service Core Details</h3>
                            <p class="text-[11px] text-slate-500">Service title, category badge, and visibility</p>
                        </div>
                    </div>

                    {{-- Service Name --}}
                    <div>
                        <label for="name" class="block text-xs font-extrabold uppercase tracking-widest text-slate-700 mb-2">Service Name / Title <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="service-name" required
                               value="{{ old('name', $service->name ?? '') }}"
                               placeholder="e.g. Planning Drawings & Council Submissions"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-semibold shadow-2xs">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        {{-- Icon / Category Badge --}}
                        <div>
                            <label for="icon" class="block text-xs font-extrabold uppercase tracking-widest text-slate-700 mb-2">Category Badge Tag</label>
                            <input type="text" name="icon" id="service-icon"
                                   value="{{ old('icon', $service->icon ?? 'PLANNING & DRAWINGS') }}"
                                   placeholder="e.g. PLANNING & DRAWINGS"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-xs font-bold uppercase tracking-wider focus:ring-2 focus:ring-blue-500">
                            <p class="text-[10px] text-slate-400 mt-1">Displayed as a pill badge on top of service cards.</p>
                        </div>

                        {{-- Display Order --}}
                        <div>
                            <label for="sort_order" class="block text-xs font-extrabold uppercase tracking-widest text-slate-700 mb-2">Display Order</label>
                            <input type="number" name="sort_order" id="sort_order" min="0"
                                   value="{{ old('sort_order', $service->sort_order ?? 0) }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-xs font-mono text-center focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    {{-- Status Switch --}}
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-800">Publish Active on Website</span>
                            <p class="text-[10px] text-slate-500">Enable to make this service visible on the homepage and /services page.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-hidden peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                {{-- Card 2: Showcase Cover Image --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">2</span>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Showcase Image & CAD Drawing</h3>
                            <p class="text-[11px] text-slate-500">Upload a CAD drawing, 3D render, or blueprint image for the card header.</p>
                        </div>
                    </div>

                    {{-- Dropzone Container --}}
                    <div id="image-dropzone" class="border-2 border-dashed border-blue-300 hover:border-blue-600 rounded-xl p-6 text-center transition-all bg-slate-50/50 hover:bg-blue-50/30 cursor-pointer relative group">
                        <input type="file" name="cover_image" id="cover-image-input" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30">
                        
                        <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                            <div class="w-12 h-12 rounded-full bg-blue-100/80 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-extrabold text-blue-600 group-hover:underline">Click to Upload CAD Image or Drag & Drop File</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">Supports JPG, PNG, WEBP (Max 4MB)</p>
                            </div>
                            <div id="image-file-badge" class="hidden px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold items-center gap-1.5 mt-1">
                                <span>✅ Selected:</span> <span id="image-filename-text"></span>
                            </div>
                        </div>
                    </div>
                    @error('cover_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Card 3: Descriptions & Features --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">3</span>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Descriptions & Feature Bullet Points</h3>
                            <p class="text-[11px] text-slate-500">Short summary for website cards & key feature bullet points</p>
                        </div>
                    </div>

                    {{-- Short Description --}}
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="short_description" class="block text-xs font-extrabold uppercase tracking-widest text-slate-700">Short Summary (Website Cards) <span class="text-red-500">*</span></label>
                            <span id="short-desc-counter" class="text-[10px] font-mono text-slate-400">0 / 500</span>
                        </div>
                        <textarea name="short_description" id="short-description-input" rows="3" required maxlength="500"
                                  placeholder="Full planning application packages including location site plans, block plans, floor layouts, elevations, and sections."
                                  class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 text-xs font-medium leading-relaxed focus:ring-2 focus:ring-blue-500 resize-none">{{ old('short_description', $service->short_description ?? '') }}</textarea>
                        @error('short_description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Full Description --}}
                    <div>
                        <label for="full_description" class="block text-xs font-extrabold uppercase tracking-widest text-slate-700 mb-2">Full Detail Description (Service Page)</label>
                        <textarea name="full_description" id="full_description" rows="5"
                                  placeholder="Comprehensive detail description explaining your architectural drafting workflow, compliance standards, and deliverable packages..."
                                  class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 text-xs leading-relaxed focus:ring-2 focus:ring-blue-500 resize-none">{{ old('full_description', $service->full_description ?? '') }}</textarea>
                    </div>

                    {{-- Features List (One per line) --}}
                    <div>
                        <label for="features_text" class="block text-xs font-extrabold uppercase tracking-widest text-slate-700 mb-2">Key Features (One per line)</label>
                        <textarea name="features_text" id="features-input" rows="5"
                                  placeholder="Full planning application drawing sets&#10;Building control compliance drawings&#10;Scale bars, block plans & site location maps&#10;Fast 3–5 day turnaround&#10;Minor revisions included free"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 text-xs font-mono leading-relaxed focus:ring-2 focus:ring-blue-500 resize-none">{{ old('features_text', isset($service) && $service->features ? implode("\n", $service->features) : '') }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1">💡 Enter each feature on a new line. They will display as checklist bullet points.</p>
                    </div>
                </div>

                {{-- Form Actions Bar --}}
                <div class="bg-white p-5 rounded-2xl border border-slate-200 flex flex-wrap items-center justify-between gap-4 shadow-xs">
                    <a href="{{ route('admin.services.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">Cancel</a>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs uppercase tracking-wider transition-all shadow-md shadow-blue-600/30 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $isEdit ? 'Update Service' : 'Publish New Service' }}</span>
                    </button>
                </div>

            </div>

            {{-- Right Column: Live Website Card Preview (5 cols) --}}
            <div class="lg:col-span-5 sticky top-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                        <span>👁️</span> Live Website Card Preview
                    </span>
                    <span class="px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 rounded border border-blue-200">Real-Time Sync</span>
                </div>

                {{-- Live Preview Card Container --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden transition-all duration-300">
                    
                    {{-- Top Header Pill Bar --}}
                    <div class="px-5 pt-4 pb-3 flex items-center justify-between bg-slate-50 border-b border-slate-100">
                        <span id="preview-badge" class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 border border-blue-200 text-[10px] font-extrabold uppercase tracking-widest">
                            {{ old('icon', $service->icon ?? 'PLANNING & DRAWINGS') }}
                        </span>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">Affordable Price</span>
                    </div>

                    {{-- Image Header Container --}}
                    <div class="relative h-44 overflow-hidden bg-slate-900 flex items-center justify-center">
                        <img id="preview-image"
                             src="{{ isset($service) && $service->cover_image ? Storage::url($service->cover_image) : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80' }}"
                             alt="Service Preview"
                             class="w-full h-full object-cover opacity-90 transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
                        
                        <div class="absolute bottom-3 left-4 right-4 flex items-center justify-between text-white text-[11px] font-bold">
                            <span id="preview-title-overlay" class="truncate">{{ old('name', $service->name ?? 'Planning Drawings') }}</span>
                            <span class="text-[10px] bg-blue-600/80 px-2 py-0.5 rounded backdrop-blur-xs">UK Compliant</span>
                        </div>
                    </div>

                    {{-- Body Content --}}
                    <div class="p-6 space-y-4">
                        <h3 id="preview-title" class="text-lg font-extrabold text-slate-900 leading-snug">
                            {{ old('name', $service->name ?? 'Planning Drawings & Council Submissions') }}
                        </h3>

                        <p id="preview-desc" class="text-xs text-slate-600 leading-relaxed line-clamp-3">
                            {{ old('short_description', $service->short_description ?? 'Full planning application packages including location site plans, block plans, floor layouts, elevations, and sections.') }}
                        </p>

                        {{-- Dynamic Features Checklist Preview --}}
                        <div id="preview-features-container" class="pt-3 border-t border-slate-100 space-y-2">
                            @php
                                $previewFeatures = old('features_text', isset($service) && $service->features ? implode("\n", $service->features) : "Planning application drawing sets\nBuilding control drawings\nScale bars & site plans");
                                $featureLines = array_filter(array_map('trim', explode("\n", $previewFeatures)));
                            @endphp

                            @foreach(array_slice($featureLines, 0, 4) as $feat)
                                <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                                    <span class="w-4 h-4 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-bold flex-shrink-0">✓</span>
                                    <span class="truncate">{{ $feat }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Footer Action Bar --}}
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs font-bold">
                        <span class="text-blue-600 hover:underline cursor-pointer">Learn More →</span>
                        <span class="text-slate-400 text-[10px]">BuildCares Service</span>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
(function () {
    const inputName = document.getElementById('service-name');
    const inputIcon = document.getElementById('service-icon');
    const inputShortDesc = document.getElementById('short-description-input');
    const inputFeatures = document.getElementById('features-input');
    const inputImage = document.getElementById('cover-image-input');
    const dropzone = document.getElementById('image-dropzone');
    const fileBadge = document.getElementById('image-file-badge');
    const filenameText = document.getElementById('image-filename-text');

    const previewBadge = document.getElementById('preview-badge');
    const previewTitle = document.getElementById('preview-title');
    const previewTitleOverlay = document.getElementById('preview-title-overlay');
    const previewDesc = document.getElementById('preview-desc');
    const previewImage = document.getElementById('preview-image');
    const previewFeaturesContainer = document.getElementById('preview-features-container');
    const shortDescCounter = document.getElementById('short-desc-counter');

    function syncPreview() {
        // Sync Name
        const nameVal = inputName?.value.trim() || 'Planning Drawings & Submissions';
        if (previewTitle) previewTitle.textContent = nameVal;
        if (previewTitleOverlay) previewTitleOverlay.textContent = nameVal;

        // Sync Icon Badge
        const iconVal = inputIcon?.value.trim().toUpperCase() || 'PLANNING & DRAWINGS';
        if (previewBadge) previewBadge.textContent = iconVal;

        // Sync Short Description & Counter
        const descVal = inputShortDesc?.value || '';
        if (shortDescCounter) shortDescCounter.textContent = descVal.length + ' / 500';
        if (previewDesc) previewDesc.textContent = descVal || 'One- or two-line summary shown on service cards.';

        // Sync Features
        const featVal = inputFeatures?.value || '';
        const lines = featVal.split('\n').map(l => l.trim()).filter(l => l.length > 0);

        if (previewFeaturesContainer) {
            previewFeaturesContainer.innerHTML = '';
            const displayLines = lines.slice(0, 4);
            if (displayLines.length === 0) {
                displayLines.push('Planning application drawing sets', 'Building control compliance');
            }
            displayLines.forEach(line => {
                const item = document.createElement('div');
                item.className = 'flex items-center gap-2 text-xs font-semibold text-slate-700';
                item.innerHTML = `<span class="w-4 h-4 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-bold flex-shrink-0">✓</span><span class="truncate">${line}</span>`;
                previewFeaturesContainer.appendChild(item);
            });
        }
    }

    [inputName, inputIcon, inputShortDesc, inputFeatures].forEach(el => {
        el?.addEventListener('input', syncPreview);
    });

    // Image File Dropzone & Instant Preview
    inputImage?.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];

            if (filenameText && fileBadge) {
                const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                filenameText.textContent = file.name + ' (' + sizeMb + ' MB)';
                fileBadge.classList.remove('hidden');
                fileBadge.classList.add('inline-flex');
            }

            try {
                const url = URL.createObjectURL(file);
                if (previewImage) previewImage.src = url;
            } catch (e) {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    if (previewImage) previewImage.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    });

    ['dragenter', 'dragover'].forEach(evt => {
        dropzone?.addEventListener(evt, (e) => {
            e.preventDefault();
            dropzone.classList.add('border-blue-600', 'bg-blue-50/50');
        });
    });

    ['dragleave', 'drop'].forEach(evt => {
        dropzone?.addEventListener(evt, (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-blue-600', 'bg-blue-50/50');
        });
    });

    dropzone?.addEventListener('drop', (e) => {
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            inputImage.files = e.dataTransfer.files;
            inputImage.dispatchEvent(new Event('change'));
        }
    });

    // Initial sync
    syncPreview();
})();
</script>
@endpush

@endsection
