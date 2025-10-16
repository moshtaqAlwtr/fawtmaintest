@extends('master')

@section('title', 'عرض خطط السير')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/itinerary-calendar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.2/main.min.css">
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
                        <p class="text-muted mt-2">إدارة ومراقبة خطط السير الأسبوعية للمناديب</p>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('itinerary.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة خطة جديدة
                    </a>
                    <button class="btn btn-outline-primary ms-2" id="toggleViewBtn">
                        <i class="fas fa-exchange-alt me-1"></i>
                        <span>عرض القائمة</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="container-fluid mb-4">
        <div class="row g-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon">
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
                                            'visits' => $weekVisits
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
                    <div class="stats-icon">
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
                    <div class="stats-icon">
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
                    <div class="stats-icon">
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

    <!-- Calendar View & List View -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Calendar View -->
                <div class="main-card mb-4" id="calendarView">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    التقويم الأسبوعي
                                </h4>
                                <p class="card-subtitle text-muted mb-0">عرض خطط السير في تقويم أسبوعي</p>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="btn-group" role="group" id="viewSwitchGroup">
                                    <button type="button" class="btn btn-outline-primary active" data-view="month">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        شهر
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" data-view="week">
                                        <i class="fas fa-calendar-week me-1"></i>
                                        أسبوع
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" data-view="day">
                                        <i class="fas fa-calendar-day me-1"></i>
                                        يوم
                                    </button>
                                </div>
                                <button class="btn btn-outline-secondary" id="today-btn">
                                    <i class="fas fa-calendar-check me-1"></i>
                                    اليوم
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div id="calendar"></div>
                    </div>
                </div>

                <!-- List View (Hidden initially) -->
                <div class="main-card" id="listView" style="display: none;">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">
                                    <i class="fas fa-list me-2"></i>
                                    قائمة خطط السير
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
                            <!-- Accordion for List View -->
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
                                                                {{ $weekStartDate->format('d/m/Y') }} - {{ $weekEndDate->format('d/m/Y') }}
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
                                                            <button type="button" class="btn btn-outline-primary btn-sm expand-employees-btn"
                                                                    data-week="{{ $accordionItemIndex }}">
                                                                <i class="fas fa-eye me-1"></i>
                                                                عرض الكل
                                                            </button>
                                                            <button type="button" class="btn btn-outline-secondary btn-sm collapse-employees-btn"
                                                                    data-week="{{ $accordionItemIndex }}">
                                                                <i class="fas fa-eye-slash me-1"></i>
                                                                إخفاء الكل
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="employees-in-week" id="employeesContainer{{ $accordionItemIndex }}">
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
                                                                $dayVisits = count($weekVisits[$dayKey]['visits'] ?? []);
                                                                $employeeWeekTotalVisits += $dayVisits;

                                                                if (!empty($weekVisits[$dayKey]['visits'])) {
                                                                    foreach ($weekVisits[$dayKey]['visits'] as $visit) {
                                                                        if ($visit->status === 'active') {
                                                                            $employeeWeekCompletedVisits++;
                                                                        }
                                                                    }
                                                                }
                                                                $employeeWeekNewClients += $weekVisits[$dayKey]['new_clients_count'] ?? 0;
                                                            }

                                                            $employeeWeekIncompletedVisits = $employeeWeekTotalVisits - $employeeWeekCompletedVisits;
                                                        @endphp

                                                        <div class="employee-section mb-4"
                                                             data-employee-id="{{ $employeeId }}"
                                                             data-week="{{ $accordionItemIndex }}">
                                                            <!-- رأس الموظف مع إمكانية النقر للتوسيع/الطي -->
                                                            <div class="employee-header cursor-pointer"
                                                                 onclick="toggleEmployee({{ $accordionItemIndex }}, {{ $employeeId }})">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div class="employee-info d-flex align-items-center">
                                                                        <div class="employee-toggle-icon me-2">
                                                                            <i class="fas fa-chevron-right transition-all"
                                                                               id="employeeToggle{{ $accordionItemIndex }}_{{ $employeeId }}"></i>
                                                                        </div>
                                                                        <div class="employee-avatar">
                                                                            <i class="fas fa-user"></i>
                                                                        </div>
                                                                        <div class="employee-details">
                                                                            <h6 class="employee-name">{{ $employee->name ?? 'غير معروف' }}</h6>
                                                                            <div class="employee-stats">
                                                                                <span class="badge bg-secondary me-2">
                                                                                    <i class="fas fa-building me-1"></i>
                                                                                    {{ $employeeWeekTotalVisits }} زيارات
                                                                                </span>
                                                                                <span class="badge bg-success me-2">
                                                                                    <i class="fas fa-check-circle me-1"></i>
                                                                                    {{ $employeeWeekCompletedVisits }} مكتملة
                                                                                </span>
                                                                                @if ($employeeWeekIncompletedVisits > 0)
                                                                                    <span class="badge bg-warning me-2">
                                                                                        <i class="fas fa-clock me-1"></i>
                                                                                        {{ $employeeWeekIncompletedVisits }} غير مكتملة
                                                                                    </span>
                                                                                @endif
                                                                                @if ($employeeWeekNewClients > 0)
                                                                                    <span class="badge bg-info">
                                                                                        <i class="fas fa-user-plus me-1"></i>
                                                                                        {{ $employeeWeekNewClients }} عملاء جدد
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
                                                                            $dayVisits = $weekVisits[$dayKey]['visits'] ?? [];
                                                                            $dayVisitCount = count($dayVisits);
                                                                            $dayCompletedVisits = 0;
                                                                            $dayNewClients = $weekVisits[$dayKey]['new_clients_count'] ?? 0;

                                                                            foreach ($dayVisits as $visit) {
                                                                                if ($visit->status === 'active') {
                                                                                    $dayCompletedVisits++;
                                                                                }
                                                                            }
                                                                        @endphp

                                                                        <div class="col-lg-3 col-md-6">
                                                                            <div class="day-card">
                                                                                <div class="day-header">
                                                                                    <h6 class="day-name">{{ $dayName }}</h6>
                                                                                    <div class="d-flex align-items-center">
                                                                                        <span class="visits-count">
                                                                                            {{ $dayVisitCount }} زيارة
                                                                                        </span>
                                                                                        @if ($dayNewClients > 0)
                                                                                            <span class="badge bg-info ms-2"
                                                                                                  title="عملاء جدد اليوم">
                                                                                                <i class="fas fa-user-plus me-1"></i>
                                                                                                {{ $dayNewClients }}
                                                                                            </span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                <div class="day-visits">
                                                                                    @if ($dayVisitCount > 0)
                                                                                        <div class="day-stats mb-2">
                                                                                            <span class="badge bg-success text-white">
                                                                                                <i class="fas fa-check-circle me-1"></i>
                                                                                                {{ $dayCompletedVisits }} مكتملة
                                                                                            </span>
                                                                                            @if (($dayVisitCount - $dayCompletedVisits) > 0)
                                                                                                <span class="badge bg-warning text-dark ms-1">
                                                                                                    <i class="fas fa-clock me-1"></i>
                                                                                                    {{ ($dayVisitCount - $dayCompletedVisits) }} غير مكتملة
                                                                                                </span>
                                                                                            @endif

                                                                                                            @if ($statusToShow)
                                                                                                                <span class="badge rounded-pill mb-1" style="background-color: {{ $statusToShow->color ?? '#6c757d' }}; font-size: 11px;">
                                                                                                                    <i class="fas fa-circle me-1"></i>
                                                                                                                    {{ $statusToShow->name ?? 'غير محدد' }}
                                                                                                                </span>
                                                                                                            @else
                                                                                                                <span class="badge rounded-pill bg-secondary mb-1" style="font-size: 11px;">
                                                                                                                    <i class="fas fa-question-circle me-1"></i>
                                                                                                                    غير محدد
                                                                                                                </span>
                                                                                                            @endif
                                                                                        </div>

                                                                                        @foreach ($dayVisits as $index => $visit)
                                                                                            @if (isset($visit->client))
                                                                                                @php
                                                                                                    $client = $visit->client;
                                                                                                    $isNewClient = $visit->client->is_new_for_visit_date ?? false;

                                                                                                    // التحقق من وجود حالة العميل
                                                                                                    $statusToShow = null;
                                                                                                    if (isset($client->status_client)) {
                                                                                                        $statusToShow = $client->status_client;
                                                                                                    }

                                                                                                    // التحقق من وجود ملاحظات
                                                                                                    $lastNote = null;
                                                                                                    if (method_exists($client, 'appointmentNotes')) {
                                                                                                        $lastNote = $client
                                                                                                            ->appointmentNotes()
                                                                                                            ->where('employee_id', auth()->id())
                                                                                                            ->where('process', 'إبلاغ المشرف')
                                                                                                            ->whereNotNull('employee_view_status')
                                                                                                            ->latest()
                                                                                                            ->first();

                                                                                                        if (
                                                                                                            auth()->user()->role === 'employee' &&
                                                                                                            $lastNote &&
                                                                                                            $lastNote->employee_id == auth()->id() &&
                                                                                                            isset($statuses) && $statuses
                                                                                                        ) {
                                                                                                            $statusToShow = $statuses->find($lastNote->employee_view_status);
                                                                                                        }
                                                                                                    }
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
                                                                                                                <span class="badge bg-success me-2 mb-1" style="font-size: 11px;">
                                                                                                                    <i class="fas fa-check-circle me-1"></i>
                                                                                                                    تمت الزيارة
                                                                                                                </span>
                                                                                                            @else
                                                                                                                <span class="badge bg-secondary me-2 mb-1" style="font-size: 11px;">
                                                                                                                    <i class="fas fa-times-circle me-1"></i>
                                                                                                                    لم تتم الزيارة
                                                                                                                </span>
                                                                                                            @endif

                                                                                                            @if ($isNewClient)
                                                                                                                <span class="badge bg-info me-2 mb-1" style="font-size: 11px;" title="عميل جديد">
                                                                                                                    <i class="fas fa-user-plus me-1"></i>
                                                                                                                    جديد
                                                                                                                </span>
                                                                                                            @endif