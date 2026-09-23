@extends('layouts.admin')

@section('title', 'الرسائل')

@section('content')

<div class="page-heading">
    <div>
        <h2 class="fw-bold mb-1">الرسائل</h2>
        <p class="page-subtitle">
            رسائل اتصل بنا من الزوار
            @if($unreadCount)
                · <span class="badge bg-danger">{{ $unreadCount }} غير مقروءة</span>
            @endif
        </p>
    </div>
</div>

{{-- Filter tabs --}}
<ul class="nav nav-pills mb-4 flex-wrap gap-2">
    <li class="nav-item">
        <a href="{{ route('admin.messages.index') }}"
           class="nav-link {{ is_null($filter) ? 'active' : '' }}">
            الكل
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.messages.index', ['filter' => 'unread']) }}"
           class="nav-link {{ $filter === 'unread' ? 'active' : '' }}">
            غير المقروءة
        </a>
    </li>
</ul>

<div class="data-table-card">

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

                        <td data-label="المرسل">
                            <strong>{{ $message->name }}</strong>
                            <div class="small">
                                {{ $message->email }}
                                @if($message->phone)
                                    · {{ $message->phone }}
                                @endif
                            </div>
                        </td>

                        <td data-label="الموضوع">{{ $message->subject }}</td>

                        <td data-label="الحالة">

                            @if($message->is_read)
                                <span class="status-badge" style="background:#f1f5f9;color:#64748b;">
                                    <i class="bi bi-envelope-open"></i>
                                    مقروءة
                                </span>
                            @else
                                <span class="status-badge status-rejected">
                                    <i class="bi bi-envelope-fill"></i>
                                    غير مقروءة
                                </span>
                            @endif

                        </td>

                        <td data-label="التاريخ">{{ $message->created_at->format('Y-m-d H:i') }}</td>

                        <td data-label="">

                            <div class="table-actions d-flex gap-1 justify-content-end">

                                <a href="{{ route('admin.messages.show', $message) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-envelope-open me-1"></i>
                                    فتح
                                </a>

                                @unless($message->is_read)

                                    <form method="POST" action="{{ route('admin.messages.read', $message) }}">
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
                        <td colspan="5" class="text-center py-5 table-empty">
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
