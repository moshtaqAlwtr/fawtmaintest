@extends('master')

@section('title')
    الإشعارات المرسلة
@stop

@section('content')

    <!-- Card Container -->
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <!-- Card Header with Gradient Background -->
        <div class="card-header bg-gradient-primary text-white py-3 rounded-top">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-2 mb-md-0">
                    <h4 class="mb-1 text-white">
                        <i class="fas fa-paper-plane me-2"></i> الإشعارات المرسلة
                    </h4>
                    <p class="mb-0 text-white-50 small">عرض جميع الإشعارات التي قمت بإرسالها</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('notifications.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-bell me-1"></i> الإشعارات الواردة
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body p-0">
            @if ($notifications->isEmpty())
                <!-- Empty State -->
                <div class="text-center p-5 bg-light rounded-3 m-3">
                    <div class="mb-3">
                        <i class="fas fa-paper-plane text-muted opacity-25" style="font-size: 3.5rem;"></i>
                    </div>
                    <h5 class="text-muted mb-2">لا توجد إشعارات مرسلة</h5>
                    <p class="text-muted mb-4">لم تقم بإرسال أي إشعارات حتى الآن</p>
                </div>
            @else
                <!-- Notifications List -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 rounded-start" style="min-width: 220px;">المستلم</th>
                                <th>محتوى الإشعار</th>
                                <th class="text-center" style="min-width: 120px;">التاريخ</th>
                                <th class="text-center rounded-end" style="min-width: 100px;">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notifications as $notification)
                                <tr>
                                    <!-- Receiver Column -->
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                @if ($notification->receiver)
                                                    <img src="{{ asset('assets/img/avatars/default.png') }}"
                                                        alt="{{ $notification->receiver->name }}" class="rounded-circle border"
                                                        onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><rect width=\'100%\' height=\'100%\' fill=\'%23f8f9fa\'/><text x=\'50%\' y=\'50%\' font-size=\'40\' text-anchor=\'middle\' dominant-baseline=\'middle\' fill=\'%236c757d\'>{{ substr($notification->receiver->name, 0, 1) }}</text></svg>'">
                                                @else
                                                    <span class="avatar-initial rounded-circle bg-secondary text-white">
                                                        ?
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $notification->receiver->name ?? 'المستخدم غير موجود' }}</h6>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Notification Content -->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 fw-semibold">{{ $notification->title ?? 'بدون عنوان' }}</h6>
                                            <p class="mb-0 text-muted pe-2" style="max-width: 350px;">
                                                {{ $notification->description ?? 'لا توجد تفاصيل' }}
                                            </p>
                                        </div>
                                    </td>

                                    <!-- Date Column -->
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark" data-bs-toggle="tooltip"
                                            title="{{ $notification->created_at->format('Y/m/d h:i A') }}">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </td>

                                    <!-- Status Column -->
                                    <td class="text-center">
                                        @if ($notification->read)
                                            <span class="badge bg-success">مقروء</span>
                                        @else
                                            <span class="badge bg-warning">غير مقروء</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Card Footer - Pagination -->
        @if ($notifications->hasPages())
            <div class="card-footer bg-transparent border-0 py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        <p class="mb-0 text-muted small">
                            عرض <span class="fw-bold">{{ $notifications->firstItem() }}</span> إلى
                            <span class="fw-bold">{{ $notifications->lastItem() }}</span> من
                            <span class="fw-bold">{{ $notifications->total() }}</span> إشعار
                        </p>
                    </div>

                    <nav aria-label="Notifications pagination">
                        <ul class="pagination pagination-sm mb-0">
                            <!-- First Page Link -->
                            <li class="page-item {{ $notifications->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link border-0 rounded-start-pill" href="{{ $notifications->url(1) }}"
                                    aria-label="First">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            </li>

                            <!-- Previous Page Link -->
                            <li class="page-item {{ $notifications->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link border-0" href="{{ $notifications->previousPageUrl() }}"
                                    aria-label="Previous">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            @foreach (range(1, $notifications->lastPage()) as $i)
                                @if (
                                    $i == 1 ||
                                        $i == $notifications->lastPage() ||
                                        ($i >= $notifications->currentPage() - 2 && $i <= $notifications->currentPage() + 2))
                                    <li class="page-item {{ $i == $notifications->currentPage() ? 'active' : '' }}">
                                        <a class="page-link border-0"
                                            href="{{ $notifications->url($i) }}">{{ $i }}</a>
                                    </li>
                                @elseif($i == $notifications->currentPage() - 3 || $i == $notifications->currentPage() + 3)
                                    <li class="page-item disabled">
                                        <span class="page-link border-0">...</span>
                                    </li>
                                @endif
                            @endforeach

                            <!-- Next Page Link -->
                            <li class="page-item {{ !$notifications->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link border-0" href="{{ $notifications->nextPageUrl() }}"
                                    aria-label="Next">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            </li>

                            <!-- Last Page Link -->
                            <li class="page-item {{ !$notifications->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link border-0 rounded-end-pill"
                                    href="{{ $notifications->url($notifications->lastPage()) }}" aria-label="Last">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection