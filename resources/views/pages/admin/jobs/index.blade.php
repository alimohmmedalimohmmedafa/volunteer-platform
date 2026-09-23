@extends('layouts.admin')

@section('title', 'إدارة الوظائف')

@section('content')

<div class="page-heading">
    <div>
        <h2 class="fw-bold mb-1">إدارة الوظائف</h2>
        <p class="page-subtitle">جميع فرص التطوع على المنصة</p>
    </div>
    <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>
        إنشاء وظيفة
    </a>
</div>

{{-- Organization filter --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.jobs.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">عرض حسب المنظمة</label>
                <select name="organization" class="form-select">
                    <option value="">جميع المنظمات</option>
                    @foreach($organizations as $organization)
                        <option value="{{ $organization->id }}"
                                {{ (string) $organizationId === (string) $organization->id ? 'selected' : '' }}>
                            {{ $organization->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-1"></i>
                    تصفية
                </button>
                @if($organizationId)
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">مسح</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="data-table-card">

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>الوظيفة</th>
                    <th>المنظمة</th>
                    <th>الموقع</th>
                    <th>الطلبات</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>

            <tbody>

                @forelse($jobs as $job)

                    <tr>

                        <td data-label="الوظيفة">
                            <strong>{{ $job->title }}</strong>
                            <div class="small text-muted">
                                تنتهي {{ $job->application_deadline?->format('Y-m-d') }}
                            </div>
                        </td>

                        <td data-label="المنظمة">{{ $job->organization->name }}</td>

                        <td data-label="الموقع">
                            <i class="bi bi-geo-alt ms-1 text-muted"></i>
                            {{ $job->location }}
                        </td>

                        <td data-label="الطلبات">
                            <span class="status-badge" style="background:#f1f5f9;color:#334155;">
                                <i class="bi bi-people"></i>
                                {{ $job->applications_count }}
                            </span>
                        </td>

                        <td data-label="الحالة">

                            @if($job->status === 'published')
                                <span class="status-badge status-published">
                                    <i class="bi bi-check-circle"></i>
                                    منشورة
                                </span>
                            @elseif($job->status === 'cancelled')
                                <span class="status-badge status-cancelled">
                                    <i class="bi bi-x-circle"></i>
                                    ملغاة
                                </span>
                            @else
                                <span class="status-badge status-completed">
                                    <i class="bi bi-check2-square"></i>
                                    مكتملة
                                </span>
                            @endif

                        </td>

                        <td data-label="الإجراءات">

                            <div class="table-actions d-flex gap-1 flex-wrap">

                                <a href="{{ route('jobs.show', $job) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="عرض">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('admin.jobs.edit', $job) }}"
                                   class="btn btn-sm btn-outline-secondary"
                                   title="تعديل">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                @if($job->status === 'published')

                                    <button class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#cancelJob{{ $job->id }}"
                                            title="إلغاء">
                                        <i class="bi bi-x-circle"></i>
                                    </button>

                                    <form method="POST" action="{{ route('admin.jobs.complete', $job) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success" title="إكمال">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-5 table-empty">
                            <i class="bi bi-briefcase display-5 text-muted"></i>
                            <p class="text-muted mt-3 mb-0">لا توجد وظائف.</p>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-4">
    {{ $jobs->links() }}
</div>

@foreach($jobs as $job)
    @if($job->status === 'published')
        <div class="modal fade confirm-modal" id="cancelJob{{ $job->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <div class="modal-body text-center pb-4">
                        <div class="modal-icon reject mb-3">
                            <i class="bi bi-x-lg"></i>
                        </div>
                        <h5 class="fw-bold">تأكيد الإلغاء</h5>
                        <p class="text-muted mb-4">هل تريد إلغاء وظيفة «{{ $job->title }}»؟</p>
                        <form method="POST" action="{{ route('admin.jobs.cancel', $job) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-circle me-1"></i>
                                تأكيد الإلغاء
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection
