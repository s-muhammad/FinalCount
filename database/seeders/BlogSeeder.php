<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogs = [
            [
                'image' => 'https://placehold.co/800x400/2c3e50/ffffff?text=Evolution+of+Resistance',
                'title' => [
                    'fa' => 'مفهوم و سیر تطور مقاومت اسلامی در دوران معاصر',
                    'ar' => 'مفهوم وتطور المقاومة الإسلامية في العصر الحديث',
                    'en' => 'The Concept and Evolution of Islamic Resistance in the Modern Era',
                ],
                'summary' => [
                    'fa' => 'بررسی ریشه‌های تاریخی و تکامل مفهوم مقاومت در برابر اشغالگری و استعمار در کشورهای اسلامی.',
                    'ar' => 'دراسة الجذور التاريخية وتطور مفهوم المقاومة ضد الاحتلال والاستعمار في الدول الإسلامية.',
                    'en' => 'An examination of the historical roots and evolution of the concept of resistance against occupation and colonialism in Islamic countries.',
                ],
                'body' => [
                    'fa' => 'مقاومت اسلامی به عنوان یک گفتمان و حرکت میدانی، ریشه در آموزه‌های دینی مبنی بر نفی سلطه بیگانگان و دفاع از سرزمین‌های اسلامی دارد. در دهه‌های اخیر، این مفهوم از یک رویکرد صرفاً دفاعی محلی به یک ساختار منسجم و شبکه‌ای در سطح منطقه تبدیل شده است. بیداری ملت‌ها و ایستادگی در برابر نیروهای اشغالگر، موجب تقویت این گفتمان شده است. امروزه محور مقاومت تنها محدود به عرصه نظامی نیست، بلکه ابعاد سیاسی، اقتصادی و دیپلماتیک را نیز به شکل فعال در بر می‌گیرد تا از حقوق ملت‌های مظلوم دفاع کند.',
                    'ar' => 'المقاومة الإسلامية كخطاب وحركة ميدانية، لها جذور في التعاليم الدينية القائمة على رفض الهيمنة الأجنبية والدفاع عن الأراضي الإسلامية. في العقود الأخيرة، تحول هذا المفهوم من مجرد نهج دفاعي محلي إلى هيكل متماسك وشبكي على مستوى المنطقة. إن صحوة الشعوب والصمود في وجه قوات الاحتلال قد عززت هذا الخطاب. اليوم، لا يقتصر محور المقاومة على الساحة العسكرية فحسب، بل يشمل بشكل فعال الأبعاد السياسية والاقتصادية والدبلوماسية للدفاع عن حقوق الشعوب المظلومة.',
                    'en' => 'Islamic resistance, both as a discourse and a field movement, is rooted in religious teachings based on the rejection of foreign domination and the defense of Islamic lands. In recent decades, this concept has evolved from a strictly local defensive approach into a cohesive, networked structure across the region. The awakening of nations and their steadfastness against occupying forces have strengthened this discourse. Today, the axis of resistance is not limited solely to the military arena; it actively encompasses political, economic, and diplomatic dimensions to defend the rights of oppressed peoples.',
                ],
                'category_id' => 1,
            ],
            [
                'image' => 'https://placehold.co/800x400/8b0000/ffffff?text=Youth+in+Resistance',
                'title' => [
                    'fa' => 'نقش جوانان در آینده‌سازی محور مقاومت',
                    'ar' => 'دور الشباب في بناء مستقبل محور المقاومة',
                    'en' => 'The Role of Youth in Shaping the Future of the Resistance Axis',
                ],
                'summary' => [
                    'fa' => 'چگونه نسل جدید با بهره‌گیری از فناوری و رسانه، افق‌های جدیدی را در جبهه مقاومت می‌گشاید.',
                    'ar' => 'كيف يفتح الجيل الجديد آفاقاً جديدة في جبهة المقاومة باستخدام التكنولوجيا والإعلام.',
                    'en' => 'How the new generation is opening new horizons on the resistance front by utilizing technology and media.',
                ],
                'body' => [
                    'fa' => 'جوانان همواره موتور محرک تحولات بزرگ اجتماعی و سیاسی بوده‌اند. در محور مقاومت، نسل جدید نه تنها میراث‌دار ارزش‌های نسل‌های پیشین است، بلکه با تسلط بر فناوری‌های نوین و رسانه‌های دیجیتال، روایتگری مقاومت را به سطح جهانی ارتقا داده است. فضای مجازی به میدان نبرد جدیدی تبدیل شده است که جوانان در آن با جنگ روانی و رسانه‌ای دشمن مقابله می‌کنند. این پویایی و نوآوری، تضمین‌کننده تداوم و انتقال پیام مقاومت به آیندگان است.',
                    'ar' => 'لطالما كان الشباب المحرك الأساسي للتغيرات الاجتماعية والسياسية الكبرى. في محور المقاومة، لا يقتصر دور الجيل الجديد على وراثة قيم الأجيال السابقة، بل امتد للارتقاء بسردية المقاومة إلى المستوى العالمي من خلال إتقان التقنيات الحديثة والإعلام الرقمي. لقد أصبح الفضاء الإلكتروني ساحة معركة جديدة يواجه فيها الشباب الحرب النفسية والإعلامية للعدو. هذه الديناميكية والابتكار تضمن استمرارية ونقل رسالة المقاومة للأجيال القادمة.',
                    'en' => 'Youth have always been the driving force behind major social and political transformations. Within the axis of resistance, the new generation is not only inheriting the values of previous generations but is also elevating the narrative of resistance to a global level by mastering modern technologies and digital media. Cyberspace has become a new battlefield where young people counter the psychological and media warfare of the adversary. This dynamism and innovation guarantee the continuity and transmission of the resistance\'s message to future generations.',
                ],
                'category_id' => 2,
            ],
            [
                'image' => 'https://placehold.co/800x400/556b2f/ffffff?text=Cultural+Front',
                'title' => [
                    'fa' => 'جبهه فرهنگی: تجلی مقاومت در ادبیات و هنر',
                    'ar' => 'الجبهة الثقافية: تجلي المقاومة في الأدب والفن',
                    'en' => 'The Cultural Front: The Manifestation of Resistance in Literature and Art',
                ],
                'summary' => [
                    'fa' => 'نقش شعر، سینما و ادبیات داستانی در حفظ حافظه تاریخی و هویت‌بخشی به جریان مقاومت.',
                    'ar' => 'دور الشعر والسينما والأدب القصصي في الحفاظ على الذاكرة التاريخية وإعطاء الهوية لتيار المقاومة.',
                    'en' => 'The role of poetry, cinema, and fiction in preserving historical memory and providing identity to the resistance movement.',
                ],
                'body' => [
                    'fa' => 'مبارزه با اشغالگری تنها با سلاح انجام نمی‌شود؛ کلمات و تصاویر نیز سلاح‌های برنده‌ای در جبهه فرهنگی هستند. ادبیات مقاومت، اعم از شعر، داستان و رمان، نقش مهمی در زنده نگه داشتن یاد شهدا و مظلومیت ملت‌ها ایفا می‌کند. از سوی دیگر، سینما و هنرهای تجسمی به عنوان ابزارهای قدرتمند، توانسته‌اند پیام پایداری را از مرزهای جغرافیایی فراتر برده و افکار عمومی جهان را بیدار کنند. هنر مقاومت، صدای رسای کسانی است که رسانه‌های جریان اصلی سعی در خاموش کردن آن‌ها دارند.',
                    'ar' => 'النضال ضد الاحتلال لا يقتصر على السلاح فقط؛ فالكلمات والصور هي أيضاً أسلحة قاطعة في الجبهة الثقافية. يلعب أدب المقاومة، بما في ذلك الشعر والقصص والروايات، دوراً مهماً في إبقاء ذكرى الشهداء ومظلومية الشعوب حية. من ناحية أخرى، تمكنت السينما والفنون البصرية، كأدوات قوية، من تجاوز الحدود الجغرافية لإيصال رسالة الصمود وإيقاظ الرأي العام العالمي. فن المقاومة هو الصوت المدوي لأولئك الذين تحاول وسائل الإعلام السائدة إسكاتهم.',
                    'en' => 'The struggle against occupation is not waged with weapons alone; words and images are also sharp weapons on the cultural front. Resistance literature, including poetry, stories, and novels, plays a crucial role in keeping the memory of martyrs and the oppression of nations alive. On the other hand, cinema and visual arts, as powerful tools, have managed to carry the message of perseverance beyond geographical borders and awaken global public opinion. The art of resistance is the resounding voice of those whom mainstream media attempts to silence.',
                ],
                'category_id' => 3,
            ]
        ];
        foreach ($blogs as $blog) {
            Blog::create($blog);
        }
    }
}
