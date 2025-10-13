@extends('master')

@section('title')
    الرد على الإشعار
@stop

@section('content')

    <!-- Card Container -->
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <!-- Card Header with Gradient Background -->
        <div class="card-header bg-gradient-primary text-white py-3 rounded-top">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-2 mb-md-0">
                    <h4 class="mb-1 text-white">
                        <i class="fas fa-reply me-2"></i> الرد على الإشعار
                    </h4>
                    <p class="mb-0 text-white-50 small">إرسال رد على الإشعار المستلم</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('notifications.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right me-1"></i> العودة للإشعارات
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Notification Details -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">تفاصيل الإشعار</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>المرسل:</strong> {{ $notification->user->name ?? 'غير معروف' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>التاريخ:</strong> {{ $notification->created_at->format('Y/m/d h:i A') }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p><strong>العنوان:</strong> {{ $notification->title ?? 'بدون عنوان' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p><strong>المحتوى:</strong></p>
                            <div class="bg-light p-3 rounded">
                                {{ $notification->description ?? 'لا توجد تفاصيل' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Response Form -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">إرسال رد</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('notifications.respond', $notification->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="response" class="form-label">نص الرد</label>
                            <textarea class="form-control" id="response" name="response" rows="5" 
                                placeholder="اكتب ردك على هذا الإشعار..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> إرسال الرد
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Auto-resize textarea
            $('#response').on('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });
    </script>
@endsection