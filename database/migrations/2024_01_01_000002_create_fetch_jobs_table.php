<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This table tracks each fetch session — what categories were requested,
     * the status, and a summary of what was saved vs skipped (duplicates).
     */
    public function up(): void
    {
        Schema::create('fetch_jobs', function (Blueprint $table) {
            $table->id();

            // JSON-encoded array of category strings submitted by the user
            $table->json('categories');

            // pending | running | completed | failed
            $table->string('status')->default('pending');

            $table->unsignedInteger('total_saved')->default(0);
            $table->unsignedInteger('total_skipped')->default(0);   // duplicates
            $table->unsignedInteger('total_errors')->default(0);

            // Human-readable progress messages stored as JSON lines
            $table->json('log')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fetch_jobs');
    }
};
