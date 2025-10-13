// إضافة الأنماط CSS
const style = document.createElement('style');
style.textContent = `
    .notification-item {
        transition: background-color 0.2s;
        border-radius: 4px;
        padding: 8px;
        text-decoration: none !important;
        display: block;
    }
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    .notification-text {
        color: #6c757d;
        font-size: 0.85rem;
        line-height: 1.4;
        margin-bottom: 0.5rem;
    }
    .notification-sender-info,
    .notification-receiver-info,
    .notification-users-info {
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
        margin-bottom: 0.5rem;
    }
    .badge-light-primary {
        background-color: rgba(115, 103, 240, 0.12);
        color: #7367f0;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-light-info {
        background-color: rgba(0, 207, 232, 0.12);
        color: #00cfe8;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-light-success {
        background-color: rgba(40, 199, 111, 0.12);
        color: #28c76f;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-light-warning {
        background-color: rgba(255, 159, 67, 0.12);
        color: #ff9f43;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .notification-item .media-body h6 {
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }
    .notification-arrow {
        color: #b8c2cc;
        font-size: 0.75rem;
        margin: 0 4px;
    }
    .scrollable-container {
        max-height: 400px;
        overflow-y: auto;
        min-height: 50px;
        background-color: #fff;
        padding: 10px 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        max-width: 100%;
        display: block;
        position: relative;
        overflow-x: hidden;
        will-change: scroll-position;
        backface-visibility: hidden;
        transform-style: preserve-3d;
        perspective: 1000px;
        transition: all 0.3s ease;
        overflow: visible;
        display: contents;
        contain: layout;
        contain: paint;
        contain: style;
        contain: size;
    }
    .nav-link-label {
        position: relative;
        cursor: pointer;
        display: flex;
        align-items: center;
        padding: 0 10px;
        height: 100%;
        min-height: 50px;
        justify-content: center;
        text-decoration: none;
        outline: none;
        will-change: transform;
        backface-visibility: hidden;
        transform-style: preserve-3d;
        perspective: 1000px;
        transition: all 0.3s ease;
        overflow: visible;
        display: contents;
        contain: layout;
        contain: paint;
        contain: style;
        contain: size;
    }
    .badge-up {
        position: absolute;
        top: -8px;
        right: -8px;
        font-size: 0.6rem;
        padding: 0.25em 0.4em;
        border-radius: 50%;
        min-width: 18px;
        min-height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1001;
        background-color: #7367f0;
        color: #fff;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        pointer-events: none;
        will-change: transform;
        backface-visibility: hidden;
        transform-style: preserve-3d;
        perspective: 1000px;
        transition: all 0.3s ease;
        overflow: visible;
        display: contents;
        contain: layout;
        contain: paint;
        contain: style;
        contain: size;
    }
    .ficon.feather.icon-bell {
        display: inline-block !important;
        visibility: visible !important;
        font-size: 1.2rem;
        color: #6c757d;
        transition: color 0.3s;
        margin-right: 5px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 1.2rem;
        height: 1.2rem;
        flex-shrink: 0;
        will-change: transform;
        backface-visibility: hidden;
        transform-style: preserve-3d;
        perspective: 1000px;
        transition: all 0.3s ease;
        overflow: visible;
        display: contents;
        contain: layout;
        contain: paint;
        contain: style;
        contain: size;
    }
    .ficon.feather.icon-bell:hover {
        color: #7367f0;
    }
    .dropdown-notification {
        display: block !important;
        position: relative;
        z-index: 999;
        margin: 0 10px;
        min-width: 50px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        will-change: transform;
        backface-visibility: hidden;
        transform-style: preserve-3d;
        perspective: 1000px;
        transition: all 0.3s ease;
        overflow: visible;
        display: contents;
        contain: layout;
        contain: paint;
        contain: style;
        contain: size;
    }
    .dropdown-menu-media {
        display: block !important;
        visibility: visible !important;
        min-width: 300px;
        z-index: 1000;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: 0.25rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
        margin-top: 10px;
        right: 0;
        left: auto;
        transform: translateZ(0);
        opacity: 1;
        position: absolute;
        will-change: transform;
        backface-visibility: hidden;
        transform-style: preserve-3d;
        perspective: 1000px;
        transition: all 0.3s ease;
        display: none;
        overflow: visible;
        display: contents;
        contain: layout;
        contain: paint;
        contain: style;
    }
    .dropdown-menu-media.show {
        display: block !important;
    }
    .nav-item.dropdown {
        display: block !important;
    }
    .dropdown-menu {
        display: none;
    }
    .dropdown-menu.show {
        display: block !important;
    }
`;
document.head.appendChild(style);

