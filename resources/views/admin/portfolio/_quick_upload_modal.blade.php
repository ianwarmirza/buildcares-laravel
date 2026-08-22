{{-- Quick Upload Modal --}}
<div id="quick-upload-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 animate-fadeInUp">
        {{-- Modal Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-display font-semibold text-slate-800 text-base">Quick Upload (Photo or PDF)</h3>
                    <p class="text-slate-500 text-xs">Add a file directly to any portfolio category tab</p>
                </div>
            </div>
            <button type="button" onclick="closeQuickUploadModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
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

            {{-- File Input --}}
            <div>
                <label for="qu-file" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Upload Photo or PDF <span class="text-red-500">*</span>
                </label>
                <input type="file" id="qu-file" name="file" accept=".jpg,.jpeg,.png,.webp,.pdf" required
                       class="w-full px-3 py-2 text-sm text-slate-600 border border-slate-300 rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100/80 focus:outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                <p class="text-[11px] text-slate-400 mt-1">Supports JPG, PNG, WEBP images or PDF files (up to 20MB)</p>
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
</script>
