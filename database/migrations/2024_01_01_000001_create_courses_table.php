<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            // YouTube playlist unique identifier — used for deduplication
            $table->string('playlist_id')->unique();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('channel_name')->nullable();
            $table->string('category');

            // Extra metadata — useful for UI display
            $table->unsignedInteger('video_count')->default(0);
            $table->string('playlist_url')->nullable();

            $table->timestamps();

            // Index category for fast filtering
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
