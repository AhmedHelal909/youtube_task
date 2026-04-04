{{-- resources/views/courses/_card.blade.php --}}
<div class="course-card">

    {{-- Thumbnail --}}
    <div class="course-thumb">
        <a href="{{ $course->playlist_url ?? $course->youtube_url }}" target="_blank" rel="noopener">
            <img
                src="{{ $course->thumbnail_url ?? 'https://via.placeholder.com/320x180/1a1a2e/ffffff?text=No+Thumbnail' }}"
                alt="{{ $course->title }}"
                loading="lazy"
            >
        </a>

        {{-- Price badge — "مجاني" (free) since all are YouTube --}}
        <span class="price-badge">مجاني</span>

        {{-- Video count badge --}}
        @if($course->video_count > 0)
            <span class="video-count-badge">
                <i class="bi bi-collection-play-fill"></i>
                {{ $course->video_count }} مقاطع
            </span>
        @endif
    </div>

    {{-- Body --}}
    <div class="course-body">
        <h3 class="course-title">
            <a href="{{ $course->playlist_url ?? $course->youtube_url }}"
               target="_blank" rel="noopener">
                {{ Str::limit($course->title, 70) }}
            </a>
        </h3>

        @if($course->channel_name)
            <p class="course-channel">
                <i class="bi bi-person-fill me-1"></i>
                {{ $course->channel_name }}
            </p>
        @endif

        {{-- Category tag + CTA --}}
        <div class="course-footer">
            <span class="course-category-tag">{{ $course->category }}</span>
            <a href="{{ $course->playlist_url ?? $course->youtube_url }}"
               target="_blank"
               rel="noopener"
               class="btn-watch">
                التسويق
            </a>
        </div>
    </div>

</div>
