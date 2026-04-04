<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * YouTubeService
 *
 * Wraps the YouTube Data API v3 (search.list + playlistItems.list) to find
 * educational playlists matching a given search query.
 *
 * Design choices:
 * - We request exactly 2 playlists per query (maxResults=2) to match the spec
 *   and stay well within the daily quota (10,000 units; search = 100 units each).
 * - We use the `snippet` part — enough to populate every field we store.
 * - videoCount is fetched via a second call to playlists.list (contentDetails)
 *   because search.list does not return item counts.
 */
class YouTubeService
{
    private const BASE_URL = 'https://www.googleapis.com/youtube/v3';

    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.youtube.key');

        if (empty($this->apiKey)) {
            throw new RuntimeException(
                'YOUTUBE_API_KEY is not set. Please add it to your .env file.'
            );
        }
    }

    /**
     * Search YouTube for playlists matching $query.
     *
     * @param  string $query   The search query string
     * @param  int    $limit   Number of playlists to return (default 2)
     * @return array[]         Array of normalised playlist data arrays
     */
    public function searchPlaylists(string $query, int $limit = 2): array
    {
        // ── Step 1: search.list ──────────────────────────────────────────────
        $searchResponse = Http::get(self::BASE_URL . '/search', [
            'part'       => 'snippet',
            'q'          => $query,
            'type'       => 'playlist',
            'maxResults' => $limit,
            'key'        => $this->apiKey,
        ]);

        if ($searchResponse->failed()) {
            $error = $searchResponse->json('error.message', 'Unknown YouTube API error');
            Log::warning("YouTubeService search failed for '{$query}': {$error}");
            return [];
        }

        $items = $searchResponse->json('items', []);

        if (empty($items)) {
            return [];
        }

        // Collect playlist IDs so we can batch-fetch contentDetails
        $playlistIds = array_map(
            fn($item) => $item['id']['playlistId'] ?? null,
            $items
        );
        $playlistIds = array_filter($playlistIds);

        // ── Step 2: playlists.list (for video counts) ────────────────────────
        $videoCounts = $this->fetchVideoCounts($playlistIds);

        // ── Step 3: Normalise ────────────────────────────────────────────────
        $playlists = [];

        foreach ($items as $item) {
            $playlistId = $item['id']['playlistId'] ?? null;

            if (!$playlistId) {
                continue;
            }

            $snippet = $item['snippet'] ?? [];

            $playlists[] = [
                'playlist_id'   => $playlistId,
                'title'         => $snippet['title'] ?? 'Untitled Playlist',
                'description'   => $snippet['description'] ?? null,
                'thumbnail_url' => $this->bestThumbnail($snippet['thumbnails'] ?? []),
                'channel_name'  => $snippet['channelTitle'] ?? null,
                'playlist_url'  => "https://www.youtube.com/playlist?list={$playlistId}",
                'video_count'   => $videoCounts[$playlistId] ?? 0,
            ];
        }
        return $playlists;
    }

    /**
     * Fetch the video count (itemCount) for an array of playlist IDs in one
     * API call to minimise quota usage.
     *
     * @param  string[] $playlistIds
     * @return array<string, int>  Map of playlistId => itemCount
     */
    private function fetchVideoCounts(array $playlistIds): array
    {
        if (empty($playlistIds)) {
            return [];
        }

        $response = Http::get(self::BASE_URL . '/playlists', [
            'part' => 'contentDetails',
            'id'   => implode(',', $playlistIds),
            'key'  => $this->apiKey,
        ]);

        if ($response->failed()) {
            Log::warning('YouTubeService: Could not fetch video counts', [
                'ids' => $playlistIds,
            ]);
            return [];
        }

        $counts = [];
        foreach ($response->json('items', []) as $item) {
            $id              = $item['id'] ?? null;
            $count           = $item['contentDetails']['itemCount'] ?? 0;
            if ($id) {
                $counts[$id] = (int) $count;
            }
        }

        return $counts;
    }

    /**
     * Pick the highest-quality thumbnail URL available.
     */
    private function bestThumbnail(array $thumbnails): ?string
    {
        foreach (['maxres', 'standard', 'high', 'medium', 'default'] as $quality) {
            if (!empty($thumbnails[$quality]['url'])) {
                return $thumbnails[$quality]['url'];
            }
        }

        return null;
    }
}
