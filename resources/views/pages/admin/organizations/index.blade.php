@extends('layouts.admin')

@section('title', 'إدارة المنظمات')

@section('content')

<div class="page-heading">
    <div>
        <h2 class="fw-bold mb-1">إدارة المنظمات</h2>
        <p class="page-subtitle">مراجعة وإدارة منظمات المنصة</p>
    </div>
    <a href="{{ route('admin.organizations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>
        إضافة منظمة
    </a>
</div>

{{-- Status filter tabs --}}
<ul class="nav nav-pills mb-4 flex-wrap gap-2">
    <li class="nav-item">
        <a href="{{ route('admin.organizations.index') }}"
           class="nav-link {{ is_null($status) ? 'active' : '' }}">
            الكل
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.organizations.index', ['status' => 'pending']) }}"
           class="nav-link {{ $status === 'pending' ? 'active' : '' }}">
            قيد المراجعة
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.organizations.index', ['status' => 'approved']) }}"
           class="nav-link {{ $status === 'approved' ? 'active' : '' }}">
            معتمدة
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.organizations.index', ['status' => 'rejected']) }}"
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
                    <th>المنظمة</th>
                    <th>البريد</th>
                    <th>المدينة</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>

                @forelse($organizations as $organization)

                    <tr>

                        <td data-label="المنظمة">
                            <div class="table-user">
                                <div class="tu-avatar">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div>
                                    <strong>{{ $organization->name }}</strong>
                                    @if($organization->approved_by)
                                        <div class="small text-muted">
                                            <i class="bi bi-person-check"></i>
                                            أضافها المدير
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td data-label="البريد">{{ $organization->email }}</td>

                        <td data-label="المدينة">{{ $organization->city }}</td>

                        <td data-label="الحالة">

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

                        </td>

                        <td data-label="التاريخ">{{ $organization->created_at->format('Y-m-d') }}</td>

                        <td data-label="">
                            <div class="table-actions">
                                <a href="{{ route('admin.organizations.show', $organization) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                    عرض
                                </a>
                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-5 table-empty">
                            <i class="bi bi-building display-5 text-muted"></i>
                            <p class="text-muted mt-3 mb-0">لا توجد منظمات.</p>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-4">
    {{ $organizations->links() }}
</div>

@endsection
