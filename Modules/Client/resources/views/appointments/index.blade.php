@extends('sales::master')
@section('title')
    ادارة المواعيد
@stop
@section('css')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Calendar Styles */
        #calendar {
            max-width: 100%;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            height: 650px;
        }

        .fc-header-toolbar {
            margin-bottom: 1.5em !important;
        }

        .fc-event {
            cursor: pointer;
            font-size: 0.85em;
            padding: 3px 5px;
            border-radius: 4px;
            margin: 2px 0;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .fc-event:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 5;
        }

        /* Status colors for calendar events */
        .status-1 { /* قيد الانتظار */
            background-color: #ffc107 !important;
            border-color: #e0a800 !important;
            color: #000 !important;
        }

        .status-2 { /* مكتمل */
            background-color: #28a745 !important;
            border-color: #1e7e34 !important;
            color: #fff !important;
        }

        .status-3 { /* ملغي */
            background-color: #dc3545 !important;
            border-color: #bd2130 !important;
            color: #fff !important;
        }

        .status-4 { /* معاد جدولته */
            background-color: #17a2b8 !important;
            border-color: #138496 !important;
            color: #fff !important;
        }

        /* Responsive styles */
        @media (max-width: 575.98px) {
            .min-mobile {
                display: table-cell;
            }

            .min-tablet {
                display: none;
            }

            .min-desktop {
                display: none;
            }

            .fc .fc-toolbar {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }

            .fc .fc-toolbar-title {
                font-size: 1.2em;
            }

            .fc-header-toolbar {
                margin-bottom: 1em !important;
            }
        }

        @media (min-width: 576px) and (max-width: 991.98px) {
            .min-mobile {
                display: table-cell;
            }

            .min-tablet {
                display: table-cell;
            }

            .min-desktop {
                display: none;
            }
        }

        @media (min-width: 992px) {
            .min-mobile {
                display: table-cell;
            }

            .min-tablet {
                display: table-cell;
            }

            .min-desktop {
                display: table-cell;
            }
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Tab and Toggle Styles */
        #calendar-tab {
            display: none;
        }

        #calendar-tab.show.active {
            display: block !important;
        }

        .btn-group .btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        /* Today cell highlight */
        .fc-day-today {
            background-color: rgba(0, 123, 255, 0.1) !important;
        }

        /* RTL specific styles */
        .fc-direction-rtl .fc-button-group > .fc-button:first-child {
            border-radius: 0 4px 4px 0;
        }

        .fc-direction-rtl .fc-button-group > .fc-button:last-child {
            border-radius: 4px 0 0 4px;
        }

        /* Additional RTL Styles */
        .rtl-swal {
            direction: rtl !important;
            text-align: right !important;
        }

        .rtl-popup {
            direction: rtl !important;
        }

        .appointment-details p {
            margin-bottom: 8px;
        }

        /* Loading indicator styles */
        .calendar-loading {
            position: relative;
        }

        .calendar-loading:after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .calendar-loading:before {
            content: "جاري التحميل...";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1001;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }

        /* Button styles */
        .action-btn {
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Badge styles */
        .badge {
            padding: 0.4em 0.65em;
            font-weight: 500;
            border-radius: 50rem;
        }
    </style>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.alerts.success')
    @include('layouts.alerts.error')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">ادارة المواعيد</h2>
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
    <div class="content-body">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap justify-content-end" style="gap: 10px;">
                    <!-- زر تحميل ملف -->

                    <!-- زر تصدير -->
                    <button id="exportExcelBtn" class="bg-white border d-flex align-items-center justify-content-center"
                        style="width: 44px; height: 44px; border-radius: 6px;" title="تصدير ك Excel">
                        <i class="fas fa-file-excel text-primary"></i>
                    </button>

                    <!-- زر أضف موعد جديد -->
                    <a href="{{ route('appointments.create') }}"
                        class="btn btn-primary d-flex align-items-center justify-content-center"
                        style="height: 44px; padding: 0 16px; font-weight: bold; border-radius: 6px;">
                        <i class="fas fa-plus ms-2"></i>
                        أضف موعد جديد
                    </a>
                </div>
            </div>
        </div>

        <!-- بطاقة البحث -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center p-2">
                <div class="d-flex gap-2">
                    <span class="hide-button-text">بحث وتصفية</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-secondary btn-sm" onclick="toggleSearchFields(this)">
                        <i class="fa fa-times"></i>
                        <span class="hide-button-text">اخفاء</span>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse"
                        data-bs-target="#advancedSearchForm" onclick="toggleSearchText(this)">
                        <i class="fa fa-filter"></i>
                        <span class="button-text">متقدم</span>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <form class="form" id="searchForm">
                    @csrf
                    <div class="row g-3">
                        <!-- الحقول الأساسية -->
                        <div class="col-md-4">
                            <label for="" class=""> اختر العميل</label>
                            <select name="client" class="form-control select2">
                                <option value="">اختر العميل</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ request('client') == $client->id ? 'selected' : '' }}>
                                        {{ $client->trade_name }} ({{ $client->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="" class=""> حالة الموعد</label>
                            <select name="appointment_status" class="form-control select2">
                                <option value="">حالة الموعد</option>
                                <option value="pending" {{ request('appointment_status') == 'pending' ? 'selected' : '' }}>
                                    قادم
                                </option>
                                <option value="completed" {{ request('appointment_status') == 'completed' ? 'selected' : '' }}>
                                    مكتمل
                                </option>
                                <option value="cancelled" {{ request('appointment_status') == 'cancelled' ? 'selected' : '' }}>
                                    ملغي
                                </option>
                                <option value="rescheduled" {{ request('appointment_status') == 'rescheduled' ? 'selected' : '' }}>
                                    معاد جدلته
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="" class=""> نوع الموعد</label>
                            <select name="appointment_type" class="form-control select2">
                                <option value="">نوع الموعد</option>
                                <option value="visit" {{ request('appointment_type') == 'visit' ? 'selected' : '' }}>
                                    زيارة
                                </option>
                                <option value="meeting" {{ request('appointment_type') == 'meeting' ? 'selected' : '' }}>
                                    اجتماع
                                </option>
                                <option value="call" {{ request('appointment_type') == 'call' ? 'selected' : '' }}>
                                    مكالمة
                                </option>
                                <option value="followup" {{ request('appointment_type') == 'followup' ? 'selected' : '' }}>
                                    متابعة
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- البحث المتقدم -->
                    <div class="collapse" id="advancedSearchForm">
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <label for="" class=""> الموظف</label>
                                <select name="employee" class="form-control">
                                    <option value="">الموظف</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ request('employee') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="" class=""> تاريخ الموعد من </label>
                                <input type="date" name="date_from" class="form-control" placeholder="التاريخ من"
                                    value="{{ request('date_from') }}">
                            </div>

                            <div class="col-md-4">
                                <label for="" class=""> تاريخ الموعد إلى </label>
                                <input type="date" name="date_to" class="form-control" placeholder="التاريخ إلى"
                                    value="{{ request('date_to') }}">
                            </div>

                            <div class="col-md-4">
                                <label for="" class=""> الأولوية</label>
                                <select name="priority" class="form-control">
                                    <option value="">الأولوية</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>
                                        عالية
                                    </option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>
                                        متوسطة
                                    </option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>
                                        منخفضة
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            بحث
                        </button>
                        <button type="button" class="btn btn-outline-warning" id="resetFilters">
                            <i class="fas fa-undo me-1"></i>
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- بطاقة عرض المواعيد -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-row-reverse">
                    <!-- أزرار عرض القائمة والشبكة والتقويم على اليسار -->
                    <div class="btn-group me-2" role="group" aria-label="View Toggle">

                        <button type="button" class="btn btn-outline-secondary active" id="tableViewBtn"
                            title="عرض الجدول">
                            <i class="fas fa-table"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="calendarViewBtn"
                            title="عرض التقويم">
                            <i class="fas fa-calendar-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="tab-content" id="appointmentsTabsContent">
                    <!-- عرض المواعيد (جدول) -->
                    <div class="tab-pane fade show active" id="appointments-content" role="tabpanel"
                        aria-labelledby="appointments-tab">
                        @include('client::appointments.partials.appointments_table', [
                            'appointments' => $appointments,
                        ])
                    </div>

                    <!-- عرض التقويم -->
                    <div class="tab-pane fade" id="calendar-tab" role="tabpanel">
                        <div id="calendar-container" class="p-2">
                            <!-- Calendar element for FullCalendar -->
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/ar.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<script>
// Make calendarBookings available globally for the calendar partial
window.calendarBookings = @json($calendarBookings ?? []);
// Make fullCalendarEvents available for FullCalendar
window.fullCalendarEvents = @json($fullCalendarEvents ?? []);

// Debug information
console.log('Calendar Bookings Data:', window.calendarBookings);
console.log('Full Calendar Events Data:', window.fullCalendarEvents);

// Test data if no events are available
if (!window.fullCalendarEvents || window.fullCalendarEvents.length === 0) {
    console.log('No calendar events found, using test data');
    window.fullCalendarEvents = [
        {
            id: 1,
            title: 'موعد تجريبي',
            start: new Date().toISOString().split('T')[0] + 'T10:00:00',
            extendedProps: {
                client_name: 'عميل تجريبي',
                client_phone: '123456789',
                status_code: 1,
                status_text: 'قيد الانتظار',
                status_id: 1,
                notes: 'ملاحظات تجريبية',
                employee: 'موظف تجريبي',
                time: '10:00'
            }
        }
    ];
}
</script>

<script>
// تأكيد تحديث CSRF token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// عند تحميل الصفحة
$(document).ready(function() {
    // معالجة نموذج البحث
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        loadAppointments(1);
    });

    // زر إعادة التعيين
    $('#resetFilters').on('click', function() {
        $('#searchForm')[0].reset();
        $('.select2').val('').trigger('change');
        loadAppointments(1);
    });

    // معالجة الباجينيشن
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        if (!url) return;

        let page = new URL(url).searchParams.get('page') || 1;
        loadAppointments(page);

        // التمرير لأعلى الجدول
        $('html, body').animate({
            scrollTop: $("#appointments-content").offset().top - 100
        }, 500);
    });

    // معالجة تغيير الحالة
    $(document).on('click', '.status-action', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let statusText = $(this).text().trim();

        Swal.fire({
            title: 'تأكيد التحديث',
            text: `هل تريد تغيير الحالة إلى "${statusText}"؟`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، تحديث',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                updateStatus(url);
            }
        });
    });

    // معالجة حذف موعد
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');

        Swal.fire({
            title: 'تأكيد الحذف',
            text: "هل أنت متأكد من حذف هذا الموعد؟ لا يمكن التراجع عن هذه العملية!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                deleteAppointment(url);
            }
        });
    });

    // زر تصدير الصفحة الحالية إلى Excel
    $('#exportExcelBtn').on('click', function() {
        exportAppointmentsToExcel();
    });

    // إعدادات Select2
    if ($.fn.select2) {
        $('.select2').select2({
            dir: 'rtl',
            language: 'ar',
            placeholder: 'اختر...',
            allowClear: true
        });
    }
});

