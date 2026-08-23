{{-- Quick Upload Modal --}}
<div id="quick-upload-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 animate-fadeInUp">
        {{-- Modal Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-display font-semibold text-slate-800 text-base">Quick Upload (Photo or PDF)</h3>
                    <p class="text-slate-500 text-xs">Drag & drop or select a file to upload to your portfolio</p>
                </div>
            </div>
            <button type="button" onclick="closeQuickUploadModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Modal Body Form --}}
        <form action="{{ route('admin.portfolio.quickUpload') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            {{-- Category Select --}}
            <div>
                <label for="qu-category" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Select Tab / Category <span class="text-red-500">*</span>
                </label>
                <select id="qu-category" name="category" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-slate-800 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 bg-white font-medium">
                    @foreach(\App\Models\PortfolioItem::$categories as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Drag & Drop Zone --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Upload Photo or PDF <span class="text-red-500">*</span>
                </label>

                <div class="relative">
                    <input type="file" id="qu-file" name="file" accept=".jpg,.jpeg,.png,.webp,.pdf" required class="sr-only">

                    <div id="dropzone"
                         onclick="document.getElementById('qu-file').click()"
                         class="w-full p-5 border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-xl bg-slate-50/70 hover:bg-blue-50/30 transition-all cursor-pointer text-center group">

                        {{-- Default Dropzone Prompt --}}
                        <div id="dropzone-prompt" class="flex flex-col items-center justify-center py-2">
                            <div class="w-12 h-12 mb-2 rounded-full bg-blue-100/80 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">
                                Drag & drop your file here, or <span class="text-blue-600 underline">browse</span>
                            </p>
                            <p class="text-[11px] text-slate-400 mt-1">Supports JPG, PNG, WEBP images or PDF files (up to 20MB)</p>
                        </div>

                        {{-- Selected File Preview --}}
                        <div id="dropzone-preview" class="hidden flex-col items-center justify-center py-2">
                            <div id="file-icon" class="w-12 h-12 mb-2 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center font-bold">
                                {{-- Dynamic icon injected via JS --}}
                            </div>
                            <p id="file-name" class="text-sm font-bold text-slate-800 truncate max-w-xs"></p>
                            <p id="file-size" class="text-xs text-slate-500 mt-0.5"></p>
                            <span class="text-[11px] text-blue-600 font-semibold mt-2 underline">Click or drag another file to change</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Title (Optional) --}}
            <div>
                <label for="qu-title" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Project Title <span class="text-slate-400 font-normal lowercase">(optional — defaults to filename)</span>
                </label>
                <input type="text" id="qu-title" name="title" placeholder="e.g. Rear Extension Ground Floor Plan"
                       class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-slate-800 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
            </div>

            {{-- Description (Optional) --}}
            <div>
                <label for="qu-description" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Short Description <span class="text-slate-400 font-normal lowercase">(optional)</span>
                </label>
                <textarea id="qu-description" name="description" rows="2" placeholder="Brief notes or description about this project..."
                          class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-slate-800 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600"></textarea>
            </div>

            {{-- Modal Actions --}}
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeQuickUploadModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload to Portfolio
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openQuickUploadModal(category = '') {
    const modal = document.getElementById('quick-upload-modal');
    const catSelect = document.getElementById('qu-category');
    if (category && catSelect) {
        catSelect.value = category;
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeQuickUploadModal() {
    const modal = document.getElementById('quick-upload-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeQuickUploadModal();
});

document.addEventListener('DOMContentLoaded', function () {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('qu-file');
    const promptView = document.getElementById('dropzone-prompt');
    const previewView = document.getElementById('dropzone-preview');
    const fileNameEl = document.getElementById('file-name');
    const fileSizeEl = document.getElementById('file-size');
    const fileIconEl = document.getElementById('file-icon');
    const titleInput = document.getElementById('qu-title');

    if (!dropzone || !fileInput) return;

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
        }, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, function() {
            dropzone.classList.add('border-blue-600', 'bg-blue-50/80', 'ring-4', 'ring-blue-100');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, function() {
            dropzone.classList.remove('border-blue-600', 'bg-blue-50/80', 'ring-4', 'ring-blue-100');
        }, false);
    });

    dropzone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            handleFileSelect(this.files[0]);
        }
    });

    function handleFileSelect(file) {
        if (!file) return;

        promptView.classList.add('hidden');
        previewView.classList.remove('hidden');
        previewView.classList.add('flex');

        fileNameEl.textContent = file.name;
        fileSizeEl.textContent = formatBytes(file.size);

        const ext = file.name.split('.').pop().toLowerCase();
        if (ext === 'pdf') {
            fileIconEl.className = 'w-12 h-12 mb-2 rounded-xl bg-red-500/15 text-red-600 flex items-center justify-center font-bold';
            fileIconEl.innerHTML = '<svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>';
        } else {
            fileIconEl.className = 'w-12 h-12 mb-2 rounded-xl bg-blue-500/15 text-blue-600 flex items-center justify-center font-bold';
            fileIconEl.innerHTML = '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>';
        }

        if (titleInput && !titleInput.value.trim()) {
            const rawName = file.name.substring(0, file.name.lastIndexOf('.')) || file.name;
            titleInput.value = rawName.replace(/[-_]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }
});
</script>
