@extends('master')

@section('title', 'عرض خطط السير الأسبوعية')
@section('css')
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.print.min.css" media="print" />
    <style>
        /* Calendar Customizations */
        .fc-event {
            cursor: pointer;
            border-radius: 4px;
            padding: 4px;
            margin-bottom: 2px;
            font-size: 0.85rem;
        }
        .fc-day-grid-event .fc-content {
            white-space: normal;
            overflow: hidden;
            padding: 2px;
        }
        .fc-time {
            display: none;
        }
        .employee-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            margin-left: 4px;
            font-size: 0.7rem;
            color: white;
            background-color: var(--gray-600);
        }
        .completed-visit {
            border-left: 3px solid #28a745;
        }
        .incompleted-visit {
            border-left: 3px solid #ffc107;
        }
        .fc-today {
            background-color: rgba(0, 123, 255, 0.1) !important;
        }
        .visit-stats {
            font-size: 0.65rem;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .visit-stats i {
            font-size: 0.6rem;
        }
        .visit-count-label {
            padding: 1px 5px;
            border-radius: 8px;
            color: white;
            background-color: var(--gray-600);
        }
        .visit-week-label {
            font-size: 0.7rem;
            color: var(--gray-700);
            font-weight: bold;
        }
        .calendar-loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 300px;
        }
        .week-tabs {
            display: flex;
            overflow-x: auto;
            margin-bottom: 1.5rem;
            padding: 0.5rem 0;
            gap: 0.5rem;
            scrollbar-width: thin;
        }
        .week-tabs::-webkit-scrollbar {
            height: 5px;
        }
        .week-tabs::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .week-tabs::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        .week-tab {
            background-color: var(--white);
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            flex: 0 0 auto;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 200px;
            position: relative;
        }
        .week-tab:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        .week-tab.active {
            background-color: var(--gray-100);
            border-color: var(--gray-300);
            box-shadow: var(--shadow);
        }
        .week-tab-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .week-tab-info {
            display: flex;
            align-items: center;
            font-size: 0.75rem;
            color: var(--gray-600);
            margin-top: 0.5rem;
            gap: 8px;
        }
        .visit-details-modal .modal-body {
            max-height: 60vh;
            overflow-y: auto;
        }
        .visit-row {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: var(--border-radius);
            border: 1px solid var(--gray-300);
            transition: all 0.3s ease;
        }
        .visit-row:hover {
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }
        .lazy-load {
            opacity: 0;
            transition: opacity 0.3s;
        }
        .loaded {
            opacity: 1;
        }
        /* Optimize for performance */
        .main-card, .stats-card {
            will-change: transform;
        }
    </style>
@endsection

