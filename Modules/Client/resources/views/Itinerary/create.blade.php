@extends('master')

@section('title', 'تخطيط خط السير للمناديب')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/craeteEdit.css') }}">

    <div class="card">
        <div class="card-body">
            <div class="container-fluid">
                <div class="itinerary-header">
                    <h2><i class="fas fa-route text-primary"></i> تخطيط خط السير الأسبوعي</h2>
                    <h6 id="week-info" class="text-muted"></h6>
                    <div>
                        <button id="auto-distribute" class="btn btn-info shadow-sm mr-2">
                            <i class="fas fa-magic"></i> توزيع تلقائي
                        </button>
                        <button id="clear-all" class="btn btn-warning shadow-sm mr-2">
                            <i class="fas fa-eraser"></i> مسح الكل
                        </button>
                        <button id="save-itinerary" class="btn btn-success shadow-sm">
                            <i class="fas fa-save"></i> حفظ خط السير
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
                                <div class="form-group">
                                    <label for="employee-select" class="font-weight-bold">اختر المندوب</label>
                                    <select id="employee-select" class="form-control client-select select2"
                                        {{ auth()->user()->role === 'employee' ? 'disabled' : '' }}>
                                        @if (auth()->user()->role !== 'employee')
                                            <option value="">-- اختر مندوب --</option>
                                        @endif
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ auth()->user()->id == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="year-select" class="font-weight-bold">السنة</label>
                                        <select id="year-select" class="form-control client-select">
                                            @for ($year = date('Y') - 2; $year <= date('Y') + 2; $year++)
                                                <option value="{{ $year }}"
                                                    {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="week-select" class="font-weight-bold">الأسبوع</label>
                                        <select id="week-select" class="form-control client-select select2">
                                            @for ($i = 1; $i <= 52; $i++)
                                                <option value="{{ $i }}">الأسبوع {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="group-select" class="font-weight-bold">اختر مجموعة العملاء</label>
                                    <select id="group-select" class="form-control client-select select2"
                                        {{ auth()->user()->role === 'employee' && $groups->isEmpty() ? 'disabled' : '' }}>
                                        <option value="">-- اختر مجموعة --</option>
                                        @if (auth()->user()->role === 'employee')
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        @endif
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
                                    <div id="available-clients-list" class="available-clients-list">
                                        <p class="text-center text-muted mt-4">الرجاء اختيار مندوب ومجموعة لعرض العملاء.</p>
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
                                    'sunday' => ['name' => 'الأحد', 'icon' => 'fa-sun'],
                                    'monday' => ['name' => 'الاثنين', 'icon' => 'fa-briefcase'],
                                    'tuesday' => ['name' => 'الثلاثاء', 'icon' => 'fa-calendar-check'],
                                    'wednesday' => ['name' => 'الأربعاء', 'icon' => 'fa-calendar-alt'],
                                    'thursday' => ['name' => 'الخميس', 'icon' => 'fa-calendar-week'],
                                    'friday' => ['name' => 'الجمعة', 'icon' => 'fa-mosque'],
                                ];
                            @endphp

                            @foreach ($days as $dayEn => $dayInfo)
                                <div class="day-assignment" data-day="{{ $dayEn }}">
                                    <div class="day-title">
                                        <i class="fas {{ $dayInfo['icon'] }}"></i>
                                        {{ $dayInfo['name'] }}
                                        <span class="client-count-badge" id="count-{{ $dayEn }}">0 عميل</span>

                                        <div class="day-action-buttons">
                                            <button class="btn btn-sm btn-outline-primary btn-day-action add-all-btn"
                                                data-day="{{ $dayEn }}" title="إضافة كل العملاء المتاحين">
                                                <i class="fas fa-plus-circle"></i> الكل
                                            </button>
                                            <button class="btn btn-sm btn-outline-success btn-day-action add-5-btn"
                                                data-day="{{ $dayEn }}" title="إضافة أول 5 عملاء">
                                                <i class="fas fa-forward"></i> 5
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger btn-day-action clear-day-btn"
                                                data-day="{{ $dayEn }}" title="مسح اليوم">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="client-select-wrapper">
                                        <select class="client-select day-client-select select2"
                                            data-day="{{ $dayEn }}" disabled>
                                            <option value="">-- اختر عميل لإضافته --</option>
                                        </select>
                                    </div>

                                    <div class="selected-clients-list" id="clients-{{ $dayEn }}">
                                        <div class="empty-day-message">
                                            <i class="fas fa-calendar-plus text-muted"></i>
                                            لم يتم تعيين عملاء لهذا اليوم بعد
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
            let currentYear = {{ date('Y') }};
            let currentWeek = getCurrentWeek();
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

            const employeeSelect = $('#employee-select');
            const yearSelect = $('#year-select');
            const weekSelect = $('#week-select');
            const groupSelect = $('#group-select');
            const availableClientsList = $('#available-clients-list');
            const spinner = $('#available-clients-container .loading-spinner');

            initializeWeekSelect();
            updateWeekInfo();
            initializeDragAndDrop();

            // Event Listeners
            employeeSelect.on('change', handleEmployeeChange);
            yearSelect.on('change', handleYearChange);
            weekSelect.on('change', handleWeekChange);
            groupSelect.on('change', handleGroupChange);
            $(document).on('change', '.day-client-select', handleClientSelection);
            $(document).on('click', '.remove-client-btn', handleRemoveClient);
            $(document).on('click', '.add-all-btn', handleAddAllClients);
            $(document).on('click', '.add-5-btn', handleAdd5Clients);
            $(document).on('click', '.clear-day-btn', handleClearDay);
            $('#save-itinerary').on('click', saveItinerary);
            $('#auto-distribute').on('click', handleAutoDistribute);
            $('#clear-all').on('click', handleClearAll);
            $('#client-search').on('keyup', handleClientSearch);

            function handleEmployeeChange() {
                const employeeId = $(this).val();
                resetUI();
                if (employeeId) {
                    fetchGroupsForEmployee(employeeId);
                    loadItineraryForWeek();
                }
            }

            function handleYearChange() {
                currentYear = $(this).val();
                updateWeekInfo();
                loadItineraryForWeek();
            }

            function handleWeekChange() {
                currentWeek = $(this).val();
                updateWeekInfo();
                loadItineraryForWeek();
            }

            function handleGroupChange() {
                const groupId = $(this).val();
                if (groupId) {
                    fetchClientsForGroup(groupId);
                } else {
                    availableClientsList.html(
                        '<p class="text-center text-muted">الرجاء اختيار مجموعة لعرض العملاء.</p>');
                    $('.day-client-select').prop('disabled', true);
                }
            }

            function handleClientSelection() {
                const day = $(this).data('day');
                const clientId = $(this).val();
                if (clientId) {
                    const client = availableClients.find(c => c.id == clientId);
                    if (client && !dayAssignments[day].find(c => c.id == clientId)) {
                        addClientToDay(day, client);
                        $(this).val('');
                    }
                }
            }

            function handleRemoveClient() {
                const day = $(this).data('day');
                const clientId = $(this).data('client-id');
                removeClientFromDay(day, clientId);
            }

            function handleAddAllClients() {
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
            }

            function handleAdd5Clients() {
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
            }

            function handleClearDay() {
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
            }

            function handleAutoDistribute() {
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

            function handleClearAll() {
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

            function loadItineraryForWeek() {
                const employeeId = employeeSelect.val();
                if (!employeeId) return;

                resetDayAssignments();

                $.ajax({
                    url: `/api/employees/${employeeId}/itinerary`,
                    method: 'GET',
                    data: {
                        year: currentYear,
                        week: currentWeek
                    },
                    success: function(itinerary) {
                        if (itinerary?.length > 0) {
                            itinerary.forEach(visit => {
                                const day = visit.day_of_week;
                                if (visit.client && dayAssignments[day]) {
                                    if (!dayAssignments[day].find(c => c.id == visit.client.id)) {
                                        dayAssignments[day].push(visit.client);
                                    }
                                }
                            });
                        }
                        updateAllDayDisplays();
                    },
                    error: function(xhr) {
                        console.error('خطأ في جلب البيانات:', xhr.responseJSON);
                        updateAllDayDisplays();
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
                        availableClients = mergeClients(clients, dayAssignments);
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
                        showError('فشل في جلب العملاء', xhr.responseJSON?.message);
                    }
                });
            }

            function saveItinerary() {
                const employeeId = employeeSelect.val();
                if (!employeeId) {
                    showAlert('error', 'خطأ', 'الرجاء اختيار مندوب أولاً');
                    return;
                }

                const visits = {};
                Object.keys(dayAssignments).forEach(day => {
                    visits[day] = dayAssignments[day]
                        .filter(client => client && client.id)
                        .map(client => client.id);
                });

                Swal.fire({
                    title: 'تأكيد الحفظ',
                    text: 'هل أنت متأكد من حفظ خط السير؟',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، احفظ',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        executeSave(employeeId, visits);
                    }
                });
            }

            function executeSave(employeeId, visits) {
                const saveBtn = $('#save-itinerary');
                const originalText = saveBtn.html();
                saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...');

                $.ajax({
                    url: '{{ route("itinerary.store") }}',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        employee_id: employeeId,
                        year: currentYear,
                        week_number: currentWeek,
                        visits: visits,
                        _token: '{{ csrf_token() }}'
                    }),
                    success: function(response) {
                        saveBtn.prop('disabled', false).html(originalText);
                        if (response.success) {
                            showAlert('success', 'تم الحفظ', response.message);
                        } else {
                            showAlert('error', 'خطأ', response.message);
                        }
                    },
                    error: function(xhr) {
                        saveBtn.prop('disabled', false).html(originalText);
                        const errorMsg = xhr.responseJSON?.message || 'فشل في الاتصال بالخادم';
                        showAlert('error', 'خطأ في الحفظ', errorMsg);
                        console.error('تفاصيل الخطأ:', xhr.responseJSON);
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
                countBadge.text(`${dayAssignments[day].length} عميل`);

                if (dayAssignments[day].length === 0) {
                    container.html(`
                        <div class="empty-day-message">
                            <i class="fas fa-calendar-plus text-muted"></i>
                            لم يتم تعيين عملاء لهذا اليوم بعد
                        </div>
                    `);
                } else {
                    dayAssignments[day].forEach(client => {
                        container.append(createSelectedClientCard(client, day));
                    });
                }

                $('[data-toggle="tooltip"]').tooltip();
            }

            function updateAllDayDisplays() {
                Object.keys(dayAssignments).forEach(day => {
                    updateDayDisplay(day);
                });
            }

            function resetDayAssignments() {
                Object.keys(dayAssignments).forEach(day => {
                    dayAssignments[day] = [];
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

                    $(this).html(options).prop('disabled', availableForDay.length === 0);
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
                    availableClientsList.append(createAvailableClientCard(client));
                });

                $('[data-toggle="tooltip"]').tooltip();
            }

            function mergeClients(newClients, assignments) {
                const assignedClients = [];
                Object.values(assignments).forEach(dayClients => {
                    dayClients.forEach(client => {
                        if (!assignedClients.find(c => c.id === client.id)) {
                            assignedClients.push(client);
                        }
                    });
                });

                return [...newClients, ...assignedClients].filter((client, index, self) =>
                    index === self.findIndex((c) => c.id === client.id)
                );
            }

            function handleClientSearch(e) {
                const term = $(this).val().toLowerCase();
                $('.available-client-card').each(function() {
                    const $card = $(this);
                    const name = $card.find('.client-name').text().toLowerCase();
                    const code = $card.find('.client-meta').text().toLowerCase();
                    $card.toggle(name.includes(term) || code.includes(term));
                });
            }

            function resetUI() {
                groupSelect.prop('disabled', true).html('<option value="">-- اختر مجموعة --</option>');
                availableClientsList.html(
                    '<p class="text-center text-muted mt-4">اختر مندوب ومجموعة لعرض العملاء.</p>');
                $('.day-client-select').prop('disabled', true).html(
                    '<option value="">-- اختر عميل لإضافته --</option>');
                resetDayAssignments();
                updateAllDayDisplays();
            }

            function showAlert(icon, title, text, timer = null) {
                const options = {
                    icon: icon,
                    title: title,
                    text: text,
                    confirmButtonText: 'حسناً',
                    reverseButtons: true
                };

                if (timer) {
                    options.timer = timer;
                    options.showConfirmButton = false;
                }

                Swal.fire(options);
            }

            function showError(title, message) {
                Swal.fire({
                    icon: 'error',
                    title: title,
                    text: message || 'حدث خطأ غير متوقع',
                    confirmButtonText: 'حسناً'
                });
            }

            function createSelectedClientCard(client, day) {
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
                if (data?.length > 0) {
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
                }
                return `<i class="fas ${iconClass} text-muted" data-toggle="tooltip" title="لا يوجد ${type}ات"></i>`;
            }

            function getCurrentWeek() {
                const now = new Date();
                const startOfYear = new Date(now.getFullYear(), 0, 1);
                const pastDaysOfYear = (now - startOfYear) / 86400000;
                return Math.ceil((pastDaysOfYear + startOfYear.getDay() + 1) / 7);
            }

            function initializeWeekSelect() {
                const currentWeekNumber = getCurrentWeek();
                weekSelect.val(currentWeekNumber);
                currentWeek = currentWeekNumber;
            }

            function updateWeekInfo() {
                $('#week-info').text(`العام: ${currentYear}, الأسبوع: ${currentWeek}`);
            }

            function fetchGroupsForEmployee(employeeId) {
                $.ajax({
                    url: `/api/employees/${employeeId}/groups`,
                    method: 'GET',
                    success: function(groups) {
                        let options = '<option value="">-- اختر مجموعة --</option>';
                        groups.forEach(group => options +=
                            `<option value="${group.id}">${group.name}</option>`);
                        groupSelect.html(options).prop('disabled', false);
                    },
                    error: function() {
                        showError('خطأ', 'فشل في جلب مجموعات الموظف');
                    }
                });
            }

            function getDayNameAr(day) {
                const days = {
                    saturday: 'السبت',
                    sunday: 'الأحد',
                    monday: 'الاثنين',
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