@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card border-0 shadow-sm text-center">

                <div class="card-body p-5">

                    <i class="bi bi-hourglass-split text-warning display-4"></i>

                    <h2 class="fw-bold mt-4">
                        طلب المنظمة قيد المراجعة
                    </h2>

                    <p class="text-muted mt-3">
                        تم تسجيل منظمتك بنجاح.
                        طلب التسجيل حاليًا قيد المراجعة من إدارة المنصة.
                    </p>

                    <div class="alert alert-warning mt-4">
                        لا يمكنك الدخول إلى مساحة المنظمة حتى تتم الموافقة على طلبك.
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button class="btn btn-outline-secondary">
                            تسجيل الخروج
                        </button>
                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection