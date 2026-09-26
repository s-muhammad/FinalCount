<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->boolean('is_martyr')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('personables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->morphs('personable');
            $table->timestamps();

            $table->foreign('person_id')
                ->references('id')
                ->on('people')
                ->onDelete('cascade');

            $table->unique(['person_id', 'personable_type', 'personable_id'], 'personables_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personables');
        Schema::dropIfExists('people');
    }
};