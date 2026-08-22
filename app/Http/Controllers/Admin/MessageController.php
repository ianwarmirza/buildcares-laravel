<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class MessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);
        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }
        return view('admin.messages.show', compact('message'));
    }

    public function toggleRead(ContactMessage $message)
    {
        $message->update(['is_read' => ! $message->is_read]);
        return back()->with('success', $message->is_read ? 'Marked as read.' : 'Marked as unread.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }

    public function bulkDestroy(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('admin.messages.index')->with('error', 'No messages selected.');
        }

        $count = ContactMessage::whereIn('id', $ids)->delete();
        return redirect()->route('admin.messages.index')->with('success', "{$count} messages deleted.");
    }

    public function deleteAll()
    {
        $count = ContactMessage::count();
        ContactMessage::query()->delete();
        return redirect()->route('admin.messages.index')->with('success', "All {$count} messages deleted.");
    }
}
