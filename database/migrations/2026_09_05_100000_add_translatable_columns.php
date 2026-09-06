<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // News
        Schema::table('news', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_ar');
            $table->text('summary_ar')->nullable()->after('summary');
            $table->text('summary_en')->nullable()->after('summary_ar');
            $table->longText('body_ar')->nullable()->after('body');
            $table->longText('body_en')->nullable()->after('body_ar');
        });

        // Messages
        Schema::table('messages', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_ar');
            $table->text('summary_ar')->nullable()->after('summary');
            $table->text('summary_en')->nullable()->after('summary_ar');
            $table->longText('body_ar')->nullable()->after('body');
            $table->longText('body_en')->nullable()->after('body_ar');
        });

        // Media
        Schema::table('media', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_ar');
            $table->text('description_ar')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_ar');
        });

        // Articles
        Schema::table('articles', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_ar');
            $table->text('summary_ar')->nullable()->after('summary');
            $table->text('summary_en')->nullable()->after('summary_ar');
            $table->longText('body_ar')->nullable()->after('body');
            $table->longText('body_en')->nullable()->after('body_ar');
        });

        // Interviews
        Schema::table('interviews', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_ar');
            $table->text('summary_ar')->nullable()->after('summary');
            $table->text('summary_en')->nullable()->after('summary_ar');
            $table->longText('body_ar')->nullable()->after('body');
            $table->longText('body_en')->nullable()->after('body_ar');
            $table->string('guest_name_ar')->nullable()->after('guest_name');
            $table->string('guest_name_en')->nullable()->after('guest_name_ar');
        });

        // Quotes
        Schema::table('quotes', function (Blueprint $table) {
            $table->longText('body_ar')->nullable()->after('body');
            $table->longText('body_en')->nullable()->after('body_ar');
            $table->string('source_ar')->nullable()->after('source');
            $table->string('source_en')->nullable()->after('source_ar');
        });

        // Gallery
        Schema::table('gallery', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_ar');
            $table->text('description_ar')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_ar');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['title_ar', 'title_en', 'summary_ar', 'summary_en', 'body_ar', 'body_en']);
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['title_ar', 'title_en', 'summary_ar', 'summary_en', 'body_ar', 'body_en']);
        });
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['title_ar', 'title_en', 'description_ar', 'description_en']);
        });
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['title_ar', 'title_en', 'summary_ar', 'summary_en', 'body_ar', 'body_en']);
        });
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropColumn(['title_ar', 'title_en', 'summary_ar', 'summary_en', 'body_ar', 'body_en', 'guest_name_ar', 'guest_name_en']);
        });
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['body_ar', 'body_en', 'source_ar', 'source_en']);
        });
        Schema::table('gallery', function (Blueprint $table) {
            $table->dropColumn(['title_ar', 'title_en', 'description_ar', 'description_en']);
        });
    }
};
