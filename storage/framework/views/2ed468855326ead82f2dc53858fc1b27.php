
<div class="course-card">

    
    <div class="course-thumb">
        <a href="<?php echo e($course->playlist_url ?? $course->youtube_url); ?>" target="_blank" rel="noopener">
            <img
                src="<?php echo e($course->thumbnail_url ?? 'https://via.placeholder.com/320x180/1a1a2e/ffffff?text=No+Thumbnail'); ?>"
                alt="<?php echo e($course->title); ?>"
                loading="lazy"
            >
        </a>

        
        <span class="price-badge">مجاني</span>

        
        <?php if($course->video_count > 0): ?>
            <span class="video-count-badge">
                <i class="bi bi-collection-play-fill"></i>
                <?php echo e($course->video_count); ?> مقاطع
            </span>
        <?php endif; ?>
    </div>

    
    <div class="course-body">
        <h3 class="course-title">
            <a href="<?php echo e($course->playlist_url ?? $course->youtube_url); ?>"
               target="_blank" rel="noopener">
                <?php echo e(Str::limit($course->title, 70)); ?>

            </a>
        </h3>

        <?php if($course->channel_name): ?>
            <p class="course-channel">
                <i class="bi bi-person-fill me-1"></i>
                <?php echo e($course->channel_name); ?>

            </p>
        <?php endif; ?>

        
        <div class="course-footer">
            <span class="course-category-tag"><?php echo e($course->category); ?></span>
            <a href="<?php echo e($course->playlist_url ?? $course->youtube_url); ?>"
               target="_blank"
               rel="noopener"
               class="btn-watch">
                التسويق
            </a>
        </div>
    </div>

</div>
<?php /**PATH C:\wamp64\www\taaaaaaaaaaaaaaama\resources\views/courses/_card.blade.php ENDPATH**/ ?>