@extends('layouts.employee')

@section('title', 'الوظائف')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold mb-1">الوظائف</h2>
    <p class="text-muted mb-0">جميع فرص التطوع على المنصة</p>
</div>

{{-- Organization filter --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-body py-3">

        <form method="GET"
              action="{{ route('employee.jobs.index') }}"
              class="row g-2 align-items-center">

            <div class="col-md-4">

                <label class="form-label mb-1">عرض حسب المنظمة</label>

                <select name="organization"
                        class="form-select">

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
                    تصفية
                </button>

                @if($organizationId)
                    <a href="{{ route('employee.jobs.index') }}"
                       class="btn btn-outline-secondary">
                        مسح
                    </a>
                @endif

            </div>

        </form>

    </div>

</div>

<div class="card border-0 shadow-sm">

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

                        <td>
                            <strong>{{ $job->title }}</strong>
                            <div class="small text-muted">
                                تنتهي {{ $job->application_deadline?->format('Y-m-d') }}
                            </div>
                        </td>

                        <td>{{ $job->organization->name }}</td>

                        <td>{{ $job->location }}</td>

                        <td>
                            <span class="badge bg-light text-dark">
                                {{ $job->applications_count }}
                            </span>
                        </td>

                        <td>

                            @if($job->status === 'published')

                                <span class="badge bg-success">منشورة</span>

                            @elseif($job->status === 'cancelled')

                                <span class="badge bg-danger">ملغاة</span>

                            @else

                                <span class="badge bg-secondary">مكتملة</span>

                            @endif

                        </td>

                        <td>

                            <div class="d-flex gap-1">

                                <a href="{{ route('jobs.show', $job) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="عرض">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('employee.jobs.edit', $job) }}"
                                   class="btn btn-sm btn-outline-secondary"
                                   title="تعديل">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                @if($job->status === 'published')

                                    <form method="POST"
                                          action="{{ route('employee.jobs.cancel', $job) }}"
                                          onsubmit="return confirm('هل تريد إلغاء هذه الوظيفة؟');">
                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-sm btn-outline-danger"
                                                title="إلغاء">
                                            <i class="bi bi-x-circle"></i>
                                        </button>

                                    </form>

                                    <form method="POST"
                                          action="{{ route('employee.jobs.complete', $job) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-sm btn-outline-success"
                                                title="إكمال">
                                            <i class="bi bi-check-lg"></i>
                                        </button>

                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center py-5">

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

@endsection