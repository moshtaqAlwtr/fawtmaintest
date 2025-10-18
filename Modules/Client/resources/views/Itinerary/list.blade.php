

@extends('sales::master')

@section('title', 'عرض جميع خطط السير')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/listIntry.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tippy.js@6.3.7/dist/tippy.css">
    <style>
        /* Calendar Styles */
        .fc-event {
            cursor: pointer;
            padding: 6px;
            margin: 2px 0;
            border-radius: 6px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .fc-event:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .fc-day-today {
            background-color: var(--primary-light) !important;
        }

        .fc-button-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
            border-radius: 6px !important;
            transition: all 0.3s ease;
        }

        .fc-button-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(0,0,0,0.15) !important;
        }

        .calendar-container {
            background: #fff;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .calendar-title {
            color: var(--primary-color);
            font-weight: 600;
            margin: 0;
        }

        .calendar-actions {
            display: flex;
            gap: 10px;
        }

        .fc-toolbar-title {
            color: var(--primary-color) !important;
            font-size: 1.5rem !important;
            font-weight: 600 !important;
        }

        .visit-event {
            padding: 5px 8px;
            border-radius: 5px;
            font-size: 0.9rem;
            margin: 2px 0;
        }

        .visit-complete {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }

        .visit-pending {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: #212529 !important;
        }

        .visit-new-client {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
        }

        /* Legend */
        .legend-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: 15px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 5px;
        }

        /* Event Modal Styles */
        .event-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .event-modal-content {
            background: #fff;
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            position: relative;
        }

        .event-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .event-modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }

        .event-modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #999;
            cursor: pointer;
            transition: color 0.3s;
        }

        .event-modal-close:hover {
            color: var(--primary-color);
        }

        .event-modal-body {
            margin-bottom: 20px;
        }

        .event-details-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .event-details-item {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        .event-details-item:last-child {
            border-bottom: none;
        }

        .event-details-label {
            font-weight: 600;
            width: 120px;
            color: #555;
        }

        .event-visits-container {
            margin-top: 15px;
        }

        .event-visit-card {
            background: #f9f9f9;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .event-visit-card:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
        }

        .event-visit-info {
            flex: 1;
        }

        .event-visit-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .event-visit-details {
            color: #666;
            font-size: 0.9rem;
        }

        .event-visit-badges {
            display: flex;
            gap: 5px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .event-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        /* Loading Indicator */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255,255,255,0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s linear 0.2s, opacity 0.2s;
        }

        .loading-overlay.active {
            visibility: visible;
            opacity: 1;
            transition-delay: 0s;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
                            <li class="breadcrumb-item"><a href="">الرئيسيه</a></li>
                            <li class="breadcrumb-item active">عرض</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap justify-content-end" style="gap: 10px;">
                <a href="{{ route('itinerary.create') }}"
                    class="btn btn-primary d-flex align-items-center justify-content-center"
                    style="height: 44px; padding: 0 16px; font-weight: bold; border-radius: 6px;">
                    <i class="fas fa-plus ms-2"></i>
                    أضف خط سير جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card">
                <div class="stats-content">
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-route"></i>
                    </div>
                    <div>
                        <h3 id="totalVisitsCount">
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
                        <p>إجمالي الزيارات</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card">
                <div class="stats-content">
                    <div class="stats-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h3 id="completedVisitsCount">
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
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card">
                <div class="stats-content">
                    <div class="stats-icon bg-info">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h3 id="newClientsCount">{{ $newClientsTodayCount ?? 0 }}</h3>
                        <p>عملاء جدد</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card">
                <div class="stats-content">
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 id="pendingVisitsCount">{{ $totalPlannedVisits - $totalCompletedVisits }}</h3>
                        <p>زيارات قيد الانتظار</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar View -->
    <div class="calendar-container">
        <!-- Calendar Controls -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> تقويم خط السير</h4>
            </div>
            <div class="calendar-actions">
                <button class="btn btn-primary btn-sm me-2" id="addNewVisitBtn">
                    <i class="fas fa-plus me-1"></i> إضافة زيارة
                </button>
                <button class="btn btn-outline-primary btn-sm me-2" id="filterByEmployeeBtn">
                    <i class="fas fa-filter me-1"></i> تصفية حسب المندوب
                </button>
                <button class="btn btn-outline-success btn-sm" id="exportVisitsBtn">
                    <i class="fas fa-file-export me-1"></i> تصدير
                </button>
            </div>
        </div>

        <div id="itinerary-calendar"></div>
    </div>

    <!-- Legend for calendar events -->
    <div class="legend-container mb-4">
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

    <!-- Original List View Content -->
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

        // ترتيب الأسابيع من الأحدث للأقدم
        krsort($weeklyData);

        $days = [
            'saturday' => 'السبت',
            'sunday' => 'الأحد',
            'monday' => 'الإثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
        ];
    @endphp

    <div class="container-fluid mb-4">
        <div class="row g-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stats-content">
                        <h3>{{ $totalWeeks }}</h3>
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
                        <h3>{{ $totalPlannedVisits }}</h3>
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
                        <h3>{{ $totalCompletedVisits }}</h3>
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
                                                                                                    $client = $visit->client;
                                                                                                    $lastNote = isset($client->appointmentNotes) ? $client
                                                                                                        ->appointmentNotes()
                                                                                                        ->where('employee_id', auth()->id())
                                                                                                        ->where('process', 'إبلاغ المشرف')
                                                                                                        ->whereNotNull('employee_view_status')
                                                                                                        ->latest()
                                                                                                        ->first() : null;

                                                                                                    $statusToShow = $client->status_client ?? null;

                                                                                                    if (auth()->user()->role === 'employee' && $lastNote && $lastNote->employee_id == auth()->id()) {
                                                                                                        $statusToShow = isset($statuses) && $statuses ? $statuses->find($lastNote->employee_view_status) : null;
                                                                                                    }

                                                                                                    $isNewClient = $visit->client->is_new_for_visit_date ?? false;
                                                                                                @endphp

                                                                                                <div class="visit-card-wrapper d-flex align-items-center justify-content-between mb-2 flex-wrap">
                                                                                                    <a href="{{ route('clients.show', $visit->client->id) }}"
                                                                                                        class="visit-card flex-grow-1 d-flex align-items-center text-decoration-none text-dark flex-wrap">
                                                                                                        <div class="visit-number me-3">
                                                                                                            {{ $index + 1 }}
                                                                                                        </div>

                                                                                                        <div class="visit-info me-auto">
                                                                                                            <h6 class="client-name mb-0">
                                                                                                                {{ $visit->client->trade_name ?? 'غير معروف' }}
                                                                                                            </h6>
                                                                                                            <p class="client-code mb-0 text-muted">
                                                                                                                {{ $visit->client->code ?? '---' }}
                                                                                                            </p>
                                                                                                        </div>

                                                                                                        <div class="d-flex align-items-center flex-wrap">
                                                                                                            @if ($visit->status === 'active')
                                                                                                                <span
                                                                                                                    class="badge bg-success me-2 mb-1"
                                                                                                                    style="font-size: 11px;">
                                                                                                                    <i
                                                                                                                        class="fas fa-check-circle me-1"></i>
                                                                                                                    تمت الزيارة
                                                                                                                </span>
                                                                                                            @else
                                                                                                                <span
                                                                                                                    class="badge bg-secondary me-2 mb-1"
                                                                                                                    style="font-size: 11px;">
                                                                                                                    <i class="fas fa-times-circle me-1"></i>
                                                                                                                    لم تتم الزيارة
                                                                                                                </span>
                                                                                                            @endif

                                                                                                            @if ($isNewClient)
                                                                                                                <span
                                                                                                                    class="badge bg-info me-2 mb-1"
                                                                                                                    style="font-size: 11px;"
                                                                                                                    title="عميل جديد">
                                                                                                                    <i class="fas fa-user-plus me-1"></i>
                                                                                                                    جديد
                                                                                                                </span>
                                                                                                            @endif

                                                                                                            @if (isset($statusToShow) && !is_bool($statusToShow) && $statusToShow)
                                                                                                                <span
                                                                                                                    class="badge rounded-pill mb-1"
                                                                                                                    style="background-color: {{ $statusToShow->color }}; font-size: 11px;">
                                                                                                                    <i class="fas fa-circle me-1"></i>
                                                                                                                    {{ $statusToShow->name }}
                                                                                                                </span>
                                                                                                            @else
                                                                                                                <span
                                                                                                                    class="badge rounded-pill bg-secondary mb-1"
                                                                                                                    style="font-size: 11px;">
                                                                                                                    <i class="fas fa-question-circle me-1"></i>
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
                                                                                                        <i class="fas fa-trash"></i>
                                                                                                    </button>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @else
                                                                                        <div class="no-visits text-center mt-3">
                                                                                            <i class="fas fa-calendar-times fa-2x text-muted"></i>
                                                                                            <p class="text-muted mt-2">لا يوجد زيارات مخططة</p>
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

    <!-- Event Details Modal -->
    <div id="eventModal" class="event-modal">
        <div class="event-modal-content">
            <div class="event-modal-header">
                <h5 class="event-modal-title" id="eventModalTitle">تفاصيل الزيارات</h5>
                <button type="button" class="event-modal-close" id="closeEventModal">&times;</button>
            </div>
            <div class="event-modal-body">
                <ul class="event-details-list">
                    <li class="event-details-item">
                        <div class="event-details-label">المندوب:</div>
                        <div id="eventEmployee"></div>
                    </li>
                    <li class="event-details-item">
                        <div class="event-details-label">التاريخ:</div>
                        <div id="eventDate"></div>
                    </li>
                    <li class="event-details-item">
                        <div class="event-details-label">عدد الزيارات:</div>
                        <div id="eventVisitCount"></div>
                    </li>
                </ul>

                <div class="event-visits-container">
                    <h6><i class="fas fa-building me-2"></i> الزيارات المخططة</h6>
                    <div id="eventVisitsList"></div>
                </div>
            </div>
            <div class="event-modal-actions">
                <button type="button" class="btn btn-secondary" id="closeEventModalBtn">إغلاق</button>
                <a href="#" id="editEventBtn" class="btn btn-primary">تعديل خط السير</a>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>
@endsection

@section('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/tippy.js@6.3.7/dist/tippy.umd.min.js'></script>
    <script>
        // Global variables
        let calendar;

        // Initialize when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize calendar
            initializeCalendar();

            // Set up event handlers
            setupEventHandlers();

            // Add animation effects
            addPageEffects();
        });

        /**
         * Initialize the calendar component
         */
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
                    const completedCount = employeeData.completed_visits_count || 0;

                    // Determine event color based on status
                    let backgroundColor, textColor = '#fff';

                    if (newClientsCount > 0) {
                        backgroundColor = '#17a2b8'; // New clients - blue
                    } else if (completedCount > 0 && completedCount === visitCount) {
                        backgroundColor = '#28a745'; // All completed - green
                    } else if (completedCount > 0) {
                        backgroundColor = '#fd7e14'; // Some completed - orange
                    } else {
                        backgroundColor = '#ffc107'; // None completed - yellow
                        textColor = '#212529';
                    }

                    // Create event for each employee's visits on this date
                    events.push({
                        title: `${employeeData.employee_name}: ${visitCount} زيارة` +
                               (newClientsCount > 0 ? ` (${newClientsCount} جديد)` : ''),
                        start: date,
                        backgroundColor: backgroundColor,
                        borderColor: backgroundColor,
                        textColor: textColor,
                        extendedProps: {
                            employeeId: employeeId,
                            employeeName: employeeData.employee_name,
                            visitCount: visitCount,
                            completedVisitsCount: completedCount,
                            pendingVisitsCount: visitCount - completedCount,
                            newClientsCount: newClientsCount,
                            date: date,
                            visits: employeeData.visits || []
                        }
                    });
                }
            }

            // Create calendar
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ar',
                direction: 'rtl',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                buttonText: {
                    today: 'اليوم',
                    month: 'شهر',
                    week: 'أسبوع',
                    day: 'يوم',
                    listWeek: 'أجندة'
                },
                events: events,
                selectable: true,
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                eventDidMount: function(info) {
                    // Add tooltip
                    tippy(info.el, {
                        content: `
                            <div style="padding: 10px;">
                                <div style="font-weight: bold; margin-bottom: 5px;">${info.event.extendedProps.employeeName}</div>
                                <div>إجمالي الزيارات: ${info.event.extendedProps.visitCount}</div>
                                <div>الزيارات المكتملة: ${info.event.extendedProps.completedVisitsCount}</div>
                                <div>العملاء الجدد: ${info.event.extendedProps.newClientsCount}</div>
                                <div>التاريخ: ${formatDate(info.event.start)}</div>
                            </div>
                        `,
                        allowHTML: true,
                        placement: 'top'
                    });
                },
                eventClick: function(info) {
                    showEventDetails(info.event);
                },
                dateClick: function(info) {
                    addNewVisit(info.date);
                }
            });

            // Render the calendar
            calendar.render();
        }

        /**
         * Set up all event handlers
         */
        function setupEventHandlers() {
            // Calendar action buttons
            document.getElementById('addNewVisitBtn').addEventListener('click', function() {
                const today = new Date();
                addNewVisit(today);
            });

            document.getElementById('filterByEmployeeBtn').addEventListener('click', function() {
                // For future implementation
                alert('سيتم إضافة خيارات التصفية حسب المندوب قريبًا');
            });

            document.getElementById('exportVisitsBtn').addEventListener('click', function() {
                // For future implementation
                alert('سيتم إضافة ميزة تصدير البيانات قريبًا');
            });

            // Event modal close buttons
            document.getElementById('closeEventModal').addEventListener('click', closeEventModal);
            document.getElementById('closeEventModalBtn').addEventListener('click', closeEventModal);

            // Original list view event handlers
            setupAccordionEventHandlers();
        }

        /**
         * Set up accordion event handlers for the list view
         */
        function setupAccordionEventHandlers() {
            // Expand/collapse all weeks
            window.expandAll = function() {
                document.querySelectorAll('.accordion-collapse').forEach(element => {
                    if (!element.classList.contains('show')) {
                        const button = document.querySelector(`[data-bs-target="#${element.id}"]`);
                        if (button) button.click();
                    }
                });
            };

            window.collapseAll = function() {
                document.querySelectorAll('.accordion-collapse.show').forEach(element => {
                    const button = document.querySelector(`[data-bs-target="#${element.id}"]`);
                    if (button) button.click();
                });
            };

            // Expand/collapse all employees
            window.expandAllEmployees = function() {
                document.querySelectorAll('.employee-schedule').forEach(schedule => {
                    if (schedule.style.display === 'none') {
                        schedule.style.display = 'block';
                        const scheduleId = schedule.id;
                        const toggleIcon = document.querySelector(`#employeeToggle${scheduleId.replace('employeeSchedule', '')}`);
                        if (toggleIcon) {
                            toggleIcon.classList.remove('fa-chevron-right');
                            toggleIcon.classList.add('fa-chevron-down');
                        }
                    }
                });
            };

            window.collapseAllEmployees = function() {
                document.querySelectorAll('.employee-schedule').forEach(schedule => {
                    if (schedule.style.display === 'block') {
                        schedule.style.display = 'none';
                        const scheduleId = schedule.id;
                        const toggleIcon = document.querySelector(`#employeeToggle${scheduleId.replace('employeeSchedule', '')}`);
                        if (toggleIcon) {
                            toggleIcon.classList.remove('fa-chevron-down');
                            toggleIcon.classList.add('fa-chevron-right');
                        }
                    }
                });
            };

            // Toggle employee section
            window.toggleEmployee = function(weekIndex, employeeId) {
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
            };

            // Expand/collapse employees buttons
            document.querySelectorAll('.expand-employees-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const weekIndex = this.getAttribute('data-week');
                    expandWeekEmployees(weekIndex);
                });
            });

            document.querySelectorAll('.collapse-employees-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const weekIndex = this.getAttribute('data-week');
                    collapseWeekEmployees(weekIndex);
                });
            });

            // Delete visit buttons
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
                        deleteVisit(visitId, deleteUrl, this);
                    }
                });
            });
        }

        /**
         * Delete a visit with AJAX
         */
        function deleteVisit(visitId, deleteUrl, buttonEl) {
            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!token) {
                console.error('CSRF token not found');
                return;
            }

            // Show loading
            showLoading();

            // Send delete request
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();

                if (data.success) {
                    // Remove element from DOM
                    buttonEl.closest('.visit-card-wrapper').remove();
                    alert('تم حذف الزيارة بنجاح');

                    // Reload page to update statistics
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert('حدث خطأ أثناء الحذف: ' + (data.message || 'خطأ غير معروف'));
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                alert('حدث خطأ أثناء الحذف');
            });
        }

        /**
         * Expand all employees for a specific week
         */
        function expandWeekEmployees(weekIndex) {
            const container = document.getElementById(`employeesContainer${weekIndex}`);
            if (!container) return;

            const schedules = container.querySelectorAll('.employee-schedule');
            schedules.forEach(schedule => {
                schedule.style.display = 'block';

                const scheduleId = schedule.id;
                const toggleIcon = document.querySelector(`#employeeToggle${scheduleId.replace('employeeSchedule', '')}`);
                if (toggleIcon) {
                    toggleIcon.classList.remove('fa-chevron-right');
                    toggleIcon.classList.add('fa-chevron-down');
                }
            });
        }

        /**
         * Collapse all employees for a specific week
         */
        function collapseWeekEmployees(weekIndex) {
            const container = document.getElementById(`employeesContainer${weekIndex}`);
            if (!container) return;

            const schedules = container.querySelectorAll('.employee-schedule');
            schedules.forEach(schedule => {
                schedule.style.display = 'none';

                const scheduleId = schedule.id;
                const toggleIcon = document.querySelector(`#employeeToggle${scheduleId.replace('employeeSchedule', '')}`);
                if (toggleIcon) {
                    toggleIcon.classList.remove('fa-chevron-down');
                    toggleIcon.classList.add('fa-chevron-right');
                }
            });
        }

        /**
         * Show event details in modal
         */
        function showEventDetails(event) {
            // Get modal elements
            const modal = document.getElementById('eventModal');
            const title = document.getElementById('eventModalTitle');
            const employee = document.getElementById('eventEmployee');
            const date = document.getElementById('eventDate');
            const visitCount = document.getElementById('eventVisitCount');
            const visitsList = document.getElementById('eventVisitsList');
            const editBtn = document.getElementById('editEventBtn');

            // Set modal content
            title.textContent = 'تفاصيل الزيارات';
            employee.textContent = event.extendedProps.employeeName;
            date.textContent = formatDate(event.start);
            visitCount.textContent = `${event.extendedProps.visitCount} (${event.extendedProps.completedVisitsCount} مكتملة)`;

            // Clear previous visits list
            visitsList.innerHTML = '';

            // Add visits to list
            if (event.extendedProps.visits && event.extendedProps.visits.length > 0) {
                event.extendedProps.visits.forEach(visit => {
                    if (visit.client) {
                        const visitEl = document.createElement('div');
                        visitEl.className = 'event-visit-card';

                        const isComplete = visit.status === 'active';
                        const isNewClient = visit.client.is_new_for_visit_date || false;

                        visitEl.innerHTML = `
                            <div class="event-visit-info">
                                <div class="event-visit-name">${visit.client.trade_name || 'غير معروف'}</div>
                                <div class="event-visit-details">
                                    <span>كود: ${visit.client.code || '---'}</span>
                                    <span class="me-2">المدينة: ${visit.client.city || '---'}</span>
                                </div>
                                <div class="event-visit-badges">
                                    ${isComplete ?
                                        '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> تمت الزيارة</span>' :
                                        '<span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i> لم تتم الزيارة</span>'}
                                    ${isNewClient ?
                                        '<span class="badge bg-info ms-2"><i class="fas fa-user-plus me-1"></i> عميل جديد</span>' :
                                        ''}
                                </div>
                            </div>
                            <div>
                                <a href="/clients/${visit.client.id}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        `;

                        visitsList.appendChild(visitEl);
                    }
                });
            } else {
                // Show empty state
                visitsList.innerHTML = `
                    <div class="text-center my-4">
                        <i class="fas fa-calendar-times fa-2x text-muted"></i>
                        <p class="text-muted mt-2">لا توجد زيارات مخططة لهذا اليوم</p>
                    </div>
                `;
            }

            // Set edit button URL - get the week number from the date
            const weekNumber = getWeekNumber(event.start);
            editBtn.href = `/itinerary/edit/${event.extendedProps.employeeId}?year=${event.start.getFullYear()}&week=${weekNumber}`;

            // Show modal
            modal.style.display = 'flex';
        }

        /**
         * Close event details modal
         */
        function closeEventModal() {
            document.getElementById('eventModal').style.display = 'none';
        }

        /**
         * Add new visit
         */
        function addNewVisit(date) {
            // Navigate to create page with selected date
            const year = date.getFullYear();
            const weekNumber = getWeekNumber(date);

            window.location.href = `/itinerary/create?year=${year}&week=${weekNumber}`;
        }

        /**
         * Show loading overlay
         */
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }

        /**
         * Hide loading overlay
         */
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }

        /**
         * Add page effects and animations
         */
        function addPageEffects() {
            // Animate elements on page load
            const items = document.querySelectorAll('.accordion-item, .stats-card');
            items.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('fade-in');
                }, 100 * index);
            });

            // Add hover effects to visit cards
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

            // Add effects to accordion arrows
            document.querySelectorAll('.accordion-button').forEach(button => {
                button.addEventListener('click', function() {
                    const target = this.getAttribute('data-bs-target');
                    const targetElement = document.querySelector(target);
                    const arrow = this.querySelector('.accordion-arrow');

                    if (targetElement) {
                        targetElement.addEventListener('shown.bs.collapse', function() {
                            if (arrow) arrow.style.transform = 'rotate(180deg)';
                        });

                        targetElement.addEventListener('hidden.bs.collapse', function() {
                            if (arrow) arrow.style.transform = 'rotate(0deg)';
                        });
                    }
                });
            });
        }

        /* ===== Helper Functions ===== */

        /**
         * Format date for display
         */
        function formatDate(date) {
            if (!date) return '';

            const d = new Date(date);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return d.toLocaleDateString('ar-SA', options);
        }

        /**
         * Get week number from date
         */
        function getWeekNumber(date) {
            const d = new Date(date);
            d.setHours(0, 0, 0, 0);
            d.setDate(d.getDate() + 4 - (d.getDay() || 7));
            const yearStart = new Date(d.getFullYear(), 0, 1);
            return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
        }
    </script>
@endsection
