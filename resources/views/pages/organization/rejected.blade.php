@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card border-0 shadow-sm text-center">

                <div class="card-body p-5">

                    <i class="bi bi-x-circle text-danger display-4"></i>

                    <h2 class="fw-bold mt-4">
                        تم رفض طلب المنظمة
                    </h2>

                    <p class="text-muted mt-3">
                        نعتذر، لم تتم الموافقة على طلب تسجيل منظمتك.
                        يمكنك التواصل مع إدارة المنصة لمعرفة سبب الرفض.
                    </p>

                    <div class="alert alert-danger mt-4">
                        لا يمكنك الدخول إلى مساحة المنظمة بعد رفض طلبك.
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