@section('content')
    <!-- Header Section -->
    <div class="page-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-title">
                        <i class="fas fa-route me-3"></i>
                        <h2 class="mb-0">خطط السير الأسبوعية</h2>
                        <p class="text-muted mt-2">عرض آخر ٥ أسابيع من خطط السير للمناديب</p>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('itinerary.create') }}" class="btn btn-primary btn-lg shadow-sm">
                        <i class="fas fa-plus me-2"></i>
                        أضف خط سير جديد
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="container-fluid mb-4">
        <div class="row g-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card lazy-load">
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stats-content">
                        <h3 id="totalWeeks">-</h3>
                        <p>إجمالي الأسابيع</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card lazy-load">
                    <div class="stats-icon bg-info">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <h3 id="totalEmployees">-</h3>
                        <p>إجمالي المناديب</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card lazy-load">
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stats-content">
                        <h3 id="totalVisits">-</h3>
                        <p>إجمالي الزيارات المخططة</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card lazy-load">
                    <div class="stats-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-content">
                        <h3 id="completedVisits">-</h3>
                        <p>الزيارات المكتملة</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Toggle Buttons -->
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-12">
                <div class="btn-group view-toggle-group">
                    <button class="btn btn-outline-primary active" id="calendarViewBtn">
                        <i class="fas fa-calendar-alt me-1"></i> عرض التقويم
                    </button>
                    <button class="btn btn-outline-primary" id="weekViewBtn">
                        <i class="fas fa-list me-1"></i> عرض الأسابيع
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Week Tabs (Last 5 Weeks) -->
    <div class="container-fluid mb-4 d-none" id="weekTabsContainer">
        <div class="row">
            <div class="col-12">
                <div class="week-tabs" id="weekTabs">
                    <!-- Week tabs will be dynamically loaded here -->
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Calendar View (Default) -->
            <div class="col-12" id="calendarView">
                <div class="main-card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    تقويم خطط السير
                                </h4>
                                <p class="card-subtitle text-muted mb-0">عرض الزيارات على التقويم الأسبوعي</p>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="prevBtn">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="todayBtn">
                                        اليوم
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="nextBtn">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                </div>
                                <div class="btn-group ms-2" role="group">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="monthViewBtn">
                                        شهر
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm active" id="weekViewCalendarBtn">
                                        أسبوع
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="dayViewBtn">
                                        يوم
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <!-- Week Detail View (Initially Hidden) -->
            <div class="col-12 d-none" id="weekDetailView">
                <div class="main-card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-1" id="weekDetailTitle">
                                    <i class="fas fa-calendar-week me-2"></i>
                                    تفاصيل الأسبوع
                                </h4>
                                <p class="card-subtitle text-muted mb-0" id="weekDetailDates"></p>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" id="backToCalendarBtn">
                                    <i class="fas fa-arrow-right me-1"></i>
                                    العودة للتقويم
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="weekActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cog me-1"></i> خيارات
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="weekActionsDropdown">
                                        <li><a class="dropdown-item" href="#" id="expandAllEmployeesBtn"><i class="fas fa-expand me-2"></i> عرض جميع الموظفين</a></li>
                                        <li><a class="dropdown-item" href="#" id="collapseAllEmployeesBtn"><i class="fas fa-compress me-2"></i> إخفاء جميع الموظفين</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" id="printWeekBtn"><i class="fas fa-print me-2"></i> طباعة الأسبوع</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="weekDetailContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">جاري التحميل...</span>
                            </div>
                            <p class="mt-2">جاري تحميل بيانات الأسبوع...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visit Details Modal -->
    <div class="modal fade" id="visitDetailsModal" tabindex="-1" aria-labelledby="visitDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="visitDetailsModalLabel">تفاصيل الزيارات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body visit-details-modal">
                    <div id="visitDetailsContent">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">جاري التحميل...</span>
                            </div>
                            <p class="mt-2">جاري تحميل البيانات...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Visit Confirmation Modal -->
    <div class="modal fade" id="deleteVisitModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد حذف الزيارة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد من رغبتك في حذف هذه الزيارة؟</p>
                    <p class="text-danger">لا يمكن التراجع عن هذا الإجراء.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">حذف</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Required JS Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/ar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/ar.js"></script>

    <script>
        let itineraryData = null;
        let currentWeekIdentifier = null;
        let calendar = null;
        let deleteVisitId = null;

        // تهيئة التقويم والبيانات
        $(document).ready(function() {
            // تحميل البيانات
            fetchItineraryData();

            // تهيئة التقويم
            initializeCalendar();

            // تهيئة أحداث الواجهة
            setupEventHandlers();

            // تنشيط التأثيرات البصرية بعد التحميل
            setTimeout(() => {
                $('.lazy-load').addClass('loaded');
            }, 200);
        });

        // جلب بيانات خطط السير
        function fetchItineraryData() {
            $.ajax({
                url: "{{ route('api.itinerary.full') }}",
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        itineraryData = response.data;

                        // تحديث الإحصائيات
                        updateStatistics(itineraryData.statistics);

                        // تحميل علامات تبويب الأسابيع (آخر 5 أسابيع فقط)
                        loadWeekTabs(itineraryData.weeks.slice(0, 5));

                        // تحديث التقويم
                        updateCalendarEvents(itineraryData.weeks);
                    } else {
                        console.error('خطأ في تحميل البيانات');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('خطأ في الاتصال بالخادم:', error);
                }
            });
        }

        // تهيئة التقويم
        function initializeCalendar() {
            calendar = $('#calendar').fullCalendar({
                header: false, // نحن نستخدم أزرارنا المخصصة
                defaultView: 'agendaWeek',
                contentHeight: 'auto',
                locale: 'ar',
                rtl: true, // من اليمين إلى اليسار
                firstDay: 6, // السبت هو اليوم الأول
                hiddenDays: [], // لا توجد أيام مخفية
                allDaySlot: false,
                slotLabelFormat: 'h:mm a',
                slotDuration: '01:00:00',
                slotLabelInterval: '01:00:00',
                minTime: '08:00:00',
                maxTime: '18:00:00',
                columnHeaderFormat: 'ddd DD/MM',
                timeFormat: 'h:mm a',
                views: {
                    month: {
                        titleFormat: 'MMMM YYYY',
                        columnHeaderFormat: 'ddd'
                    },
                    week: {
                        titleFormat: 'MMM D YYYY',
                        columnHeaderFormat: 'ddd DD/MM'
                    },
                    day: {
                        titleFormat: 'dddd, MMMM D, YYYY',
                        columnHeaderFormat: 'dddd'
                    }
                },
                dayRender: function(date, cell) {
                    const today = moment();
                    if (date.isSame(today, 'day')) {
                        cell.addClass('fc-today');
                    }
                },
                eventRender: function(event, element) {
                    // تخصيص عرض الحدث
                    element.find('.fc-title').html(event.title);

                    if (event.completed) {
                        element.addClass('completed-visit');
                    } else {
                        element.addClass('incompleted-visit');
                    }

                    if (event.employeeName) {
                        element.append('<div class="employee-badge">' + event.employeeName + '</div>');
                    }

                    if (event.clientCode) {
                        element.append('<div class="visit-stats"><span class="visit-count-label">' + event.clientCode + '</span></div>');
                    }
                },
                eventClick: function(calEvent, jsEvent, view) {
                    showVisitDetails(calEvent);
                },
                loading: function(isLoading) {
                    if (!isLoading) {
                        // التقويم جاهز
                    }
                }
            });

            // أزرار التنقل في التقويم
            $('#prevBtn').click(function() {
                calendar.fullCalendar('prev');
            });

            $('#nextBtn').click(function() {
                calendar.fullCalendar('next');
            });

            $('#todayBtn').click(function() {
                calendar.fullCalendar('today');
            });

            // أزرار تغيير العرض
            $('#monthViewBtn').click(function() {
                $('.btn-group button').removeClass('active');
                $(this).addClass('active');
                calendar.fullCalendar('changeView', 'month');
            });

            $('#weekViewCalendarBtn').click(function() {
                $('.btn-group button').removeClass('active');
                $(this).addClass('active');
                calendar.fullCalendar('changeView', 'agendaWeek');
            });

            $('#dayViewBtn').click(function() {
                $('.btn-group button').removeClass('active');
                $(this).addClass('active');
                calendar.fullCalendar('changeView', 'agendaDay');
            });
        }

        // تحديث أحداث التقويم
        function updateCalendarEvents(weeks) {
            if (!calendar) return;

            // إزالة جميع الأحداث الحالية
            calendar.fullCalendar('removeEvents');

            // إضافة أحداث جديدة
            const events = [];

            weeks.forEach(week => {
                const weekStartDate = moment(week.from);

                week.employees.forEach(employee => {
                    Object.keys(employee.days).forEach(day => {
                        const dayData = employee.days[day];
                        const dayOffset = getDayOffset(day); // وظيفة مساعدة للحصول على إزاحة اليوم
                        const visitDate = moment(weekStartDate).add(dayOffset, 'days');

                        // إذا كان هناك زيارات في هذا اليوم
                        if (dayData.visit_count > 0) {
                            // إضافة حدث مجمع للزيارات المتعددة
                            if (dayData.visit_count > 1) {
                                events.push({
                                    title: '<strong>' + dayData.visit_count + ' زيارات</strong>',
                                    start: visitDate.format('YYYY-MM-DD'),
                                    allDay: true,
                                    employeeName: employee.name,
                                    className: 'multiple-visits',
                                    completed: dayData.completed === dayData.visit_count,
                                    weekId: week.year + '-W' + week.week_number,
                                    employeeId: employee.id,
                                    day: day,
                                    visits: dayData.visits
                                });
                            }
                            // إضافة أحداث فردية لكل زيارة
                            else {
                                const visit = dayData.visits[0];
                                events.push({
                                    title: '<strong>' + visit.name + '</strong>',
                                    start: visitDate.format('YYYY-MM-DD'),
                                    allDay: true,
                                    employeeName: employee.name,
                                    className: 'single-visit',
                                    completed: visit.status === 'تمت الزيارة',
                                    clientCode: visit.code,
                                    weekId: week.year + '-W' + week.week_number,
                                    employeeId: employee.id,
                                    day: day,
                                    visits: [visit]
                                });
                            }
                        }
                    });
                });
            });

            // إضافة الأحداث إلى التقويم
            calendar.fullCalendar('addEventSource', events);
        }

        // تحميل علامات تبويب الأسابيع
        function loadWeekTabs(weeks) {
            if (!weeks || weeks.length === 0) {
                $('#weekTabs').html('<div class="alert alert-info">لا توجد بيانات لعرضها</div>');
                return;
            }

            let tabsHtml = '';

            weeks.forEach((week, index) => {
                const weekIdentifier = week.year + '-W' + week.week_number;
                const isActive = index === 0;

                if (isActive) {
                    currentWeekIdentifier = weekIdentifier;
                }

                tabsHtml += `
                    <div class="week-tab ${isActive ? 'active' : ''}" data-week-id="${weekIdentifier}">
                        <div class="week-tab-title">الأسبوع ${week.week_number} - ${week.year}</div>
                        <div class="text-muted" style="font-size: 0.8rem;">
                            ${moment(week.from).format('DD/MM/YYYY')} - ${moment(week.to).format('DD/MM/YYYY')}
                        </div>
                        <div class="week-tab-info">
                            <span><i class="fas fa-users"></i> ${week.employee_count}</span>
                            <span><i class="fas fa-building"></i> ${week.total_visits}</span>
                            <span><i class="fas fa-check-circle"></i> ${week.completed_visits}</span>
                        </div>
                    </div>
                `;
            });

            $('#weekTabs').html(tabsHtml);

            // إضافة حدث النقر على علامات التبويب
            $('.week-tab').click(function() {
                const weekId = $(this).data('week-id');
                $('.week-tab').removeClass('active');
                $(this).addClass('active');
                currentWeekIdentifier = weekId;
                loadWeekDetails(weekId);

                // عرض تفاصيل الأسبوع وإخفاء التقويم
                $('#calendarView').addClass('d-none');
                $('#weekDetailView').removeClass('d-none');
            });
        }

        // تحميل تفاصيل الأسبوع
        function loadWeekDetails(weekId) {
            // البحث عن بيانات الأسبوع
            const weekParts = weekId.split('-W');
            const year = weekParts[0];
            const weekNumber = weekParts[1];

            const week = itineraryData.weeks.find(w => w.year == year && w.week_number == weekNumber);

            if (!week) {
                $('#weekDetailContent').html('<div class="alert alert-danger">لم يتم العثور على بيانات الأسبوع</div>');
                return;
            }

            // تحديث عنوان تفاصيل الأسبوع
            $('#weekDetailTitle').html(`<i class="fas fa-calendar-week me-2"></i> الأسبوع ${week.week_number} - ${week.year}`);
            $('#weekDetailDates').html(`${moment(week.from).format('DD/MM/YYYY')} - ${moment(week.to).format('DD/MM/YYYY')}`);

            // بناء محتوى الأسبوع
            let weekHtml = '';

            // إضافة إحصائيات الأسبوع
            weekHtml += `
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon bg-info">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stats-content">
                                <h3>${week.employee_count}</h3>
                                <p>المناديب</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon bg-warning">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stats-content">
                                <h3>${week.total_visits}</h3>
                                <p>إجمالي الزيارات</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stats-content">
                                <h3>${week.completed_visits}</h3>
                                <p>الزيارات المكتملة</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon bg-primary">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="stats-content">
                                <h3>${week.new_clients}</h3>
                                <p>العملاء الجدد</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- قائمة الموظفين في الأسبوع -->
                <div class="accordion-modern mb-4" id="employeesAccordion">
                `;

            // إضافة بطاقات الموظفين
            week.employees.forEach((employee, empIndex) => {
                weekHtml += `
                    <div class="accordion-item" id="employee-${employee.id}">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#employeeCollapse-${employee.id}" aria-expanded="false">
                                <div class="employee-info">
                                    <div class="employee-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="employee-details">
                                        <h6 class="employee-name">${employee.name}</h6>
                                        <div class="employee-stats">
                                            <span class="badge bg-secondary me-2">
                                                <i class="fas fa-building me-1"></i>
                                                ${employee.total_visits} زيارات
                                            </span>
                                            <span class="badge bg-success me-2">
                                                <i class="fas fa-check-circle me-1"></i>
                                                ${employee.completed_visits} مكتملة
                                            </span>
                                            <span class="badge bg-warning me-2">
                                                <i class="fas fa-clock me-1"></i>
                                                ${employee.incompleted_visits} غير مكتملة
                                            </span>
                                            ${employee.new_clients > 0 ? `
                                            <span class="badge bg-info">
                                                <i class="fas fa-user-plus me-1"></i>
                                                ${employee.new_clients} عملاء جدد
                                            </span>` : ''}
                                        </div>
                                    </div>
                                </div>
                                <i class="accordion-arrow fas fa-chevron-down"></i>
                            </button>
                        </h2>
                        <div id="employeeCollapse-${employee.id}" class="accordion-collapse collapse" data-bs-parent="#employeesAccordion">
                            <div class="accordion-body">
                                <!-- أيام الأسبوع للموظف -->
                                <div class="row g-3">
                `;

                // إضافة بطاقات الأيام
                const days = {
                    'saturday': 'السبت',
                    'sunday': 'الأحد',
                    'monday': 'الإثنين',
                    'tuesday': 'الثلاثاء',
                    'wednesday': 'الأربعاء',
                    'thursday': 'الخميس',
                    'friday': 'الجمعة'
                };

                Object.keys(days).forEach(dayKey => {
                    const dayName = days[dayKey];
                    const dayData = employee.days[dayKey] || { visit_count: 0, completed: 0, visits: [] };

                    weekHtml += `
                        <div class="col-md-6 col-lg-4">
                            <div class="day-card">
                                <div class="day-header">
                                    <h6 class="day-name">${dayName}</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="visits-count">
                                            ${dayData.visit_count} زيارة
                                        </span>
                                        ${dayData.new_clients > 0 ? `
                                        <span class="badge bg-info ms-2" title="عملاء جدد">
                                            <i class="fas fa-user-plus me-1"></i>
                                            ${dayData.new_clients}
                                        </span>` : ''}
                                    </div>
                                </div>
                                <div class="day-visits">
                    `;

                    if (dayData.visit_count > 0) {
                        // إضافة إحصائيات اليوم
                        weekHtml += `
                            <div class="day-stats mb-2">
                                <span class="badge bg-success text-white">
                                    <i class="fas fa-check-circle me-1"></i>
                                    ${dayData.completed} مكتملة
                                </span>
                                ${(dayData.visit_count - dayData.completed) > 0 ? `
                                <span class="badge bg-warning text-dark ms-1">
                                    <i class="fas fa-clock me-1"></i>
                                    ${(dayData.visit_count - dayData.completed)} غير مكتملة
                                </span>` : ''}
                            </div>
                        `;

                        // إضافة بطاقات الزيارات
                        dayData.visits.forEach((visit, visitIndex) => {
                            weekHtml += `
                                <div class="visit-row mb-2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="visit-number">
                                                ${visitIndex + 1}
                                            </div>
                                            <div class="visit-info">
                                                <h6 class="client-name mb-0">${visit.name}</h6>
                                                <p class="client-code mb-0 text-muted">${visit.code || '---'}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            ${visit.status === 'تمت الزيارة' ? `
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>
                                            </span>` : `
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times-circle me-1"></i>
                                            </span>`}

                                            ${visit.is_new ? `
                                            <span class="badge bg-info" title="عميل جديد">
                                                <i class="fas fa-user-plus"></i>
                                            </span>` : ''}

                                            ${visit.client_status ? `
                                            <span class="badge rounded-pill" style="background-color: ${visit.client_status.color}">
                                                ${visit.client_status.name}
                                            </span>` : ''}

                                            <button class="btn btn-sm btn-outline-danger delete-visit-btn"
                                                data-visit-id="${visit.id}"
                                                title="حذف الزيارة">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        weekHtml += `
                            <div class="no-visits text-center py-3">
                                <i class="fas fa-calendar-times text-muted mb-2"></i>
                                <p class="text-muted mb-0">لا يوجد زيارات مخططة</p>
                            </div>
                        `;
                    }

                    weekHtml += `
                                </div>
                            </div>
                        </div>
                    `;
                });

                weekHtml += `
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            weekHtml += `
                </div>
            `;

            // تحديث المحتوى
            $('#weekDetailContent').html(weekHtml);

            // تنشيط أحداث حذف الزيارة
            setupDeleteVisitEvents();
        }

        // تحديث الإحصائيات
        function updateStatistics(stats) {
            $('#totalWeeks').text(stats.total_weeks || 0);
            $('#totalEmployees').text(stats.total_employees || 0);
            $('#totalVisits').text(stats.total_visits || 0);
            $('#completedVisits').text(stats.completed_visits || 0);
        }

        // تهيئة أحداث الواجهة
        function setupEventHandlers() {
            // تبديل العرض بين التقويم والأسابيع
            $('#calendarViewBtn').click(function() {
                $(this).addClass('active');
                $('#weekViewBtn').removeClass('active');
                $('#calendarView').removeClass('d-none');
                $('#weekTabsContainer').addClass('d-none');
                $('#weekDetailView').addClass('d-none');
            });

            $('#weekViewBtn').click(function() {
                $(this).addClass('active');
                $('#calendarViewBtn').removeClass('active');
                $('#calendarView').addClass('d-none');
                $('#weekTabsContainer').removeClass('d-none');

                // إذا كان هناك أسبوع محدد مسبقًا، اعرضه
                if (currentWeekIdentifier) {
                    $('#weekDetailView').removeClass('d-none');
                } else {
                    // اختر أول أسبوع افتراضيًا
                    $('.week-tab').first().trigger('click');
                }
            });

            // زر العودة للتقويم
            $('#backToCalendarBtn').click(function() {
                $('#calendarViewBtn').trigger('click');
            });

            // أزرار توسيع/طي جميع الموظفين
            $('#expandAllEmployeesBtn').click(function() {
                $('.accordion-collapse').collapse('show');
            });

            $('#collapseAllEmployeesBtn').click(function() {
                $('.accordion-collapse').collapse('hide');
            });

            // زر طباعة الأسبوع
            $('#printWeekBtn').click(function() {
                window.print();
            });

            // تأكيد حذف الزيارة
            $('#confirmDeleteBtn').click(function() {
                if (!deleteVisitId) return;

                // إرسال طلب الحذف
                $.ajax({
                    url: `/itinerary/visits/${deleteVisitId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // إعادة تحميل البيانات
                            fetchItineraryData();

                            // إغلاق النافذة
                            $('#deleteVisitModal').modal('hide');

                            // عرض رسالة النجاح
                            alert('تم حذف الزيارة بنجاح');
                        } else {
                            alert('حدث خطأ أثناء حذف الزيارة');
                        }
                    },
                    error: function() {
                        alert('حدث خطأ في الاتصال بالخادم');
                    }
                });
            });
        }

        // تهيئة أحداث حذف الزيارة
        function setupDeleteVisitEvents() {
            $('.delete-visit-btn').click(function(e) {
                e.preventDefault();
                e.stopPropagation();

                // تخزين معرف الزيارة المراد حذفها
                deleteVisitId = $(this).data('visit-id');

                // عرض نافذة التأكيد
                $('#deleteVisitModal').modal('show');
            });
        }

        // عرض تفاصيل الزيارة
        function showVisitDetails(eventData) {
            if (!eventData || !eventData.visits) return;

            // تحديث عنوان النافذة
            const day = getArabicDay(eventData.day);
            const date = moment().year(eventData.weekId.split('-W')[0])
                                 .week(eventData.weekId.split('-W')[1])
                                 .day(getDayOffset(eventData.day))
                                 .format('DD/MM/YYYY');

            $('#visitDetailsModalLabel').html(`
                <i class="fas fa-calendar-day me-2"></i>
                زيارات ${day} (${date})
            `);

            // بناء محتوى تفاصيل الزيارة
            let content = `
                <div class="mb-3">
                    <span class="badge bg-primary">
                        <i class="fas fa-user me-1"></i>
                        ${eventData.employeeName}
                    </span>
                    <span class="badge bg-secondary ms-2">
                        <i class="fas fa-building me-1"></i>
                        ${eventData.visits.length} زيارة
                    </span>
                </div>
            `;

            // إضافة قائمة الزيارات
            content += '<div class="row">';

            eventData.visits.forEach((visit, index) => {
                content += `
                    <div class="col-md-6 mb-3">
                        <div class="visit-row">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0">
                                    <span class="badge bg-secondary me-2">${index + 1}</span>
                                    ${visit.name}
                                </h6>
                                <div>
                                    ${visit.status === 'تمت الزيارة' ? `
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        تمت الزيارة
                                    </span>` : `
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>
                                        لم تتم الزيارة
                                    </span>`}
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-2">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-barcode me-1"></i>
                                    ${visit.code || 'بدون كود'}
                                </span>

                                ${visit.is_new ? `
                                <span class="badge bg-info">
                                    <i class="fas fa-user-plus me-1"></i>
                                    عميل جديد
                                </span>` : ''}

                                ${visit.client_status ? `
                                <span class="badge rounded-pill" style="background-color: ${visit.client_status.color}">
                                    <i class="fas fa-circle me-1"></i>
                                    ${visit.client_status.name}
                                </span>` : ''}
                            </div>

                            <div class="d-flex justify-content-end mt-2">
                                <a href="/clients/${visit.id}" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-eye me-1"></i>
                                    تفاصيل العميل
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-visit-btn"
                                    data-visit-id="${visit.id}"
                                    data-bs-dismiss="modal">
                                    <i class="fas fa-trash me-1"></i>
                                    حذف
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            content += '</div>';

            // تحديث المحتوى
            $('#visitDetailsContent').html(content);

            // عرض النافذة
            $('#visitDetailsModal').modal('show');

            // تهيئة أحداث الحذف
            setupDeleteVisitEvents();
        }

        // وظائف مساعدة

        // الحصول على إزاحة اليوم (بالنسبة للسبت كيوم 0)
        function getDayOffset(day) {
            const dayMap = {
                'saturday': 0,
                'sunday': 1,
                'monday': 2,
                'tuesday': 3,
                'wednesday': 4,
                'thursday': 5,
                'friday': 6
            };

            return dayMap[day.toLowerCase()] || 0;
        }

        // الحصول على اسم اليوم بالعربية
        function getArabicDay(day) {
            const dayMap = {
                'saturday': 'السبت',
                'sunday': 'الأحد',
                'monday': 'الإثنين',
                'tuesday': 'الثلاثاء',
                'wednesday': 'الأربعاء',
                'thursday': 'الخميس',
                'friday': 'الجمعة'
            };

            return dayMap[day.toLowerCase()] || day;
        }

        // تنفيذ تحسينات الأداء
        document.addEventListener('DOMContentLoaded', function() {
            // تأخير تحميل المكونات غير المرئية
            setTimeout(() => {
                // تشغيل التقويم بعد تحميل الصفحة لتحسين الأداء
                if (calendar) {
                    calendar.fullCalendar('render');
                }

                // تحميل الصور بكسل عديمة
                const lazyImages = document.querySelectorAll('.lazy-image');
                lazyImages.forEach(img => {
                    img.src = img.dataset.src;
                });
            }, 300);

            // إضافة تقنية Intersection Observer للعناصر الكسولة
            if ('IntersectionObserver' in window) {
                const lazyElements = document.querySelectorAll('.lazy-load');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('loaded');
                            observer.unobserve(entry.target);
                        }
                    });
                });

                lazyElements.forEach(el => observer.observe(el));
            }

            // تطبيق تقنيات التحسين
            document.querySelectorAll('a, button').forEach(el => {
                el.addEventListener('touchstart', function() {}, {passive: true});
            });

            // تحسين تمرير الصفحة
            document.querySelectorAll('.week-tabs, .visit-details-modal .modal-body').forEach(el => {
                el.addEventListener('scroll', function() {}, {passive: true});
            });
        });
    </script>

    <style>
        /* أنماط الطباعة */
        @media print {
            header, footer, .page-header, .view-toggle-group, .btn-group,
            .delete-visit-btn, #calendarViewBtn, #weekViewBtn, .accordion-button::after,
            .modal, .btn-close {
                display: none !important;
            }

            body, html {
                width: 100% !important;
                height: auto !important;
                overflow: visible !important;
            }

            .main-card {
                break-inside: avoid;
            }

            .accordion-collapse {
                display: block !important;
            }

            .container-fluid {
                width: 100% !important;
                padding: 0 !important;
            }

            .fc-button-group, .fc-right, .fc-left {
                display: none !important;
            }

            .day-card {
                break-inside: avoid;
            }

            .stats-card {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
@endsection