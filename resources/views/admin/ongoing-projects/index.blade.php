@extends('layouts.admin')

@section('title', 'Manage Ongoing Projects — Admin Panel')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Ongoing Projects</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage the live ongoing projects displayed in the main window ticker animation.</p>
    </div>
    <a href="{{ route('admin.ongoing-projects.create') }}" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs uppercase tracking-wider transition-all shadow-md shadow-blue-600/30 flex items-center gap-2 self-start sm:self-auto">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Add Project</span>
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-2">
    <span>✓</span> {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    @if($projects->count() > 0)
    <form action="{{ route('admin.ongoing-projects.bulkDestroy') }}" method="POST" id="bulk-delete-form">
        @csrf
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between gap-4">
            <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                <input type="checkbox" id="select-all" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <span>Select All</span>
            </label>
            <button type="submit" onclick="return confirm('Are you sure you want to delete selected projects?')" class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-xs font-extrabold transition-colors">
                Delete Selected
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-100/70 text-slate-700 font-extrabold text-xs uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="p-4 w-10"></th>
                        <th class="p-4">Site Address</th>
                        <th class="p-4">Proposal / Project Scope</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Order</th>
                        <th class="p-4 text-center">Active</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($projects as $proj)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-4">
                            <input type="checkbox" name="ids[]" value="{{ $proj->id }}" class="item-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="p-4 font-bold text-slate-900">
                            {{ $proj->site_address }}
                        </td>
                        <td class="p-4 text-xs font-semibold text-slate-700 max-w-xs truncate">
                            {{ $proj->proposal }}
                        </td>
                        <td class="p-4">
                            <span class="text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $proj->status }}
                            </span>
                        </td>
                        <td class="p-4 text-center font-mono text-xs font-bold text-slate-500">
                            {{ $proj->sort_order }}
                        </td>
                        <td class="p-4 text-center">
                            @if($proj->is_active)
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500" title="Active"></span>
                            @else
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-slate-300" title="Inactive"></span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.ongoing-projects.edit', $proj->id) }}" class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.ongoing-projects.destroy', $proj->id) }}" method="POST" onsubmit="return confirm('Delete this ongoing project?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
    <div class="p-4 border-t border-slate-100">
        {{ $projects->links() }}
    </div>
    @else
    <div class="p-12 text-center text-slate-500">
        <p class="font-bold text-base mb-1">No Ongoing Projects Added Yet</p>
        <p class="text-xs mb-4">Add ongoing projects to display them in the live ticker on the homepage.</p>
        <a href="{{ route('admin.ongoing-projects.create') }}" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-extrabold text-xs uppercase tracking-wider hover:bg-blue-700 transition-colors inline-block">Add First Project</a>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.getElementById('select-all')?.addEventListener('change', function(e) {
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = e.target.checked);
});
</script>
@endpush
@endsection
