<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $siteSettings = [
            'site_email'      => Setting::get('site_email', 'anwar@buildcares.com'),
            'site_phone'      => Setting::get('site_phone', '+44 7586 750755'),
            'whatsapp_number' => Setting::get('whatsapp_number', '447586750755'),
            'working_hours'   => Setting::get('working_hours', 'Mon–Sun 9AM – 10PM'),
            'site_address'    => Setting::get('site_address', ''),
        ];

        return view('admin.settings.index', compact('user', 'siteSettings'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password'      => 'nullable|required_with:new_password|string',
            'new_password'          => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'The provided current password does not match.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return back()->with('success', 'Profile information updated successfully.');
    }

    public function updateSiteInfo(Request $request)
    {
        $validated = $request->validate([
            'site_email'      => 'required|email|max:255',
            'site_phone'      => 'required|string|max:50',
            'whatsapp_number' => 'required|string|max:30',
            'working_hours'   => 'nullable|string|max:255',
            'site_address'    => 'nullable|string|max:255',
        ]);

        Setting::setMany([
            'site_email'      => $validated['site_email'],
            'site_phone'      => $validated['site_phone'],
            'whatsapp_number' => preg_replace('/[^0-9]/', '', $validated['whatsapp_number']),
            'working_hours'   => $validated['working_hours'],
            'site_address'    => $validated['site_address'],
        ]);

        return back()->with('success', 'Site basic contact information updated successfully.');
    }
}
