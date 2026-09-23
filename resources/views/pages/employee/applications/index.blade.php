@extends('layouts.employee')

@section('title', 'الطلبات')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold mb-1">الطلبات</h2>
    <p class="text-muted mb-0">جميع طلبات التطوع على المنصة</p>
</div>

{{-- Status filter tabs --}}
<ul class="nav nav-pills mb-4">

    <li class="nav-item">
        <a href="{{ route('employee.applications.index') }}"
           class="nav-link {{ is_null($status) ? 'active' : '' }}">
            الكل
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('employee.applications.index', ['status' => 'pending']) }}"
           class="nav-link {{ $status === 'pending' ? 'active' : '' }}">
            قيد المراجعة
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('employee.applications.index', ['status' => 'accepted']) }}"
           class="nav-link {{ $status === 'accepted' ? 'active' : '' }}">
            مقبولة
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('employee.applications.index', ['status' => 'rejected']) }}"
           class="nav-link {{ $status === 'rejected' ? 'active' : '' }}">
            مرفوضة
        </a>
    </li>

</ul>

<div class="card border-0 shadow-sm">

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>
                    <th>المتطوع</th>
                    <th>الوظيفة</th>
                    <th>المنظمة</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th></th>
                </tr>

            </thead>

            <tbody>

                @forelse($applications as $application)

                    <tr>

                        <td>
                            <strong>{{ $application->volunteer?->user?->name ?? '—' }}</strong>
                            <div class="small text-muted">
                                {{ $application->volunteer?->user?->email }}
                            </div>
                        </td>

                        <td>{{ $application->job->title }}</td>

                        <td>{{ $application->job->organization->name }}</td>

                        <td>

                            @if($application->status === 'pending')

                                <span class="badge bg-warning text-dark">قيد المراجعة</span>

                            @elseif($application->status === 'accepted')

                                <span class="badge bg-success">مقبولة</span>

                            @else

                                <span class="badge bg-danger">مرفوضة</span>

                            @endif

                        </td>

                        <td>{{ $application->applied_at?->format('Y-m-d') }}</td>

                        <td>

                            @if($application->cv)

                                <a href="{{ route('employee.applications.cv', $application) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary"
                                   title="السيرة الذاتية">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center py-5">

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

@endsection