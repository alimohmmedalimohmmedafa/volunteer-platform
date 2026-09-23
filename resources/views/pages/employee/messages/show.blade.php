@extends('layouts.employee')

@section('title', 'رسالة من الموقع')

@section('content')

<div class="mb-4">

    <a href="{{ route('employee.messages.index') }}"
       class="btn btn-link mb-2 px-0">
        <i class="bi bi-arrow-right"></i>
        العودة إلى الرسائل
    </a>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        <h4 class="fw-bold mb-1">{{ $message->subject }}</h4>

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

@endsection