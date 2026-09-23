@extends('layouts.employee')

@section('title', 'مراجعة منظمة')

@section('content')

<div class="mb-4">

    <a href="{{ route('employee.organizations.index') }}"
       class="btn btn-link mb-2 px-0">
        <i class="bi bi-arrow-right"></i>
        العودة إلى المنظمات
    </a>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        <div class="row">

            <div class="col-md-3 text-center">

                @if($organization->logo)

                    <img
                        src="{{ asset('storage/' . $organization->logo) }}"
                        alt="{{ $organization->name }}"
                        class="img-fluid rounded"
                        style="max-height: 180px;"
                    >

                @else

                    <div class="bg-light rounded p-5">

                        <i class="bi bi-building display-4 text-muted"></i>

                    </div>

                @endif

            </div>

            <div class="col-md-9">

                <h2 class="fw-bold">{{ $organization->name }}</h2>

                <p class="text-muted">{{ $organization->bio }}</p>

                <div class="mb-2">
                    <strong>البريد:</strong> {{ $organization->email }}
                </div>

                <div class="mb-2">
                    <strong>الهاتف:</strong> {{ $organization->phone }}
                </div>

                <div class="mb-2">
                    <strong>المدينة:</strong> {{ $organization->city }}
                </div>

                <div class="mb-2">
                    <strong>الدولة:</strong> {{ $organization->country }}
                </div>

                @if($organization->website)
                    <div class="mb-2">
                        <strong>الموقع:</strong>
                        <a href="{{ $organization->website }}" target="_blank">
                            {{ $organization->website }}
                        </a>
                    </div>
                @endif

                <div class="mb-3">

                    <strong>الحالة:</strong>

                    @if($organization->status === 'pending')

                        <span class="badge bg-warning text-dark">قيد المراجعة</span>

                    @elseif($organization->status === 'approved')

                        <span class="badge bg-success">معتمدة</span>

                    @else

                        <span class="badge bg-danger">مرفوضة</span>

                    @endif

                </div>

                @if($organization->status === 'pending')

                    <div class="d-flex gap-2">

                        <form method="POST"
                              action="{{ route('employee.organizations.approve', $organization) }}">
                            @csrf
                            @method('PATCH')

                            <button class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i>
                                الموافقة
                            </button>

                        </form>

                        <form method="POST"
                              action="{{ route('employee.organizations.reject', $organization) }}"
                              onsubmit="return confirm('هل أنت متأكد من رفض هذه المنظمة؟');">
                            @csrf
                            @method('PATCH')

                            <button class="btn btn-danger">
                                <i class="bi bi-x-circle me-1"></i>
                                رفض
                            </button>

                        </form>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection