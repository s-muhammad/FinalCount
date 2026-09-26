<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rss_imports', function (Blueprint $table) {
            $table->string('image')->nullable()->after('raw_title');
            $table->longText('raw_body')->nullable()->after('image');
            $table->string('title')->nullable()->after('raw_title');
            $table->string('title_ar')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_ar');
            $table->text('summary')->nullable()->after('title_en');
            $table->text('summary_ar')->nullable()->after('summary');
            $table->text('summary_en')->nullable()->after('summary_ar');
            $table->longText('body')->nullable()->after('summary_en');
            $table->longText('body_ar')->nullable()->after('body');
            $table->longText('body_en')->nullable()->after('body_ar');
            $table->string('published_as')->nullable()->after('status');
        });

        \Illuminate\Support\Facades\DB::table('rss_imports')
            ->whereNotNull('news_id')
            ->select('id', 'news_id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    $news = \Illuminate\Support\Facades\DB::table('news')->where('id', $row->news_id)->first();

                    if (! $news) {
                        continue;
                    }

                    \Illuminate\Support\Facades\DB::table('rss_imports')->where('id', $row->id)->update([
                        'image' => $news->image,
                        'raw_body' => $news->summary ?: $news->body,
                        'title' => $news->title,
                        'title_ar' => $news->title_ar,
                        'title_en' => $news->title_en,
                        'summary' => $news->summary,
                        'summary_ar' => $news->summary_ar,
                        'summary_en' => $news->summary_en,
                        'body' => $news->body,
                        'body_ar' => $news->body_ar,
                        'body_en' => $news->body_en,
                        'published_as' => 'news',
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('rss_imports', function (Blueprint $table) {
            $table->dropColumn([
                'image', 'raw_body',
                'title', 'title_ar', 'title_en',
                'summary', 'summary_ar', 'summary_en',
                'body', 'body_ar', 'body_en',
                'published_as',
            ]);
        });
    }
};