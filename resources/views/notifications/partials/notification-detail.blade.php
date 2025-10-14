{{-- resources/views/notifications/partials/notification-detail.blade.php --}}

<div class="notification-detail-content">
    <div class="notification-info mb-3">
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class="d-flex align-items-center">
                    <i class="feather icon-user text-primary mr-2"></i>
                    <strong class="mr-2">من:</strong>
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-primary mr-1" style="width: 30px; height: 30px;">
                            <span class="avatar-content" style="font-size: 14px;">
                                {{ substr($notification->user->name ?? 'N', 0, 1) }}
                            </span>
                        </div>
                        <span>{{ $notification->user->name ?? 'النظام' }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-2">
                <div class="d-flex align-items-center">
                    <i class="feather icon-users text-success mr-2"></i>
                    <strong class="mr-2">إلى:</strong>
                    @if($notification->receiver)
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-success mr-1" style="width: 30px; height: 30px;">
                                <span class="avatar-content" style="font-size: 14px;">
                                    {{ substr($notification->receiver->name, 0, 1) }}
                                </span>
                            </div>
                            <span>{{ $notification->receiver->name }}</span>
                        </div>
                    @else
                        <span class="text-muted">الجميع</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6 mb-2">
                <div class="d-flex align-items-center">
                    <i class="feather icon-clock text-info mr-2"></i>
                    <strong class="mr-2">التاريخ:</strong>
                    <span>{{ $notification->created_at->format('Y-m-d h:i A') }}</span>
                </div>
            </div>

            <div class="col-md-6 mb-2">
                <div class="d-flex align-items-center">
                    <i class="feather icon-tag text-warning mr-2"></i>
                    <strong class="mr-2">النوع:</strong>
                    @if($notification->type == 'reply')
                        <span class="badge badge-info">رد</span>
                    @elseif($notification->type == 'access_notification')
                        <span class="badge badge-warning">تنبيه دخول</span>
                    @elseif($notification->type == 'automatic_notification')
                        <span class="badge badge-primary">تنبيه تلقائي</span>
                    @elseif($notification->type == 'collection')
                        <span class="badge badge-success">تحصيل</span>
                    @elseif($notification->type == 'visit')
                        <span class="badge badge-info">زيارة عميل</span>
                    @elseif($notification->type == 'task')
                        <span class="badge badge-warning">مهمة</span>
                    @elseif($notification->type == 'reminder')
                        <span class="badge badge-secondary">تذكير</span>
                    @elseif($notification->type == 'urgent')
                        <span class="badge badge-danger">عاجل</span>
                    @else
                        <span class="badge badge-primary">إشعار عادي</span>
                    @endif
                </div>
            </div>
            
            @if(isset($notification->data['access_time']))
            <div class="col-md-6 mb-2">
                <div class="d-flex align-items-center">
                    <i class="feather icon-clock text-info mr-2"></i>
                    <strong class="mr-2">وقت الدخول:</strong>
                    <span>{{ $notification->data['access_time'] }}</span>
                </div>
            </div>
            @endif
            
            @if(isset($notification->data['route']))
            <div class="col-md-6 mb-2">
                <div class="d-flex align-items-center">
                    <i class="feather icon-navigation text-info mr-2"></i>
                    <strong class="mr-2">المسار:</strong>
                    <span>{{ $notification->data['route'] }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    <hr>

    <div class="notification-message">
        <h6 class="text-bold-600 mb-2">الرسالة:</h6>
        <div class="alert alert-light border">
            <p class="mb-0">{{ $notification->description }}</p>
        </div>
    </div>

    @if($replies->count() > 0)
        <hr>
        <div class="notification-replies mt-3">
            <h6 class="text-bold-600 mb-3">
                <i class="feather icon-message-circle text-success"></i>
                الردود ({{ $replies->count() }})
            </h6>

            <div class="replies-list" style="max-height: 300px; overflow-y: auto;">
                @foreach($replies as $reply)
                    <div class="reply-item mb-2 p-2 {{ $reply->user_id == auth()->id() ? 'bg-light-primary' : 'bg-light-info' }}" 
                         style="border-right: 3px solid {{ $reply->user_id == auth()->id() ? '#7367f0' : '#00cfe8' }}; border-radius: 5px;">
                        <div class="d-flex align-items-start mb-1">
                            <div class="avatar {{ $reply->user_id == auth()->id() ? 'bg-primary' : 'bg-info' }} mr-1" 
                                 style="width: 32px; height: 32px;">
                                <span class="avatar-content" style="font-size: 14px;">
                                    {{ substr($reply->user->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <strong>
                                        {{ $reply->user->name }}
                                        @if($reply->user_id == auth()->id())
                                            <span class="badge badge-primary badge-sm">أنت</span>
                                        @endif
                                    </strong>
                                    <small class="text-muted">
                                        {{ $reply->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <p class="mb-0 mt-1">{{ $reply->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($notification->receiver_id == auth()->id())
        <hr>
        <div class="text-center mt-3">
            <button type="button" 
                    class="btn btn-success"
                    id="replyButton"
                    data-id="{{ $notification->id }}"
                    data-title="{{ $notification->title }}"
                    data-sender="{{ $notification->user->name ?? 'النظام' }}">
                <i class="feather icon-corner-up-left"></i>
                {{ $replies->count() > 0 ? 'إضافة رد جديد' : 'الرد على هذا الإشعار' }}
            </button>
        </div>
    @endif
</div>

<script>
// Handle reply button click
$('#replyButton').on('click', function() {
    const notificationId = $(this).data('id');
    const title = $(this).data('title');
    const sender = $(this).data('sender');
    
    // Set values in the reply modal (which should be in the parent view)
    $('#reply_notification_id').val(notificationId);
    $('#originalNotificationTitle').text(title);
    $('#originalSender').text(sender);
    $('#reply_message').val('');
    
    // Show the reply modal
    $('#replyModal').modal('show');
});

// Function to open reply modal (for backward compatibility)
function openReplyModal(notificationId, title, sender) {
    $('#notificationModal').modal('hide');
    
    setTimeout(function() {
        $('#reply_notification_id').val(notificationId);
        $('#originalNotificationTitle').text(title);
        $('#originalSender').text(sender);
        $('#reply_message').val('');
        $('#replyModal').modal('show');
    }, 300);
}
</script>