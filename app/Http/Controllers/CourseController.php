<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\FetchJob;
use App\Services\CourseFetcherService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseFetcherService $fetcher,
    ) {}

    // ─── Pages ───────────────────────────────────────────────────────────────

    /**
     * Home page — shows the input form and the courses grid.
     *
     * Supports optional `?category=` query-string filter.
     */
    public function index(Request $request): View
    {
        $selectedCategory = $request->query('category');

        // Distinct category list for the filter tabs
        $categories = Course::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // Course query
        $coursesQuery = Course::query()->orderByDesc('created_at');

        if ($selectedCategory) {
            $coursesQuery->byCategory($selectedCategory);
        }

        $courses = $coursesQuery->paginate(12)->withQueryString();

        return view('courses.index', compact(
            'courses',
            'categories',
            'selectedCategory',
        ));
    }

    // ─── Actions ─────────────────────────────────────────────────────────────

    /**
     * POST /fetch
     *
     * Accepts a textarea of categories (one per line), creates a FetchJob,
     * runs the pipeline synchronously, then redirects back.
     */
    public function fetch(Request $request)
    {
        $request->validate([
            'categories' => ['required', 'string', 'max:2000'],
        ]);

        // Parse raw textarea into a clean array of non-empty lines
        $rawLines = explode("\n", $request->input('categories'));
        $categories = array_values(
            array_unique(
                array_filter(
                    array_map('trim', $rawLines)
                )
            )
        );

        if (empty($categories)) {
            return back()->withErrors([
                'categories' => 'Please enter at least one category.',
            ])->withInput();
        }

        // Create and persist the job record immediately
        $job = FetchJob::create([
            'categories' => $categories,
            'status'     => 'pending',
        ]);

        try {
            $this->fetcher->run($job);
        } catch (Throwable $e) {
            $job->markFailed($e->getMessage());

            return back()
                ->with('error', 'Fetch failed: ' . $e->getMessage())
                ->withInput();
        }

        return redirect()
            ->route('courses.index')
            ->with('success', sprintf(
                'Fetch complete! Saved %d new playlists, skipped %d duplicates.',
                $job->fresh()->total_saved,
                $job->fresh()->total_skipped,
            ));
    }

    /**
     * GET /jobs/{job}
     *
     * Shows the log for a single FetchJob (useful for debugging).
     */
    public function showJob(FetchJob $job): View
    {
        return view('courses.job', compact('job'));
    }

    // ─── API (JSON) ──────────────────────────────────────────────────────────

    /**
     * GET /api/courses
     *
     * Returns paginated courses as JSON — handy for future JS enhancements.
     */
    public function apiIndex(Request $request)
    {
        $query    = Course::query()->orderByDesc('created_at');
        $category = $request->query('category');

        if ($category) {
            $query->byCategory($category);
        }

        return response()->json($query->paginate(20));
    }
}
