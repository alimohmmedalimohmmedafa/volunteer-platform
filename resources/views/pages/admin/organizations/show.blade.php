@extends('layouts.admin')

@section('title', 'تفاصيل المنظمة')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.organizations.index') }}" class="btn btn-link mb-2 px-0">
        <i class="bi bi-arrow-right"></i>
        العودة إلى المنظمات
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row g-4 align-items-start">

            <div class="col-md-3 text-center">
                @if($organization->logo)
                    <img
                        src="{{ asset('storage/' . $organization->logo) }}"
                        alt="{{ $organization->name }}"
                        class="img-fluid rounded"
                        style="max-height: 180px;"
                    >
                @else
                    <div class="bg-light rounded p-5">
                        <i class="bi bi-building display-4 text-muted"></i>
                    </div>
                @endif
            </div>

            <div class="col-md-9">

                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <h2 class="fw-bold mb-0">{{ $organization->name }}</h2>

                    @if($organization->status === 'pending')
                        <span class="status-badge status-pending">
                            <i class="bi bi-hourglass-split"></i>
                            قيد المراجعة
                        </span>
                    @elseif($organization->status === 'approved')
                        <span class="status-badge status-accepted">
                            <i class="bi bi-check-circle"></i>
                            معتمدة
                        </span>
                    @else
                        <span class="status-badge status-rejected">
                            <i class="bi bi-x-circle"></i>
                            مرفوضة
                        </span>
                    @endif

                </div>

                <p class="text-muted">{{ $organization->bio }}</p>

                <div class="row g-3 mt-1">

                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="info-label">
                                <i class="bi bi-envelope ms-1"></i>
                                البريد
                            </span>
                            <span class="info-value">{{ $organization->email }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="info-label">
                                <i class="bi bi-telephone ms-1"></i>
                                الهاتف
                            </span>
                            <span class="info-value">{{ $organization->phone }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="info-label">
                                <i class="bi bi-geo-alt ms-1"></i>
                                المدينة
                            </span>
                            <span class="info-value">{{ $organization->city }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="info-label">
                                <i class="bi bi-globe2 ms-1"></i>
                                الدولة
                            </span>
                            <span class="info-value">{{ $organization->country }}</span>
                        </div>
                    </div>

                    @if($organization->website)
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-link-45deg ms-1"></i>
                                    الموقع
                                </span>
                                <span class="info-value">
                                    <a href="{{ $organization->website }}" target="_blank">{{ $organization->website }}</a>
                                </span>
                            </div>
                        </div>
                    @endif

                    @if($organization->approved_by)
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-person-check ms-1"></i>
                                    المشرف
                                </span>
                                <span class="info-value">
                                    {{ $organization->approver?->name ?? 'المدير' }} ·
                                    {{ $organization->approved_at?->format('Y-m-d') }}
                                </span>
                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>
</div>

@if($organization->status === 'pending')

    <div class="card border-0 shadow-sm">
        <div class="card-body d-flex flex-wrap align-items-center gap-2 justify-content-between">
            <div>
                <h5 class="fw-bold mb-1">مراجعة الطلب</h5>
                <p class="text-muted mb-0">هل ترغب في اعتماد هذه المنظمة أم رفض طلبها؟</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="bi bi-check-circle me-1"></i>
                    الموافقة
                </button>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-circle me-1"></i>
                    رفض
                </button>
            </div>
        </div>
    </div>

@elseif($organization->status === 'rejected')

    <div class="card border-0 shadow-sm">
        <div class="card-body d-flex flex-wrap align-items-center gap-2 justify-content-between">
            <div>
                <h5 class="fw-bold mb-1">منظمة مرفوضة</h5>
                <p class="text-muted mb-0">يمكنك إعادة اعتماد هذه المنظمة في أي وقت.</p>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                <i class="bi bi-check-circle me-1"></i>
                إعادة الموافقة
            </button>
        </div>
    </div>

@endif

{{-- Approve modal --}}
<div class="modal fade confirm-modal" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body text-center pb-4">
                <div class="modal-icon accept mb-3">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h5 class="fw-bold">تأكيد الموافقة</h5>
                <p class="text-muted mb-4">هل أنت متأكد من الموافقة على منظمة «{{ $organization->name }}»؟</p>
                <form method="POST" action="{{ route('admin.organizations.approve', $organization) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>
                        تأكيد الموافقة
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@if($organization->status === 'pending')
    {{-- Reject modal --}}
    <div class="modal fade confirm-modal" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center pb-4">
                    <div class="modal-icon reject mb-3">
                        <i class="bi bi-x-lg"></i>
                    </div>
                    <h5 class="fw-bold">تأكيد الرفض</h5>
                    <p class="text-muted mb-4">هل أنت متأكد من رفض منظمة «{{ $organization->name }}»؟</p>
                    <form method="POST" action="{{ route('admin.organizations.reject', $organization) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle me-1"></i>
                            تأكيد الرفض
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
