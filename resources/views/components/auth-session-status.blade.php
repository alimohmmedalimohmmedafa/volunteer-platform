@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success alert-dismissible fade show']) }} role="alert">
        <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>
        <span>{{ $status }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
    </div>
@endif