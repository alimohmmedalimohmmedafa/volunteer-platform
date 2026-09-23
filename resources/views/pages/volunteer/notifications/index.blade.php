@extends('layouts.volunteer')

@section('title', 'الإشعارات')

@section('content')

<div class="applications-toolbar">
    <div>
        <h1 class="fw-bold mb-1">الإشعارات</h1>
        <p class="text-muted mb-0">آخر الأخبار والتحديثات الخاصة بحسابك.</p>
    </div>

    @if(auth()->user()->notifications()->where('is_read', false)->exists())
        <form method="POST" action="{{ route('volunteer.notifications.readAll') }}">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-check2-all me-1" aria-hidden="true"></i>
                تحديد الكل كمقروء
            </button>
        </form>
    @endif
</div>

@if($notifications->count())

    <div class="panel">
        <div class="panel-body p-0">

            <ul class="list-unstyled mb-0">

                @foreach($notifications as $notification)

                    <li class="application-row p-3 p-lg-4">

                        <div class="ar-icon mx-auto mx-lg-0 mb-2 mb-lg-0"
                             style="{{ $notification->is_read ? '' : 'background:var(--primary-soft);color:var(--primary);' }}"
                             aria-hidden="true">
                            <i class="bi {{ $notification->is_read ? 'bi-envelope' : 'bi-bell-fill' }}"></i>
                        </div>

                        <div class="text-center text-lg-start">
                            <div class="ar-title d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-2">
                                {{ $notification->title }}

                                @if(!$notification->is_read)
                                    <span class="badge bg-primary">جديد</span>
                                @endif

                                @if($notification->type)
                                    <span class="badge bg-secondary">{{ $notification->type }}</span>
                                @endif
                            </div>

                            <div class="ar-meta justify-content-center justify-content-lg-start mt-1">
                                <span>{{ $notification->message }}</span>
                            </div>

                            <div class="small text-muted mt-1">
                                {{ $notification->created_at?->diffForHumans() }}
                            </div>
                        </div>

                        @if(!$notification->is_read)
                            <div class="text-center text-lg-end mt-2 mt-lg-0">
                                <form method="POST" action="{{ route('volunteer.notifications.read', $notification) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-primary text-nowrap">
                                        <i class="bi bi-check2 me-1" aria-hidden="true"></i>
                                        تحديد كمقروء
                                    </button>
                                </form>
                            </div>
                        @endif

                    </li>

                @endforeach

            </ul>

        </div>
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>

@else

    <div class="panel">
        <div class="panel-body text-center py-5">
            <i class="bi bi-bell-slash fs-1 text-muted" aria-hidden="true"></i>
            <h4 class="mt-3">لا توجد إشعارات</h4>
            <p class="text-muted mb-0">ستظهر هنا إشعارات حول طلباتك وفرص التطوع.</p>
        </div>
    </div>

@endif

@endsection