$(document).ready(function() {
    console.log('Notifications.js loaded and ready');
    
    // Test if notification icon exists
    if ($('#notification-count').length > 0) {
        console.log('Notification icon found');
    } else {
        console.log('Notification icon NOT found');
    }
    try {
    // Format time for notifications
    function formatNotificationTime(dateTime) {
        const now = new Date();
        const notificationDate = new Date(dateTime);
        const diffInSeconds = Math.floor((now - notificationDate) / 1000);

        // إذا مر أكثر من 24 ساعة (86400 ثانية) لا تعرض الإشعار
        if (diffInSeconds > 86400) {
            return null;
        }

        if (diffInSeconds < 60) {
            return 'منذ لحظات';
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            const minuteText = minutes === 1 ? 'دقيقة' : 'دقائق';
            return `منذ ${minutes} ${minuteText}`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            const hourText = hours === 1 ? 'ساعة' : 'ساعات';
            return `منذ ${hours} ${hourText}`;
        }
        
        return null; // Added fallback return
    }

    // Fetch all notifications for dropdown
    function fetchNotifications() {
        console.log('Fetching notifications...');
        $.ajax({
            url: "/sales/notifications/unread",
            method: "GET",
            success: function(response) {
                console.log('Notifications fetched successfully:', response);
                let notifications = response.notifications;
                let validNotifications = [];
                let currentTime = new Date();

                // تصفية الإشعارات لليوم الحالي فقط
                notifications.forEach(notification => {
                    let notificationTime = new Date(notification.created_at);
                    let diffInHours = (currentTime - notificationTime) / (1000 * 60 * 60);

                    if (diffInHours <= 24) {
                        validNotifications.push(notification);
                    }
                });

                let count = validNotifications.length;
                console.log('Updating notification count to:', count);
                $('#notification-count').text(count);
                $('#notification-title').text(count + " إشعارات جديدة");

                let notificationList = $('#notification-list');
                notificationList.empty();

                if (count > 0) {
                    validNotifications.forEach(notification => {
                        let timeAgo = formatNotificationTime(notification.created_at);
                        if (timeAgo !== null) {
                            // استخدام المعلومات المرسلة من الـ Controller
                            let isReceiver = notification.is_receiver;
                            let isSender = notification.is_sender;

                            // تحديد الأيقونة واللون حسب نوع الإشعار
                            let icon = 'feather icon-bell';
                            let iconColor = 'primary';

                            if (notification.type === 'reply') {
                                icon = 'feather icon-message-circle';
                                iconColor = 'success';
                            } else if (notification.type === 'automatic_notification') {
                                icon = 'feather icon-alert-circle';
                                iconColor = 'warning';
                            }

                            // تحديد الرابط
                            let link = notification.can_reply ?
                                '/sales/notifications/respond/' + notification.id :
                                '/sales/notifications/' + notification.id;

                            // بناء HTML للمرسل والمستقبل
                            let senderReceiverHTML = '';

                            if (isReceiver && !isSender) {
                                // المستخدم هو المستقبل فقط - نعرض المرسل
                                senderReceiverHTML = `
                                    <div class="notification-sender-info">
                                        <span class="badge badge-light-primary">
                                            <i class="feather icon-send"></i>
                                            من: ${notification.sender}
                                        </span>
                                    </div>
                                `;
                            } else if (isSender && !isReceiver) {
                                // المستخدم هو المرسل فقط - نعرض المستقبل
                                senderReceiverHTML = `
                                    <div class="notification-receiver-info">
                                        <span class="badge badge-light-info">
                                            <i class="feather icon-user"></i>
                                            إلى: ${notification.receiver}
                                        </span>
                                    </div>
                                `;
                            } else {
                                // حالة أخرى - نعرض كلاهما
                                senderReceiverHTML = `
                                    <div class="notification-users-info">
                                        <span class="badge badge-light-primary">
                                            <i class="feather icon-send"></i>
                                            ${notification.sender}
                                        </span>
                                        <span class="notification-arrow">→</span>
                                        <span class="badge badge-light-info">
                                            <i class="feather icon-user"></i>
                                            ${notification.receiver}
                                        </span>
                                    </div>
                                `;
                            }

                            // بناء عنصر الإشعار
                            let listItem = `
                                <a class="d-flex justify-content-between notification-item"
                                    href="${link}"
                                    data-id="${notification.id}">
                                    <div class="media d-flex align-items-start w-100">
                                        <div class="media-left pr-2">
                                            <i class="${icon} font-medium-5 ${iconColor}"></i>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="${iconColor} media-heading">${notification.title}</h6>
                                            <p class="notification-text">${notification.description}</p>

                                            ${senderReceiverHTML}

                                            <small class="text-muted d-block">
                                                <i class="feather icon-clock"></i> ${timeAgo}
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            `;
                            notificationList.append(listItem);
                        }
                    });
                } else {
                    notificationList.append(
                        '<p class="text-center p-2 text-muted">لا يوجد إشعارات جديدة</p>'
                    );
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching notifications:', error);
                console.error('XHR:', xhr);
                console.error('Status:', status);
                $('#notification-list').html(
                    '<p class="text-center p-2 text-danger">حدث خطأ في تحميل الإشعارات</p>'
                );
            }
        });
    }

    // Fetch notification count only
    function fetchNotificationCount() {
        console.log('Fetching notification count...');
        $.ajax({
            url: "/sales/notifications/unread/count",
            method: "GET",
            success: function(response) {
                console.log('Notification count fetched successfully:', response);
                console.log('Updating notification count from count endpoint:', response.count);
                $('#notification-count').text(response.count);
                // Update the title as well
                console.log('Updating notification title from count endpoint:', response.count + " إشعارات جديدة");
                $('#notification-title').text(response.count + " إشعارات جديدة");
            },
            error: function(xhr, status, error) {
                console.error('Error fetching notification count:', error);
                console.error('XHR:', xhr);
                console.error('Status:', status);
            }
        });
    }

    // Initialize notifications
    console.log('Initializing notifications...');
    fetchNotifications();
    fetchNotificationCount();

    // تحديث الإشعارات كل دقيقة
    console.log('Setting up notification intervals...');
    setInterval(fetchNotifications, 60000);
    setInterval(fetchNotificationCount, 60000);

    // Mark notification as read when clicked
    $(document).on('click', '.notification-item', function(e) {
        e.preventDefault();

        let notificationId = $(this).data('id');
        let redirectUrl = $(this).attr('href');

        $.ajax({
            url: "/sales/notifications/mark/show/" + notificationId,
            method: "GET",
            success: function() {
                fetchNotifications();
                fetchNotificationCount();
                window.location.href = redirectUrl;
            },
            error: function(xhr, status, error) {
                console.error('Error marking notification as read:', error);
                window.location.href = redirectUrl;
            }
        });
    });
} catch (error) {
    console.error('Error in notifications.js:', error);
}
});
