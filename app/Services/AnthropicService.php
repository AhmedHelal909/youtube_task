<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * AnthropicService
 *
 * Wraps the Anthropic Messages API (claude-3-haiku-20240307) to generate
 * a list of YouTube search queries for a given educational category.
 *
 * Design choices:
 * - We ask for JSON output so parsing is reliable.
 * - We request 15 titles to give YouTube search room to find 2 playlists each,
 *   even if some searches return zero playlist results.
 * - Temperature kept at 0.8 for creative variety without incoherence.
 */
class AnthropicService
{
    private const API_URL = 'https://api.anthropic.com/v1/messages';
    private const MODEL   = 'claude-3-haiku-20240307';

    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.key');

        if (empty($this->apiKey)) {
            throw new RuntimeException(
                'ANTHROPIC_API_KEY is not set. Please add it to your .env file.'
            );
        }
    }

    /**
     * Generate 10–20 educational YouTube course-title search queries
     * for the given category.
     *
     * @param  string $category  e.g. "Programming", "Marketing"
     * @return string[]          Array of search query strings
     *
     * @throws RuntimeException  On API failure or unparseable response
     */
    public function generateCourseTitles(string $category): array
    {
        $prompt = <<<PROMPT
You are an educational content curator. Your task is to generate a list of
15 diverse and specific YouTube search queries that would find high-quality
educational playlist courses about "{$category}".

Requirements:
- Each query should be different and cover different sub-topics or skill levels.
- Queries should be phrased as a human would type them into YouTube search.
- Focus on complete courses / full tutorials (playlists), not single videos.
- Mix beginner, intermediate, and advanced queries.

Respond ONLY with a valid JSON array of strings. No explanations, no markdown fences.
Example output format:
["query one", "query two", "query three"]
PROMPT;

        $response = Http::withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->post(self::API_URL, [
            'model'      => self::MODEL,
            'max_tokens' => 1024,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        if ($response->failed()) {
            $error = $response->json('error.message', 'Unknown Anthropic API error');
            Log::error("AnthropicService error for category '{$category}': {$error}");
            throw new RuntimeException("Anthropic API error: {$error}");
        }

        $text = $response->json('content.0.text', '');

        // Strip any accidental markdown fences the model may add
        $text = preg_replace('/```json|```/', '', $text);
        $text = trim($text);

        $titles = json_decode($text, true);

        if (!is_array($titles) || empty($titles)) {
            Log::warning("AnthropicService: Could not parse titles for '{$category}'", [
                'raw' => $text,
            ]);
            throw new RuntimeException(
                "Could not parse AI response for category '{$category}'. Raw: {$text}"
            );
        }

        // Sanitise — remove any empty strings
        return array_values(array_filter(array_map('trim', $titles)));
    }
}
