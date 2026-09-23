@extends('layouts.volunteer')

@section('title', $message->subject)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="fw-bold mb-1">{{ $message->subject }}</h1>
        <p class="text-muted mb-0">رسالة من {{ $message->organization->name }}</p>
    </div>
    <a href="{{ route('volunteer.messages.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right me-1" aria-hidden="true"></i> العودة إلى الرسائل
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-body">
                <div class="mb-4">
                    <div class="text-muted small mb-1">منظمة</div>
                    <div class="fw-semibold">{{ $message->organization->name }}</div>
                </div>
                <div class="mb-4">
                    <div class="text-muted small mb-1">الفرصة</div>
                    <div class="fw-semibold">{{ $message->application->job->title }}</div>
                </div>
                <div class="mb-4">
                    <div class="text-muted small mb-1">تاريخ الفرصة</div>
                    <div class="fw-semibold">{{ $message->application->job->start_date?->format('d M Y') }}</div>
                </div>
                <hr>
                <div class="mt-4" style="white-space: pre-line;">{{ $message->message }}</div>
            </div>
        </div>
    </div>
</div>
@endsection