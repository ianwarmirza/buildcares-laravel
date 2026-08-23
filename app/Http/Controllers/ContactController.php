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
        $num1 = rand(3, 9);
        $num2 = rand(1, 9);

        session([
            'captcha_answer' => $num1 + $num2,
            'form_load_time' => time(),
        ]);

        return view('contact', compact('num1', 'num2'));
    }

    public function store(Request $request)
    {
        // Anti-Bot Layer 1: Honeypot check
        if ($request->filled('website_url_hp')) {
            Log::info('Bot submission blocked via honeypot field.');
            return back()->with('success', 'Thank you for your message. We will get back to you within 24 hours.');
        }

        // Anti-Bot Layer 2: Fast submission check (< 2 seconds)
        $formTime = session('form_load_time', 0);
        if ($formTime > 0 && (time() - $formTime) < 2) {
            Log::info('Bot submission blocked via fast submission timer.');
            return back()->withErrors(['security_answer' => 'Form submitted too quickly. Please verify you are human.'])->withInput();
        }

        // Anti-Bot Layer 3: Math Security Challenge verification
        $expectedAnswer = session('captcha_answer');
        $userAnswer = (int) $request->input('security_answer');

        if (is_null($expectedAnswer) || $userAnswer !== $expectedAnswer) {
            return back()->withErrors(['security_answer' => 'Security check failed. Please solve the math question correctly.'])->withInput();
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:30',
            'service' => 'nullable|string|max:100',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        $contact = ContactMessage::create($validated);

        // Reset captcha for next attempt
        session()->forget(['captcha_answer', 'form_load_time']);

        // Notify the site owner.
        try {
            $recipientEmail = \App\Models\Setting::get('site_email', config('contact.recipient_email'));
            Mail::to($recipientEmail)->send(new ContactMessageMail($contact));
        } catch (\Throwable $e) {
            Log::warning('Contact email send failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Thank you for your message. We will get back to you within 24 hours.');
    }
}
