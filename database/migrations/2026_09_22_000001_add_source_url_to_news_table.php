<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('news', 'source_url')) {
            Schema::table('news', function (Blueprint $table) {
                $table->string('source_url')->nullable()->after('slug');
            });
        }

        DB::table('rss_imports')
            ->whereNotNull('news_id')
            ->select('id', 'news_id', 'source_url')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('news')
                        ->where('id', $row->news_id)
                        ->whereNull('source_url')
                        ->update(['source_url' => $row->source_url]);
                }
            });

        if (! Schema::hasIndex('news', ['source_url'])) {
            Schema::table('news', function (Blueprint $table) {
                $table->unique('source_url');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasIndex('news', ['source_url'])) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropUnique(['source_url']);
            });
        }

        if (Schema::hasColumn('news', 'source_url')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropColumn('source_url');
            });
        }
    }
};