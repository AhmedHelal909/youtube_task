@extends('layouts.app')

@section('title', 'جمع الدورات التعليمية من يوتيوب')

@section('content')

{{-- ════════════════════════════════════════════════════════════════════════
    HERO / INPUT SECTION
════════════════════════════════════════════════════════════════════════ --}}
<section class="hero-section">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- Header text --}}
                <div class="text-center mb-4">
                    <h1 class="hero-title">جمع الدورات التعليمية من يوتيوب</h1>
                    <p class="hero-subtitle">
                        أدخل التصنيفات وانتظر أبدأ — النظام سيجمع الدورات تلقائياً باستخدام الذكاء الاصطناعي
                    </p>
                </div>

                {{-- Input card --}}
                <div class="input-card">
                    <form action="{{ route('courses.fetch') }}" method="POST" id="fetchForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label text-muted small">
                                أدخل التصنيفات (كل تصنيف في سطر جديد)
                            </label>
                            <textarea
                                name="categories"
                                id="categories"
                                class="form-control category-textarea @error('categories') is-invalid @enderror"
                                placeholder="التسويق&#10;البرمجة&#10;الجرافيكس&#10;الهندسة&#10;إدارة الأعمال"
                                rows="5"
                            >{{ old('categories') }}</textarea>

                            @error('categories')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Suggested categories --}}
                        <div class="suggested-chips mb-4">
                            @foreach(['التسويق','البرمجة','الجرافيكس','الهندسة','إدارة الأعمال'] as $suggestion)
                                <button type="button"
                                        class="chip"
                                        onclick="addCategory('{{ $suggestion }}')">
                                    {{ $suggestion }}
                                </button>
                            @endforeach
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
                {{-- /input card --}}

            </div>
        </div>
    </div>
</section>
{{-- /hero --}}


{{-- ════════════════════════════════════════════════════════════════════════
    COURSES SECTION
════════════════════════════════════════════════════════════════════════ --}}
<section class="courses-section">
    <div class="container">

        {{-- Section header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title">الدورات المكتشفة</h2>
            <span class="results-count">
                عرض أكثر من {{ $courses->total() }} دورة {{ $courses->total() > 5 ? '| 5 دقيقة' : '' }}
            </span>
        </div>

        {{-- ── Category Filter Tabs ────────────────────────────────────────── --}}
        <div class="category-tabs mb-4">
            <a href="{{ route('courses.index') }}"
               class="category-tab {{ !$selectedCategory ? 'active' : '' }}">
                الكل
                <span class="tab-count">{{ \App\Models\Course::count() }}</span>
            </a>

            @foreach($categories as $cat)
                <a href="{{ route('courses.index', ['category' => $cat]) }}"
                   class="category-tab {{ $selectedCategory === $cat ? 'active' : '' }}">
                    {{ $cat }}
                    <span class="tab-count">{{ \App\Models\Course::byCategory($cat)->count() }}</span>
                </a>
            @endforeach
        </div>

        {{-- ── Course Cards Grid ────────────────────────────────────────────── --}}
        @if($courses->isEmpty())
            <div class="empty-state text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="mt-3 text-muted">لا توجد دورات بعد. أدخل تصنيفات وابدأ الجمع!</p>
            </div>
        @else
            <div class="row g-3">
                @foreach($courses as $course)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        @include('courses._card', ['course' => $course])
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $courses->links('courses._pagination') }}
            </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
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
@endpush
