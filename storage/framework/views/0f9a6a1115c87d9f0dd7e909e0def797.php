<?php $__env->startSection('title', 'جمع الدورات التعليمية من يوتيوب'); ?>

<?php $__env->startSection('content'); ?>


<section class="hero-section">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-10">

                
                <div class="text-center mb-4">
                    <h1 class="hero-title">جمع الدورات التعليمية من يوتيوب</h1>
                    <p class="hero-subtitle">
                        أدخل التصنيفات وانتظر أبدأ — النظام سيجمع الدورات تلقائياً باستخدام الذكاء الاصطناعي
                    </p>
                </div>

                
                <div class="input-card">
                    <form action="<?php echo e(route('courses.fetch')); ?>" method="POST" id="fetchForm">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label class="form-label text-muted small">
                                أدخل التصنيفات (كل تصنيف في سطر جديد)
                            </label>
                            <textarea
                                name="categories"
                                id="categories"
                                class="form-control category-textarea <?php $__errorArgs = ['categories'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="التسويق&#10;البرمجة&#10;الجرافيكس&#10;الهندسة&#10;إدارة الأعمال"
                                rows="5"
                            ><?php echo e(old('categories')); ?></textarea>

                            <?php $__errorArgs = ['categories'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="suggested-chips mb-4">
                            <?php $__currentLoopData = ['التسويق','البرمجة','الجرافيكس','الهندسة','إدارة الأعمال']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $suggestion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button"
                                        class="chip"
                                        onclick="addCategory('<?php echo e($suggestion); ?>')">
                                    <?php echo e($suggestion); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="d-flex gap-2 align-items-center">
                            <button type="submit"
                                    class="btn btn-fetch"
                                    id="fetchBtn">
                                <i class="bi bi-play-fill me-1"></i>
                                إبدأ الجمع
                            </button>
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm"
                                    onclick="document.getElementById('categories').value = ''">
                                <i class="bi bi-x me-1"></i>
                                إيقاف
                            </button>
                        </div>

                    </form>
                </div>
                

            </div>
        </div>
    </div>
</section>




<section class="courses-section">
    <div class="container">

        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title">الدورات المكتشفة</h2>
            <span class="results-count">
                عرض أكثر من <?php echo e($courses->total()); ?> دورة <?php echo e($courses->total() > 5 ? '| 5 دقيقة' : ''); ?>

            </span>
        </div>

        
        <div class="category-tabs mb-4">
            <a href="<?php echo e(route('courses.index')); ?>"
               class="category-tab <?php echo e(!$selectedCategory ? 'active' : ''); ?>">
                الكل
                <span class="tab-count"><?php echo e(\App\Models\Course::count()); ?></span>
            </a>

            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('courses.index', ['category' => $cat])); ?>"
                   class="category-tab <?php echo e($selectedCategory === $cat ? 'active' : ''); ?>">
                    <?php echo e($cat); ?>

                    <span class="tab-count"><?php echo e(\App\Models\Course::byCategory($cat)->count()); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php if($courses->isEmpty()): ?>
            <div class="empty-state text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="mt-3 text-muted">لا توجد دورات بعد. أدخل تصنيفات وابدأ الجمع!</p>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <?php echo $__env->make('courses._card', ['course' => $course], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($courses->links('courses._pagination')); ?>

            </div>
        <?php endif; ?>

    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/**
 * Add a category chip text to the textarea.
 * Prevents duplicates and keeps formatting clean.
 */
function addCategory(name) {
    const ta = document.getElementById('categories');
    const existing = ta.value.split('\n').map(s => s.trim()).filter(Boolean);

    if (!existing.includes(name)) {
        ta.value = existing.length > 0
            ? existing.join('\n') + '\n' + name
            : name;
    }
}

/**
 * Show a loading state on the fetch button to prevent double-submit.
 */
document.getElementById('fetchForm').addEventListener('submit', function () {
    const btn = document.getElementById('fetchBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> جاري الجمع…';
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\taaaaaaaaaaaaaaama\resources\views/courses/index.blade.php ENDPATH**/ ?>