// دالة تحميل المواعيد
function loadAppointments(page = 1) {
    let formData = $('#searchForm').serialize();
    formData += '&page=' + page;

    showLoader();

    $.ajax({
        url: '{{ route("appointments.index") }}',
        type: 'GET',
        data: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            $('#appointments-content').html(response);
            hideLoader();
            toastr.success('تم تحديث البيانات');
        },
        error: function(xhr) {
            hideLoader();
            toastr.error('حدث خطأ في التحميل');
            console.error(xhr);
        }
    });
}

// دالة تحديث الحالة
function updateStatus(url) {
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            if(response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم التحديث',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                let currentPage = $('.pagination .page-item.active .page-link').text() || 1;
                setTimeout(() => loadAppointments(currentPage), 500);
            } else {
                toastr.error(response.message || 'حدث خطأ');
            }
        },
        error: function(xhr) {
            toastr.error('فشل تحديث الحالة');
            console.error(xhr);
        }
    });
}

// دالة حذف موعد
function deleteAppointment(url) {
    $.ajax({
        url: url,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            if(response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم الحذف',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                let currentPage = $('.pagination .page-item.active .page-link').text() || 1;
                setTimeout(() => loadAppointments(currentPage), 500);
            } else {
                toastr.error(response.message || 'حدث خطأ');
            }
        },
        error: function(xhr) {
            toastr.error('فشل حذف الموعد');
            console.error(xhr);
        }
    });
}

