@extends('layouts.employee')

@section('title', 'لوحة تحكم الموظف')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold mb-1">لوحة التحكم</h2>
    <p class="text-muted mb-0">
        مرحبًا بعودتك، {{ auth()->user()->name }}
    </p>
</div>

<div class="row g-4">

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">المنظمات</p>
                    <h3 class="fw-bold mb-0">{{ $stats['totalOrganizations'] }}</h3>
                </div>
                <i class="bi bi-building display-5 text-info"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">الوظائف</p>
                    <h3 class="fw-bold mb-0">{{ $stats['totalJobs'] }}</h3>
                </div>
                <i class="bi bi-briefcase display-5 text-warning"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">المتطوعون</p>
                    <h3 class="fw-bold mb-0">{{ $stats['totalVolunteers'] }}</h3>
                </div>
                <i class="bi bi-people display-5 text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">الطلبات</p>
                    <h3 class="fw-bold mb-0">{{ $stats['totalApplications'] }}</h3>
                </div>
                <i class="bi bi-file-earmark-text display-5 text-secondary"></i>
            </div>
        </div>
    </div>

</div>

<div class="row g-4 mt-1">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">رسائل غير مقروءة</p>
                    <h3 class="fw-bold mb-0">{{ $stats['unreadMessages'] }}</h3>
                </div>
                <i class="bi bi-envelope display-5 text-danger"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <a href="{{ route('employee.organizations.index') }}"
                   class="text-decoration-none">
                    <i class="bi bi-building fs-3 text-primary"></i>
                    <p class="mt-2 mb-0 fw-bold text-dark">المنظمات</p>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <a href="{{ route('employee.messages.index') }}"
                   class="text-decoration-none">
                    <i class="bi bi-envelope fs-3 text-danger"></i>
                    <p class="mt-2 mb-0 fw-bold text-dark">الرسائل</p>
                </a>
            </div>
        </div>
    </div>

</div>

@endsection