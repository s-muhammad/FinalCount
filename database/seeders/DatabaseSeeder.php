<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'ادمین',
            'email' => 'admin@nangaran.ir',
            'password' => Hash::make('password'),
        ]);

        $settings = [
            ['key' => 'site_name', 'value' => 'نگاران'],
            ['key' => 'site_description', 'value' => 'پایگاه اطلاع‌رسانی حضرت آیت‌الله العظمی خامنه‌ای'],
            ['key' => 'countdown_target_date', 'value' => '2040-09-09'],
            ['key' => 'countdown_title', 'value' => 'شما ۲۵ سال آینده را خواهید دید'],
            ['key' => 'countdown_description', 'value' => 'به حول و قوه الهی، تا ۲۵ سال آینده چیزی به نام رژیم صهیونیستی وجود نخواهد داشت.'],
            ['key' => 'footer_text', 'value' => 'تمامی حقوق برای پایگاه اطلاع‌رسانی نگاران محفوظ است'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
