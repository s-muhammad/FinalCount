<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('news', 'source_url_hash')) {
            Schema::table('news', function (Blueprint $table) {
                $table->string('source_url_hash', 40)->nullable()->after('source_url');
            });
        }

        DB::table('news')
            ->whereNotNull('source_url')
            ->select('id', 'source_url')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('news')
                        ->where('id', $row->id)
                        ->whereNull('source_url_hash')
                        ->update(['source_url_hash' => sha1($row->source_url)]);
                }
            });

        if (Schema::hasIndex('news', ['source_url'])) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropUnique('news_source_url_unique');
            });
        }

        Schema::table('news', function (Blueprint $table) {
            $table->text('source_url')->nullable()->change();
        });

        if (! Schema::hasIndex('news', ['source_url_hash'])) {
            Schema::table('news', function (Blueprint $table) {
                $table->unique('source_url_hash');
            });
        }

        if (Schema::hasIndex('rss_imports', ['source_url'])) {
            Schema::table('rss_imports', function (Blueprint $table) {
                $table->dropUnique('rss_imports_source_url_unique');
            });
        }

        Schema::table('rss_imports', function (Blueprint $table) {
            $table->text('source_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasIndex('news', ['source_url_hash'])) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropUnique(['source_url_hash']);
            });
        }

        if (Schema::hasColumn('news', 'source_url_hash')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropColumn('source_url_hash');
            });
        }
    }
};