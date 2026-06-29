<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'site_title', 'value' => 'My Website', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_image', 'value' => null, 'type' => 'image', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Best site ever', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'site_logo', 'value' => null, 'type' => 'image', 'group' => 'general'],
            ['key' => 'site_favicon', 'value' => null, 'type' => 'image', 'group' => 'general'],
            ['key' => 'site_email', 'value' => 'info@example.com', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_phone', 'value' => '+98-912-0000000', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_address', 'value' => 'Tehran, Iran', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'site_active', 'value' => '1', 'type' => 'boolean', 'group' => 'general'],
            ['key' => 'maintenance_msg', 'value' => 'سایت در حال بروزرسانی است', 'type' => 'textarea', 'group' => 'general'],

            ['key' => 'social_facebook', 'value' => 'https://facebook.com/yourpage', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_twitter', 'value' => 'https://twitter.com/yourpage', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/yourpage', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/yourpage', 'type' => 'text', 'group' => 'social'],

            ['key' => 'seo_meta_keywords', 'value' => 'laravel, php, blog', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'seo_meta_author', 'value' => 'Admin', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_og_image', 'value' => null, 'type' => 'image', 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            Settings::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
