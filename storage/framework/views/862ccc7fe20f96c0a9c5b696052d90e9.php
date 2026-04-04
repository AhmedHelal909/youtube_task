<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'جمع الدورات التعليمية من يوتيوب'); ?></title>

    
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap"
          rel="stylesheet">

    
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">

    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body>

    
    <nav class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo e(route('courses.index')); ?>">
                <i class="bi bi-play-circle-fill text-danger me-1"></i>
                YouTube Courses
            </a>
        </div>
    </nav>

    
    <?php if(session('success')): ?>
        <div class="alert-banner alert-banner--success">
            <div class="container d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                <?php echo e(session('success')); ?>

            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert-banner alert-banner--danger">
            <div class="container d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo e(session('error')); ?>

            </div>
        </div>
    <?php endif; ?>

    
    <?php echo $__env->yieldContent('content'); ?>

    <footer class="footer mt-5 py-4 text-center text-muted small">
        <div class="container">
            YouTube Course Scraper &mdash; Powered by Anthropic Claude + YouTube Data API v3
        </div>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\wamp64\www\taaaaaaaaaaaaaaama\resources\views/layouts/app.blade.php ENDPATH**/ ?>