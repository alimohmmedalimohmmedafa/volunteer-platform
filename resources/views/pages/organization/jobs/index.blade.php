@extends('layouts.organization')

@section('title', 'فرص التطوع')

@section('content')

<div class="applications-toolbar">
    <div>
        <h1 class="fw-bold mb-1">فرص التطوع</h1>
        <p class="text-muted mb-0">إدارة فرص التطوع الخاصة بمنظمتك.</p>
    </div>

    <a href="{{ route('organization.jobs.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
        إضافة فرصة
    </a>
</div>

@if($jobs->count())

    <div class="data-table-card">

        <div class="table-responsive">
            <table class="table align-middle mb-0">

                <thead>
                <tr>
                    <th>الفرصة</th>
                    <th>الموقع</th>
                    <th>آخر موعد</th>
                    <th>الحالة</th>
                    <th>المتقدمون</th>
                    <th class="text-end">الإجراءات</th>
                </tr>
                </thead>

                <tbody>

                @foreach($jobs as $job)

                    <tr>

                        <td data-label="الفرصة">
                            <a href="{{ route('organization.jobs.show', $job) }}"
                               class="fw-semibold text-decoration-none d-inline-block">
                                {{ $job->title }}
                            </a>
                            <small class="text-muted d-block">{{ $job->start_date?->format('d M Y') }}</small>
                        </td>

                        <td data-label="الموقع">
                            <i class="bi bi-geo-alt text-muted me-1" aria-hidden="true"></i>
                            {{ $job->location }}
                        </td>

                        <td data-label="آخر موعد">
                            {{ $job->application_deadline?->format('d M Y') }}
                        </td>

                        <td data-label="الحالة">
                            <span class="status-badge status-{{ $job->status }}">
                                @if($job->status === 'published')
                                    <i class="bi bi-megaphone" aria-hidden="true"></i> منشورة
                                @elseif($job->status === 'completed')
                                    <i class="bi bi-check-circle" aria-hidden="true"></i> مكتملة
                                @else
                                    <i class="bi bi-x-circle" aria-hidden="true"></i> ملغاة
                                @endif
                            </span>
                        </td>

                        <td data-label="المتقدمون">
                            <span class="badge bg-secondary-subtle text-dark">
                                {{ $job->applications_count }}
                            </span>
                        </td>

                        <td data-label="الإجراءات" class="text-lg-end">
                            <div class="dropdown table-actions">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        aria-label="إجراءات لفرصة {{ $job->title }}">
                                    <i class="bi bi-three-dots-vertical" aria-hidden="true"></i>
                                    إجراءات
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('organization.jobs.show', $job) }}">
                                            <i class="bi bi-eye me-2" aria-hidden="true"></i>
                                            عرض التفاصيل
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('organization.jobs.edit', $job) }}">
                                            <i class="bi bi-pencil me-2" aria-hidden="true"></i>
                                            تعديل
                                        </a>
                                    </li>

                                    @if($job->status === 'published')
                                        <li><hr class="dropdown-divider"></li>

                                        <li>
                                            <form method="POST"
                                                  action="{{ route('organization.jobs.complete', $job) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="bi bi-check-circle me-2" aria-hidden="true"></i>
                                                    تحديد كمكتملة
                                                </button>
                                            </form>
                                        </li>

                                        <li>
                                            <form method="POST"
                                                  action="{{ route('organization.jobs.cancel', $job) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-x-circle me-2" aria-hidden="true"></i>
                                                    إلغاء الفرصة
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>
        </div>

    </div>

    <div class="mt-4">
        {{ $jobs->links() }}
    </div>

@else

    <div class="panel">
        <div class="panel-body text-center py-5">
            <i class="bi bi-briefcase fs-1 text-muted" aria-hidden="true"></i>
            <h4 class="mt-3">لا توجد فرص تطوع</h4>
            <p class="text-muted mb-3">لم تقم منظمتك بإنشاء أي فرصة تطوع حتى الآن.</p>
            <a href="{{ route('organization.jobs.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
                إضافة أول فرصة
            </a>
        </div>
    </div>

@endif

@endsection