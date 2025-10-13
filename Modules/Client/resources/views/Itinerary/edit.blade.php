@extends('master')

@section('title', 'تعديل خط السير للمندوب')

@section('content')
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Cairo', sans-serif;
        }

        .itinerary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 10px;
        }

        .itinerary-header h2 {
            color: #333;
            font-weight: 700;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .card-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            font-weight: 600;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 1rem 1.5rem;
        }

        .day-assignment {
            margin-bottom: 25px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #007bff;
        }

        .day-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .day-title i {
            color: #007bff;
        }

        .client-select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.95rem;
            background-color: white;
            transition: all 0.3s ease;
        }

        .client-select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .selected-clients-list {
            min-height: 150px;
            max-height: 300px;
            overflow-y: auto;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }

        .selected-client-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 10px;
            background: white;
            border: 1px solid #e0e0e0;
            border-left: 4px solid #28a745;
            border-radius: 8px;
            transition: all 0.3s ease;
            animation: slideInRight 0.3s ease-in-out;
            cursor: move;
        }

        .selected-client-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .selected-client-card.dragging {
            opacity: 0.5;
            cursor: grabbing;
        }

        .remove-client-btn {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
        }

        .remove-client-btn:hover {
            background: #c82333;
            transform: scale(1.1);
        }

        .client-count-badge {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            min-width: 60px;
            text-align: center;
        }

        .empty-day-message {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 25px 20px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background-color: #fafafa;
        }

        .available-client-card {
            padding: 12px 15px;
            margin-bottom: 10px;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-left: 5px solid #17a2b8;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
            cursor: grab;
        }

        .available-client-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .available-client-card.client-assigned {
            background-color: #f8f9fa;
            border-left-color: #28a745;
            opacity: 0.8;
        }

        .day-action-buttons {
            display: flex;
            gap: 5px;
            margin-right: auto;
        }

        .btn-day-action {
            padding: 4px 10px;
            font-size: 0.8rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .day-assignment.drop-zone-active {
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.1), rgba(0, 86, 179, 0.1));
            border-left-color: #007bff;
            border-left-width: 6px;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>

    <div class="card">
        <div class="card-body">
            <div class="container-fluid">
                <div class="itinerary-header">
                    <h2><i class="fas fa-route text-primary"></i> تعديل خط السير للمندوب: {{ $employee->name }}</h2>
                    <h6 id="week-info" class="text-muted"></h6>
                    <div>
                        <button id="auto-distribute" class="btn btn-info shadow-sm mr-2">
                            <i class="fas fa-magic"></i> توزيع تلقائي
                        </button>
                        <button id="clear-all" class="btn btn-warning shadow-sm mr-2">
                            <i class="fas fa-eraser"></i> مسح الكل
                        </button>
                        <button id="save-itinerary" class="btn btn-success shadow-sm">
                            <i class="fas fa-save"></i> حفظ التعديلات
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-cogs"></i> الإعدادات والفلاتر</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="year-select" class="font-weight-bold">السنة</label>
                                        <select id="year-select" class="form-control client-select">
                                            @for ($year = date('Y') - 2; $year <= date('Y') + 2; $year++)
                                                <option value="{{ $year }}"
                                                    {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="week-select" class="font-weight-bold">الأسبوع</label>
                                        <select id="week-select" class="form-control client-select select2">
                                            @for ($i = 1; $i <= 52; $i++)
                                                <option value="{{ $i }}"
                                                    {{ $i == $currentWeek ? 'selected' : '' }}>الأسبوع {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="group-select" class="font-weight-bold">اختر مجموعة العملاء</label>
                                    <select id="group-select" class="form-control client-select select2">
                                        <option value="">-- اختر مجموعة --</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-users"></i> العملاء المتاحين</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="text" id="client-search" class="form-control"
                                        placeholder="ابحث عن عميل بالاسم أو الكود...">
                                </div>
                                <div id="available-clients-container" style="position: relative;">
                                    <div class="loading-spinner spinner-border text-primary" role="status"
                                        style="display: none;">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div id="available-clients-list" class="available-clients-list"
                                        style="min-height: 200px; background-color: #f8f9fa; border-radius: 8px; padding: 15px; overflow-y: auto; max-height: 50vh;">
                                        <p class="text-center text-muted mt-4">الرجاء اختيار مجموعة لعرض العملاء.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="client-assignment-container">
                            @php
                                $days = [
                                    'saturday' => ['name' => 'السبت', 'icon' => 'fa-calendar-day'],
                                    'sunday' => ['name' => 'الأحد', 'icon' => 'fa-calendar-day'],
                                    'monday' => ['name' => 'الإثنين', 'icon' => 'fa-calendar-day'],
                                    'tuesday' => ['name' => 'الثلاثاء', 'icon' => 'fa-calendar-day'],
                                    'wednesday' => ['name' => 'الأربعاء', 'icon' => 'fa-calendar-day'],
                                    'thursday' => ['name' => 'الخميس', 'icon' => 'fa-calendar-day'],
                                    'friday' => ['name' => 'الجمعة', 'icon' => 'fa-calendar-day'],
                                ];
                            @endphp

                            @foreach ($days as $day => $dayInfo)
                                <div class="day-assignment" data-day="{{ $day }}">
                                    <div class="day-title">
                                        <i class="fas {{ $dayInfo['icon'] }}"></i>
                                        {{ $dayInfo['name'] }}
                                        <span class="client-count-badge" id="count-{{ $day }}">0 عميل</span>

                                        <div class="day-action-buttons">
                                            <button class="btn btn-sm btn-outline-primary btn-day-action add-all-btn"
                                                data-day="{{ $day }}" title="إضافة كل العملاء المتاحين">
                                                <i class="fas fa-plus-circle"></i> الكل
                                            </button>
                                            <button class="btn btn-sm btn-outline-success btn-day-action add-5-btn"
                                                data-day="{{ $day }}" title="إضافة أول 5 عملاء">
                                                <i class="fas fa-forward"></i> 5
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger btn-day-action clear-day-btn"
                                                data-day="{{ $day }}" title="مسح اليوم">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="client-select-wrapper">
                                        <select class="client-select day-client-select select2"
                                            data-day="{{ $day }}" disabled>
                                            <option value="">-- اختر عميل لإضافته --</option>
                                        </select>
                                    </div>

                                    <div class="selected-clients-list" id="clients-{{ $day }}">
                                        <div class="empty-day-message">
                                            <i class="fas fa-info-circle text-muted"></i>
                                            لا توجد عملاء محددين لهذا اليوم
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {
            const employeeId = {{ $employee->id }};
            let currentYear = {{ $currentYear }};
            let currentWeek = {{ $currentWeek }};
            let availableClients = [];
            let dayAssignments = {
                saturday: [],
                sunday: [],
                monday: [],
                tuesday: [],
                wednesday: [],
                thursday: [],
                friday: []
            };

            const yearSelect = $('#year-select');
            const weekSelect = $('#week-select');
            const groupSelect = $('#group-select');
            const availableClientsList = $('#available-clients-list');
            const spinner = $('#available-clients-container .loading-spinner');

            updateWeekInfo();
            loadSavedItinerary();
            initializeDragAndDrop();

            // Event Listeners
            yearSelect.on('change', function() {
                currentYear = $(this).val();
                updateWeekInfo();
                loadSavedItinerary();
            });

            weekSelect.on('change', function() {
                currentWeek = $(this).val();
                updateWeekInfo();
                loadSavedItinerary();
            });

            groupSelect.on('change', function() {
                const groupId = $(this).val();
                if (groupId) {
                    fetchClientsForGroup(groupId);
                } else {
                    availableClientsList.html(
                        '<p class="text-center text-muted">الرجاء اختيار مجموعة لعرض العملاء.</p>');
                    $('.day-client-select').prop('disabled', true);
                }
            });

            $(document).on('change', '.day-client-select', function() {
                const day = $(this).data('day');
                const clientId = $(this).val();
                if (clientId) {
                    const client = availableClients.find(c => c.id == clientId);
                    if (client && !dayAssignments[day].find(c => c.id == clientId)) {
                        addClientToDay(day, client);
                        $(this).val('');
                    }
                }
            });

            $(document).on('click', '.remove-client-btn', function() {
                const day = $(this).data('day');
                const clientId = $(this).data('client-id');
                removeClientFromDay(day, clientId);
            });

            $(document).on('click', '.add-all-btn', function() {
                const day = $(this).data('day');
                const availableForDay = availableClients.filter(client =>
                    !dayAssignments[day].find(assigned => assigned.id == client.id)
                );

                if (availableForDay.length === 0) {
                    Swal.fire('تنبيه', 'لا يوجد عملاء متاحين للإضافة', 'info');
                    return;
                }

                Swal.fire({
                    title: 'تأكيد الإضافة',
                    text: `هل تريد إضافة ${availableForDay.length} عميل لـ ${getDayNameAr(day)}؟`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، أضف الكل',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        availableForDay.forEach(client => addClientToDay(day, client));
                        Swal.fire('تم!', `تمت إضافة ${availableForDay.length} عميل بنجاح`, 'success');
                    }
                });
            });

            $(document).on('click', '.add-5-btn', function() {
                const day = $(this).data('day');
                const availableForDay = availableClients.filter(client =>
                    !dayAssignments[day].find(assigned => assigned.id == client.id)
                ).slice(0, 5);

                if (availableForDay.length === 0) {
                    Swal.fire('تنبيه', 'لا يوجد عملاء متاحين للإضافة', 'info');
                    return;
                }

                availableForDay.forEach(client => addClientToDay(day, client));
                Swal.fire('تم!', `تمت إضافة ${availableForDay.length} عميل`, 'success');
            });

            $(document).on('click', '.clear-day-btn', function() {
                const day = $(this).data('day');
                if (dayAssignments[day].length === 0) {
                    Swal.fire('تنبيه', 'لا يوجد عملاء في هذا اليوم', 'info');
                    return;
                }

                Swal.fire({
                    title: 'تأكيد المسح',
                    text: `هل تريد مسح ${dayAssignments[day].length} عميل من ${getDayNameAr(day)}؟`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، امسح',
                    cancelButtonText: 'إلغاء',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        dayAssignments[day] = [];
                        updateDayDisplay(day);
                        updateDayClientSelects();
                        updateAvailableClientsList();
                        Swal.fire('تم!', 'تم مسح العملاء بنجاح', 'success');
                    }
                });
            });

            $('#save-itinerary').on('click', function() {
                saveItinerary();
            });

            $('#auto-distribute').on('click', function() {
                if (availableClients.length === 0) {
                    Swal.fire('تنبيه', 'لا يوجد عملاء متاحين للتوزيع', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'التوزيع التلقائي',
                    html: `
                        <p>سيتم توزيع ${availableClients.length} عميل على أيام الأسبوع</p>
                        <label class="mt-3">استثناء يوم:</label>
                        <select id="exclude-day" class="form-control">
                            <option value="">لا يوجد</option>
                            <option value="friday">الجمعة (إجازة)</option>
                            <option value="saturday">السبت</option>
                        </select>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'ابدأ التوزيع',
                    cancelButtonText: 'إلغاء',
                    preConfirm: () => {
                        return $('#exclude-day').val();
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        distributeClientsAutomatically(result.value);
                    }
                });
            });

            $('#clear-all').on('click', function() {
                const totalClients = Object.values(dayAssignments).reduce((sum, day) => sum + day.length, 0);
                if (totalClients === 0) {
                    Swal.fire('تنبيه', 'لا يوجد عملاء لمسحهم', 'info');
                    return;
                }

                Swal.fire({
                    title: 'تأكيد مسح الكل',
                    text: `هل تريد مسح جميع العملاء (${totalClients} عميل) من كل الأيام؟`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، امسح الكل',
                    cancelButtonText: 'إلغاء',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Object.keys(dayAssignments).forEach(day => {
                            dayAssignments[day] = [];
                        });
                        updateAllDayDisplays();
                        updateDayClientSelects();
                        updateAvailableClientsList();
                        Swal.fire('تم!', 'تم مسح جميع العملاء بنجاح', 'success');
                    }
                });
            });

            $('#client-search').on('keyup', function() {
                filterAvailableClients($(this).val());
            });

            function loadSavedItinerary() {
                Object.keys(dayAssignments).forEach(day => {
                    dayAssignments[day] = [];
                });

                $.ajax({
                    url: `/api/employees/${employeeId}/itinerary`,
                    method: 'GET',
                    data: {
                        year: currentYear,
                        week: currentWeek
                    },
                    success: function(itinerary) {
                        if (itinerary && itinerary.length > 0) {
                            itinerary.forEach(visit => {
                                const day = visit.day_of_week;
                                if (visit.client && dayAssignments[day]) {
                                    if (!dayAssignments[day].find(c => c.id == visit.client.id)) {
                                        dayAssignments[day].push(visit.client);
                                    }
                                }
                            });

                            Object.keys(dayAssignments).forEach(day => {
                                updateDayDisplay(day);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('خطأ في جلب البيانات المحفوظة:', error);
                    }
                });
            }

            function fetchClientsForGroup(groupId) {
                spinner.show();
                $('.day-client-select').prop('disabled', true);

                $.ajax({
                    url: `/api/groups/${groupId}/clients`,
                    method: 'GET',
                    success: function(clients) {
                        spinner.hide();

                        const assignedClients = [];
                        Object.values(dayAssignments).forEach(dayClients => {
                            dayClients.forEach(client => {
                                if (!assignedClients.find(c => c.id === client.id)) {
                                    assignedClients.push(client);
                                }
                            });
                        });

                        availableClients = [...clients, ...assignedClients];
                        availableClients = availableClients.filter((client, index, self) =>
                            index === self.findIndex((c) => c.id === client.id)
                        );

                        updateAvailableClientsList();
                        updateDayClientSelects();

                        if (availableClients.length > 0) {
                            $('.day-client-select').prop('disabled', false);
                        } else {
                            availableClientsList.html(
                                '<p class="text-center text-muted">لا يوجد عملاء متاحين في هذه المجموعة.</p>'
                            );
                        }
                    },
                    error: function(xhr) {
                        spinner.hide();
                        let errorMsg = 'فشل في جلب العملاء.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire('خطأ', errorMsg, 'error');
                    }
                });
            }

            function addClientToDay(day, client) {
                if (!dayAssignments[day].find(c => c.id == client.id)) {
                    dayAssignments[day].push(client);
                    updateDayDisplay(day);
                    updateDayClientSelects();
                    updateAvailableClientsList();
                }
            }

            function removeClientFromDay(day, clientId) {
                dayAssignments[day] = dayAssignments[day].filter(c => c.id != clientId);
                updateDayDisplay(day);
                updateDayClientSelects();
                updateAvailableClientsList();
            }

            function updateDayDisplay(day) {
                const container = $(`#clients-${day}`);
                const countBadge = $(`#count-${day}`);

                container.empty();

                if (dayAssignments[day].length === 0) {
                    container.html(`
                        <div class="empty-day-message">
                            <i class="fas fa-calendar-plus text-muted"></i>
                            <p>لم يتم تعيين عملاء لهذا اليوم بعد</p>
                        </div>
                    `);
                    countBadge.text('0 عميل');
                } else {
                    dayAssignments[day].forEach(client => {
                        const clientCard = createSelectedClientCard(client, day);
                        container.append(clientCard);
                    });
                    countBadge.text(`${dayAssignments[day].length} عميل`);
                }

                $('[data-toggle="tooltip"]').tooltip();
            }

            function updateAllDayDisplays() {
                Object.keys(dayAssignments).forEach(day => {
                    updateDayDisplay(day);
                });
            }

            function updateDayClientSelects() {
                $('.day-client-select').each(function() {
                    const day = $(this).data('day');
                    const availableForDay = availableClients.filter(client =>
                        !dayAssignments[day].find(assigned => assigned.id == client.id)
                    );

                    let options = '<option value="">-- اختر عميل لإضافته --</option>';
                    availableForDay.forEach(client => {
                        options +=
                            `<option value="${client.id}">${client.trade_name} - ${client.code}</option>`;
                    });

                    $(this).html(options);
                });
            }

            function updateAvailableClientsList() {
                availableClientsList.empty();

                if (availableClients.length === 0) {
                    availableClientsList.html(
                        '<p class="text-center text-muted">لا يوجد عملاء متاحين في هذه المجموعة.</p>');
                    return;
                }

                availableClients.forEach(client => {
                    const clientCard = createAvailableClientCard(client);
                    availableClientsList.append(clientCard);
                });

                $('[data-toggle="tooltip"]').tooltip();
            }

            function saveItinerary() {
                const visits = {};
                Object.keys(dayAssignments).forEach(day => {
                    visits[day] = dayAssignments[day].map(client => client.id);
                });

                const saveBtn = $('#save-itinerary');
                const originalText = saveBtn.html();
                saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...');

                $.ajax({
                    url: '{{ route('itinerary.update', $employee->id) }}',
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        employee_id: employeeId,
                        year: currentYear,
                        week_number: currentWeek,
                        visits: visits
                    },
                    success: function(response) {
                        saveBtn.prop('disabled', false).html(originalText);

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الحفظ بنجاح!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('خطأ', response.message || 'حدث خطأ غير متوقع.', 'error');
                        }
                    },
                    error: function(xhr) {
                        saveBtn.prop('disabled', false).html(originalText);

                        let errorMsg = 'فشل في الاتصال بالخادم.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire('خطأ', errorMsg, 'error');
                    }
                });
            }

            function distributeClientsAutomatically(excludeDay) {
                Object.keys(dayAssignments).forEach(day => {
                    dayAssignments[day] = [];
                });

                const availableDays = Object.keys(dayAssignments).filter(day => day !== excludeDay);
                availableClients.forEach((client, index) => {
                    const dayIndex = index % availableDays.length;
                    const day = availableDays[dayIndex];
                    dayAssignments[day].push(client);
                });

                updateAllDayDisplays();
                updateDayClientSelects();
                updateAvailableClientsList();
                Swal.fire('تم!', 'تم توزيع العملاء بنجاح على أيام الأسبوع', 'success');
            }

            function initializeDragAndDrop() {
                let draggedClient = null;

                $(document).on('dragstart', '.available-client-card', function(e) {
                    const clientId = $(this).data('client-id');
                    draggedClient = availableClients.find(c => c.id == clientId);
                    $(this).addClass('dragging');
                    e.originalEvent.dataTransfer.effectAllowed = 'copy';
                });

                $(document).on('dragend', '.available-client-card', function() {
                    $(this).removeClass('dragging');
                    draggedClient = null;
                    $('.day-assignment').removeClass('drop-zone-active');
                });

                $('.day-assignment').on('dragover', function(e) {
                    e.preventDefault();
                    $(this).addClass('drop-zone-active');
                    e.originalEvent.dataTransfer.dropEffect = 'copy';
                });

                $('.day-assignment').on('dragleave', function() {
                    $(this).removeClass('drop-zone-active');
                });

                $('.day-assignment').on('drop', function(e) {
                    e.preventDefault();
                    $(this).removeClass('drop-zone-active');

                    if (draggedClient) {
                        const day = $(this).data('day');
                        if (!dayAssignments[day].find(c => c.id == draggedClient.id)) {
                            addClientToDay(day, draggedClient);
                        }
                    }
                });
            }

            function updateWeekInfo() {
                $('#week-info').text(`العام: ${currentYear}, الأسبوع: ${currentWeek}`);
            }

            function filterAvailableClients(searchTerm) {
                const term = searchTerm.toLowerCase();
                $('.available-client-card').each(function() {
                    const name = $(this).find('.client-name').text().toLowerCase();
                    const code = $(this).find('.client-meta').text().toLowerCase();
                    $(this).toggle(name.includes(term) || code.includes(term));
                });
            }

            function createSelectedClientCard(client, day) {
                if (!client) return '';

                const visitIcon = createActivityIcon('fa-walking', client.visits, 'زيارة');
                const invoiceIcon = createActivityIcon('fa-file-invoice-dollar', client.invoices, 'فاتورة');
                const noteIcon = createActivityIcon('fa-sticky-note', client.appointment_notes, 'ملاحظة');
                const receiptIcon = createActivityIcon('fa-receipt', client.receipts, 'سند قبض');

                return `
                    <div class="selected-client-card" data-client-id="${client.id}" draggable="false">
                        <div class="selected-client-info">
                            <div class="client-name">${client.trade_name}</div>
                            <div class="client-meta">الكود: ${client.code} | ${client.city || 'غير محدد'}</div>
                            <div class="activity-icons mt-1">${visitIcon}${invoiceIcon}${noteIcon}${receiptIcon}</div>
                        </div>
                        <button class="remove-client-btn" data-day="${day}" data-client-id="${client.id}" title="إزالة العميل">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>`;
            }

            function createAvailableClientCard(client) {
                if (!client) return '';

                const visitIcon = createActivityIcon('fa-walking', client.visits, 'زيارة');
                const invoiceIcon = createActivityIcon('fa-file-invoice-dollar', client.invoices, 'فاتورة');
                const noteIcon = createActivityIcon('fa-sticky-note', client.appointment_notes, 'ملاحظة');
                const receiptIcon = createActivityIcon('fa-receipt', client.receipts, 'سند قبض');

                const isAssigned = Object.values(dayAssignments).some(dayClients =>
                    dayClients.find(c => c.id == client.id)
                );

                return `
                    <div class="available-client-card ${isAssigned ? 'client-assigned' : ''}"
                         data-client-id="${client.id}" draggable="true">
                        <div class="client-info">
                            <strong class="client-name">${client.trade_name}</strong>
                            <small class="d-block text-muted client-meta">الكود: ${client.code} | ${client.city || 'غير محدد'}</small>
                            ${isAssigned ? '<small class="text-success"><i class="fas fa-check"></i> مُعيَّن</small>' : ''}
                        </div>
                        <div class="activity-icons">${visitIcon}${invoiceIcon}${noteIcon}${receiptIcon}</div>
                    </div>`;
            }

            function createActivityIcon(iconClass, data, type) {
                if (data && data.length > 0) {
                    const latestItem = data[0];
                    const date = new Date(latestItem.created_at).toLocaleDateString('ar-EG-u-nu-latn', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });

                    let tooltipText = `آخر ${type}: ${date}`;
                    if (type === 'ملاحظة' && latestItem.description) {
                        tooltipText += ` - ${latestItem.description}`;
                    }

                    return `<i class="fas ${iconClass} text-success" data-toggle="tooltip" title="${tooltipText}"></i>`;
                } else {
                    return `<i class="fas ${iconClass} text-muted" data-toggle="tooltip" title="لا يوجد ${type}ات"></i>`;
                }
            }

            function getDayNameAr(day) {
                const days = {
                    saturday: 'السبت',
                    sunday: 'الأحد',
                    monday: 'الإثنين',
                    tuesday: 'الثلاثاء',
                    wednesday: 'الأربعاء',
                    thursday: 'الخميس',
                    friday: 'الجمعة'
                };
                return days[day] || day;
            }

            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection