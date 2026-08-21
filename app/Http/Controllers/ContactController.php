<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:30',
            'service' => 'nullable|string|max:100',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        $contact = ContactMessage::create($validated);

        // Notify the site owner. If mail isn't configured we still keep the DB record;
        // the admin will see it in the panel.
        try {
            $recipientEmail = site_setting('site_email', config('contact.recipient_email'));
            Mail::to($recipientEmail)->send(new ContactMessageMail($contact));
        } catch (\Throwable $e) {
            Log::warning('Contact email send failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Thank you for your message. We will get back to you within 24 hours.');
    }
}
