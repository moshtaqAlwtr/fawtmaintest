@extends('master')

@section('title')
    إدارة الإشعارات
@stop

@section('content')
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">تفاصيل الإشعار</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('notifications.index') }}">الإشعارات</a>
                            </li>
                            <li class="breadcrumb-item active">التفاصيل</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="notification-detail">
            <div class="row match-height">
                <!-- الإشعار الأصلي -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h4 class="card-title text-white">
                                <i class="feather icon-mail"></i>
                                {{ $notification->title }}
                            </h4>
                            <div class="heading-elements">
                                @if($notification->read == 0)
                                    <span class="badge badge-warning badge-pill">جديد</span>
                                @else
                                    <span class="badge badge-secondary badge-pill">مقروء</span>
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="notification-info mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="feather icon-user text-primary mr-1"></i>
                                            <strong>من:</strong>
                                            <span class="ml-2">{{ $notification->user->name ?? 'النظام' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="feather icon-users text-success mr-1"></i>
                                            <strong>إلى:</strong>
                                            <span class="ml-2">{{ $notification->receiver->name ?? 'الجميع' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="feather icon-clock text-info mr-1"></i>
                                            <strong>التاريخ:</strong>
                                            <span class="ml-2">{{ $notification->created_at->format('Y-m-d h:i A') }}</span>
                                            <small class="text-muted ml-2">({{ $notification->created_at->diffForHumans() }})</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="feather icon-tag text-warning mr-1"></i>
                                            <strong>النوع:</strong>
                                            <span class="ml-2">
                                                @if($notification->type == 'reply')
                                                    <span class="badge badge-info">رد</span>
                                                @else
                                                    <span class="badge badge-primary">إشعار عادي</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="notification-content">
                                <h5 class="mb-2">الرسالة:</h5>
                                <div class="alert alert-light">
                                    <p class="mb-0">{{ $notification->description }}</p>
                                </div>
                            </div>

                            @if($notification->receiver_id == auth()->id() && $replies->count() == 0)
                                <div class="mt-3">
                                    <button type="button" 
                                            class="btn btn-success btn-lg"
                                            data-toggle="modal"
                                            data-target="#replyModal">
                                        <i class="feather icon-corner-up-left"></i>
                                        الرد على هذا الإشعار
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- الردود -->
                @if($replies->count() > 0)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-success">
                                <h4 class="card-title text-white">
                                    <i class="feather icon-message-circle"></i>
                                    الردود ({{ $replies->count() }})
                                </h4>
                            </div>

                            <div class="card-body">
                                <div class="notification-replies">
                                    @foreach($replies as $reply)
                                        <div class="reply-item {{ $reply->user_id == auth()->id() ? 'my-reply' : 'their-reply' }} mb-3">
                                            <div class="card {{ $reply->user_id == auth()->id() ? 'border-primary' : 'border-info' }}">
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar {{ $reply->user_id == auth()->id() ? 'bg-primary' : 'bg-info' }} mr-1">
                                                                <span class="avatar-content">
                                                                    {{ substr($reply->user->name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <strong>{{ $reply->user->name }}</strong>
                                                                @if($reply->user_id == auth()->id())
                                                                    <span class="badge badge-primary badge-sm ml-1">أنت</span>
                                                                @endif
                                                                <br>
                                                                <small class="text-muted">
                                                                    <i class="feather icon-clock"></i>
                                                                    {{ $reply->created_at->format('Y-m-d h:i A') }}
                                                                    ({{ $reply->created_at->diffForHumans() }})
                                                                </small>
                                                            </div>
                                                        </div>

                                                        @if($reply->read == 0)
                                                            <span class="badge badge-warning">جديد</span>
                                                        @endif
                                                    </div>

                                                    <div class="reply-content">
                                                        <p class="mb-0">{{ $reply->description }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if($notification->receiver_id == auth()->id())
                                    <div class="mt-3 text-center">
                                        <button type="button" 
                                                class="btn btn-success"
                                                data-toggle="modal"
                                                data-target="#replyModal">
                                            <i class="feather icon-corner-up-left"></i>
                                            إضافة رد جديد
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Modal الرد -->
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white">
                    <i class="feather icon-corner-up-left"></i>
                    الرد على الإشعار
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form id="replyForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>الإشعار الأصلي:</strong>
                        <p class="mb-1">{{ $notification->title }}</p>
                        <small class="text-muted">من: {{ $notification->user->name ?? 'النظام' }}</small>
                    </div>

                    <div class="form-group">
                        <label for="reply_message">
                            رسالة الرد <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" 
                                  id="reply_message" 
                                  name="reply_message" 
                                  rows="6" 
                                  placeholder="اكتب ردك هنا..."
                                  maxlength="500"
                                  required></textarea>
                        <small class="text-muted">
                            الحد الأقصى 500 حرف
                            <span id="charCount" class="float-left">0/500</span>
                        </small>
                    </div>

                    <input type="hidden" id="reply_notification_id" value="{{ $notification->id }}">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="feather icon-x"></i> إلغاء
                    </button>
                    <button type="submit" class="btn btn-success" id="submitReply">
                        <i class="feather icon-send"></i> إرسال الرد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.reply-item.my-reply {
    margin-left: 15%;
}

.reply-item.their-reply {
    margin-right: 15%;
}

.notification-replies {
    max-height: 600px;
    overflow-y: auto;
}

.reply-content {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    margin-top: 10px;
}

.card-header.bg-primary,
.card-header.bg-success {
    color: white !important;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // عداد الأحرف
    $('#reply_message').on('input', function() {
        const count = $(this).val().length;
        $('#charCount').text(`${count}/500`);
    });

    // إرسال الرد
    $('#replyForm').on('submit', function(e) {
        e.preventDefault();

        const notificationId = $('#reply_notification_id').val();
        const replyMessage = $('#reply_message').val().trim();

        if (!replyMessage) {
            if (typeof toastr !== 'undefined') {
                toastr.error('يرجى كتابة رسالة الرد');
            } else {
                alert('يرجى كتابة رسالة الرد');
            }
            return;
        }

        // تعطيل الزر أثناء الإرسال
        const submitBtn = $('#submitReply');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="feather icon-loader"></i> جاري الإرسال...');

        $.ajax({
            url: `/notifications/${notificationId}/reply`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                reply_message: replyMessage
            },
            success: function(response) {
                if (response.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    } else {
                        alert(response.message);
                    }
                    
                    // إغلاق النافذة وإعادة تحميل الصفحة
                    $('#replyModal').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                } else {
                    const message = response.message || 'حدث خطأ أثناء إرسال الرد';
                    if (typeof toastr !== 'undefined') {
                        toastr.error(message);
                    } else {
                        alert(message);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log('Error sending reply:', xhr, status, error);
                let message = 'حدث خطأ أثناء إرسال الرد';
                
                if (xhr.status === 405) {
                    message = 'الطريقة غير مسموحة. قد تكون هناك مشكلة في التوجيه.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                if (typeof toastr !== 'undefined') {
                    toastr.error(message);
                } else {
                    alert(message);
                }
            },
            complete: function() {
                // إعادة تفعيل الزر
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // إعادة تعيين النموذج عند إغلاق النافذة
    $('#replyModal').on('hidden.bs.modal', function() {
        $('#reply_message').val('');
        $('#charCount').text('0/500');
        $('#submitReply').prop('disabled', false).html('<i class="feather icon-send"></i> إرسال الرد');
    });

    // تحديث حالة الإشعارات تلقائياً
    function updateNotificationStatus() {
        $.ajax({
            url: '/notifications/unread',
            method: 'GET',
            success: function(response) {
                $('#notification-count').text(response.count);
            },
            error: function(xhr, status, error) {
                console.log('Error updating notification status:', xhr, status, error);
            }
        });
    }

    // التحديث كل 30 ثانية
    setInterval(updateNotificationStatus, 30000);
});
</script>
@endsection