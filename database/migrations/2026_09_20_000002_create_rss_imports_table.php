<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rss_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_id')->nullable()->constrained('rss_feeds')->nullOnDelete();
            $table->foreignId('news_id')->nullable()->constrained('news')->nullOnDelete();
            $table->string('source_url')->unique();
            $table->string('status')->default('pending');
            $table->string('keywords')->nullable();
            $table->text('error')->nullable();
            $table->string('raw_title')->nullable();
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rss_imports');
    }
};