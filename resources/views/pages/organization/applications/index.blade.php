@extends('layouts.organization')

@section('title', 'طلبات المتطوعين')

@section('content')

<div class="applications-toolbar">
    <div>
        <h1 class="fw-bold mb-1">طلبات المتطوعين</h1>
        <p class="text-muted mb-0">الطلبات المقدمة على فرص التطوع الخاصة بمنظمتك.</p>
    </div>
</div>

@if($applications->count())

    <div class="data-table-card">

        <div class="table-responsive">
            <table class="table align-middle mb-0">

                <thead>
                <tr>
                    <th>المتطوع</th>
                    <th>فرصة التطوع</th>
                    <th>تاريخ التقديم</th>
                    <th>الحالة</th>
                    <th>السيرة الذاتية</th>
                    <th class="text-end">إجراءات</th>
                </tr>
                </thead>

                <tbody>

                @foreach($applications as $application)

                    <tr>

                        <td data-label="المتطوع">
                            <div class="d-flex align-items-center gap-2">
                                @if($application->volunteer?->photo)
                                    <img src="{{ asset('storage/' . $application->volunteer->photo) }}"
                                         alt="{{ $application->volunteer->user->name }}"
                                         class="rounded-circle"
                                         style="width:40px;height:40px;object-fit:cover;">
                                @else
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary-subtle text-secondary fw-bold"
                                          style="width:40px;height:40px;">
                                        <i class="bi bi-person" aria-hidden="true"></i>
                                    </span>
                                @endif

                                <div>
                                    <a href="{{ route('organization.applications.volunteer-profile', $application) }}"
                                       class="fw-semibold text-decoration-none">
                                        {{ $application->volunteer?->user?->name ?? 'غير معروف' }}
                                    </a>
                                    <small class="text-muted">{{ $application->volunteer?->user?->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>

                        <td data-label="فرصة التطوع">
                            {{ $application->job->title }}
                        </td>

                        <td data-label="تاريخ التقديم">
                            {{ $application->applied_at?->format('d M Y') }}
                        </td>

                        <td data-label="الحالة">
                            <span class="status-badge status-{{ $application->status }}">
                                @if($application->status === 'pending')
                                    <i class="bi bi-hourglass-split" aria-hidden="true"></i> قيد المراجعة
                                @elseif($application->status === 'accepted')
                                    <i class="bi bi-check-circle" aria-hidden="true"></i> مقبول
                                @else
                                    <i class="bi bi-x-circle" aria-hidden="true"></i> مرفوض
                                @endif
                            </span>
                        </td>

                        <td data-label="السيرة الذاتية">
                            @if($application->cv)
                                <a href="{{ route('organization.applications.cv', $application) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i>
                                    عرض
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td data-label="إجراءات" class="text-lg-end">
                            @if($application->status === 'pending')

                                <div class="d-flex flex-wrap gap-2 table-actions">

                                    <button type="button"
                                            class="btn btn-sm btn-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#acceptApp-{{ $application->id }}">
                                        <i class="bi bi-check-lg me-1" aria-hidden="true"></i>
                                        قبول
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectApp-{{ $application->id }}">
                                        <i class="bi bi-x-lg me-1" aria-hidden="true"></i>
                                        رفض
                                    </button>

                                </div>

                            @elseif($application->status === 'accepted')
                                <span class="text-success small fw-semibold">
                                    <i class="bi bi-check-circle me-1" aria-hidden="true"></i> تم قبوله
                                </span>
                            @else
                                <span class="text-danger small fw-semibold">
                                    <i class="bi bi-x-circle me-1" aria-hidden="true"></i> تم رفضه
                                </span>
                            @endif
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>
        </div>

    </div>

    {{-- Confirmation modals (kept outside the table) --}}
    @foreach($applications as $application)

        <div class="modal fade confirm-modal" id="acceptApp-{{ $application->id }}" tabindex="-1"
             aria-labelledby="acceptAppLabel-{{ $application->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center p-4">
                        <div class="modal-icon accept mb-3">
                            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                        </div>
                        <h5 class="fw-bold mb-2">قبول الطلب؟</h5>
                        <p class="text-muted mb-4">
                            سيتم قبول طلب {{ $application->volunteer?->user?->name ?? 'المتطوع' }}
                            على فرصة "{{ $application->job->title }}".
                        </p>

                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                إلغاء
                            </button>

                            <form method="POST"
                                  action="{{ route('organization.applications.accept', $application) }}">
                                @csrf
                                @method('PATCH')
                                <textarea name="message" rows="4" class="form-control mb-3" required
                                          placeholder="اكتب رسالة القبول وتعليمات الحضور للمتطوع..."></textarea>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-lg me-1" aria-hidden="true"></i>
                                    تأكيد القبول وإرسال الرسالة
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade confirm-modal" id="rejectApp-{{ $application->id }}" tabindex="-1"
             aria-labelledby="rejectAppLabel-{{ $application->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center p-4">
                        <div class="modal-icon reject mb-3">
                            <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                        </div>
                        <h5 class="fw-bold mb-2">رفض الطلب؟</h5>
                        <p class="text-muted mb-4">
                            سيتم رفض طلب {{ $application->volunteer?->user?->name ?? 'المتطوع' }}
                            على فرصة "{{ $application->job->title }}".
                        </p>

                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                إلغاء
                            </button>

                            <form method="POST"
                                  action="{{ route('organization.applications.reject', $application) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-lg me-1" aria-hidden="true"></i>
                                    تأكيد الرفض
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endforeach

    <div class="mt-4">
        {{ $applications->links() }}
    </div>

@else

    <div class="panel">
        <div class="panel-body text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted" aria-hidden="true"></i>
            <h4 class="mt-3">لا توجد طلبات حتى الآن</h4>
            <p class="text-muted mb-0">لم تصل أي طلبات على فرص التطوع الخاصة بمنظمتك.</p>
        </div>
    </div>

@endif

@endsection