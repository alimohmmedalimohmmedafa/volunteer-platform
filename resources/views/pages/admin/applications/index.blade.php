@extends('layouts.admin')

@section('title', 'إدارة الطلبات')

@section('content')

<div class="page-heading">
    <div>
        <h2 class="fw-bold mb-1">إدارة الطلبات</h2>
        <p class="page-subtitle">جميع طلبات التطوع على المنصة</p>
    </div>
</div>

{{-- Status filter tabs --}}
<ul class="nav nav-pills mb-4 flex-wrap gap-2">
    <li class="nav-item">
        <a href="{{ route('admin.applications.index') }}"
           class="nav-link {{ is_null($status) ? 'active' : '' }}">
            الكل
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.applications.index', ['status' => 'pending']) }}"
           class="nav-link {{ $status === 'pending' ? 'active' : '' }}">
            قيد المراجعة
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.applications.index', ['status' => 'accepted']) }}"
           class="nav-link {{ $status === 'accepted' ? 'active' : '' }}">
            مقبولة
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.applications.index', ['status' => 'rejected']) }}"
           class="nav-link {{ $status === 'rejected' ? 'active' : '' }}">
            مرفوضة
        </a>
    </li>
</ul>

<div class="data-table-card">

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>المتطوع</th>
                    <th>الوظيفة</th>
                    <th>المنظمة</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>

            <tbody>

                @forelse($applications as $application)

                    <tr>

                        <td data-label="المتطوع">
                            <strong>{{ $application->volunteer?->user?->name ?? '—' }}</strong>
                            <div class="small text-muted">
                                {{ $application->volunteer?->user?->email }}
                            </div>
                        </td>

                        <td data-label="الوظيفة">{{ $application->job->title }}</td>

                        <td data-label="المنظمة">{{ $application->job->organization->name }}</td>

                        <td data-label="الحالة">

                            @if($application->status === 'pending')
                                <span class="status-badge status-pending">
                                    <i class="bi bi-hourglass-split"></i>
                                    قيد المراجعة
                                </span>
                            @elseif($application->status === 'accepted')
                                <span class="status-badge status-accepted">
                                    <i class="bi bi-check-circle"></i>
                                    مقبولة
                                </span>
                            @else
                                <span class="status-badge status-rejected">
                                    <i class="bi bi-x-circle"></i>
                                    مرفوضة
                                </span>
                            @endif

                        </td>

                        <td data-label="التاريخ">{{ $application->applied_at?->format('Y-m-d') }}</td>

                        <td data-label="الإجراءات">

                            <div class="table-actions d-flex gap-1 flex-wrap">

                                @if($application->cv)
                                    <a href="{{ route('admin.applications.cv', $application) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary"
                                       title="السيرة الذاتية">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                @endif

                                @if($application->status === 'pending')

                                    <button class="btn btn-sm btn-outline-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#acceptApp{{ $application->id }}"
                                            title="قبول">
                                        <i class="bi bi-check-lg"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectApp{{ $application->id }}"
                                            title="رفض">
                                        <i class="bi bi-x-lg"></i>
                                    </button>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-5 table-empty">
                            <i class="bi bi-file-earmark-text display-5 text-muted"></i>
                            <p class="text-muted mt-3 mb-0">لا توجد طلبات.</p>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-4">
    {{ $applications->links() }}
</div>

@foreach($applications as $application)
    @if($application->status === 'pending')

        <div class="modal fade confirm-modal" id="acceptApp{{ $application->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <div class="modal-body text-center pb-4">
                        <div class="modal-icon accept mb-3">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <h5 class="fw-bold">تأكيد القبول</h5>
                        <p class="text-muted mb-4">قبول طلب «{{ $application->volunteer?->user?->name ?? 'المتطوع' }}» لوظيفة «{{ $application->job->title }}»؟</p>
                        <form method="POST" action="{{ route('admin.applications.accept', $application) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i>
                                تأكيد القبول
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade confirm-modal" id="rejectApp{{ $application->id }}" tabindex="-1" aria-hidden="true">
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
                        <p class="text-muted mb-4">رفض طلب «{{ $application->volunteer?->user?->name ?? 'المتطوع' }}»؟</p>
                        <form method="POST" action="{{ route('admin.applications.reject', $application) }}">
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
@endforeach

@endsection
