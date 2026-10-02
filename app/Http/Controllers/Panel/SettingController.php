<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Show the website settings form.
     */
    public function edit(): View
    {
        return view('panel.settings', ['settings' => Setting::allValues()]);
    }

    /**
     * Save the website settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_tagline' => ['required', 'string', 'max:60'],
            'hero_title' => ['required', 'string', 'max:120'],
            'hero_text' => ['required', 'string', 'max:400'],
            'meta_description' => ['required', 'string', 'max:160'],
            'footer_text' => ['required', 'string', 'max:300'],
            'contact_title' => ['required', 'string', 'max:120'],
            'contact_text' => ['required', 'string', 'max:300'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'contact_whatsapp' => ['nullable', 'regex:/^62\d{8,13}$/'],
            'contact_instagram' => ['nullable', 'string', 'max:60', 'regex:/^[A-Za-z0-9._]+$/'],
            'contact_location' => ['nullable', 'string', 'max:100'],
        ], [
            'contact_whatsapp.regex' => 'Nomor WhatsApp ditulis dengan awalan 62 tanpa tanda plus atau spasi, misalnya 6281234567890.',
            'contact_instagram.regex' => 'Tulis nama pengguna Instagram saja, tanpa @ dan tanpa tautan.',
        ]);

        Setting::saveMany($validated);

        return redirect()->route('panel.settings.edit')->with('status', 'Pengaturan website disimpan.');
    }
}