// مؤشر التحميل
function showLoader() {
    let loader = `
        <div id="appointmentsLoader" class="text-center py-5">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <p class="mt-3 text-muted">جاري تحميل البيانات...</p>
        </div>
    `;

    if($('#appointmentsLoader').length === 0) {
        $('#appointments-content').prepend(loader);
    }
    $('#appointments-content').find('.card-content, .alert').hide();
}

function hideLoader() {
    $('#appointmentsLoader').remove();
    $('#appointments-content').find('.card-content, .alert').show();
}

// تصدير الصفحة الحالية إلى Excel
function exportAppointmentsToExcel() {
    // التحقق من وجود بيانات
    if ($('#appointments-content table tbody tr').length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'لا توجد بيانات',
            text: 'لا توجد مواعيد لتصديرها في الصفحة الحالية'
        });
        return;
    }

    // عرض مؤشر التحميل
    Swal.fire({
        title: 'جاري التصدير...',
        html: 'يرجى الانتظار',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // جلب البيانات المفلترة من الجدول
    let tableData = [];
    let headers = [];

    // استخراج رؤوس الأعمدة
    $('#appointments-content table thead tr th').each(function(index) {
        let headerText = $(this).text().trim();
        // تجاهل عمود الرقم التسلسلي والإجراءات
        if (headerText && headerText !== 'الإجراءات' && headerText !== '#' && headerText !== '') {
            headers.push(headerText);
        }
    });

    // استخراج بيانات الصفوف
    $('#appointments-content table tbody tr').each(function() {
        let rowData = [];
        let cellIndex = 0;

        $(this).find('td').each(function(index) {
            // تجاهل عمود الرقم التسلسلي (أول عمود)
            if (index === 0) return;

            // تجاهل عمود الإجراءات (آخر عمود)
            if ($(this).find('.dropdown').length > 0) return;

            let cellText = '';

            // معالجة خاصة للحالات (badges)
            if ($(this).find('.badge').length > 0) {
                cellText = $(this).find('.badge').text().trim();
            }
            // معالجة التواريخ والأوقات
            else if ($(this).find('small').length > 0) {
                let mainText = $(this).clone().children().remove().end().text().trim();
                let smallText = $(this).find('small').text().trim();
                cellText = mainText + ' ' + smallText;
            }
            // معالجة النصوص العادية
            else {
                cellText = $(this).text().trim();
            }

            if (cellText) {
                rowData.push(cellText);
            }
        });

        if (rowData.length > 0) {
            tableData.push(rowData);
        }
    });

    // إنشاء ملف Excel
    try {
        // إنشاء workbook جديد
        const wb = XLSX.utils.book_new();

        // تحضير البيانات مع الرؤوس
        const wsData = [headers, ...tableData];

        // إنشاء worksheet
        const ws = XLSX.utils.aoa_to_sheet(wsData);

        // تنسيق عرض الأعمدة
        const colWidths = headers.map(() => ({ wch: 20 }));
        ws['!cols'] = colWidths;

        // إضافة worksheet إلى workbook
        XLSX.utils.book_append_sheet(wb, ws, "المواعيد");

        // تحديد اسم الملف مع التاريخ
        const today = new Date();
        const dateStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
        const timeStr = `${String(today.getHours()).padStart(2, '0')}-${String(today.getMinutes()).padStart(2, '0')}`;
        const filename = `appointments_${dateStr}_${timeStr}.xlsx`;

        // تصدير الملف
        XLSX.writeFile(wb, filename);

        // رسالة نجاح
        Swal.fire({
            icon: 'success',
            title: 'تم التصدير بنجاح',
            html: `<p>تم تصدير <strong>${tableData.length}</strong> موعد</p>`,
            timer: 3000,
            showConfirmButton: true,
            confirmButtonText: 'حسناً'
        });

        toastr.success(`تم تصدير ${tableData.length} موعد بنجاح`);

    } catch (error) {
        console.error('خطأ في التصدير:', error);
        Swal.fire({
            icon: 'error',
            title: 'خطأ في التصدير',
            text: 'حدث خطأ أثناء تصدير البيانات. يرجى المحاولة مرة أخرى.',
            confirmButtonText: 'حسناً'
        });
    }
}

// دوال toggle للبحث المتقدم
function toggleSearchText(button) {
    const text = button.querySelector('.button-text');
    const isExpanded = button.getAttribute('aria-expanded') === 'true';
    text.textContent = isExpanded ? 'متقدم' : 'بسيط';
}

function toggleSearchFields(button) {
    const card = button.closest('.card');
    const cardBody = card.querySelector('.card-body');
    const icon = button.querySelector('i');
    const text = button.querySelector('.hide-button-text');

    if (cardBody.style.display === 'none') {
        cardBody.style.display = 'block';
        icon.className = 'fa fa-times';
        text.textContent = 'اخفاء';
    } else {
        cardBody.style.display = 'none';
        icon.className = 'fa fa-eye';
        text.textContent = 'إظهار';
    }
}

// ==== كود التقويم ====
let calendar = null; // Global variable to hold the FullCalendar instance

document.addEventListener('DOMContentLoaded', function() {
    // الحصول على أزرار التبديل
    const calendarViewBtn = document.getElementById('calendarViewBtn');
    const tableViewBtn = document.getElementById('tableViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');

    // الحصول على محتوى التبويبات
    const appointmentsTab = document.getElementById('appointments-content');
    const calendarTab = document.getElementById('calendar-tab');

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
        console.log('Switching to view:', view);

        // إزالة فئة "active" من جميع الأزرار
        [listViewBtn, tableViewBtn, calendarViewBtn].forEach(btn => {
            if (btn) btn.classList.remove('active');
        });

        // إعداد الزر المناسب كنشط وإظهار علامة التبويب الصحيحة
        if (view === 'calendar') {
            if (calendarViewBtn) calendarViewBtn.classList.add('active');
            if (appointmentsTab) appointmentsTab.classList.remove('show', 'active');
            if (calendarTab) {
                calendarTab.classList.add('show', 'active');
                calendarTab.style.display = 'block'; // تأكيد الإظهار
            }

            // تهيئة وعرض التقويم
            initializeCalendar();
        } else {
            // تنشيط الزر المناسب
            if (view === 'list' && listViewBtn) {
                listViewBtn.classList.add('active');
            } else if (tableViewBtn) {
                tableViewBtn.classList.add('active');
            }

            // إظهار عرض الجدول وإخفاء التقويم
            if (appointmentsTab) appointmentsTab.classList.add('show', 'active');
            if (calendarTab) {
                calendarTab.classList.remove('show', 'active');
                calendarTab.style.display = 'none'; // تأكيد الإخفاء
            }
        }
    }

    // وظيفة تهيئة التقويم
    function initializeCalendar() {
        const calendarEl = document.getElementById('calendar');

        if (!calendarEl) {
            console.error('Calendar element not found!');
            return;
        }

        console.log('Initializing calendar...');

        // If calendar already exists, just show it
        if (calendar) {
            calendar.render();
            return;
        }

        // إظهار مؤشر التحميل
        calendarEl.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">جاري تحميل المواعيد...</p></div>';

        // استخدام البيانات المتاحة عالميًا
        const events = window.fullCalendarEvents || [];

        console.log('Calendar data loaded:', events.length, 'events');

        // تهيئة وتكوين التقويم
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
            eventClick: function(info) {
                // إظهار تفاصيل الموعد في النافذة المنبثقة
                Swal.fire({
                    title: info.event.title,
                    html: `
                        <div class="appointment-details text-right" dir="rtl">
                            <p><strong>العميل:</strong> ${info.event.extendedProps.client_name || 'غير محدد'}</p>
                            <p><strong>رقم الهاتف:</strong> ${info.event.extendedProps.client_phone || 'غير متوفر'}</p>
                            <p><strong>التاريخ:</strong> ${moment(info.event.start).format('YYYY-MM-DD')}</p>
                            <p><strong>الوقت:</strong> ${info.event.extendedProps.time || 'غير محدد'}</p>
                            <p><strong>الحالة:</strong> ${info.event.extendedProps.status_text || 'غير محدد'}</p>
                            <p><strong>الموظف:</strong> ${info.event.extendedProps.employee || 'غير محدد'}</p>
                            <p><strong>ملاحظات:</strong> ${info.event.extendedProps.notes || 'لا توجد ملاحظات'}</p>
                        </div>
                    `,
                    confirmButtonText: 'إغلاق',
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
            // تخصيص تصميم الأحداث
            eventClassNames: function(arg) {
                // إضافة فئات CSS بناءً على حالة الموعد
                return ['appointment-event', `status-${arg.event.extendedProps.status_id || 1}`];
            },
            // تكييف العرض مع اللغة العربية والاتجاه من اليمين إلى اليسار
            dayMaxEvents: true,
            firstDay: 6, // السبت
            // تعريف أحداث النقر على اليوم
            dateClick: function(info) {
                // يمكن إضافة خيار لإنشاء موعد جديد هنا
                console.log('Date clicked:', info.dateStr);
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
                        // إعادة توجيه إلى صفحة إنشاء موعد مع تاريخ محدد مسبقًا
                        window.location.href = `/appointments/create?date=${info.dateStr}`;
                    }
                });
            },
            // الإعدادات المتجاوبة
            height: 'auto',
            // إظهار مؤشر التحميل
            loading: function(isLoading) {
                if (isLoading) {
                    calendarEl.classList.add('calendar-loading');
                } else {
                    calendarEl.classList.remove('calendar-loading');
                }
            }
        });

        // عرض التقويم
        calendar.render();

        console.log('Calendar rendered successfully');
    }
});
</script>
@endsection
