@extends('sales::master')

@section('title', 'عرض جميع خطط السير')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/listIntry.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <style>
        .fc-event {
            cursor: pointer;
            padding: 4px;
            margin: 2px 0;
            border-radius: 4px;
            background-color: var(--primary-light);
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }

        .fc-day-today {
            background-color: var(--primary-light) !important;
        }

        .fc-button-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .calendar-container {
            background: #fff;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-top: 2rem;
        }

        /* Custom styles for calendar view */
        .view-toggle {
            margin-bottom: 20px;
            text-align: center;
        }

        .view-toggle .btn {
            margin: 0 5px;
        }

        .calendar-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .calendar-header h4 {
            margin: 0;
            font-weight: 600;
        }

        .fc-toolbar-title {
            color: var(--primary-color) !important;
            font-size: 1.5rem !important;
        }

        .fc .fc-button {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .fc .fc-button:hover {
            opacity: 0.9;
        }

        .fc .fc-button:disabled {
            background-color: var(--primary-light) !important;
            border-color: var(--primary-light) !important;
        }

        .visit-event {
            cursor: pointer;
            padding: 3px 5px;
            margin: 1px 0;
            border-radius: 3px;
            font-size: 0.85rem;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .legend-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 3px;
        }

        /* View toggle styles */
        .view-toggle .btn {
            transition: all 0.3s ease;
        }

        .view-toggle .btn.active {
            background-color: var(--primary-color);
            color: white;
        }

        /* Calendar view by default */
        #listView {
            display: none;
        }

        #calendarView {
            display: block;
        }
    </style>
