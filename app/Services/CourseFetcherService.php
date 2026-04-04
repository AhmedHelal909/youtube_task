<?php

namespace App\Services;

use App\Models\Course;
use App\Models\FetchJob;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * CourseFetcherService
 *
 * Orchestrates the full pipeline for a single FetchJob:
 *   1. For each category → ask Anthropic for 15 search queries.
 *   2. For each query → ask YouTube for 2 playlists.
 *   3. Persist each playlist (skipping duplicates by playlist_id).
 *   4. Update the FetchJob with counts and log lines.
 *
 * This service is called synchronously (QUEUE_CONNECTION=sync), which means
 * it blocks the HTTP response until everything is done.  For production, move
 * the body of `run()` into a queued Job class and dispatch it.
 */
class CourseFetcherService
{
    public function __construct(
        private readonly GeminiService $ai,
        private readonly YouTubeService   $youtube,
    ) {}

    /**
     * Execute the fetch pipeline for the given FetchJob.
     */
    public function run(FetchJob $job): void
    {
        $job->markRunning();
        $job->appendLog('Starting fetch pipeline…');

        $totalSaved   = 0;
        $totalSkipped = 0;
        $totalErrors  = 0;

        foreach ($job->categories as $category) {
            $category = trim($category);

            if ($category === '') {
                continue;
            }

            $job->appendLog("▶ Category: {$category}");

            // ── Step 1: Generate search queries via AI ───────────────────────
            try {
                $queries = $this->ai->generateCourseTitles($category);
                $job->appendLog("  ✓ AI generated " . count($queries) . " search queries.");
            } catch (Throwable $e) {
                $job->appendLog("  ✗ AI generation failed: " . $e->getMessage());
                $totalErrors++;
                continue;
            }

            // ── Step 2 & 3: Search YouTube + persist ─────────────────────────
            foreach ($queries as $query) {
                try {
                    $playlists = $this->youtube->searchPlaylists($query, limit: 2);

                    foreach ($playlists as $data) {
                        $data['category'] = $category;

                        // firstOrCreate = deduplication by playlist_id
                        [$course, $created] = $this->upsertCourse($data);

                        if ($created) {
                            $totalSaved++;
                            $job->appendLog(
                                "    + Saved: \"{$course->title}\" ({$course->playlist_id})"
                            );
                        } else {
                            $totalSkipped++;
                            $job->appendLog(
                                "    ~ Skipped duplicate: {$course->playlist_id}"
                            );
                        }
                    }

                    if (empty($playlists)) {
                        $job->appendLog("    ⚠ No playlists found for: \"{$query}\"");
                    }
                } catch (Throwable $e) {
                    $totalErrors++;
                    $job->appendLog("    ✗ Error for \"{$query}\": " . $e->getMessage());
                    Log::error('CourseFetcherService error', [
                        'query'    => $query,
                        'category' => $category,
                        'message'  => $e->getMessage(),
                    ]);
                }

                // Small delay to be respectful of rate limits
                usleep(100_000); // 100 ms
            }
        }

        // ── Finalise ─────────────────────────────────────────────────────────
        $job->update([
            'total_saved'   => $totalSaved,
            'total_skipped' => $totalSkipped,
            'total_errors'  => $totalErrors,
        ]);

        $job->appendLog(
            "Done. Saved: {$totalSaved} | Skipped: {$totalSkipped} | Errors: {$totalErrors}"
        );

        $job->markCompleted();
    }

    /**
     * Insert a course or locate the existing one by playlist_id.
     *
     * @return array{Course, bool}  [model, wasCreated]
     */
    private function upsertCourse(array $data): array
    {
        $existing = Course::where('playlist_id', $data['playlist_id'])->first();

        if ($existing) {
            return [$existing, false];
        }

        $course = Course::create($data);

        return [$course, true];
    }
}
