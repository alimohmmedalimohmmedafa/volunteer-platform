@extends('layouts.admin')

@section('title', 'إضافة موظف')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.employees.index') }}" class="btn btn-link mb-2 px-0">
        <i class="bi bi-arrow-right"></i>
        العودة إلى الموظفين
    </a>
    <h2 class="fw-bold mb-1">إضافة موظف جديد</h2>
    <p class="page-subtitle">
        سيتمكن الموظف من تسجيل الدخول بعد الإنشاء مباشرة.
    </p>
</div>

<div class="col-lg-10">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form method="POST" action="{{ route('admin.employees.store') }}">
                @csrf

                <h5 class="fw-bold mb-3">
                    <i class="bi bi-person-badge text-primary me-1"></i>
                    بيانات الموظف
                </h5>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="مثال: أحمد محمد"
                               class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="example@mail.com"
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control">
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        إنشاء الموظف
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
