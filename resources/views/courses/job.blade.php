@extends('layouts.app')

@section('title', 'Fetch Job #' . $job->id)

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="section-title mb-0">سجل الجلب #{{ $job->id }}</h1>
        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-right me-1"></i> العودة
        </a>
    </div>

    {{-- Summary cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card stat-card--blue">
                <div class="stat-label">الحالة</div>
                <div class="stat-value">
                    @switch($job->status)
                        @case('completed') <span class="text-success">مكتمل ✓</span> @break
                        @case('running')   <span class="text-warning">يعمل…</span>   @break
                        @case('failed')    <span class="text-danger">فشل ✗</span>    @break
                        @default           {{ $job->status }}
                    @endswitch
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card--green">
                <div class="stat-label">تم الحفظ</div>
                <div class="stat-value">{{ $job->total_saved }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card--yellow">
                <div class="stat-label">تم التخطي (مكرر)</div>
                <div class="stat-value">{{ $job->total_skipped }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card--red">
                <div class="stat-label">الأخطاء</div>
                <div class="stat-value">{{ $job->total_errors }}</div>
            </div>
        </div>
    </div>

    {{-- Categories processed --}}
    <div class="mb-4">
        <h5 class="text-muted mb-2">التصنيفات:</h5>
        <div class="d-flex gap-2 flex-wrap">
            @foreach($job->categories as $cat)
                <span class="badge bg-secondary">{{ $cat }}</span>
            @endforeach
        </div>
    </div>

    {{-- Log output --}}
    <div class="job-log">
        <h5 class="mb-3"><i class="bi bi-terminal me-2"></i>السجل التفصيلي</h5>
        <div class="log-output">
            @forelse($job->log ?? [] as $line)
                <div class="log-line {{ str_contains($line, 'ERROR') || str_contains($line, '✗') ? 'log-line--error' : (str_contains($line, '+') ? 'log-line--success' : (str_contains($line, '~') ? 'log-line--skip' : '')) }}">
                    {{ $line }}
                </div>
            @empty
                <div class="text-muted">لا يوجد سجل.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
