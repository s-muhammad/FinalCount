<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => [
                    'fa' => 'تحولات میدانی',
                    'ar' => 'التطورات الميدانية',
                    'en' => 'Field Developments',
                ],
                'description' => [
                    'fa' => 'پوشش لحظه‌ای اخبار جبهه‌ها، عملیات‌های نظامی و آخرین رویدادهای خطوط نبرد.',
                    'ar' => 'تغطية لحظية لأخبار الجبهات، العمليات العسكرية وآخر تطورات خطوط المواجهة.',
                    'en' => 'Real-time coverage of frontlines, military operations, and the latest combat developments.',
                ],
                'image' => ' ',
                'is_in_menu' => true,
                'is_on_homepage' => true,
            ],
            [
                'name' => [
                    'fa' => 'رصد و تحلیل',
                    'ar' => 'رصد وتحليل',
                    'en' => 'Monitoring & Analysis',
                ],
                'description' => [
                    'fa' => 'پایش تحرکات دشمن، بررسی شکاف‌های درونی و ارائه تحلیل‌های راهبردی.',
                    'ar' => 'متابعة تحركات العدو، كشف تصدعاته الداخلية، وتقديم تحليلات استراتيجية.',
                    'en' => 'Tracking enemy movements, examining internal vulnerabilities, and providing strategic analysis.',
                ],
                'image' => ' ',
                'is_in_menu' => true,
                'is_on_homepage' => true,
            ],
            [
                'name' => [
                    'fa' => 'چهره‌ها و شهدا',
                    'ar' => 'قادة وشهداء',
                    'en' => 'Figures & Martyrs',
                ],
                'description' => [
                    'fa' => 'زندگینامه، دستاوردها و وصایای فرماندهان و شهدایی که مسیر مقاومت را روشن کردند.',
                    'ar' => 'سير وتضحيات ووصايا القادة والشهداء الذين أضاءوا درب المقاومة.',
                    'en' => 'Biographies, legacies, and testaments of commanders and martyrs who illuminate the path of resistance.',
                ],
                'image' => ' ',
                'is_in_menu' => true,
                'is_on_homepage' => true,
            ],
            [
            'name' => [
                'fa' => 'درباره ما',
                'ar' => 'من نحن',
                'en' => 'About Us',
            ],
            'description' => [
                'fa' => ' ',
                'ar' => ' ',
                'en' => ' ',
            ],
            'image' => ' ',
            'is_in_menu' => true,
            'is_on_homepage' => false,
        ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
