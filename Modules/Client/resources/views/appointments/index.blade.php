@extends('sales::master')
@section('title')
    ادارة المواعيد
@stop
@section('css')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        /* Calendar Styles */
        #calendar {
            max-width: 100%;
            margin: 0 auto;
        }

        .fc-event {
            cursor: pointer;
            font-size: 0.85em;
            padding: 2px 4px;
        }

        /* إضافة CSS للتجاوب مع أحجام الشاشات المختلفة */
        @media (max-width: 575.98px) {
            .min-mobile {
                display: table-cell;
            }


            .fixed-status-menu {
                position: fixed;
                left: 20px;
                top: 50%;
                transform: translateY(-50%);
                z-index: 1000;
                background: white;
                border-radius: 8px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                padding: 10px 0;
                width: 180px;
            }

            .status-menu-item {
                padding: 8px 15px;
                display: flex;
                align-items: center;
                cursor: pointer;
                transition: all 0.3s;
            }

            .status-menu-item:hover {
                background-color: #f8f9fa;
            }

            .status-menu-item i {
                margin-left: 8px;
                font-size: 14px;
            }

            .status-menu-item .text-danger {
                color: #dc3545;
            }

            .status-menu-item .text-success {
                color: #28a745;
            }

            .status-menu-item .text-warning {
                color: #ffc107;
            }

            .status-menu-item .text-info {
                color: #17a2b8;
            }

            .status-menu-item .text-primary {
                color: #007bff;
            }

            .min-tablet {
                display: none;
            }

            .min-desktop {
                display: none;
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

        .dropdown-menu {
            position: absolute;
            right: 0;
            left: auto;
        }

        /* عشان نخلي القائمة ثابتة على الشاشة وقت ما تظهر */
        .fixed-dropdown-menu {
            position: fixed !important;
            top: 100px;
            /* عدّل حسب المكان المناسب */
            right: 120px;
            /* تزحزح نحو اليسار */
            z-index: 1050;
            /* عشان تبقى فوق كل العناصر */
        }
        
        /* Calendar tab styles */
        #calendar-tab {
            display: none;
        }
        
        .btn-group .btn.active {
            background-color: #007bff;
            color: white;
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

                    <!-- زر أضف العميل -->

                    <!-- زر تحميل ملف -->
                    <label class="bg-white border d-flex align-items-center justify-content-center"
                        style="width: 44px; height: 44px; cursor: pointer; border-radius: 6px;" title="تحميل ملف">
                        <i class="fas fa-cloud-upload-alt text-primary"></i>
                        <input type="file" name="file" class="d-none">
                    </label>

                    <!-- زر استيراد -->
                    <button type="submit" class="bg-white border d-flex align-items-center justify-content-center"
                        style="width: 44px; height: 44px; border-radius: 6px;" title="استيراد ك Excel">
                        <i class="fas fa-database text-primary"></i>
                    </button>

                    <!-- زر حد ائتماني -->


                    <!-- زر تصدير ك Excel (الجديد) -->
                    <button id="exportExcelBtn" class="bg-white border d-flex align-items-center justify-content-center"
                        style="width: 44px; height: 44px; border-radius: 6px;" title="تصدير ك Excel">
                        <i class="fas fa-file-excel text-primary"></i>
                    </button>

                    <a href="{{ route('appointments.create') }}"
                        class="btn btn-primary d-flex align-items-center justify-content-center"
                        style="height: 44px; padding: 0 16px; font-weight: bold; border-radius: 6px;">
                        <i class="fas fa-plus ms-2"></i>
                        أضف موعد جديد
                    </a>
                </div>
            </div>
        </div>

        {{-- resources/views/appointments/partials/search_card.blade.php --}}

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
                            معاد جدولته
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
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-row-reverse">

                    <!-- أزرار عرض القائمة والشبكة والتقويم على اليسار -->
                    <div class="btn-group me-2" role="group" aria-label="View Toggle">
                        <button type="button" class="btn btn-outline-secondary" id="listViewBtn" title="عرض القائمة">
                            <i class="fas fa-list"></i>
                        </button>
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
                    <!-- Appointments Tab (Table View) -->
                    <div class="tab-pane fade show active" id="appointments-content" role="tabpanel"
                        aria-labelledby="appointments-tab">
                        @include('client::appointments.partials.appointments_table', [
                            'appointments' => $appointments->where('status', 1),
                        ])
                    </div>

                    <!-- Calendar Tab -->
                    <div class="tab-pane fade" id="calendar-tab" role="tabpanel">
                        <div id="calendar"></div>
                    </div>

                    <!-- Supply Orders Tab -->
                    <div class="tab-pane fade" id="supply-orders-content" role="tabpanel"
                        aria-labelledby="supply-orders-tab">
                        {{-- @include('client.appointments.partials.supply_orders_table', [
                    'appointments' => $appointments->where('status', 2)
                ]) --}}
                    </div>

                    <!-- Clients Tab -->
                    <div class="tab-pane fade" id="clients-content" role="tabpanel" aria-labelledby="clients-tab">
                        {{-- @include('client.appointments.partials.clients_table', [
                    'clients' => $clients
                ]) --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- Add this CSS -->
        <style>
            .btn-group .btn {
                padding: 0.375rem 0.75rem;
            }

            .btn-group .btn i {
                font-size: 1rem;
            }

            .btn-group .btn.active {
                background-color: #007bff;
                color: white;
                border-color: #007bff;
            }
        </style>


    </div>
    </div>
    </div>




@endsection
@section('scripts')
    <script src="{{ asset('assets/js/applmintion.js') }}"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // View toggle buttons
            const listViewBtn = document.getElementById('listViewBtn');
            const tableViewBtn = document.getElementById('tableViewBtn');
            const calendarViewBtn = document.getElementById('calendarViewBtn');
            
            // Tab content elements
            const appointmentsTab = document.getElementById('appointments-content');
            const calendarTab = document.getElementById('calendar-tab');
            
            // Calendar data passed from controller
            const calendarEvents = @json($calendarAppointments);
            
            // Add click event listeners for view toggle buttons
            listViewBtn.addEventListener('click', function() {
                // Activate list view button
                listViewBtn.classList.add('active');
                tableViewBtn.classList.remove('active');
                calendarViewBtn.classList.remove('active');
                
                // Show appointments table, hide calendar
                appointmentsTab.classList.add('show', 'active');
                calendarTab.classList.remove('show', 'active');
                
                console.log('Switched to List View');
            });
            
            tableViewBtn.addEventListener('click', function() {
                // Activate table view button
                tableViewBtn.classList.add('active');
                listViewBtn.classList.remove('active');
                calendarViewBtn.classList.remove('active');
                
                // Show appointments table, hide calendar
                appointmentsTab.classList.add('show', 'active');
                calendarTab.classList.remove('show', 'active');
                
                console.log('Switched to Table View');
            });
            
            calendarViewBtn.addEventListener('click', function() {
                // Activate calendar view button
                calendarViewBtn.classList.add('active');
                tableViewBtn.classList.remove('active');
                listViewBtn.classList.remove('active');
                
                // Hide appointments table, show calendar
                appointmentsTab.classList.remove('show', 'active');
                calendarTab.classList.add('show', 'active');
                
                // Initialize or render calendar
                renderCalendar();
                
                console.log('Switched to Calendar View');
            });
            
            // Initialize FullCalendar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: 'ar',
                buttonText: {
                    today: 'اليوم',
                    month: 'شهر',
                    week: 'أسبوع',
                    day: 'يوم'
                },
                events: calendarEvents,
                eventClick: function(info) {
                    // Handle event click
                    alert('Event: ' + info.event.title);
                    // You can add more detailed information here
                    console.log('Event details:', info.event);
                }
            });
            
            // Function to render calendar
            function renderCalendar() {
                if (calendarViewBtn.classList.contains('active')) {
                    calendar.render();
                }
            }
        });
    </script>

@endsection