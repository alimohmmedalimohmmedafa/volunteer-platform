@extends('layouts.employee')

@section('title', 'الرسائل')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold mb-1">الرسائل</h2>
    <p class="text-muted mb-0">
        رسائل اتصل بنا من الزوار
        @if($unreadCount)
            · <span class="badge bg-danger">{{ $unreadCount }} غير مقروءة</span>
        @endif
    </p>
</div>

{{-- Filter tabs --}}
<ul class="nav nav-pills mb-4">

    <li class="nav-item">
        <a href="{{ route('employee.messages.index') }}"
           class="nav-link {{ is_null($filter) ? 'active' : '' }}">
            الكل
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('employee.messages.index', ['filter' => 'unread']) }}"
           class="nav-link {{ $filter === 'unread' ? 'active' : '' }}">
            غير المقروءة
        </a>
    </li>

</ul>

<div class="card border-0 shadow-sm">

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>
                    <th>المرسل</th>
                    <th>الموضوع</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th></th>
                </tr>

            </thead>

            <tbody>

                @forelse($messages as $message)

                    <tr class="{{ $message->is_read ? 'text-muted' : 'fw-semibold' }}">

                        <td>
                            <strong>{{ $message->name }}</strong>
                            <div class="small">
                                {{ $message->email }}
                                @if($message->phone)
                                    · {{ $message->phone }}
                                @endif
                            </div>
                        </td>

                        <td>{{ $message->subject }}</td>

                        <td>

                            @if($message->is_read)

                                <span class="badge bg-light text-dark">مقروءة</span>

                            @else

                                <span class="badge bg-danger">غير مقروءة</span>

                            @endif

                        </td>

                        <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>

                        <td>

                            <div class="d-flex gap-1">

                                <a href="{{ route('employee.messages.show', $message) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    فتح
                                </a>

                                @unless($message->is_read)

                                    <form method="POST"
                                          action="{{ route('employee.messages.read', $message) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-sm btn-outline-secondary"
                                                title="تحديد كمقروءة">
                                            <i class="bi bi-check2-square"></i>
                                        </button>

                                    </form>

                                @endunless

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center py-5">

                            <i class="bi bi-envelope display-5 text-muted"></i>

                            <p class="text-muted mt-3 mb-0">لا توجد رسائل.</p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-4">
    {{ $messages->links() }}
</div>

@endsection