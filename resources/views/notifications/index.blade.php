@extends('master')

@section('content')
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">الإشعارات</h2>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-3 col-12">
            <div class="btn-group float-md-right">
                <a href="{{ route('notifications.create') }}" class="btn btn-primary">
                    <i class="feather icon-plus"></i> إرسال إشعار جديد
                </a>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="notifications-section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">قائمة الإشعارات</h4>
                            <div class="card-actions">
                                <button type="button" class="btn btn-primary btn-sm" id="mark-all-read">
                                    <i class="feather icon-check"></i> تعليم الكل كمقروء
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Filter Form -->
                            <form id="filterForm" method="GET" class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="receiver_id">الموظف المستلم:</label>
                                            <select name="receiver_id" id="receiver_id" class="form-control select2">
                                                <option value="">جميع الموظفين</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ request('receiver_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="user_id">المرسل:</label>
                                            <select name="user_id" id="user_id" class="form-control select2">
                                                <option value="">جميع المرسلين</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="date_from">من تاريخ:</label>
                                            <input type="date"
                                                   name="date_from"
                                                   id="date_from"
                                                   class="form-control"
                                                   value="{{ request('date_from') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="date_to">إلى تاريخ:</label>
                                            <input type="date"
                                                   name="date_to"
                                                   id="date_to"
                                                   class="form-control"
                                                   value="{{ request('date_to') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group" style="margin-top: 32px;">
                                            <button type="button" id="clearFilter" class="btn btn-secondary btn-block">
                                                <i class="feather icon-x"></i> مسح
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div id="notificationsTableContainer">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>الحالة</th>
                                                <th>العنوان</th>
                                                <th>الوصف</th>
                                                <th>المرسل</th>
                                                <th>المستلم</th>
                                                <th>التاريخ</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($notifications as $notification)
                                            <tr class="{{ $notification->read == 0 ? 'table-warning' : '' }}"
                                                data-notification-id="{{ $notification->id }}">
                                                <td>
                                                    @if($notification->read == 0)
                                                        <span class="badge badge-warning">
                                                            <i class="feather icon-mail"></i> جديد
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">
                                                            <i class="feather icon-mail"></i> مقروء
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $notification->title }}</strong>
                                                    @if($notification->type == 'reply')
                                                        <span class="badge badge-info badge-sm">
                                                            <i class="feather icon-corner-up-left"></i> رد
                                                        </span>
                                                    @elseif($notification->type == 'access_notification')
                                                        <span class="badge badge-warning badge-sm">
                                                            <i class="feather icon-alert-triangle"></i> دخول
                                                        </span>
                                                    @elseif($notification->type == 'automatic_notification')
                                                        <span class="badge badge-primary badge-sm">
                                                            <i class="feather icon-bell"></i> تلقائي
                                                        </span>
                                                    @elseif($notification->type == 'collection')
                                                        <span class="badge badge-success badge-sm">
                                                            <i class="feather icon-dollar-sign"></i> تحصيل
                                                        </span>
                                                    @elseif($notification->type == 'visit')
                                                        <span class="badge badge-info badge-sm">
                                                            <i class="feather icon-map-pin"></i> زيارة
                                                        </span>
                                                    @elseif($notification->type == 'task')
                                                        <span class="badge badge-warning badge-sm">
                                                            <i class="feather icon-check-square"></i> مهمة
                                                        </span>
                                                    @elseif($notification->type == 'reminder')
                                                        <span class="badge badge-secondary badge-sm">
                                                            <i class="feather icon-clock"></i> تذكير
                                                        </span>
                                                    @elseif($notification->type == 'urgent')
                                                        <span class="badge badge-danger badge-sm">
                                                            <i class="feather icon-alert-circle"></i> عاجل
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ Str::limit($notification->description, 50) }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar bg-primary mr-1">
                                                            <span class="avatar-content">
                                                                {{ substr($notification->user->name ?? 'N', 0, 1) }}
                                                            </span>
                                                        </div>
                                                        {{ $notification->user->name ?? 'النظام' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($notification->receiver)
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar bg-success mr-1">
                                                                <span class="avatar-content">
                                                                    {{ substr($notification->receiver->name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                            {{ $notification->receiver->name }}
                                                        </div>
                                                    @else
                                                        <span class="text-muted">الجميع</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button"
                                                                class="btn btn-sm btn-info view-notification"
                                                                data-id="{{ $notification->id }}"
                                                                data-toggle="modal"
                                                                data-target="#notificationModal">
                                                            <i class="feather icon-eye"></i>
                                                        </button>

                                                        @if($notification->receiver_id == auth()->id() && $notification->read == 0)
                                                            <button type="button"
                                                                    class="btn btn-sm btn-success reply-notification"
                                                                    data-id="{{ $notification->id }}"
                                                                    data-title="{{ $notification->title }}"
                                                                    data-sender="{{ $notification->user->name ?? 'النظام' }}">
                                                                <i class="feather icon-corner-up-left"></i>
                                                            </button>
                                                        @endif

                                                        @if($notification->read == 0)
                                                            <button type="button"
                                                                    class="btn btn-sm btn-warning mark-read"
                                                                    data-id="{{ $notification->id }}">
                                                                <i class="feather icon-check"></i>
                                                            </button>
                                                        @endif

                                                        <button type="button"
                                                                class="btn btn-sm btn-danger delete-notification"
                                                                data-id="{{ $notification->id }}">
                                                            <i class="feather icon-trash-2"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <div class="py-4">
                                                        <i class="feather icon-inbox font-large-2 text-muted"></i>
                                                        <p class="text-muted mt-2">لا توجد إشعارات</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-2" id="paginationContainer">
                                    {{ $notifications->appends(request()->except('page'))->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal عرض الإشعار -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="notificationTitle"></h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="notificationBody">
                <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">جاري التحميل...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal الرد على الإشعار -->
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white">الرد على الإشعار</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="replyForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>الإشعار الأصلي:</strong>
                        <p class="mb-0" id="originalNotificationTitle"></p>
                        <small class="text-muted">من: <span id="originalSender"></span></small>
                    </div>

                    <div class="form-group">
                        <label for="reply_message">رسالة الرد <span class="text-danger">*</span></label>
                        <textarea class="form-control"
                                  id="reply_message"
                                  name="reply_message"
                                  rows="5"
                                  placeholder="اكتب ردك هنا..."
                                  required></textarea>
                        <small class="text-muted">الحد الأقصى 500 حرف</small>
                    </div>

                    <input type="hidden" id="reply_notification_id" name="notification_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="feather icon-x"></i> إلغاء
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="feather icon-send"></i> إرسال الرد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // التحقق من وجود معلمة reply_to في URL وفتح نافذة الرد تلقائيًا
    const urlParams = new URLSearchParams(window.location.search);
    const replyToNotificationId = urlParams.get('reply_to');
    
    if (replyToNotificationId) {
        // جلب تفاصيل الإشعار للرد عليه
        $.ajax({
            url: `/notifications/${replyToNotificationId}`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.success) {
                    const notification = response.notification;
                    $('#reply_notification_id').val(replyToNotificationId);
                    $('#originalNotificationTitle').text(notification.title);
                    $('#originalSender').text(notification.user ? notification.user.name : 'النظام');
                    $('#reply_message').val('');
                    $('#replyModal').modal('show');
                    
                    // تحديث URL لإزالة معلمة reply_to
                    const newUrl = window.location.origin + window.location.pathname;
                    window.history.replaceState({}, document.title, newUrl);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading notification for reply:', xhr, status, error);
            }
        });
    }

    // الفلترة التلقائية عند تغيير أي حقل
    $('#receiver_id, #user_id, #date_from, #date_to').on('change', function() {
        loadNotifications();
    });

    // مسح الفلترة
    $('#clearFilter').on('click', function() {
        $('#receiver_id').val('').trigger('change.select2');
        $('#user_id').val('').trigger('change.select2');
        $('#date_from').val('');
        $('#date_to').val('');
        loadNotifications();
    });

    // دالة تحميل الإشعارات
    function loadNotifications(page = 1) {
        const receiverId = $('#receiver_id').val();
        const userId = $('#user_id').val();
        const dateFrom = $('#date_from').val();
        const dateTo = $('#date_to').val();

        // إظهار مؤشر التحميل
        $('#notificationsTableContainer').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">جاري التحميل...</span>
                </div>
                <p class="text-muted mt-3">جاري تحميل الإشعارات...</p>
            </div>
        `);

        $.ajax({
            url: '/sales/notifications',
            method: 'GET',
            data: {
                receiver_id: receiverId,
                user_id: userId,
                date_from: dateFrom,
                date_to: dateTo,
                page: page,
                ajax: 1
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.success && response.notifications) {
                    updateNotificationsTable(response.notifications);

                    // تحديث روابط الترقيم
                    if (response.pagination) {
                        $('#paginationContainer').html(response.pagination);
                    }
                } else {
                    showError('حدث خطأ في تحميل البيانات');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading notifications:', xhr, status, error);
                showError('حدث خطأ أثناء تحميل الإشعارات. يرجى المحاولة مرة أخرى.');
            }
        });
    }

    // دالة تحديث جدول الإشعارات
    function updateNotificationsTable(notifications) {
        const typeIcons = {
            'reply': '<span class="badge badge-info badge-sm"><i class="feather icon-corner-up-left"></i> رد</span>',
            'access_notification': '<span class="badge badge-warning badge-sm"><i class="feather icon-alert-triangle"></i> دخول</span>',
            'automatic_notification': '<span class="badge badge-primary badge-sm"><i class="feather icon-bell"></i> تلقائي</span>',
            'collection': '<span class="badge badge-success badge-sm"><i class="feather icon-dollar-sign"></i> تحصيل</span>',
            'visit': '<span class="badge badge-info badge-sm"><i class="feather icon-map-pin"></i> زيارة</span>',
            'task': '<span class="badge badge-warning badge-sm"><i class="feather icon-check-square"></i> مهمة</span>',
            'reminder': '<span class="badge badge-secondary badge-sm"><i class="feather icon-clock"></i> تذكير</span>',
            'urgent': '<span class="badge badge-danger badge-sm"><i class="feather icon-alert-circle"></i> عاجل</span>'
        };

        let tableHtml = `
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>الحالة</th>
                            <th>العنوان</th>
                            <th>الوصف</th>
                            <th>المرسل</th>
                            <th>المستلم</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>`;

        if (notifications.data && notifications.data.length > 0) {
            notifications.data.forEach(function(notification) {
                const statusBadge = notification.read == 0
                    ? '<span class="badge badge-warning"><i class="feather icon-mail"></i> جديد</span>'
                    : '<span class="badge badge-secondary"><i class="feather icon-mail"></i> مقروء</span>';

                const typeBadge = typeIcons[notification.type] || '';
                const rowClass = notification.read == 0 ? 'table-warning' : '';

                const senderName = notification.user ? notification.user.name : 'النظام';
                const senderInitial = senderName.charAt(0);

                const receiverHtml = notification.receiver
                    ? `<div class="d-flex align-items-center">
                           <div class="avatar bg-success mr-1">
                               <span class="avatar-content">${notification.receiver.name.charAt(0)}</span>
                           </div>
                           ${notification.receiver.name}
                       </div>`
                    : '<span class="text-muted">الجميع</span>';

                const replyButton = (notification.receiver_id == notification.current_user_id && notification.read == 0)
                    ? `<button type="button" class="btn btn-sm btn-success reply-notification"
                              data-id="${notification.id}"
                              data-title="${escapeHtml(notification.title)}"
                              data-sender="${escapeHtml(senderName)}">
                          <i class="feather icon-corner-up-left"></i>
                       </button>`
                    : '';

                const markReadButton = notification.read == 0
                    ? `<button type="button" class="btn btn-sm btn-warning mark-read"
                              data-id="${notification.id}">
                          <i class="feather icon-check"></i>
                       </button>`
                    : '';

                const description = notification.description.length > 50
                    ? notification.description.substring(0, 50) + '...'
                    : notification.description;

                tableHtml += `
                    <tr class="${rowClass}" data-notification-id="${notification.id}">
                        <td>${statusBadge}</td>
                        <td>
                            <strong>${escapeHtml(notification.title)}</strong>
                            ${typeBadge}
                        </td>
                        <td>${escapeHtml(description)}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-primary mr-1">
                                    <span class="avatar-content">${senderInitial}</span>
                                </div>
                                ${escapeHtml(senderName)}
                            </div>
                        </td>
                        <td>${receiverHtml}</td>
                        <td>
                            <small class="text-muted">${notification.created_at_human}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-info view-notification"
                                        data-id="${notification.id}"
                                        data-toggle="modal"
                                        data-target="#notificationModal">
                                    <i class="feather icon-eye"></i>
                                </button>
                                ${replyButton}
                                ${markReadButton}
                                <button type="button" class="btn btn-sm btn-danger delete-notification"
                                        data-id="${notification.id}">
                                    <i class="feather icon-trash-2"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
            });
        } else {
            tableHtml += `
                <tr>
                    <td colspan="7" class="text-center">
                        <div class="py-4">
                            <i class="feather icon-inbox font-large-2 text-muted"></i>
                            <p class="text-muted mt-2">لا توجد إشعارات</p>
                        </div>
                    </td>
                </tr>`;
        }

        tableHtml += '</tbody></table></div>';
        $('#notificationsTableContainer').html(tableHtml);
    }

    // دالة escape HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text ? text.replace(/[&<>"']/g, m => map[m]) : '';
    }

    // دالة عرض الخطأ
    function showError(message) {
        $('#notificationsTableContainer').html(`
            <div class="alert alert-danger">
                <i class="feather icon-alert-circle"></i>
                ${message}
            </div>
        `);
    }

    // معالجة نقر روابط الترقيم
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const page = new URL(url).searchParams.get('page') || 1;
            loadNotifications(page);
        }
    });

    // عرض تفاصيل الإشعار
    $(document).on('click', '.view-notification', function() {
        const notificationId = $(this).data('id');

        $('#notificationBody').html(`
            <div class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">جاري التحميل...</span>
                </div>
            </div>
        `);

        $.ajax({
            url: `/sales/notifications/${notificationId}`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.success) {
                    $('#notificationTitle').text(response.notification.title);
                    $('#notificationBody').html(response.html);
                    markAsRead(notificationId);
                } else {
                    $('#notificationBody').html(`
                        <div class="alert alert-danger">
                            <i class="feather icon-alert-circle"></i>
                            حدث خطأ أثناء تحميل الإشعار
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading notification:', xhr, status, error);
                $('#notificationBody').html(`
                    <div class="alert alert-danger">
                        <i class="feather icon-alert-circle"></i>
                        حدث خطأ أثناء تحميل الإشعار
                    </div>
                `);
            }
        });
    });

    // فتح نافذة الرد
    $(document).on('click', '.reply-notification', function() {
        const notificationId = $(this).data('id');
        const title = $(this).data('title');
        const sender = $(this).data('sender');

        $('#reply_notification_id').val(notificationId);
        $('#originalNotificationTitle').text(title);
        $('#originalSender').text(sender);
        $('#reply_message').val('');

        $('#replyModal').modal('show');
    });

    // إرسال الرد
    $('#replyForm').on('submit', function(e) {
        e.preventDefault();

        const notificationId = $('#reply_notification_id').val();
        const replyMessage = $('#reply_message').val();

        if (!replyMessage.trim()) {
            if (typeof toastr !== 'undefined') {
                toastr.error('يرجى كتابة رسالة الرد');
            } else {
                alert('يرجى كتابة رسالة الرد');
            }
            return;
        }

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="feather icon-loader"></i> جاري الإرسال...');

        $.ajax({
            url: `/sales/notifications/${notificationId}/reply`,
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
                    $('#replyModal').modal('hide');
                    loadNotifications();
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
                console.error('Error sending reply:', xhr, status, error);
                let message = 'حدث خطأ أثناء إرسال الرد';

                if (xhr.status === 403) {
                    message = 'لا يمكنك الرد على هذا الإشعار';
                } else if (xhr.status === 405) {
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
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // تعليم كمقروء
    function markAsRead(notificationId) {
        $.ajax({
            url: `/sales/notifications/${notificationId}/mark-read`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.success) {
                    $(`tr[data-notification-id="${notificationId}"]`)
                        .removeClass('table-warning')
                        .find('.badge-warning')
                        .removeClass('badge-warning')
                        .addClass('badge-secondary')
                        .html('<i class="feather icon-mail"></i> مقروء');

                    $(`tr[data-notification-id="${notificationId}"]`)
                        .find('.mark-read')
                        .remove();

                    updateNotificationCount();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error marking as read:', xhr, status, error);
            }
        });
    }

    // تعليم إشعار واحد كمقروء
    $(document).on('click', '.mark-read', function() {
        const notificationId = $(this).data('id');
        markAsRead(notificationId);
    });

    // تعليم الكل كمقروء
    $('#mark-all-read').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="feather icon-loader"></i> جاري التحميل...');

        $.ajax({
            url: '/sales/notifications/mark-all-read',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success('تم تعليم جميع الإشعارات كمقروءة');
                    } else {
                        alert('تم تعليم جميع الإشعارات كمقروءة');
                    }
                    loadNotifications();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error marking all as read:', xhr, status, error);
                const message = 'حدث خطأ أثناء تعليم الإشعارات كمقروءة';
                if (typeof toastr !== 'undefined') {
                    toastr.error(message);
                } else {
                    alert(message);
                }
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // حذف إشعار
    $(document).on('click', '.delete-notification', function() {
        const notificationId = $(this).data('id');

        if (confirm('هل أنت متأكد من حذف هذا الإشعار؟')) {
            $.ajax({
                url: `/sales/notifications/${notificationId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success(response.message);
                        } else {
                            alert(response.message);
                        }
                        $(`tr[data-notification-id="${notificationId}"]`).fadeOut(300, function() {
                            $(this).remove();

                            // إذا كان الجدول فارغ، اعرض رسالة
                            if ($('tbody tr').length === 0) {
                                loadNotifications();
                            }
                        });
                        updateNotificationCount();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting notification:', xhr, status, error);
                    const message = 'حدث خطأ أثناء الحذف';
                    if (typeof toastr !== 'undefined') {
                        toastr.error(message);
                    } else {
                        alert(message);
                    }
                }
            });
        }
    });

    // تحديث عدد الإشعارات
    function updateNotificationCount() {
        $.ajax({
            url: '/sales/notifications/unread-count',
            method: 'GET',
            success: function(response) {
                if (response.count !== undefined) {
                    $('#notification-count').text(response.count);

                    // إخفاء أو إظهار الشارة حسب العدد
                    if (response.count === 0) {
                        $('#notification-count').hide();
                    } else {
                        $('#notification-count').show();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating notification count:', xhr, status, error);
            }
        });
    }
});
</script>
@endsection
