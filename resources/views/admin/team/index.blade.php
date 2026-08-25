@extends('layouts.admin')

@section('title', 'Meet Our Team')
@section('page-title', 'Meet Our Team')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <p class="text-slate-400 text-sm">{{ $teamMembers->total() }} team members total</p>

    <div class="flex items-center gap-3">
        {{-- Bulk Delete Action Bar --}}
        <div id="bulk-delete-bar" class="hidden items-center gap-3 bg-red-500/10 text-red-600 px-3.5 py-1.5 rounded-lg border border-red-500/30">
            <span class="text-xs font-semibold"><span id="selected-count">0</span> selected</span>
            <button type="button" id="btn-delete-selected" class="px-2.5 py-1 text-xs font-bold bg-red-600 hover:bg-red-700 text-white rounded transition-colors">
                Delete Marked
            </button>
        </div>

        <a href="{{ route('admin.team.create') }}" class="btn-gold text-xs py-2.5 px-4 shadow-sm hover:shadow transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Team Member
        </a>
    </div>
</div>

{{-- Standalone Bulk Delete Form (avoiding nested forms) --}}
<form id="bulk-delete-form" action="{{ route('admin.team.bulkDestroy') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="ids" id="bulk-ids-input" value="">
</form>

<div class="card-dark rounded-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/50">
                    <th class="py-3 px-4 w-10 text-center">
                        <input type="checkbox" id="select-all-checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </th>
                    <th class="text-left py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-semibold">Team Member</th>
                    <th class="text-left py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-semibold hidden md:table-cell">Role / Title</th>
                    <th class="text-left py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-semibold hidden lg:table-cell">Contact & Info</th>
                    <th class="text-center py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-semibold">Order</th>
                    <th class="text-center py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-semibold">Status</th>
                    <th class="text-right py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($teamMembers as $member)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3 px-4 text-center">
                        <input type="checkbox" class="row-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" value="{{ $member->id }}">
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200 shadow-sm relative">
                                <img src="{{ $member->photo_url }}" alt="{{ $member->name }}"
                                     class="w-full h-full object-cover" style="{{ $member->photo_style }}"
                                     onerror="this.onerror=null; this.src='{{ $member->gender === 'female' ? \App\Models\TeamMember::getFemaleAvatarSvg() : \App\Models\TeamMember::getMaleAvatarSvg() }}';">
                            </div>
                            <div>
                                <div class="text-slate-900 font-bold leading-snug">{{ $member->name }}</div>
                                <div class="text-slate-500 text-xs md:hidden">{{ $member->role }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-slate-700 hidden md:table-cell font-medium">
                        {{ $member->role }}
                    </td>
                    <td class="py-3 px-4 text-slate-500 text-xs hidden lg:table-cell">
                        @if($member->email)<div>✉ {{ $member->email }}</div>@endif
                        @if($member->phone)<div>📞 {{ $member->phone }}</div>@endif
                    </td>
                    <td class="py-3 px-4 text-center text-slate-600 font-mono text-xs">{{ $member->sort_order }}</td>
                    <td class="py-3 px-4 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $member->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $member->is_active ? 'Active' : 'Draft' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.team.edit', $member) }}"
                               class="p-1.5 text-slate-500 hover:text-blue-600 transition-colors" title="Edit member">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.team.destroy', $member) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-500 hover:text-red-600 transition-colors" title="Delete member">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center text-slate-500">
                        No team members added yet. <a href="{{ route('admin.team.create') }}" class="text-blue-600 font-semibold hover:underline">Add the first team member</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($teamMembers->hasPages())
<div class="mt-6 flex justify-center">
    {{ $teamMembers->links() }}
</div>
@endif

@push('scripts')
<script>
(function () {
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const rowCheckboxes     = document.querySelectorAll('.row-checkbox');
    const bulkBar           = document.getElementById('bulk-delete-bar');
    const selectedCountEl   = document.getElementById('selected-count');
    const btnDeleteSelected = document.getElementById('btn-delete-selected');
    const bulkForm          = document.getElementById('bulk-delete-form');
    const bulkIdsInput      = document.getElementById('bulk-ids-input');

    function updateBulkBar() {
        const checked = Array.from(rowCheckboxes).filter(c => c.checked);
        const count = checked.length;

        if (count > 0) {
            bulkBar.classList.remove('hidden');
            bulkBar.classList.add('flex');
            selectedCountEl.textContent = count;
        } else {
            bulkBar.classList.add('hidden');
            bulkBar.classList.remove('flex');
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = count > 0 && count === rowCheckboxes.length;
        }
    }

    selectAllCheckbox?.addEventListener('change', () => {
        rowCheckboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
        updateBulkBar();
    });

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBar);
    });

    btnDeleteSelected?.addEventListener('click', () => {
        const selectedIds = Array.from(rowCheckboxes)
            .filter(c => c.checked)
            .map(c => c.value);

        if (!selectedIds.length) return;

        bulkIdsInput.value = selectedIds.join(',');
        bulkForm.submit();
    });
})();
</script>
@endpush

@endsection
