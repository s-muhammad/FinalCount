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
            'site_logo' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'countdown_target_date' => 'nullable|string|max:255',
            'countdown_title' => 'nullable|string|max:255',
            'countdown_description' => 'nullable|string',
            'countdown_bg_color' => 'nullable|string|max:50',
            'countdown_bg_image' => 'nullable|image|mimes:jpeg,png,webp|max:4096',
            'footer_text' => 'nullable|string',
            'social_instagram' => 'nullable|string|max:500',
            'social_twitter' => 'nullable|string|max:500',
            'social_youtube' => 'nullable|string|max:500',
            'social_telegram' => 'nullable|string|max:500',
            'ad_banner_enabled' => 'nullable|boolean',
            'ad_banner_image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'ad_banner_link' => 'nullable|string|max:2048',
            'site_logo_current' => 'nullable|string',
        ]);

        // اگر لوگو ارسال شده ذخیره شود
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            $validated['site_logo'] = $path;
            // اگر لوگوی قبلی وجود داشت حذف شود
            if (!empty($validated['site_logo_current'])) {
                \Storage::disk('public')->delete($validated['site_logo_current']);
            }
        }
        unset($validated['site_logo_current']);

        // اگر تصویر پس‌زمینه شمارش معکوس ارسال شده ذخیره شود
        if ($request->hasFile('countdown_bg_image')) {
            $path = $request->file('countdown_bg_image')->store('settings', 'public');
            $validated['countdown_bg_image'] = $path;
            $oldBgImage = Setting::getValue('countdown_bg_image', '');
            if ($oldBgImage) {
                \Storage::disk('public')->delete($oldBgImage);
            }
        }

        // اگر تصویر بنر تبلیغاتی ارسال شده ذخیره شود
        if ($request->hasFile('ad_banner_image')) {
            $path = $request->file('ad_banner_image')->store('settings', 'public');
            $validated['ad_banner_image'] = $path;
            $oldAdImage = Setting::getValue('ad_banner_image', '');
            if ($oldAdImage) {
                \Storage::disk('public')->delete($oldAdImage);
            }
        }

        unset($validated['ad_banner_enabled']);
        Setting::setValue('ad_banner_enabled', $request->boolean('ad_banner_enabled') ? '1' : '0');

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