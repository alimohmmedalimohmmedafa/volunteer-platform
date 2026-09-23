@extends('layouts.volunteer')

@section('title', 'رسائلي')

@section('content')
<div class="mb-4">
    <h1 class="fw-bold mb-1">رسائلي</h1>
    <p class="text-muted mb-0">رسائل المنظمات وتعليمات الحضور للفرص المقبولة.</p>
</div>

@if($messages->count())
    <div class="row g-3">
        @foreach($messages as $message)
            <div class="col-12">
                <a href="{{ route('volunteer.messages.show', $message) }}"
                   class="panel d-block text-decoration-none text-reset {{ $message->is_read ? '' : 'border-primary' }}">
                    <div class="panel-body">
                        <div class="d-flex flex-wrap justify-content-between gap-2">
                            <div>
                                <h2 class="h5 fw-bold mb-1">{{ $message->subject }}</h2>
                                <div class="text-muted small">
                                    {{ $message->organization->name }} · {{ $message->application->job->title }}
                                </div>
                            </div>
                            <div class="text-muted small">{{ $message->created_at->format('d M Y') }}</div>
                        </div>
                        <p class="mb-0 mt-3 text-muted">{{ \Illuminate\Support\Str::limit($message->message, 180) }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $messages->links() }}</div>
@else
    <div class="panel"><div class="panel-body text-center py-5">
        <i class="bi bi-envelope-open fs-1 text-muted" aria-hidden="true"></i>
        <h4 class="mt-3">لا توجد رسائل</h4>
        <p class="text-muted mb-0">ستظهر هنا رسائل المنظمات عند قبول طلباتك.</p>
    </div></div>
@endif
@endsection