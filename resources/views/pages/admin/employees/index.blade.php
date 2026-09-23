@extends('layouts.admin')

@section('title', 'الموظفون')

@section('content')

<div class="page-heading">
    <div>
        <h2 class="fw-bold mb-1">الموظفون</h2>
        <p class="page-subtitle">حسابات الموظفين المشرفين على المنصة</p>
    </div>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i>
        إضافة موظف
    </a>
</div>

<div class="data-table-card">

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>الاسم</th>
                    <th>البريد</th>
                    <th>الحالة</th>
                    <th>تاريخ الإنشاء</th>
                </tr>
            </thead>

            <tbody>

                @forelse($employees as $employee)

                    <tr>

                        <td data-label="الاسم">
                            <div class="table-user">
                                <div class="tu-avatar">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <strong>{{ $employee->name }}</strong>
                            </div>
                        </td>

                        <td data-label="البريد">{{ $employee->email }}</td>

                        <td data-label="الحالة">

                            @if($employee->status === 'suspended')
                                <span class="status-badge status-rejected">
                                    <i class="bi bi-slash-circle"></i>
                                    معطّل
                                </span>
                            @else
                                <span class="status-badge status-accepted">
                                    <i class="bi bi-check-circle"></i>
                                    نشط
                                </span>
                            @endif

                        </td>

                        <td data-label="تاريخ الإنشاء">{{ $employee->created_at->format('Y-m-d') }}</td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="text-center py-5 table-empty">
                            <i class="bi bi-person-badge display-5 text-muted"></i>
                            <p class="text-muted mt-3 mb-0">لا يوجد موظفون بعد.</p>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-4">
    {{ $employees->links() }}
</div>

@endsection
