<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('rss_imports', 'source_url_hash')) {
            Schema::table('rss_imports', function (Blueprint $table) {
                $table->string('source_url_hash', 40)->nullable()->after('source_url');
            });
        }

        DB::table('rss_imports')
            ->whereNotNull('source_url')
            ->select('id', 'source_url')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('rss_imports')
                        ->where('id', $row->id)
                        ->whereNull('source_url_hash')
                        ->update(['source_url_hash' => sha1($row->source_url)]);
                }
            });

        $duplicateHashes = DB::table('rss_imports')
            ->select('source_url_hash')
            ->whereNotNull('source_url_hash')
            ->groupBy('source_url_hash')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('source_url_hash');

        foreach ($duplicateHashes as $hash) {
            $rows = DB::table('rss_imports')
                ->where('source_url_hash', $hash)
                ->get(['id', 'news_id']);

            $keep = $rows->firstWhere('news_id') ?? $rows->first();

            foreach ($rows as $row) {
                if ($row->id !== $keep->id) {
                    DB::table('rss_imports')->where('id', $row->id)->delete();
                }
            }
        }

        if (! Schema::hasIndex('rss_imports', ['source_url_hash'])) {
            Schema::table('rss_imports', function (Blueprint $table) {
                $table->unique('source_url_hash');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasIndex('rss_imports', ['source_url_hash'])) {
            Schema::table('rss_imports', function (Blueprint $table) {
                $table->dropUnique(['source_url_hash']);
            });
        }

        if (Schema::hasColumn('rss_imports', 'source_url_hash')) {
            Schema::table('rss_imports', function (Blueprint $table) {
                $table->dropColumn('source_url_hash');
            });
        }
    }
};