@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')

@section('content')

<div class="page-heading">
    <div>
        <h2 class="fw-bold mb-1">إدارة المستخدمين</h2>
        <p class="page-subtitle">جميع حسابات المنصة وأدوارها</p>
    </div>
</div>

{{-- Search + role filter --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">البحث بالاسم أو البريد</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="مثال: أحمد">
            </div>
            <div class="col-md-4">
                <label class="form-label mb-1">الدور</label>
                <select name="role" class="form-select">
                    <option value="">جميع الأدوار</option>
                    <option value="volunteer" {{ $role === 'volunteer' ? 'selected' : '' }}>متطوع</option>
                    <option value="organization" {{ $role === 'organization' ? 'selected' : '' }}>منظمة</option>
                    <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>مدير</option>
                    <option value="employee" {{ $role === 'employee' ? 'selected' : '' }}>موظف</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>
                    بحث
                </button>
                @if($search !== '' || $role)
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">مسح</a>
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
                    <th>الاسم</th>
                    <th>البريد</th>
                    <th>الدور</th>
                    <th>الحالة</th>
                    <th>تاريخ التسجيل</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>

            <tbody>

                @forelse($users as $user)

                    <tr>

                        <td data-label="الاسم">
                            <div class="table-user">
                                <div class="tu-avatar">
                                    <i class="bi bi-person"></i>
                                </div>
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>

                        <td data-label="البريد">{{ $user->email }}</td>

                        <td data-label="الدور">

                            @if($user->role === 'admin')
                                <span class="status-badge" style="background:#1f2937;color:#fff;">
                                    <i class="bi bi-shield-fill-check"></i>
                                    مدير
                                </span>
                            @elseif($user->role === 'organization')
                                <span class="status-badge" style="background:#dbeafe;color:#1d4ed8;">
                                    <i class="bi bi-building"></i>
                                    منظمة
                                </span>
                            @elseif($user->role === 'employee')
                                <span class="status-badge" style="background:#e2e8f0;color:#334155;">
                                    <i class="bi bi-person-badge"></i>
                                    موظف
                                </span>
                            @else
                                <span class="status-badge" style="background:var(--primary-soft);color:var(--primary);">
                                    <i class="bi bi-person-heart"></i>
                                    متطوع
                                </span>
                            @endif

                        </td>

                        <td data-label="الحالة">

                            @if($user->status === 'suspended')
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

                        <td data-label="تاريخ التسجيل">{{ $user->created_at->format('Y-m-d') }}</td>

                        <td data-label="الإجراءات">

                            <div class="table-actions">

                                @if($user->role === 'admin')
                                    <span class="text-muted small">—</span>
                                @elseif($user->status === 'suspended')

                                    <form method="POST" action="{{ route('admin.users.activate', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-unlock me-1"></i>
                                            تفعيل
                                        </button>
                                    </form>

                                @else

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#suspendUser{{ $user->id }}">
                                        <i class="bi bi-slash-circle me-1"></i>
                                        تعطيل
                                    </button>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-5 table-empty">
                            <i class="bi bi-people display-5 text-muted"></i>
                            <p class="text-muted mt-3 mb-0">لا يوجد مستخدمون مطابقون.</p>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-4">
    {{ $users->links() }}
</div>

@foreach($users as $user)
    @if($user->role !== 'admin' && $user->status !== 'suspended')
        <div class="modal fade confirm-modal" id="suspendUser{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <div class="modal-body text-center pb-4">
                        <div class="modal-icon reject mb-3">
                            <i class="bi bi-slash-circle"></i>
                        </div>
                        <h5 class="fw-bold">تأكيد التعطيل</h5>
                        <p class="text-muted mb-4">هل تريد تعطيل حساب «{{ $user->name }}»؟</p>
                        <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-slash-circle me-1"></i>
                                تأكيد التعطيل
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection
