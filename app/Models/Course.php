<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * Mass-assignable fields.
     */
    protected $fillable = [
        'playlist_id',
        'title',
        'description',
        'thumbnail_url',
        'channel_name',
        'category',
        'video_count',
        'playlist_url',
    ];

    // ─── Scopes ──────────────────────────────────────────────────────────────

    /**
     * Filter by category (case-insensitive).
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Return a full YouTube playlist URL from the stored playlist_id.
     */
    public function getYoutubeUrlAttribute(): string
    {
        return "https://www.youtube.com/playlist?list={$this->playlist_id}";
    }

    /**
     * Fallback thumbnail when the YouTube CDN URL is unavailable.
     */
    public function getThumbnailAttribute(): string
    {
        return $this->thumbnail_url
            ?? "https://via.placeholder.com/320x180?text=No+Thumbnail";
    }
}