@endsection
@section('content')

    <!-- Header Section -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">ادارة خط السير </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">الرئيسيه</a>
                            </li>
                            <li class="breadcrumb-item active">عرض
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap justify-content-end" style="gap: 10px;">
                <!-- زر أضف موعد جديد -->
                <a href="{{ route('itinerary.create') }}"
                    class="btn btn-primary d-flex align-items-center justify-content-center"
                    style="height: 44px; padding: 0 16px; font-weight: bold; border-radius: 6px;">
                    <i class="fas fa-plus ms-2"></i>
                    أضف خط سير جديد
                </a>
            </div>
        </div>
    </div>

    <!-- View Toggle Buttons -->
    <div class="view-toggle">
        <button id="listViewBtn" class="btn btn-outline-primary">عرض القائمة</button>
        <button id="calendarViewBtn" class="btn btn-outline-primary active">عرض التقويم</button>
    </div>

    <!-- Calendar View -->
    <div id="calendarView">
        <div class="calendar-header">
            <h4><i class="fas fa-calendar-alt me-2"></i> تقويم خط السير</h4>
        </div>
        <div class="calendar-container">
            <div id="itinerary-calendar"></div>
        </div>

        <!-- Legend for calendar events -->
        <div class="legend-container">
            <div class="legend-item">
                <div class="legend-color" style="background-color: #28a745;"></div>
                <span>زيارة مكتملة</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #ffc107;"></div>
                <span>زيارة غير مكتملة</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #17a2b8;"></div>
                <span>عميل جديد</span>
            </div>
        </div>
    </div>

    <!-- List View (Original Content) -->
    <div id="listView">
        <!-- Statistics Cards -->
        <div class="container-fluid mb-4">
            <div class="row g-4">
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                        <div class="stats-content">
                            <h3>
                                @php
                                    $totalWeeks = 0;
                                    $weeklyData = [];

                                    // تجميع البيانات حسب الأسبوع
                                    foreach ($itineraries as $employeeId => $employeeData) {
                                        $employeeItineraries = $employeeData['weeks'] ?? [];
                                        foreach ($employeeItineraries as $weekIdentifier => $weekVisits) {
                                            if (!isset($weeklyData[$weekIdentifier])) {
                                                $weeklyData[$weekIdentifier] = [];
                                                $totalWeeks++;
                                            }
                                            $weeklyData[$weekIdentifier][$employeeId] = [
                                                'employee' => $employeeData['employee'],
                                                'visits' => $weekVisits,
                                            ];
                                        }
                                    }
                                @endphp
                                {{ $totalWeeks }}
                            </h3>
                            <p>إجمالي الأسابيع</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon bg-info">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stats-content">
                            <h3>{{ count($itineraries) }}</h3>
                            <p>إجمالي المناديب</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stats-content">
                            <h3>
                                @php
                                    $totalPlannedVisits = 0;
                                    foreach ($itineraries as $employeeId => $employeeData) {
                                        $employeeVisits = 0;
                                        $weeks = $employeeData['weeks'] ?? [];
                                        foreach ($weeks as $week) {
                                            foreach ($week as $dayData) {
                                                $employeeVisits += count($dayData['visits'] ?? []);
                                            }
                                        }
                                        $totalPlannedVisits += $employeeVisits;
                                    }
                                @endphp
                                {{ $totalPlannedVisits }}
                            </h3>
                            <p>إجمالي الزيارات المخططة</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-content">
                            <h3>
                                @php
                                    $totalCompletedVisits = 0;
                                    foreach ($itineraries as $employeeId => $employeeData) {
                                        $weeks = $employeeData['weeks'] ?? [];
                                        foreach ($weeks as $week) {
                                            foreach ($week as $dayData) {
                                                foreach ($dayData['visits'] ?? [] as $visit) {
                                                    if ($visit->status === 'active') {
                                                        $totalCompletedVisits++;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                {{ $totalCompletedVisits }}
                            </h3>
                            <p>الزيارات المكتملة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="main-card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-1">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        خطط السير الأسبوعية
                                    </h4>
                                    <p class="card-subtitle text-muted mb-0">استعراض وإدارة خطط السير مجمعة حسب الأسبوع</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="expandAll()">
                                        <i class="fas fa-expand-arrows-alt me-1"></i>
                                        توسيع الكل
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="collapseAll()">
                                        <i class="fas fa-compress-arrows-alt me-1"></i>
                                        طي الكل
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" onclick="expandAllEmployees()">
                                        <i class="fas fa-users me-1"></i>
                                        عرض جميع الموظفين
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm" onclick="collapseAllEmployees()">
                                        <i class="fas fa-user-minus me-1"></i>
                                        إخفاء جميع الموظفين
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (empty($weeklyData))
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-route"></i>
                                    </div>
                                    <h3>لا توجد خطط سير</h3>
                                    <p>لم يتم إنشاء أي خطط سير بعد. ابدأ بإضافة خط سير جديد.</p>
                                    <a href="{{ route('itinerary.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>
                                        أضف خط سير الآن
                                    </a>
                                </div>
                            @else
                                <div class="accordion-modern" id="weeklyAccordion">
                                    @php
                                        $accordionItemIndex = 0;
                                        $days = [
                                            'saturday' => 'السبت',
                                            'sunday' => 'الأحد',
                                            'monday' => 'الإثنين',
                                            'tuesday' => 'الثلاثاء',
                                            'wednesday' => 'الأربعاء',
                                            'thursday' => 'الخميس',
                                            'friday' => 'الجمعة',
                                        ];

                                        // ترتيب الأسابيع من الأحدث للأقدم
                                        krsort($weeklyData);
                                    @endphp

                                    @foreach ($weeklyData as $weekIdentifier => $weekEmployees)
                                        @php
                                            $yearWeek = explode('-W', $weekIdentifier);
                                            $year = $yearWeek[0] ?? date('Y');
                                            $weekNum = $yearWeek[1] ?? '00';
                                            $accordionItemIndex++;

                                            // حساب إحصائيات الأسبوع
                                            $weekTotalVisits = 0;
                                            $weekCompletedVisits = 0;
                                            $weekNewClients = 0;
                                            $weekEmployeesCount = count($weekEmployees);

                                            foreach ($weekEmployees as $employeeId => $employeeData) {
                                                $weekVisits = $employeeData['visits'] ?? [];
                                                foreach ($days as $dayKey => $dayName) {
                                                    $dayVisits = count($weekVisits[$dayKey]['visits'] ?? []);
                                                    $weekTotalVisits += $dayVisits;

                                                    if (!empty($weekVisits[$dayKey]['visits'])) {
                                                        foreach ($weekVisits[$dayKey]['visits'] as $visit) {
                                                            if ($visit->status === 'active') {
                                                                $weekCompletedVisits++;
                                                            }
                                                        }
                                                    }
                                                    $weekNewClients += $weekVisits[$dayKey]['new_clients_count'] ?? 0;
                                                }
                                            }

                                            $weekIncompletedVisits = $weekTotalVisits - $weekCompletedVisits;

                                            // حساب تاريخ بداية ونهاية الأسبوع
                                            $weekStartDate = new DateTime();
                                            $weekStartDate->setISODate($year, $weekNum);
                                            $weekEndDate = clone $weekStartDate;
                                            $weekEndDate->modify('+6 days');
                                        @endphp

                                        <div class="accordion-item">
                                            <div class="accordion-header" id="weekHeading{{ $accordionItemIndex }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#weekCollapse{{ $accordionItemIndex }}"
                                                    aria-expanded="false"
                                                    aria-controls="weekCollapse{{ $accordionItemIndex }}">
                                                    <div class="week-info-header">
                                                        <div class="week-avatar">
                                                            <i class="fas fa-calendar-week"></i>
                                                        </div>
                                                        <div class="week-details">
                                                            <h5 class="week-title">
                                                                الأسبوع {{ $weekNum }} - {{ $year }}
                                                            </h5>
                                                            <div class="week-date-range mb-2">
                                                                <span class="badge bg-info me-2">
                                                                    <i class="far fa-calendar me-1"></i>
                                                                    {{ $weekStartDate->format('d/m/Y') }} -
                                                                    {{ $weekEndDate->format('d/m/Y') }}
                                                                </span>
                                                            </div>
                                                            <div class="week-stats">
                                                                <span class="badge bg-primary me-2">
                                                                    <i class="fas fa-users me-1"></i>
                                                                    {{ $weekEmployeesCount }} مندوب
                                                                </span>
                                                                <span class="badge bg-secondary me-2">
                                                                    <i class="fas fa-building me-1"></i>
                                                                    {{ $weekTotalVisits }} زيارات
                                                                </span>
                                                                <span class="badge bg-success me-2">
                                                                    <i class="fas fa-check-circle me-1"></i>
                                                                    {{ $weekCompletedVisits }} مكتملة
                                                                </span>
                                                                @if ($weekIncompletedVisits > 0)
                                                                    <span class="badge bg-warning me-2">
                                                                        <i class="fas fa-clock me-1"></i>
                                                                        {{ $weekIncompletedVisits }} غير مكتملة
                                                                    </span>
                                                                @endif
                                                                @if ($weekNewClients > 0)
                                                                    <span class="badge bg-info">
                                                                        <i class="fas fa-user-plus me-1"></i>
                                                                        {{ $weekNewClients }} عملاء جدد
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <i class="fas fa-chevron-down accordion-arrow"></i>
                                                </button>
                                            </div>

                                            <div id="weekCollapse{{ $accordionItemIndex }}"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="weekHeading{{ $accordionItemIndex }}"
                                                data-bs-parent="#weeklyAccordion">
                                                <div class="accordion-body">
                                                    <!-- أزرار التحكم بالموظفين -->
                                                    <div class="employees-controls mb-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-0">
                                                                <i class="fas fa-users text-primary me-2"></i>
                                                                الموظفين في هذا الأسبوع ({{ $weekEmployeesCount }})
                                                            </h6>
                                                            <div class="btn-group" role="group">
                                                                <button type="button"
                                                                    class="btn btn-outline-primary btn-sm expand-employees-btn"
                                                                    data-week="{{ $accordionItemIndex }}">
                                                                    <i class="fas fa-eye me-1"></i>
                                                                    عرض الكل
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary btn-sm collapse-employees-btn"
                                                                    data-week="{{ $accordionItemIndex }}">
                                                                    <i class="fas fa-eye-slash me-1"></i>
                                                                    إخفاء الكل
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="employees-in-week"
                                                        id="employeesContainer{{ $accordionItemIndex }}">
                                                        @foreach ($weekEmployees as $employeeId => $employeeData)
                                                            @php
                                                                $employee = $employeeData['employee'];
                                                                $weekVisits = $employeeData['visits'] ?? [];
                                                                $employeeIndex = $loop->index;

                                                                // حساب إحصائيات الموظف في هذا الأسبوع
                                                                $employeeWeekTotalVisits = 0;
                                                                $employeeWeekCompletedVisits = 0;
                                                                $employeeWeekNewClients = 0;

                                                                foreach ($days as $dayKey => $dayName) {
                                                                    $dayVisits = count(
                                                                        $weekVisits[$dayKey]['visits'] ?? [],
                                                                    );
                                                                    $employeeWeekTotalVisits += $dayVisits;

                                                                    if (!empty($weekVisits[$dayKey]['visits'])) {
                                                                        foreach ($weekVisits[$dayKey]['visits'] as $visit) {
                                                                            if ($visit->status === 'active') {
                                                                                $employeeWeekCompletedVisits++;
                                                                            }
                                                                        }
                                                                    }
                                                                    $employeeWeekNewClients +=
                                                                        $weekVisits[$dayKey]['new_clients_count'] ?? 0;
                                                                }

                                                                $employeeWeekIncompletedVisits =
                                                                    $employeeWeekTotalVisits - $employeeWeekCompletedVisits;
                                                            @endphp

                                                            <div class="employee-section mb-4"
                                                                data-employee-id="{{ $employeeId }}"
                                                                data-week="{{ $accordionItemIndex }}">
                                                                <!-- رأس الموظف مع إمكانية النقر للتوسيع/الطي -->
                                                                <div class="employee-header cursor-pointer"
                                                                    onclick="toggleEmployee({{ $accordionItemIndex }}, {{ $employeeId }})">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between">
                                                                        <div class="employee-info d-flex align-items-center">
                                                                            <div class="employee-toggle-icon me-2">
                                                                                <i class="fas fa-chevron-right transition-all"
                                                                                    id="employeeToggle{{ $accordionItemIndex }}_{{ $employeeId }}"></i>
                                                                            </div>
                                                                            <div class="employee-avatar">
                                                                                <i class="fas fa-user"></i>
                                                                            </div>
                                                                            <div class="employee-details">
                                                                                <h6 class="employee-name">
                                                                                    {{ $employee->name ?? 'غير معروف' }}</h6>
                                                                                <div class="employee-stats">
                                                                                    <span class="badge bg-secondary me-2">
                                                                                        <i class="fas fa-building me-1"></i>
                                                                                        {{ $employeeWeekTotalVisits }} زيارات
                                                                                    </span>
                                                                                    <span class="badge bg-success me-2">
                                                                                        <i
                                                                                            class="fas fa-check-circle me-1"></i>
                                                                                        {{ $employeeWeekCompletedVisits }}
                                                                                        مكتملة
                                                                                    </span>
                                                                                    @if ($employeeWeekIncompletedVisits > 0)
                                                                                        <span class="badge bg-warning me-2">
                                                                                            <i class="fas fa-clock me-1"></i>
                                                                                            {{ $employeeWeekIncompletedVisits }}
                                                                                            غير مكتملة
                                                                                        </span>
                                                                                    @endif
                                                                                    @if ($employeeWeekNewClients > 0)
                                                                                        <span class="badge bg-info">
                                                                                            <i
                                                                                                class="fas fa-user-plus me-1"></i>
                                                                                            {{ $employeeWeekNewClients }} عملاء
                                                                                            جدد
                                                                                        </span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="employee-actions">
                                                                            <a href="{{ route('itinerary.edit', $employee->id) }}"
                                                                                class="btn btn-outline-primary btn-sm"
                                                                                onclick="event.stopPropagation();">
                                                                                <i class="fas fa-edit me-1"></i>
                                                                                تعديل
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- جدول الموظف (مخفي افتراضياً) -->
                                                                <div class="employee-schedule mt-3"
                                                                    id="employeeSchedule{{ $accordionItemIndex }}_{{ $employeeId }}"
                                                                    style="display: none;">
                                                                    <div class="row g-3">
                                                                        @foreach ($days as $dayKey => $dayName)
                                                                            @php
                                                                                $dayVisits =
                                                                                    $weekVisits[$dayKey]['visits'] ?? [];
                                                                                $dayVisitCount = count($dayVisits);
                                                                                $dayCompletedVisits = 0;
                                                                                $dayNewClients =
                                                                                    $weekVisits[$dayKey][
                                                                                        'new_clients_count'
                                                                                    ] ?? 0;

                                                                                foreach ($dayVisits as $visit) {
                                                                                    if ($visit->status === 'active') {
                                                                                        $dayCompletedVisits++;
                                                                                    }
                                                                                }
                                                                            @endphp

                                                                            <div class="col-lg-3 col-md-6">
                                                                                <div class="day-card">
                                                                                    <div class="day-header">
                                                                                        <h6 class="day-name">
                                                                                            {{ $dayName }}</h6>
                                                                                        <div class="d-flex align-items-center">
                                                                                            <span class="visits-count">
                                                                                                {{ $dayVisitCount }} زيارة
                                                                                            </span>
                                                                                            @if ($dayNewClients > 0)
                                                                                                <span
                                                                                                    class="badge bg-info ms-2"
                                                                                                    title="عملاء جدد اليوم">
                                                                                                    <i
                                                                                                        class="fas fa-user-plus me-1"></i>
                                                                                                    {{ $dayNewClients }}
                                                                                                </span>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="day-visits">
                                                                                        @if ($dayVisitCount > 0)
                                                                                            <div class="day-stats mb-2">
                                                                                                <span
                                                                                                    class="badge bg-success text-white">
                                                                                                    <i
                                                                                                        class="fas fa-check-circle me-1"></i>
                                                                                                    {{ $dayCompletedVisits }}
                                                                                                    مكتملة
                                                                                                </span>
                                                                                                @if ($dayVisitCount - $dayCompletedVisits > 0)
                                                                                                    <span
                                                                                                        class="badge bg-warning text-dark ms-1">
                                                                                                        <i
                                                                                                            class="fas fa-clock me-1"></i>
                                                                                                        {{ $dayVisitCount - $dayCompletedVisits }}
                                                                                                        غير مكتملة
                                                                                                    </span>
                                                                                                @endif
                                                                                            </div>

                                                                                            @foreach ($dayVisits as $index => $visit)
                                                                                                @if (isset($visit->client))
                                                                                                    @php
                                                                                                        $client =
                                                                                                            $visit->client;
                                                                                                        $lastNote = $client
                                                                                                            ->appointmentNotes()
                                                                                                            ->where(
                                                                                                                'employee_id',
                                                                                                                auth()->id(),
                                                                                                            )
                                                                                                            ->where(
                                                                                                                'process',
                                                                                                                'إبلاغ المشرف',
                                                                                                            )
                                                                                                            ->whereNotNull(
                                                                                                                'employee_view_status',
                                                                                                            )
                                                                                                            ->latest()
                                                                                                            ->first();

                                                                                                        $statusToShow =
                                                                                                            $client->status_client;

                                                                                                        if (
                                                                                                            auth()->user()
                                                                                                                ->role ===
                                                                                                                'employee' &&
                                                                                                            $lastNote &&
                                                                                                            $lastNote->employee_id ==
                                                                                                                auth()->id()
                                                                                                        ) {
                                                                                                            $statusToShow = $statuses->find(
                                                                                                                $lastNote->employee_view_status,
                                                                                                            );
                                                                                                        }

                                                                                                        $isNewClient =
                                                                                                            $visit->client
                                                                                                                ->is_new_for_visit_date ??
                                                                                                            false;
                                                                                                    @endphp

                                                                                                    <div
                                                                                                        class="visit-card-wrapper d-flex align-items-center justify-content-between mb-2 flex-wrap">
                                                                                                        <a href="{{ route('clients.show', $visit->client->id) }}"
                                                                                                            class="visit-card flex-grow-1 d-flex align-items-center text-decoration-none text-dark flex-wrap">
                                                                                                            <div
                                                                                                                class="visit-number me-3">
                                                                                                                {{ $index + 1 }}
                                                                                                            </div>

                                                                                                            <div
                                                                                                                class="visit-info me-auto">
                                                                                                                <h6
                                                                                                                    class="client-name mb-0">
                                                                                                                    {{ $visit->client->trade_name ?? 'غير معروف' }}
                                                                                                                </h6>
                                                                                                                <p
                                                                                                                    class="client-code mb-0 text-muted">
                                                                                                                    {{ $visit->client->code ?? '---' }}
                                                                                                                </p>
                                                                                                            </div>

                                                                                                            <div
                                                                                                                class="d-flex align-items-center flex-wrap">
                                                                                                                @if ($visit->status === 'active')
                                                                                                                    <span
                                                                                                                        class="badge bg-success me-2 mb-1"
                                                                                                                        style="font-size: 11px;">
                                                                                                                        <i
                                                                                                                            class="fas fa-check-circle me-1"></i>
                                                                                                                        تمت
                                                                                                                        الزيارة
                                                                                                                    </span>
                                                                                                                @else
                                                                                                                    <span
                                                                                                                        class="badge bg-secondary me-2 mb-1"
                                                                                                                        style="font-size: 11px;">
                                                                                                                        <i
                                                                                                                            class="fas fa-times-circle me-1"></i>
                                                                                                                        لم تتم
                                                                                                                        الزيارة
                                                                                                                    </span>
                                                                                                                @endif

                                                                                                                @if ($isNewClient)
                                                                                                                    <span
                                                                                                                        class="badge bg-info me-2 mb-1"
                                                                                                                        style="font-size: 11px;"
                                                                                                                        title="عميل جديد">
                                                                                                                        <i
                                                                                                                            class="fas fa-user-plus me-1"></i>
                                                                                                                        جديد
                                                                                                                    </span>
                                                                                                                @endif

                                                                                                                @if ($statusToShow)
                                                                                                                    <span
                                                                                                                        class="badge rounded-pill mb-1"
                                                                                                                        style="background-color: {{ $statusToShow->color }}; font-size: 11px;">
                                                                                                                        <i
                                                                                                                            class="fas fa-circle me-1"></i>
                                                                                                                        {{ $statusToShow->name }}
                                                                                                                    </span>
                                                                                                                @else
                                                                                                                    <span
                                                                                                                        class="badge rounded-pill bg-secondary mb-1"
                                                                                                                        style="font-size: 11px;">
                                                                                                                        <i
                                                                                                                            class="fas fa-question-circle me-1"></i>
                                                                                                                        غير محدد
                                                                                                                    </span>
                                                                                                                @endif
                                                                                                            </div>
                                                                                                        </a>

                                                                                                        <button type="button"
                                                                                                            class="btn btn-sm btn-danger ms-3 delete-visit-btn"
                                                                                                            title="حذف الزيارة"
                                                                                                            data-visit-id="{{ $visit->id ?? '' }}"
                                                                                                            data-url="{{ isset($visit->id) ? route('itinerary.visits.destroy', $visit->id) : '#' }}">
                                                                                                            <i
                                                                                                                class="fas fa-trash"></i>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @else
                                                                                            <div
                                                                                                class="no-visits text-center mt-3">
                                                                                                <i
                                                                                                    class="fas fa-calendar-times fa-2x text-muted"></i>
                                                                                                <p class="text-muted mt-2">لا
                                                                                                    يوجد زيارات مخططة</p>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script>
        // Initialize calendar when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            // View toggle functionality
            const listViewBtn = document.getElementById('listViewBtn');
            const calendarViewBtn = document.getElementById('calendarViewBtn');
            const listView = document.getElementById('listView');
            const calendarView = document.getElementById('calendarView');

            listViewBtn.addEventListener('click', function() {
                listView.style.display = 'block';
                calendarView.style.display = 'none';
                listViewBtn.classList.add('active');
                calendarViewBtn.classList.remove('active');
            });

            calendarViewBtn.addEventListener('click', function() {
                listView.style.display = 'none';
                calendarView.style.display = 'block';
                calendarViewBtn.classList.add('active');
                listViewBtn.classList.remove('active');

                // Initialize calendar if not already initialized
                if (!window.calendarInitialized) {
                    initializeCalendar();
                    window.calendarInitialized = true;
                }
            });

            // Initialize calendar on page load
            initializeCalendar();
            window.calendarInitialized = true;
        });

        function initializeCalendar() {
            const calendarEl = document.getElementById('itinerary-calendar');

            // Get calendar data from the server-side variable
            const calendarData = @json($calendarData ?? []);

            // Process events for FullCalendar
            const events = [];

            // Convert our calendar data to FullCalendar events
            for (const [date, employees] of Object.entries(calendarData)) {
                for (const [employeeId, employeeData] of Object.entries(employees)) {
                    const visitCount = employeeData.visit_count;
                    const newClientsCount = employeeData.new_clients_count;

                    // Create event for each employee's visits on this date
                    events.push({
                        title: `${employeeData.employee_name}: ${visitCount} زيارة` +
                               (newClientsCount > 0 ? ` (${newClientsCount} جديد)` : ''),
                        start: date,
                        backgroundColor: visitCount > 0 ?
                            (newClientsCount > 0 ? '#17a2b8' : '#28a745') : '#ffc107',
                        borderColor: visitCount > 0 ?
                            (newClientsCount > 0 ? '#17a2b8' : '#28a745') : '#ffc107',
                        textColor: '#fff',
                        extendedProps: {
                            employeeId: employeeId,
                            employeeName: employeeData.employee_name,
                            visitCount: visitCount,
                            newClientsCount: newClientsCount,
                            date: date
                        }
                    });
                }
            }

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ar',
                direction: 'rtl',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'اليوم',
                    month: 'شهر',
                    week: 'أسبوع',
                    day: 'يوم'
                },
                events: events,
                eventClick: function(info) {
                    // Show details when an event is clicked
                    alert(`المندوب: ${info.event.extendedProps.employeeName}\n` +
                          `عدد الزيارات: ${info.event.extendedProps.visitCount}\n` +
                          `عملاء جدد: ${info.event.extendedProps.newClientsCount}`);
                }
            });

            calendar.render();
        }

        // دالة لتوسيع جميع الأسابيع
        function expandAll() {
            const collapseElements = document.querySelectorAll('.accordion-collapse');
            collapseElements.forEach(element => {
                if (!element.classList.contains('show')) {
                    const button = document.querySelector(`[data-bs-target="#${element.id}"]`);
                    if (button) {
                        button.click();
                    }
                }
            });
        }

        // دالة لطي جميع الأسابيع
        function collapseAll() {
            const collapseElements = document.querySelectorAll('.accordion-collapse.show');
            collapseElements.forEach(element => {
                const button = document.querySelector(`[data-bs-target="#${element.id}"]`);
                if (button) {
                    button.click();
                }
            });
        }

        // دالة لتوسيع جميع الموظفين
        function expandAllEmployees() {
            const employeeSchedules = document.querySelectorAll('.employee-schedule');
            employeeSchedules.forEach(schedule => {
                if (schedule.style.display === 'none') {
                    schedule.style.display = 'block';
                    // تدوير الأيقونة
                    const scheduleId = schedule.id;
                    const toggleIcon = document.querySelector(
                        `#employeeToggle${scheduleId.replace('employeeSchedule', '')}`);
                    if (toggleIcon) {
                        toggleIcon.classList.remove('fa-chevron-right');
                        toggleIcon.classList.add('fa-chevron-down');
                    }
                }
            });
        }

        // دالة لطي جميع الموظفين
        function collapseAllEmployees() {
            const employeeSchedules = document.querySelectorAll('.employee-schedule');
            employeeSchedules.forEach(schedule => {
                if (schedule.style.display === 'block') {
                    schedule.style.display = 'none';
                    // تدوير الأيقونة
                    const scheduleId = schedule.id;
                    const toggleIcon = document.querySelector(
                        `#employeeToggle${scheduleId.replace('employeeSchedule', '')}`);
                    if (toggleIcon) {
                        toggleIcon.classList.remove('fa-chevron-down');
                        toggleIcon.classList.add('fa-chevron-right');
                    }
                }
            });
        }

        // دالة لتوسيع/طي موظف معين
        function toggleEmployee(weekIndex, employeeId) {
            const scheduleElement = document.getElementById(`employeeSchedule${weekIndex}_${employeeId}`);
            const toggleIcon = document.getElementById(`employeeToggle${weekIndex}_${employeeId}`);

            if (scheduleElement) {
                if (scheduleElement.style.display === 'none') {
                    scheduleElement.style.display = 'block';
                    if (toggleIcon) {
                        toggleIcon.classList.remove('fa-chevron-right');
                        toggleIcon.classList.add('fa-chevron-down');
                    }
                } else {
                    scheduleElement.style.display = 'none';
                    if (toggleIcon) {
                        toggleIcon.classList.remove('fa-chevron-down');
                        toggleIcon.classList.add('fa-chevron-right');
                    }
                }
            }
        }

        // تهيئة الأحداث عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            // إضافة أحداث أزرار التحكم بالموظفين لكل أسبوع
            document.querySelectorAll('.expand-employees-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const weekIndex = this.getAttribute('data-week');
                    expandWeekEmployees(weekIndex);
                });
            });

            document.querySelectorAll('.collapse-employees-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const weekIndex = this.getAttribute('data-week');
                    collapseWeekEmployees(weekIndex);
                });
            });

            // إضافة أحداث أزرار حذف الزيارات
            document.querySelectorAll('.delete-visit-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const visitId = this.getAttribute('data-visit-id');
                    const deleteUrl = this.getAttribute('data-url');

                    if (!visitId || !deleteUrl || deleteUrl === '#') {
                        alert('خطأ: معرف الزيارة غير صحيح');
                        return;
                    }

                    if (confirm('هل أنت متأكد من حذف هذه الزيارة؟')) {
                        // إرسال طلب الحذف
                        fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // إزالة العنصر من DOM
                                    this.closest('.visit-card-wrapper').remove();
                                    alert('تم حذف الزيارة بنجاح');

                                    // إعادة تحميل الصفحة لتحديث الإحصائيات
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    alert('حدث خطأ أثناء حذف الزيارة: ' + (data.message ||
                                        'خطأ غير معروف'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('حدث خطأ أثناء حذف الزيارة');
                            });
                    }
                });
            });

            // إضافة تأثيرات hover للكروت
            document.querySelectorAll('.visit-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                });
            });

            // إضافة تأثيرات للأكورديون
            document.querySelectorAll('.accordion-button').forEach(button => {
                button.addEventListener('click', function() {
                    const target = this.getAttribute('data-bs-target');
                    const targetElement = document.querySelector(target);
                    const arrow = this.querySelector('.accordion-arrow');

                    if (targetElement) {
                        targetElement.addEventListener('shown.bs.collapse', function() {
                            if (arrow) {
                                arrow.style.transform = 'rotate(180deg)';
                            }
                        });

                        targetElement.addEventListener('hidden.bs.collapse', function() {
                            if (arrow) {
                                arrow.style.transform = 'rotate(0deg)';
                            }
                        });
                    }
                });
            });
        });

        // دالة لتوسيع موظفي أسبوع معين
        function expandWeekEmployees(weekIndex) {
            const container = document.getElementById(`employeesContainer${weekIndex}`);

            if (container) {
                const schedules = container.querySelectorAll('.employee-schedule');
                schedules.forEach(schedule => {
                    schedule.style.display = 'block';

                    // تدوير الأيقونة
                    const scheduleId = schedule.id;
                    const toggleIcon = document.querySelector(
                        `#employeeToggle${scheduleId.replace('employeeSchedule', '')}`);
                    if (toggleIcon) {
                        toggleIcon.classList.remove('fa-chevron-right');
                        toggleIcon.classList.add('fa-chevron-down');
                    }
                });
            }
        }

        // دالة لطي موظفي أسبوع معين
        function collapseWeekEmployees(weekIndex) {
            const container = document.getElementById(`employeesContainer${weekIndex}`);

            if (container) {
                const schedules = container.querySelectorAll('.employee-schedule');
                schedules.forEach(schedule => {
                    schedule.style.display = 'none';

                    // تدوير الأيقونة
                    const scheduleId = schedule.id;
                    const toggleIcon = document.querySelector(
                        `#employeeToggle${scheduleId.replace('employeeSchedule', '')}`);
                    if (toggleIcon) {
                        toggleIcon.classList.remove('fa-chevron-down');
                        toggleIcon.classList.add('fa-chevron-right');
                    }
                });
            }
        }

        // دالة للبحث في الزيارات (اختيارية)
        function searchVisits(searchTerm) {
            if (!searchTerm || searchTerm.length < 2) {
                // إعادة عرض جميع الزيارات إذا كان البحث فارغاً
                document.querySelectorAll('.visit-card-wrapper').forEach(card => {
                    card.style.display = '';
                });
                return;
            }

            searchTerm = searchTerm.toLowerCase();

            document.querySelectorAll('.visit-card').forEach(card => {
                const clientName = card.querySelector('.client-name').textContent.toLowerCase();
                const clientCode = card.querySelector('.client-code').textContent.toLowerCase();

                if (clientName.includes(searchTerm) || clientCode.includes(searchTerm)) {
                    card.closest('.visit-card-wrapper').style.display = '';
                } else {
                    card.closest('.visit-card-wrapper').style.display = 'none';
                }
            });
        }

        // دالة لطباعة التقرير
        function printReport() {
            window.print();
        }

        // إضافة أسلوب لتحميل محتوى الصفحة بشكل تدريجي
        document.addEventListener('DOMContentLoaded', function() {
            // تأثير ظهور تدريجي للعناصر
            const items = document.querySelectorAll('.accordion-item, .stats-card');
            items.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('fade-in');
                }, 100 * index);
            });
        });
    </script>
@endsection
