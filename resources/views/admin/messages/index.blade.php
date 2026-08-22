@extends('layouts.admin')

@section('title', 'Messages')
@section('page-title', 'Contact Messages')

@section('content')

{{-- Hidden Bulk Delete Form (Separated to prevent HTML nested form issues) --}}
<form id="bulk-delete-form" action="{{ route('admin.messages.bulkDestroy') }}" method="POST" class="hidden">
    @csrf
    <div id="bulk-delete-inputs"></div>
</form>

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <p class="text-slate-400 text-sm">
        {{ $messages->total() }} total
        @if($unread = \App\Models\ContactMessage::where('is_read', false)->count())
            · <span class="text-gold font-medium">{{ $unread }} unread</span>
        @endif
    </p>

    @if($messages->total() > 0)
    <div class="flex items-center gap-3">
        {{-- Delete Selected Button --}}
        <button type="button" id="delete-selected-btn" disabled onclick="submitBulkDelete()"
                class="px-4 py-2 text-xs font-semibold rounded-lg transition-all flex items-center gap-2 text-white bg-red-600 hover:bg-red-700 disabled:opacity-40 disabled:cursor-not-allowed shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete Marked (<span id="selected-count">0</span>)
        </button>
    </div>
    @endif
</div>

<div class="card-dark rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50/50">
                <th class="py-3 px-4 w-10 text-center">
                    <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" title="Select All">
                </th>
                <th class="text-left py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-medium">From</th>
                <th class="text-left py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-medium hidden md:table-cell">Subject</th>
                <th class="text-left py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-medium hidden lg:table-cell">Service</th>
                <th class="text-left py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-medium hidden sm:table-cell">Received</th>
                <th class="text-right py-3 px-4 text-xs uppercase tracking-widest text-slate-500 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse($messages as $msg)
            <tr class="hover:bg-light-warm transition-colors {{ !$msg->is_read ? 'bg-gold/5' : '' }}">
                <td class="py-3 px-4 text-center">
                    <input type="checkbox" value="{{ $msg->id }}" class="msg-checkbox w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                </td>
                <td class="py-3 px-4">
                    <div class="flex items-center gap-3">
                        @if(!$msg->is_read)
                        <span class="w-2 h-2 rounded-full bg-gold flex-shrink-0" title="Unread"></span>
                        @else
                        <span class="w-2 h-2 flex-shrink-0"></span>
                        @endif
                        <div>
                            <div class="text-dark-900 font-medium leading-snug">{{ $msg->name }}</div>
                            <div class="text-slate-500 text-xs">{{ $msg->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="py-3 px-4 text-slate-600 hidden md:table-cell max-w-xs">
                    <a href="{{ route('admin.messages.show', $msg) }}" class="hover:text-gold transition-colors">
                        <span class="line-clamp-1 {{ !$msg->is_read ? 'font-semibold text-dark-900' : '' }}">{{ $msg->subject }}</span>
                    </a>
                </td>
                <td class="py-3 px-4 hidden lg:table-cell">
                    @if($msg->service)
                    <span class="text-xs bg-gold/10 text-gold border border-gold/20 px-2 py-0.5 rounded-full">{{ $msg->service }}</span>
                    @else
                    <span class="text-slate-400">—</span>
                    @endif
                </td>
                <td class="py-3 px-4 text-slate-500 hidden sm:table-cell text-xs">{{ $msg->created_at->diffForHumans() }}</td>
                <td class="py-3 px-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.messages.show', $msg) }}"
                           class="p-1.5 text-slate-500 hover:text-gold transition-colors" title="View">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <form action="{{ route('admin.messages.toggleRead', $msg) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="p-1.5 text-slate-500 hover:text-blue-500 transition-colors"
                                    title="{{ $msg->is_read ? 'Mark unread' : 'Mark read' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($msg->is_read)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    @endif
                                </svg>
                            </button>
                        </form>
                        <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-500 hover:text-red-500 transition-colors" title="Delete">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center text-slate-500">
                    No messages yet. They will appear here when visitors submit the contact form.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($messages->hasPages())
<div class="mt-6 flex justify-center">
    {{ $messages->links() }}
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.msg-checkbox');
    const deleteBtn = document.getElementById('delete-selected-btn');
    const countLabel = document.getElementById('selected-count');

    function updateCount() {
        const checked = document.querySelectorAll('.msg-checkbox:checked');
        const count = checked.length;
        if (countLabel) countLabel.textContent = count;
        if (deleteBtn) deleteBtn.disabled = (count === 0);
        if (selectAll) selectAll.checked = (checkboxes.length > 0 && count === checkboxes.length);
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateCount();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateCount);
    });

    updateCount();
});

function submitBulkDelete() {
    const checked = document.querySelectorAll('.msg-checkbox:checked');
    if (checked.length === 0) return;

    const form = document.getElementById('bulk-delete-form');
    const inputsContainer = document.getElementById('bulk-delete-inputs');
    inputsContainer.innerHTML = '';

    checked.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = cb.value;
        inputsContainer.appendChild(input);
    });

    form.submit();
}
</script>

@endsection
