@extends('layouts.admin')

@section('title', 'لوحة تحكم المدير')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold mb-1">لوحة التحكم</h2>
    <p class="text-muted mb-0">
        مرحبًا بعودتك، {{ auth()->user()->name }}
    </p>
</div>

<div class="row g-4">

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 stat-label">إجمالي المتطوعين</p>
                    <h3 class="fw-bold mb-0 stat-value">{{ $stats['totalVolunteers'] }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card stat-org h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 stat-label">إجمالي المنظمات</p>
                    <h3 class="fw-bold mb-0 stat-value">{{ $stats['totalOrganizations'] }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-building"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card stat-job h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 stat-label">إجمالي الوظائف</p>
                    <h3 class="fw-bold mb-0 stat-value">{{ $stats['totalJobs'] }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-briefcase"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card stat-app h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 stat-label">إجمالي الطلبات</p>
                    <h3 class="fw-bold mb-0 stat-value">{{ $stats['totalApplications'] }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row g-4 mt-1">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm stat-card stat-pending h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 stat-label">منظمات قيد المراجعة</p>
                    <h3 class="fw-bold mb-0 stat-value">{{ $stats['pendingOrganizations'] }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm stat-card stat-accepted h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 stat-label">طلبات مقبولة</p>
                    <h3 class="fw-bold mb-0 stat-value">{{ $stats['acceptedApplications'] }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm stat-card stat-rejected h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 stat-label">طلبات مرفوضة</p>
                    <h3 class="fw-bold mb-0 stat-value">{{ $stats['rejectedApplications'] }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-x-circle"></i>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Quick actions --}}
<div class="row g-4 mt-1">

    <div class="col-md-3">
        <a href="{{ route('admin.organizations.create') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-plus-circle fs-3 text-primary"></i>
                    <p class="mt-2 mb-0 fw-bold text-dark">إضافة منظمة</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.employees.create') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-person-plus fs-3 text-success"></i>
                    <p class="mt-2 mb-0 fw-bold text-dark">إضافة موظف</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.jobs.create') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-briefcase fs-3 text-warning"></i>
                    <p class="mt-2 mb-0 fw-bold text-dark">إنشاء وظيفة</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.messages.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-envelope fs-3 text-danger"></i>
                    <p class="mt-2 mb-0 fw-bold text-dark">الرسائل</p>
                </div>
            </div>
        </a>
    </div>

</div>

@endsection
