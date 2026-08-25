@extends('layouts.admin')

@section('title', ($project->exists ? 'Edit' : 'Add') . ' Ongoing Project — Admin Panel')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('admin.ongoing-projects.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $project->exists ? 'Edit' : 'Add New' }} Ongoing Project</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Enter the site address and proposal details to run in the live homepage ticker.</p>
        </div>
    </div>

    <form action="{{ $project->exists ? route('admin.ongoing-projects.update', $project->id) : route('admin.ongoing-projects.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf
        @if($project->exists)
            @method('PUT')
        @endif

        {{-- Site Address --}}
        <div>
            <label for="site_address" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">Site Address *</label>
            <input type="text" name="site_address" id="site_address" value="{{ old('site_address', $project->site_address) }}" required placeholder="e.g. 42 High Street, Oxford, UK" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold text-slate-900 placeholder:text-slate-400">
            @error('site_address')
                <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Proposal / Project Details --}}
        <div>
            <label for="proposal" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">Proposal / Project Details *</label>
            <input type="text" name="proposal" id="proposal" value="{{ old('proposal', $project->proposal) }}" required placeholder="e.g. Double Storey Rear Extension & Loft Conversion" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold text-slate-900 placeholder:text-slate-400">
            @error('proposal')
                <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-6">
            {{-- Status --}}
            <div>
                <label for="status" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">Project Status</label>
                <input type="text" name="status" id="status" value="{{ old('status', $project->status ?? 'In Progress') }}" placeholder="e.g. In Progress, Planning Submission, Building Control" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold text-slate-900">
            </div>

            {{-- Sort Order --}}
            <div>
                <label for="sort_order" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $project->sort_order ?? 1) }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold text-slate-900">
            </div>
        </div>

        {{-- Active Checkbox --}}
        <div class="pt-2">
            <label class="inline-flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $project->exists ? $project->is_active : true) ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <span class="text-sm font-extrabold text-slate-900">Display in Ongoing Projects Ticker</span>
            </label>
        </div>

        {{-- Submit Buttons --}}
        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.ongoing-projects.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 font-extrabold text-xs uppercase tracking-wider hover:bg-slate-50 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs uppercase tracking-wider transition-all shadow-md shadow-blue-600/30">
                {{ $project->exists ? 'Update Project' : 'Save Project' }}
            </button>
        </div>
    </form>
</div>
@endsection
