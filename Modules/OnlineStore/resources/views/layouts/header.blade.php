<meta name="csrf-token" content="{{ csrf_token() }}">
<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-container content" style="background-color: {{ $backgroundColorr ?? '#ffffff' }};">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none mr-auto">
                            <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
                                <i class="ficon feather icon-menu"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav bookmark-icons">
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link" href="{{ route('task.index') }}" data-toggle="tooltip" data-placement="top" title="Todo">
                                <i class="ficon feather icon-check-square"></i>
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link" href="app-chat.html" data-toggle="tooltip" data-placement="top" title="Chat">
                                <i class="ficon feather icon-message-square"></i>
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link" href="app-email.html" data-toggle="tooltip" data-placement="top" title="Email">
                                <i class="ficon feather icon-mail"></i>
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link" href="app-calender.html" data-toggle="tooltip" data-placement="top" title="Calendar">
                                <i class="ficon feather icon-calendar"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link bookmark-star">
                                <i class="ficon feather icon-star warning"></i>
                            </a>
                            <div class="bookmark-input search-input">
                                <div class="bookmark-input-icon">
                                    <i class="feather icon-search primary"></i>
                                </div>
                                <input class="form-control input" type="text" placeholder="Explore Vuexy..." tabindex="0" data-search="template-list">
                                <ul class="search-list search-list-bookmark"></ul>
                            </div>
                        </li>
                    </ul>
                </div>

                <ul class="nav navbar-nav float-right">
                    <!-- Language Selector -->
                    <li class="dropdown dropdown-language nav-item">
                        <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ficon feather icon-globe"></i>
                            <span class="selected-language"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                            @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <a class="dropdown-item" hreflang="{{ $localeCode }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                    data-language="{{ $localeCode }}">
                                    @if ($localeCode == 'ar')
                                        <i class="flag-icon flag-icon-sa"></i> {{ $properties['native'] }}
                                    @elseif ($localeCode == 'ur')
                                        <i class="flag-icon flag-icon-pk"></i> {{ $properties['native'] }}
                                    @elseif ($localeCode == 'hi')
                                        <i class="flag-icon flag-icon-in"></i> {{ $properties['native'] }}
                                    @elseif ($localeCode == 'bn')
                                        <i class="flag-icon flag-icon-bd"></i> {{ $properties['native'] }}
                                    @else
                                        <i class="flag-icon flag-icon-us"></i> {{ $properties['native'] }}
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </li>

                    <!-- Fullscreen -->
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link nav-link-expand">
                            <i class="ficon feather icon-maximize"></i>
                        </a>
                    </li>

                    <!-- Search -->
                    <li class="nav-item nav-search">
                        <a class="nav-link nav-link-search">
                            <i class="ficon feather icon-search"></i>
                        </a>
                        <div class="search-input">
                            <div class="search-input-icon">
                                <i class="feather icon-search primary"></i>
                            </div>
                            <input class="input" type="text" placeholder="Explore Vuexy..." tabindex="-1" data-search="template-list">
                            <div class="search-input-close">
                                <i class="feather icon-x"></i>
                            </div>
                            <ul class="search-list search-list-main"></ul>
                        </div>
                    </li>

                    <!-- Today Visits (للمدير فقط) -->
                    @if (auth()->user()->hasPermissionTo('branches'))
                        <li class="dropdown dropdown-notification nav-item">
                            <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
                                <i class="ficon feather icon-calendar"></i>
                                <span class="badge badge-pill badge-primary badge-up">{{ $todayVisits->count() }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header m-0 p-2">
                                        <h3 class="white">{{ $todayVisits->count() }} زيارة</h3>
                                        <span class="notification-title">زيارات اليوم</span>
                                    </div>
                                </li>
                                <li class="scrollable-container media-list">
                                    @forelse($todayVisits as $visit)
                                        <div class="visit-item media p-1">
                                            <div class="media-left">
                                                <div class="avatar bg-primary bg-lighten-4 rounded-circle">
                                                    <span class="avatar-content">{{ substr($visit->client->trade_name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="media-heading text-bold-500">{{ $visit->client->trade_name }}</h6>
                                                <p class="mb-1">
                                                    <i class="feather icon-user"></i>
                                                    <small class="text-muted">الموظف: {{ $visit->employee->name ?? 'غير معروف' }}</small>
                                                </p>
                                                <div class="visit-details">
                                                    @if ($visit->arrival_time)
                                                        <p class="mb-0">
                                                            <i class="feather icon-clock text-success"></i>
                                                            <span class="text-success">الوصول: </span>
                                                            {{ \Carbon\Carbon::parse($visit->arrival_time)->format('h:i A') }}
                                                        </p>
                                                    @endif
                                                    @if ($visit->departure_time)
                                                        <p class="mb-0">
                                                            <i class="feather icon-clock text-danger"></i>
                                                            <span class="text-danger">المغادرة: </span>
                                                            {{ \Carbon\Carbon::parse($visit->departure_time)->format('h:i A') }}
                                                        </p>
                                                    @else
                                                        <p class="mb-0 text-warning">
                                                            <i class="feather icon-clock"></i>
                                                            <span>ما زال عند العميل</span>
                                                        </p>
                                                    @endif
                                                    @if ($visit->notes)
                                                        <p class="mb-0 text-muted small">
                                                            <i class="feather icon-message-square"></i>
                                                            {{ Str::limit($visit->notes, 50) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <li class="empty-visits p-2 text-center">لا توجد زيارات اليوم</li>
                                    @endforelse
                                </li>
                                <li class="dropdown-menu-footer">
                                    <a class="dropdown-item p-1 text-center text-primary" href="">
                                        <i class="feather icon-list align-middle"></i>
                                        <span class="align-middle text-bold-600">عرض كل الزيارات</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <!-- الإشعارات -->
                    @auth
                        <li class="dropdown dropdown-notification nav-item">
                            <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
                                <i class="ficon feather icon-bell"></i>
                                <span class="badge badge-pill badge-primary badge-up" id="notification-count">0</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header m-0 p-2">
                                        <h3 class="white" id="notification-title">إشعارات جديدة</h3>
                                        <span class="notification-title">التنبيهات (آخر 24 ساعة)</span>
                                    </div>
                                </li>
                                <li class="scrollable-container media-list" id="notification-list">
                                    <p class="text-center p-2">لا يوجد إشعارات جديدة</p>
                                </li>
                                <li class="dropdown-menu-footer">
                                    <a class="dropdown-item p-1 text-center" href="{{ route('notifications.index') }}">
                                        عرض كل الإشعارات
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endauth

                    <!-- User Profile -->
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown" aria-expanded="false">
                            <div class="user-nav d-sm-flex d-none">
                                <span class="user-name text-bold-600">{{ auth()->user()->name ?? '' }}</span>
                                <span class="user-status">
                                    متصل
                                    @if (auth()->user()->branch_id)
                                        - {{ auth()->user()->currentBranch()->name ?? 'بدون فرع' }}
                                    @endif
                                </span>
                            </div>
                            <span>
                                @php
                                    $firstLetter = mb_substr(auth()->user()->name, 0, 1, 'UTF-8');
                                @endphp
                                <div class="profile-picture-header">{{ $firstLetter }}</div>
                            </span>
                            <i class="feather icon-chevron-down"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-divider"></div>
                            @if (auth()->user()->role !== 'employee')
                                <span class="dropdown-item font-weight-bold">🔹 الفروع:</span>

                                @if (auth()->user()->role === 'main')
                                    <a class="dropdown-item branch-item {{ !auth()->user()->branch_id ? 'active bg-light' : '' }}"
                                        href="{{ route('branch.switch', 0) }}">
                                        <i class="feather icon-globe"></i> جميع الفروع
                                        @if (!auth()->user()->branch_id)
                                            <i class="feather icon-check text-success float-left"></i>
                                        @endif
                                    </a>
                                @endif

                                @foreach (App\Models\Branch::all() as $branch)
                                    <a class="dropdown-item branch-item {{ auth()->user()->branch_id == $branch->id ? 'active bg-light' : '' }}"
                                        href="{{ route('branch.switch', $branch->id) }}">
                                        <i class="feather icon-map-pin"></i> {{ $branch->name }}
                                        @if (auth()->user()->branch_id == $branch->id)
                                            <i class="feather icon-check text-success float-left"></i>
                                        @endif
                                    </a>
                                @endforeach
                            @endif
                            <div class="dropdown-divider"></div>

                            <!-- تسجيل الخروج -->
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="feather icon-power"></i> تسجيل خروج
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- نافذة الرد المنبثقة -->
@auth
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="replyModalLabel">
                    <i class="feather icon-corner-up-right"></i> الرد على الإشعار
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- الإشعار الأصلي -->
                <div class="alert alert-light border mb-3">
                    <h6 class="alert-heading">
                        <i class="feather icon-mail"></i> الإشعار الأصلي
                    </h6>
                    <p class="mb-1"><strong>العنوان:</strong> <span id="originalNotificationTitle"></span></p>
                    <p class="mb-0"><strong>من:</strong> <span id="originalSender"></span></p>
                </div>

                <!-- نموذج الرد -->
                <form id="replyNotificationForm">
                    @csrf
                    <input type="hidden" id="reply_notification_id" name="notification_id">

                    <div class="form-group">
                        <label for="reply_message">
                            <i class="feather icon-message-square"></i> نص الرد
                        </label>
                        <textarea
                            class="form-control"
                            id="reply_message"
                            name="reply_message"
                            rows="4"
                            placeholder="اكتب ردك هنا..."
                            required
                            maxlength="500"
                        ></textarea>
                        <small class="form-text text-muted">
                            <span id="charCount">0</span>/500 حرف
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="feather icon-x"></i> إلغاء
                </button>
                <button type="button" class="btn btn-primary" id="sendReplyBtn">
                    <i class="feather icon-send"></i> إرسال الرد
                </button>
            </div>
        </div>
    </div>
</div>
@endauth

<style>
.notification-item {
    transition: all 0.3s ease;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-sender,
.notification-receiver {
    padding: 2px 0;
}

.notification-sender i,
.notification-receiver i {
    font-size: 12px;
    margin-left: 3px;
}

.notification-item .media-body {
    padding-left: 10px;
}

.reply-notification-btn {
    padding: 4px 12px;
    font-size: 12px;
    transition: all 0.2s ease;
}

.reply-notification-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.reply-notification-btn i {
    font-size: 14px;
    margin-left: 3px;
}

/* منطقة الرد داخل الـ Dropdown */
.reply-section {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
    border: 1px solid #e3e6f0;
}

.reply-textarea {
    resize: vertical;
    min-height: 60px;
    font-size: 13px;
    border: 1px solid #d1d3e2;
}

.reply-textarea:focus {
    border-color: #7367f0;
    box-shadow: 0 0 0 0.2rem rgba(115, 103, 240, 0.25);
}

.char-counter {
    font-size: 11px;
}

.char-counter.text-danger {
    font-weight: bold;
}

.send-reply-btn,
.cancel-reply-btn {
    font-size: 12px;
    padding: 4px 12px;
}

.send-reply-btn i,
.cancel-reply-btn i {
    font-size: 13px;
    margin-left: 3px;
}

.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

#replyModal .modal-header {
    border-bottom: 0;
}

#replyModal .modal-body {
    padding: 1.5rem;
}

#replyModal textarea {
    resize: vertical;
    min-height: 100px;
}

#charCount.text-danger {
    font-weight: bold;
}
</style>

<script>
$(document).ready(function() {
    // ========================================
    // دالة تنسيق الوقت
    // ========================================
    function formatNotificationTime(dateTime) {
        const now = new Date();
        const notificationDate = new Date(dateTime);
        const diffInSeconds = Math.floor((now - notificationDate) / 1000);

        if (diffInSeconds < 60) {
            return 'منذ لحظات';
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            return `منذ ${minutes} ${minutes === 1 ? 'دقيقة' : 'دقائق'}`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return `منذ ${hours} ${hours === 1 ? 'ساعة' : 'ساعات'}`;
        } else {
            const days = Math.floor(diffInSeconds / 86400);
            return `منذ ${days} ${days === 1 ? 'يوم' : 'أيام'}`;
        }
    }

    // ========================================
    // جلب الإشعارات - ⭐ آخر 24 ساعة فقط
    // ========================================
    function fetchNotifications() {
        console.log('🔍 بدء جلب الإشعارات (آخر 24 ساعة)...');

        $.ajax({
            url: "{{ route('notifications.unread') }}",
            method: "GET",
            beforeSend: function() {
                console.log('📤 إرسال الطلب...');
            },
            success: function(response) {
                console.log('✅ البيانات المستلمة:', response);

                if (response.success) {
                    let notifications = response.notifications || [];
                    let count = notifications.length;

                    console.log('📊 عدد الإشعارات (آخر 24 ساعة):', count);

                    // تحديث العداد
                    $('#notification-count').text(count);

                    // تحديث العنوان
                    $('#notification-title').text(
                        count > 0
                            ? count + (count === 1 ? " إشعار جديد" : " إشعارات جديدة")
                            : "لا توجد إشعارات جديدة"
                    );

                    let notificationList = $('#notification-list');
                    notificationList.empty();

                    if (count > 0) {
                        notifications.forEach(notification => {
                            let timeAgo = formatNotificationTime(notification.created_at);

                            // معلومات المُرسل
                            let senderInfo = notification.sender ? `
                                <div class="notification-sender mb-1">
                                    <small class="text-muted">
                                        <i class="feather icon-user"></i>
                                        <strong>من:</strong> ${notification.sender}
                                    </small>
                                </div>
                            ` : '';

                            // معلومات المُستقبِل
                            let receiverInfo = (notification.receiver && notification.receiver !== 'الجميع') ? `
                                <div class="notification-receiver mb-1">
                                    <small class="text-muted">
                                        <i class="feather icon-arrow-left"></i>
                                        <strong>إلى:</strong> ${notification.receiver}
                                    </small>
                                </div>
                            ` : '';

                            // ⭐ زر الرد - يظهر للجميع دائماً
                            let replyButton = `
                                <button class="btn btn-sm btn-outline-primary mt-2 reply-notification-btn"
                                    data-notification-id="${notification.id}"
                                    data-sender-id="${notification.sender_id || ''}"
                                    data-sender-name="${notification.sender || 'غير معروف'}"
                                    data-title="${notification.title}">
                                    <i class="feather icon-corner-up-right"></i> رد
                                </button>
                            `;

                            // ⭐ منطقة الرد - تظهر للجميع
                            let replySection = `
                                <div class="reply-section mt-2" id="reply-section-${notification.id}" style="display: none;">
                                    <div class="reply-form">
                                        <textarea
                                            class="form-control form-control-sm reply-textarea"
                                            id="reply-textarea-${notification.id}"
                                            rows="2"
                                            placeholder="اكتب ردك هنا..."
                                            maxlength="500"
                                        ></textarea>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <small class="text-muted">
                                                <span class="char-counter" id="char-counter-${notification.id}">0</span>/500
                                            </small>
                                            <div>
                                                <button class="btn btn-sm btn-secondary cancel-reply-btn" data-notification-id="${notification.id}">
                                                    <i class="feather icon-x"></i> إلغاء
                                                </button>
                                                <button class="btn btn-sm btn-primary send-reply-btn" data-notification-id="${notification.id}" data-sender-id="${notification.sender_id || ''}">
                                                    <i class="feather icon-send"></i> إرسال
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            // تحديد اللون
                            let iconColor = 'primary';
                            if (notification.is_sender) {
                                iconColor = 'success';
                            } else if (notification.is_receiver) {
                                iconColor = 'warning';
                            }

                            let listItem = `
                                <div class="notification-item" data-notification-id="${notification.id}">
                                    <div class="media d-flex align-items-start w-100 p-2">
                                        <div class="media-left">
                                            <i class="feather icon-bell font-medium-5 ${iconColor}"></i>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="${iconColor} media-heading mb-1">
                                                ${notification.title}
                                            </h6>
                                            ${senderInfo}
                                            ${receiverInfo}
                                            <p class="notification-text mb-1">${notification.description}</p>
                                            <small class="text-muted">
                                                <i class="feather icon-clock"></i> ${timeAgo}
                                            </small>
                                            ${replyButton}
                                            ${replySection}
                                        </div>
                                    </div>
                                    <hr class="my-1">
                                </div>
                            `;
                            notificationList.append(listItem);
                        });
                    } else {
                        notificationList.html('<p class="text-center p-2 text-muted">لا توجد إشعارات جديدة (آخر 24 ساعة)</p>');
                    }
                } else {
                    console.error('❌ خطأ في الاستجابة');
                    $('#notification-list').html('<p class="text-center p-2 text-danger">حدث خطأ في جلب الإشعارات</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ خطأ AJAX:', {
                    status: status,
                    error: error,
                    statusCode: xhr.status,
                    response: xhr.responseText
                });

                let errorMsg = 'فشل الاتصال بالخادم';
                if (xhr.status === 404) {
                    errorMsg = 'الرابط غير موجود (404)';
                } else if (xhr.status === 500) {
                    errorMsg = 'خطأ في الخادم (500)';
                } else if (xhr.status === 401) {
                    errorMsg = 'غير مصرح (401)';
                }

                $('#notification-list').html(`<p class="text-center p-2 text-danger">${errorMsg}</p>`);
            }
        });
    }

    // ========================================
    // فتح منطقة الرد داخل الـ Dropdown
    // ========================================
    $(document).on('click', '.reply-notification-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        let notificationId = $(this).data('notification-id');

        // إخفاء جميع مناطق الرد الأخرى
        $('.reply-section').slideUp(200);

        // إظهار منطقة الرد لهذا الإشعار
        $(`#reply-section-${notificationId}`).slideDown(200);

        // التركيز على textarea
        setTimeout(function() {
            $(`#reply-textarea-${notificationId}`).focus();
        }, 250);
    });

    // ========================================
    // إلغاء الرد
    // ========================================
    $(document).on('click', '.cancel-reply-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        let notificationId = $(this).data('notification-id');
        $(`#reply-section-${notificationId}`).slideUp(200);
        $(`#reply-textarea-${notificationId}`).val('');
        $(`#char-counter-${notificationId}`).text('0');
    });

    // ========================================
    // عداد الأحرف للرد في الـ Dropdown
    // ========================================
    $(document).on('input', '.reply-textarea', function() {
        let notificationId = $(this).attr('id').split('-')[2];
        let length = $(this).val().length;
        $(`#char-counter-${notificationId}`).text(length);

        if (length > 450) {
            $(`#char-counter-${notificationId}`).addClass('text-danger');
        } else {
            $(`#char-counter-${notificationId}`).removeClass('text-danger');
        }
    });

    // ========================================
    // إرسال الرد من الـ Dropdown
    // ========================================
    $(document).on('click', '.send-reply-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        let notificationId = $(this).data('notification-id');
        let replyMessage = $(`#reply-textarea-${notificationId}`).val().trim();

        if (!replyMessage) {
            if (typeof toastr !== 'undefined') {
                toastr.error('يرجى كتابة نص الرد');
            } else {
                alert('يرجى كتابة نص الرد');
            }
            return;
        }

        let btn = $(this);
        let originalHtml = btn.html();
        btn.prop('disabled', true).html('<i class="feather icon-loader spinner"></i>');

        $.ajax({
            url: `{{ route('notifications.reply', ['id' => '__ID__']) }}`.replace('__ID__', notificationId),
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                reply_message: replyMessage
            },
            success: function(response) {
                if (response.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message || 'تم إرسال الرد بنجاح');
                    } else {
                        alert(response.message || 'تم إرسال الرد بنجاح');
                    }

                    // إزالة الإشعار من القائمة بتأثير جميل
                    $(`.notification-item[data-notification-id="${notificationId}"]`).fadeOut(300, function() {
                        $(this).remove();

                        // تحديث العداد
                        let currentCount = parseInt($('#notification-count').text());
                        let newCount = Math.max(0, currentCount - 1);
                        $('#notification-count').text(newCount);

                        // تحديث العنوان
                        $('#notification-title').text(
                            newCount > 0
                                ? newCount + (newCount === 1 ? " إشعار جديد" : " إشعارات جديدة")
                                : "لا توجد إشعارات جديدة"
                        );

                        // إذا لا يوجد إشعارات
                        if (newCount === 0) {
                            $('#notification-list').html('<p class="text-center p-2 text-muted">لا توجد إشعارات جديدة (آخر 24 ساعة)</p>');
                        }
                    });
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message || 'فشل إرسال الرد');
                    } else {
                        alert(response.message || 'فشل إرسال الرد');
                    }
                }
            },
            error: function(xhr) {
                let errorMsg = 'حدث خطأ أثناء إرسال الرد';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                if (typeof toastr !== 'undefined') {
                    toastr.error(errorMsg);
                } else {
                    alert(errorMsg);
                }
            },
            complete: function() {
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // ========================================
    // عداد الأحرف للنافذة المنبثقة
    // ========================================
    $('#reply_message').on('input', function() {
        let length = $(this).val().length;
        $('#charCount').text(length);

        if (length > 450) {
            $('#charCount').addClass('text-danger');
        } else {
            $('#charCount').removeClass('text-danger');
        }
    });

    // ========================================
    // إرسال الرد من النافذة المنبثقة (احتياطي)
    // ========================================
    $('#sendReplyBtn').on('click', function() {
        let notificationId = $('#reply_notification_id').val();
        let replyMessage = $('#reply_message').val().trim();

        if (!replyMessage) {
            alert('يرجى كتابة نص الرد');
            return;
        }

        let btn = $(this);
        btn.prop('disabled', true).html('<i class="feather icon-loader spinner"></i> جاري الإرسال...');

        $.ajax({
            url: "{{ route('notifications.reply', ['id' => ':id']) }}".replace(':id', notificationId),
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            data: {
                reply_message: replyMessage
            },
            success: function(response) {
                if (response.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message || 'تم إرسال الرد بنجاح');
                    } else {
                        alert(response.message || 'تم إرسال الرد بنجاح');
                    }
                    $('#replyModal').modal('hide');

                    // إزالة الإشعار من القائمة
                    $(`.notification-item[data-notification-id="${notificationId}"]`).fadeOut(300, function() {
                        $(this).remove();

                        // تحديث العداد
                        let currentCount = parseInt($('#notification-count').text());
                        let newCount = Math.max(0, currentCount - 1);
                        $('#notification-count').text(newCount);

                        // تحديث العنوان
                        $('#notification-title').text(
                            newCount > 0
                                ? newCount + (newCount === 1 ? " إشعار جديد" : " إشعارات جديدة")
                                : "لا توجد إشعارات جديدة"
                        );

                        // إذا لا يوجد إشعارات
                        if (newCount === 0) {
                            $('#notification-list').html('<p class="text-center p-2 text-muted">لا توجد إشعارات جديدة (آخر 24 ساعة)</p>');
                        }
                    });
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message || 'فشل إرسال الرد');
                    } else {
                        alert(response.message || 'فشل إرسال الرد');
                    }
                }
            },
            error: function(xhr) {
                console.error('خطأ في إرسال الرد:', xhr);
                let errorMsg = 'حدث خطأ أثناء إرسال الرد';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.status === 404) {
                    errorMsg = 'الرابط غير موجود - تحقق من الـ Route';
                } else if (xhr.status === 405) {
                    errorMsg = 'خطأ في طريقة الإرسال (Method Not Allowed)';
                } else if (xhr.status === 500) {
                    errorMsg = 'خطأ في الخادم';
                }

                if (typeof toastr !== 'undefined') {
                    toastr.error(errorMsg);
                } else {
                    alert(errorMsg);
                }
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="feather icon-send"></i> إرسال الرد');
            }
        });
    });

    // ========================================
    // التحميل الأولي والتحديث التلقائي
    // ========================================
    fetchNotifications();
    setInterval(fetchNotifications, 60000); // كل دقيقة

    // ========================================
    // جلب الزيارات اليومية
    // ========================================
    function formatVisitTime(dateTime) {
        try {
            const now = new Date();
            const visitDate = new Date(dateTime);
            const diffInSeconds = Math.floor((now - visitDate) / 1000);

            if (diffInSeconds < 60) return 'الآن';
            if (diffInSeconds < 3600) return `منذ ${Math.floor(diffInSeconds / 60)} دقيقة`;
            if (diffInSeconds < 86400) return `منذ ${Math.floor(diffInSeconds / 3600)} ساعة`;
            return `منذ ${Math.floor(diffInSeconds / 86400)} يوم`;
        } catch (e) {
            console.error('Error formatting time:', e);
            return '--';
        }
    }

    @if (auth()->user()->hasPermissionTo('branches'))
    function fetchTodayVisits() {
        $.ajax({
            url: "{{ route('visits.today') }}",
            method: "GET",
            success: function(response) {
                let visits = response.visits || [];
                let count = response.count || 0;

                $('#visits-count').text(count);
                $('#visits-title').text(count + ' زيارة');

                let visitsList = $('#visits-list');
                visitsList.empty();

                if (count > 0) {
                    visits.forEach(visit => {
                        let timeAgo = formatVisitTime(visit.created_at);
                        visitsList.append(`
                        <div class="media d-flex align-items-start px-2 py-1">
                            <div class="media-left">
                                <i class="feather icon-user font-medium-5 primary"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="primary media-heading mb-0">${visit.client_name}</h6>
                                <small class="text-muted d-block">الموظف: ${visit.employee_name}</small>
                                <small class="text-muted d-block">الوصول: ${visit.arrival_time} | الانصراف: ${visit.departure_time}</small>
                                <small class="text-muted"><i class="far fa-clock"></i> ${timeAgo}</small>
                            </div>
                        </div>
                        <hr class="my-1">
                    `);
                    });
                } else {
                    visitsList.append('<p class="text-center p-2">لا توجد زيارات اليوم</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching visits:', error);
                $('#visits-list').html('<p class="text-center p-2 text-danger">حدث خطأ أثناء جلب البيانات</p>');
            }
        });
    }

    fetchTodayVisits();
    setInterval(fetchTodayVisits, 60000);
    @endif
});
</script>