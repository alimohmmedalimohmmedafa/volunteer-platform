@extends('layouts.admin')

@section('title', 'رسالة من الموقع')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.messages.index') }}" class="btn btn-link mb-2 px-0">
        <i class="bi bi-arrow-right"></i>
        العودة إلى الرسائل
    </a>
</div>

<div class="col-lg-10">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                <h4 class="fw-bold mb-0">{{ $message->subject }}</h4>
                @if($message->is_read)
                    <span class="status-badge" style="background:#f1f5f9;color:#64748b;">
                        <i class="bi bi-envelope-open"></i>
                        مقروءة
                    </span>
                @else
                    <span class="status-badge status-rejected">
                        <i class="bi bi-envelope-fill"></i>
                        غير مقروءة
                    </span>
                @endif
            </div>

            <p class="text-muted mb-3">
                من {{ $message->name }} · {{ $message->email }}
                @if($message->phone)
                    · {{ $message->phone }}
                @endif
                · {{ $message->created_at->format('Y-m-d H:i') }}
            </p>

            <hr>

            <p class="mb-0" style="white-space: pre-line;">
                {{ $message->message }}
            </p>

        </div>
    </div>
</div>

@endsection
