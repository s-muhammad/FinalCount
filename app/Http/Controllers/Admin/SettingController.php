<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'countdown_target_date' => 'nullable|string|max:255',
            'countdown_title' => 'nullable|string|max:255',
            'countdown_description' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'social_instagram' => 'nullable|string|max:500',
            'social_twitter' => 'nullable|string|max:500',
            'social_youtube' => 'nullable|string|max:500',
            'social_telegram' => 'nullable|string|max:500',
        ]);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value);
        }

        $modules = ['news', 'messages', 'media', 'articles', 'interviews', 'quotes', 'gallery', 'contact'];
        foreach ($modules as $module) {
            Setting::setValue("module_active_{$module}", $request->input("module_active_{$module}", '0'));
        }

        return redirect()->route('admin.settings.index')->with('success', 'تنظیمات با موفقیت بروزرسانی شد');
    }
}
