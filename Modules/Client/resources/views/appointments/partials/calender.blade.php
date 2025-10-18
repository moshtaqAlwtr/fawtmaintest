<!-- حاوية التقويم -->
<div class="calendar-container">
    <!-- أنماط CSS -->
    <style>
        .calendar-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }

        /* تنسيق رأس التقويم */
        .fc-header-toolbar {
            background-color: var(--bs-primary);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px !important;
        }

        .fc-toolbar-title {
            color: #fff !important;
            font-size: 1.5em !important;
        }

        .fc .fc-button {
            background: #fff;
            color: var(--bs-primary);
            border: none;
            padding: 8px 15px;
            font-weight: 500;
        }

        .fc .fc-button:hover {
            background: rgba(255, 255, 255, 0.9);
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: rgba(255, 255, 255, 0.8);
            color: var(--bs-primary);
        }

        /* تنسيق الأحداث */
        .fc-event {
            border: none !important;
            padding: 3px 8px !important;
            margin: 2px !important;
            border-radius: 6px !important;
        }

        /* تنسيق Legend */
        .calendar-legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            padding: 15px;
            background: rgba(var(--bs-primary-rgb), 0.05);
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .legend-text {
            font-size: 0.9em;
            color: #666;
        }

        /* ألوان الحالات */
        .status-1 { background-color: #ffc107; } /* قيد الانتظار */
        .status-2 { background-color: #28a745; color: #fff; } /* مكتمل */
        .status-3 { background-color: #dc3545; color: #fff; } /* ملغي */
        .status-4 { background-color: #17a2b8; color: #fff; } /* معاد جدولته */
    </style>

    <div class="calendar-header">
        <div class="view-toggle">
            <button class="toggle-btn active" onclick="toggleView('calendar')">
                <i class="fas fa-calendar-alt"></i>
            </button>
            <button class="toggle-btn" onclick="toggleView('list')">
                <i class="fas fa-list"></i>
            </button>
        </div>

        <h1 class="calendar-title">
            <i class="fas fa-calendar-check"></i>
            تقويم المواعيد
        </h1>

        <div class="calendar-controls">
            <button class="nav-button" onclick="previousMonth()">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="month-year" id="monthYear"></div>
            <button class="nav-button" onclick="nextMonth()">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <div class="view-filters">
            <button class="filter-btn" onclick="filterBookings('all', event)">الكل</button>
            <button class="filter-btn" onclick="filterBookings('today', event)">اليوم</button>
            <button class="filter-btn" onclick="filterBookings('week', event)">الأسبوع</button>
            <button class="filter-btn" onclick="filterBookings('month', event)">الشهر</button>
        </div>
    </div>

    <div class="calendar-body">
        <div class="calendar-grid" id="calendarGrid"></div>

        <!-- Legend Section -->
        <div class="appointments-legend mt-4">
            <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
                <div class="legend-item">
                    <span class="legend-dot pending"></span>
                    <span class="legend-text">قيد الانتظار</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot completed"></span>
                    <span class="legend-text">مكتمل</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot cancelled"></span>
                    <span class="legend-text">ملغي</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot rescheduled"></span>
                    <span class="legend-text">معاد جدولته</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Calendar Container */
        #calendar {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Calendar Header */
        .fc-toolbar-title {
            color: var(--bs-primary) !important;
            font-weight: bold !important;
        }

        .fc-button-primary {
            background-color: var(--bs-primary) !important;
            border-color: var(--bs-primary) !important;
        }

        .fc-button-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
        }

        .fc-day-today {
            background-color: rgba(0, 123, 255, 0.1) !important;
        }

        /* Events Styling */
        .fc-event {
            border: none !important;
            border-radius: 4px !important;
            padding: 4px !important;
            margin: 2px 0 !important;
        }

        .fc-event .fc-content {
            padding: 2px;
        }

        .fc-event .fc-code {
            font-weight: bold;
            font-size: 0.8em;
            opacity: 0.8;
            margin-bottom: 2px;
        }

        .fc-event .fc-time {
            font-weight: bold;
            font-size: 0.9em;
            margin-bottom: 2px;
            display: block;
        }

        .fc-event .fc-title {
            font-size: 0.85em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Legend Styling */
        .appointments-legend {
            padding: 15px;
            border-top: 1px solid #eee;
            margin-top: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin: 0 10px;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-left: 8px;
            display: inline-block;
        }

        .legend-text {
            font-size: 0.9em;
            color: #666;
        }

        /* Status Colors */
        .legend-dot.pending { background-color: #ffc107; }
        .legend-dot.completed { background-color: #28a745; }
        .legend-dot.cancelled { background-color: #dc3545; }
        .legend-dot.rescheduled { background-color: #17a2b8; }

        /* Event Status Colors */
        .status-1 { /* قيد الانتظار */
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
        }

        .status-2 { /* مكتمل */
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: #fff !important;
        }

        .status-3 { /* ملغي */
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: #fff !important;
        }

        .status-4 { /* معاد جدولته */
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
            color: #fff !important;
        }
    </style>
</div>

    </div>

    <!-- قسم Legend -->
    <div class="calendar-legend">
        <div class="legend-item">
            <span class="legend-dot status-1"></span>
            <span class="legend-text">قيد الانتظار</span>
        </div>
        <div class="legend-item">
            <span class="legend-dot status-2"></span>
            <span class="legend-text">مكتمل</span>
        </div>
        <div class="legend-item">
            <span class="legend-dot status-3"></span>
            <span class="legend-text">ملغي</span>
        </div>
        <div class="legend-item">
            <span class="legend-dot status-4"></span>
            <span class="legend-text">معاد جدولته</span>
        </div>
    </div>
</div>

<!-- النافذة المنبثقة لتفاصيل المواعيد -->
<div class="booking-details-modal" id="bookingModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">تفاصيل المواعيد</h3>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <div id="modalBookings"></div>
    </div>
</div>

<script>
// Make calendarBookings available globally for the calendar partial
window.calendarBookings = @json($calendarBookings ?? []);
// Make fullCalendarEvents available for FullCalendar
window.fullCalendarEvents = @json($fullCalendarEvents ?? []);

// Debug information
console.log('📅 Calendar Bookings Data:', window.calendarBookings);
console.log('📊 Full Calendar Events Data:', window.fullCalendarEvents);
console.log('📈 Number of events:', window.fullCalendarEvents ? window.fullCalendarEvents.length : 0);

// التحقق من وجود البيانات
if (!window.fullCalendarEvents || window.fullCalendarEvents.length === 0) {
    console.warn('⚠️ لا توجد مواعيد لعرضها في التقويم');
}
</script>

<script>
// ==== كود التقويم ====
let calendar = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM loaded, initializing calendar system...');
    
    // تهيئة التقويم
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        console.error('❌ Calendar element not found');
        return;
    }

    // تنسيق الوقت
    function formatTime(timeStr) {
        if (!timeStr) return '';
        const [hours, minutes] = timeStr.split(':');
        return `${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'ar',
        direction: 'rtl',
        headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'اليوم',
            month: 'شهر',
            week: 'أسبوع',
            day: 'يوم'
        },
        events: (window.fullCalendarEvents || []).map(event => ({
            title: `#${event.id} - ${event.client_name} - ${formatTime(event.time)}`,
            start: event.date + 'T' + event.time,
            className: `event-status-${event.status}`,
            extendedProps: event,
            backgroundColor: event.status === 1 ? '#ffc107' :
                           event.status === 2 ? '#28a745' :
                           event.status === 3 ? '#dc3545' :
                           event.status === 4 ? '#17a2b8' : '#6c757d',
            borderColor: 'transparent',
            textColor: event.status === 1 ? '#000' : '#fff'
        })),
        eventContent: function(arg) {
            return {
                html: `
                    <div class="fc-content">
                        <div class="fc-code">#${arg.event.extendedProps.id || ''}</div>
                        <div class="fc-time">${formatTime(arg.event.extendedProps.time)}</div>
                        <div class="fc-title">${arg.event.extendedProps.client_name || ''}</div>
                    </div>
                `
            };
        },
        eventDidMount: function(info) {
            // Add tooltip
            const tooltip = new Tooltip(info.el, {
                title: `
                    ${info.event.extendedProps.client_name}
                    <br>
                    الوقت: ${info.event.extendedProps.time}
                    <br>
                    الحالة: ${info.event.extendedProps.status_text}
                `,
                placement: 'top',
                trigger: 'hover',
                container: 'body',
                html: true
            });
        },
        dateClick: function(info) {
            // Handle date click - Add new appointment
            Swal.fire({
                title: 'إضافة موعد جديد',
                text: `هل تريد إضافة موعد جديد في ${info.dateStr}؟`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم',
                cancelButtonText: 'لا',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `{{ route('appointments.create') }}?date=${info.dateStr}`;
                }
            });
        },
        eventClick: function(info) {
            // Format the time
            let formattedTime = '';
            if (info.event.extendedProps.time) {
                const timeParts = info.event.extendedProps.time.split(':');
                if (timeParts.length >= 2) {
                    formattedTime = `${timeParts[0]}:${timeParts[1]}`;
                }
            }

            // Get status color class
            const statusClass = info.event.classNames[0] || '';
            
            // Show appointment details
            Swal.fire({
                title: info.event.extendedProps.client_name,
                html: `
                    <div class="text-right" dir="rtl">
                        <p><strong>التاريخ:</strong> ${moment(info.event.start).format('YYYY/MM/DD')}</p>
                        <p><strong>الوقت:</strong> ${formattedTime}</p>
                        <p><strong>رقم الهاتف:</strong> ${info.event.extendedProps.client_phone}</p>
                        <p><strong>الحالة:</strong> <span class="badge ${statusClass}">${info.event.extendedProps.status_text}</span></p>
                        <p><strong>الموظف:</strong> ${info.event.extendedProps.employee}</p>
                        <p><strong>ملاحظات:</strong> ${info.event.extendedProps.notes}</p>
                    </div>
                `,
                confirmButtonText: 'إغلاق',
                customClass: {
                    popup: 'swal-rtl'
                }
            });
        }
    });

    calendar.render();

    // إضافة مستمعي الأحداث للأزرار
    if (calendarViewBtn) {
        calendarViewBtn.addEventListener('click', function() {
            switchView('calendar');
        });
    }

    if (tableViewBtn) {
        tableViewBtn.addEventListener('click', function() {
            switchView('table');
        });
    }

    if (listViewBtn) {
        listViewBtn.addEventListener('click', function() {
            switchView('list');
        });
    }

    // وظيفة تبديل العرض
    function switchView(view) {
        console.log('🔄 Switching to view:', view);

        // إزالة فئة "active" من جميع الأزرار
        [listViewBtn, tableViewBtn, calendarViewBtn].forEach(btn => {
            if (btn) btn.classList.remove('active');
        });

        if (view === 'calendar') {
            if (calendarViewBtn) calendarViewBtn.classList.add('active');
            if (appointmentsTab) appointmentsTab.classList.remove('show', 'active');
            if (calendarTab) {
                calendarTab.classList.add('show', 'active');
                calendarTab.style.display = 'block';
            }

            // تهيئة وعرض التقويم
            setTimeout(() => {
                initializeCalendar();
            }, 100);
        } else {
            if (view === 'list' && listViewBtn) {
                listViewBtn.classList.add('active');
            } else if (tableViewBtn) {
                tableViewBtn.classList.add('active');
            }

            if (appointmentsTab) appointmentsTab.classList.add('show', 'active');
            if (calendarTab) {
                calendarTab.classList.remove('show', 'active');
                calendarTab.style.display = 'none';
            }
        }
    }

    // وظيفة تهيئة التقويم
    function initializeCalendar() {
        const calendarEl = document.getElementById('calendar');

        if (!calendarEl) {
            console.error('❌ Calendar element not found!');
            return;
        }

        console.log('📅 Initializing FullCalendar...');

        // If calendar already exists, destroy it first
        if (calendar) {
            console.log('🔄 Destroying existing calendar instance...');
            calendar.destroy();
            calendar = null;
        }

        // إظهار مؤشر التحميل
        calendarEl.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">جاري تحميل المواعيد...</p></div>';

        // استخدام البيانات المتاحة عالميًا
        const events = window.fullCalendarEvents || [];

        console.log('📊 Loading events into calendar:', events.length, 'events found');

        // Debug: عرض أول موعد للتحقق من البيانات
        if (events.length > 0) {
            console.log('📋 Sample event data:', events[0]);
        } else {
            console.warn('⚠️ No events to display in calendar');
        }

        // تهيئة وتكوين التقويم
        try {
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: 'ar',
                direction: 'rtl',
                buttonText: {
                    today: 'اليوم',
                    month: 'شهر',
                    week: 'أسبوع',
                    day: 'يوم'
                },
                events: events,
                eventDidMount: function(info) {
                    // إضافة tooltip عند التمرير
                    const tooltip = `
                        <strong>${info.event.title}</strong><br>
                        الوقت: ${info.event.extendedProps.time}<br>
                        الحالة: ${info.event.extendedProps.status_text}
                    `;
                    info.el.setAttribute('title', tooltip);
                    info.el.setAttribute('data-toggle', 'tooltip');

                    console.log('✅ Event mounted:', info.event.title, 'on', info.event.startStr);
                },
                eventClick: function(info) {
                    console.log('🖱️ Event clicked:', info.event);

                    Swal.fire({
                        title: info.event.title,
                        html: `
                            <div class="appointment-details text-right" dir="rtl" style="text-align: right;">
                                <p><strong>العميل:</strong> ${info.event.extendedProps.client_name || 'غير محدد'}</p>
                                <p><strong>رقم الهاتف:</strong> ${info.event.extendedProps.client_phone || 'غير متوفر'}</p>
                                <p><strong>التاريخ:</strong> ${moment(info.event.start).format('YYYY-MM-DD')}</p>
                                <p><strong>الوقت:</strong> ${info.event.extendedProps.time || 'غير محدد'}</p>
                                <p><strong>الحالة:</strong> <span style="background: ${info.event.backgroundColor}; color: white; padding: 4px 8px; border-radius: 4px;">${info.event.extendedProps.status_text || 'غير محدد'}</span></p>
                                <p><strong>الموظف:</strong> ${info.event.extendedProps.employee || 'غير محدد'}</p>
                                <p><strong>ملاحظات:</strong> ${info.event.extendedProps.notes || 'لا توجد ملاحظات'}</p>
                            </div>
                        `,
                        confirmButtonText: 'إغلاق',
                        width: '600px',
                        customClass: {
                            container: 'rtl-swal',
                            popup: 'rtl-popup',
                            confirmButton: 'btn btn-primary'
                        }
                    });
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false,
                    hour12: false
                },
                dayMaxEvents: 3,
                firstDay: 6, // السبت
                dateClick: function(info) {
                    console.log('📅 Date clicked:', info.dateStr);

                    Swal.fire({
                        title: 'إضافة موعد جديد',
                        text: `هل ترغب في إضافة موعد جديد بتاريخ ${info.dateStr}؟`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'نعم، أضف موعد',
                        cancelButtonText: 'لا'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `{{ route('appointments.create') }}?date=${info.dateStr}`;
                        }
                    });
                },
                height: 'auto',
                loading: function(isLoading) {
                    if (isLoading) {
                        calendarEl.classList.add('calendar-loading');
                        console.log('⏳ Calendar loading...');
                    } else {
                        calendarEl.classList.remove('calendar-loading');
                        console.log('✅ Calendar loaded');
                    }
                },
                eventContent: function(arg) {
                    // تخصيص عرض الحدث
                    let timeText = arg.event.extendedProps.time || '';
                    let italicEl = document.createElement('div');
                    italicEl.innerHTML = `
                        <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.85em;">
                            <strong>${timeText}</strong> ${arg.event.title}
                        </div>
                    `;
                    return { domNodes: [italicEl] };
                }
            });

            // عرض التقويم
            calendar.render();

            console.log('✅ Calendar rendered successfully with', events.length, 'events');

            // عرض معلومات إضافية
            const eventsInfo = calendar.getEvents();
            console.log('📊 Total events in calendar:', eventsInfo.length);

        } catch (error) {
            console.error('❌ Error initializing calendar:', error);
            calendarEl.innerHTML = `
                <div class="alert alert-danger text-center" dir="rtl">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>حدث خطأ في تحميل التقويم</strong>
                    <p class="mb-0 mt-2">يرجى تحديث الصفحة أو الاتصال بالدعم الفني</p>
                </div>
            `;
        }
    }
});
</script>
