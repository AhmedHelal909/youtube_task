<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * GeminiService
 *
 * Wraps the Google Gemini API (gemini-2.5-flash) to generate
 * a list of YouTube search queries for a given educational category.
 *
 * Design choices:
 * - We ask for JSON output so parsing is reliable.
 * - We request 10 titles to give YouTube search room to find 2 playlists each,
 *   even if some searches return zero playlist results.
 * - Temperature kept at 0.8 for creative variety without incoherence.
 * - maxOutputTokens set to 2048 to safely handle Arabic/non-Latin output
 *   which consumes more tokens per word than English.
 */
class GeminiService
{
    private const API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');

        if (empty($this->apiKey)) {
            throw new RuntimeException(
                'GEMINI_API_KEY is not set. Please add it to your .env file.'
            );
        }
    }

    /**
     * Generate 10 educational YouTube course-title search queries
     * for the given category.
     *
     * @param  string $category  e.g. "Programming", "التسويق"
     * @return string[]          Array of search query strings
     *
     * @throws RuntimeException  On API failure or unparseable response
     */
    public function generateCourseTitles(string $category): array
    {
        $prompt = <<<PROMPT
You are an educational content curator. Your task is to generate a list of
10 diverse and specific YouTube search queries that would find high-quality
educational playlist courses about "{$category}".

Requirements:
- Each query should be different and cover different sub-topics or skill levels.
- Queries should be phrased as a human would type them into YouTube search.
- Focus on complete courses / full tutorials (playlists), not single videos.
- Mix beginner, intermediate, and advanced queries.
- Reply in the same language as the category name.

Respond ONLY with a valid JSON array of strings. No explanations, no markdown fences.

Example output format:
["query one", "query two", "query three"]
PROMPT;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(self::API_URL . '?key=' . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature'     => 0.8,
                'maxOutputTokens' => 2048,
            ],
        ]);

        if ($response->failed()) {
            $error = $response->json('error.message', 'Unknown Gemini API error');
            Log::error("GeminiService error for category '{$category}': {$error}");
            throw new RuntimeException("Gemini API error: {$error}");
        }

        // Gemini response structure:
        // candidates[0].content.parts[0].text
        $text = $response->json('candidates.0.content.parts.0.text', '');

        // Strip any accidental markdown fences the model may add
        $text = preg_replace('/```json|```/', '', $text);
        $text = trim($text);

        // Primary parse attempt
        $titles = json_decode($text, true);

        // Fallback: if the JSON was truncated (token limit hit mid-array),
        // repair it by closing the array and re-parsing.
        // This commonly happens with Arabic/non-Latin text which uses
        // more tokens per word than English.
        if (!is_array($titles)) {
            $repaired = rtrim($text, " \t\n\r,") . ']';
            $titles   = json_decode($repaired, true);
        }

        if (!is_array($titles) || empty($titles)) {
            Log::warning("GeminiService: Could not parse titles for '{$category}'", [
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