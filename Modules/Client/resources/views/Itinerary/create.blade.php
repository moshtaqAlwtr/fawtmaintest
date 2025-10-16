@extends('master')

@section('title', 'تخطيط خط السير للمناديب')

@section('styles')
<style>
    :root {
        --primary: #0056b3;
        --primary-light: #e8f1fd;
        --primary-hover: #004494;
        --primary-gradient: linear-gradient(135deg, #0056b3, #003b7a);
        --primary-shadow: rgba(0, 86, 179, 0.2);
    }

    body {
        background-color: #f8f9fc;
        font-family: 'Cairo', sans-serif;
    }

    .itinerary-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 15px;
    }

    .itinerary-header h2 {
        color: var(--primary);
        font-weight: 700;
        margin-bottom: 0;
    }

    .itinerary-actions {
        display: flex;
        gap: 10px;
    }

    .main-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px var(--primary-shadow);
    }

    .card-header {
        background: var(--primary-gradient);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem 1.25rem;
    }

    .card-header i {
        margin-left: 10px;
    }

    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        font-weight: 600;
    }

    .btn-primary:hover, .btn-primary:focus {
        background: linear-gradient(135deg, #004494, #00306a);
        box-shadow: 0 4px 10px var(--primary-shadow);
        transform: translateY(-2px);
    }

    .btn-info {
        background: linear-gradient(135deg, #17a2b8, #138496);
        border: none;
        font-weight: 600;
    }

    .btn-warning {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        border: none;
        font-weight: 600;
        color: #212529;
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        border: none;
        font-weight: 600;
    }

    .form-control {
        border-radius: 8px;
        padding: 0.6rem 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem var(--primary-shadow);
    }

    /* تخطيط أيام الأسبوع */
    .day-assignment {
        background-color: white;
        border-radius: 12px;
        margin-bottom: 1.2rem;
        padding: 1.2rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
        transition: all 0.3s;
        border-right: 4px solid var(--primary);
    }

    .day-assignment:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px var(--primary-shadow);
    }

    .day-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 10px;
    }

    .day-name {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
    }

    .client-count-badge {
        background: var(--primary-gradient);
        color: white;
        font-size: 0.8rem;
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
        font-weight: 600;
        margin-right: 10px;
    }

    .day-action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-day-action {
        padding: 0.3rem 0.6rem;
        font-size: 0.8rem;
        border-radius: 6px;
    }

    .selected-clients-list {
        background-color: #f8f9fc;
        border-radius: 10px;
        padding: 1rem;
        min-height: 120px;
        max-height: 300px;
        overflow-y: auto;
    }

    .selected-client-card {
        background-color: white;
        border-radius: 8px;
        padding: 0.8rem 1rem;
        margin-bottom: 0.8rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
        border-right: 3px solid var(--primary);
        animation: slideIn 0.3s ease;
    }

    .selected-client-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px var(--primary-shadow);
    }

    .selected-client-info .client-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }

    .selected-client-info .client-meta {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .activity-icons {
        display: flex;
        gap: 10px;
        margin-top: 5px;
    }

    .activity-icons i {
        font-size: 0.9rem;
    }

    .activity-icons .text-success {
        color: var(--primary) !important;
    }

    .remove-client-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary);
        color: white;
        border: none;
        transition: all 0.3s;
    }

    .remove-client-btn:hover {
        transform: scale(1.1);
        background: #dc3545;
    }

    .empty-day-message {
        text-align: center;
        color: #6c757d;
        padding: 2rem;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
    }

    /* قائمة العملاء المتاحين */
    .available-clients-list {
        max-height: 60vh;
        overflow-y: auto;
        background-color: #f8f9fc;
        border-radius: 10px;
        padding: 1rem;
    }

    .available-client-card {
        background-color: white;
        border-radius: 8px;
        padding: 0.8rem 1rem;
        margin-bottom: 0.8rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
        cursor: grab;
        border-right: 3px solid var(--primary);
        transition: all 0.3s;
    }

    .available-client-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px var(--primary-shadow);
    }

    .available-client-card.client-assigned {
        background-color: var(--primary-light);
        opacity: 0.8;
    }

    /* حالة السحب والإفلات */
    .day-assignment.drop-zone-active {
        background-color: var(--primary-light);
        border-right-width: 6px;
    }

    /* قابلية السحب */
    .sortable-ghost {
        opacity: 0.4;
    }

    .sortable-chosen {
        background-color: var(--primary-light);
    }

    .sortable-drag {
        cursor: grabbing;
    }

    /* تنسيقات للجوال */
    @media (max-width: 768px) {
        .itinerary-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .itinerary-actions {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .btn {
            padding: 0.5rem 0.8rem;
            font-size: 0.9rem;
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="main-card card">
        <div class="card-body">
            <!-- رأس الصفحة -->
            <div class="itinerary-header">
                <div>
                    <h2><i class="fas fa-route"></i> تخطيط خط السير الأسبوعي</h2>
                    <h6 id="week-info" class="text-muted mt-2"></h6>
                </div>
                <div class="itinerary-actions">
                    <button id="auto-distribute" class="btn btn-info">
                        <i class="fas fa-magic"></i> توزيع تلقائي
                    </button>
                    <button id="clear-all" class="btn btn-warning">
                        <i class="fas fa-eraser"></i> مسح الكل
                    </button>
                    <button id="save-itinerary" class="btn btn-success">
                        <i class="fas fa-save"></i> حفظ خط السير
                    </button>
                </div>
            </div>

            <div class="row">
                <!-- الإعدادات وقائمة العملاء -->
                <div class="col-lg-4">
                    <!-- بطاقة الإعدادات والفلاتر -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-cogs"></i> الإعدادات والفلاتر
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="employee-select" class="font-weight-bold">اختر المندوب</label>
                                <select id="employee-select" class="form-control select2"
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
                                    <select id="year-select" class="form-control">
                                        @for ($year = date('Y') - 2; $year <= date('Y') + 2; $year++)
                                            <option value="{{ $year }}"
                                                {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="week-select" class="font-weight-bold">الأسبوع</label>
                                    <select id="week-select" class="form-control select2">
                                        @for ($i = 1; $i <= 52; $i++)
                                            <option value="{{ $i }}">الأسبوع {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="group-select" class="font-weight-bold">اختر مجموعة العملاء</label>
                                <select id="group-select" class="form-control select2"
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

                    <!-- بطاقة العملاء المتاحين -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-users"></i> العملاء المتاحين
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-left-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="client-search" class="form-control border-right-0"
                                        placeholder="ابحث عن عميل بالاسم أو الكود...">
                                </div>
                            </div>

                            <div id="available-clients-container" style="position: relative;">
                                <div class="loading-spinner spinner-border text-primary" role="status"
                                    style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                    <span class="sr-only">جاري التحميل...</span>
                                </div>

                                <div id="available-clients-list" class="available-clients-list">
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-users fa-2x mb-3 d-block"></i>
                                        الرجاء اختيار مندوب ومجموعة لعرض العملاء
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تخطيط أيام الأسبوع -->
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
                                    <div class="day-name">
                                        <i class="fas {{ $dayInfo['icon'] }}"></i>
                                        {{ $dayInfo['name'] }}
                                        <span class="client-count-badge" id="count-{{ $dayEn }}">0 عميل</span>
                                    </div>

                                    <div class="day-action-buttons">
                                        <button class="btn btn-sm btn-primary btn-day-action add-all-btn"
                                            data-day="{{ $dayEn }}" title="إضافة كل العملاء المتاحين">
                                            <i class="fas fa-plus-circle"></i> الكل
                                        </button>
                                        <button class="btn btn-sm btn-info btn-day-action add-5-btn"
                                            data-day="{{ $dayEn }}" title="إضافة أول 5 عملاء">
                                            <i class="fas fa-forward"></i> 5
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-day-action clear-day-btn"
                                            data-day="{{ $dayEn }}" title="مسح اليوم">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="client-select-wrapper mt-2 mb-3">
                                    <select class="form-control client-select day-client-select select2"
                                        data-day="{{ $dayEn }}" disabled>
                                        <option value="">-- اختر عميل لإضافته --</option>
                                    </select>
                                </div>

                                <div class="selected-clients-list" id="clients-{{ $dayEn }}">
                                    <div class="empty-day-message">
                                        <i class="fas fa-calendar-plus text-muted mb-3 fa-2x d-block"></i>
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

        // تهيئة الصفحة
        initializeWeekSelect();
        updateWeekInfo();
        initializeDragAndDrop();
        initializeSortable();

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
                availableClientsList.html(`
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-users fa-2x mb-3 d-block"></i>
                        الرجاء اختيار مجموعة لعرض العملاء
                    </div>
                `);
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
                    $(this).val('').trigger('change.select2');
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
                showAlert('info', 'تنبيه', 'لا يوجد عملاء متاحين للإضافة');
                return;
            }

            showConfirm(
                'تأكيد الإضافة',
                `هل تريد إضافة ${availableForDay.length} عميل لـ ${getDayNameAr(day)}؟`,
                'نعم، أضف الكل'
            ).then((result) => {
                if (result.isConfirmed) {
                    availableForDay.forEach(client => addClientToDay(day, client));
                    showAlert('success', 'تم!', `تمت إضافة ${availableForDay.length} عميل بنجاح`);
                }
            });
        }

        function handleAdd5Clients() {
            const day = $(this).data('day');
            const availableForDay = availableClients.filter(client =>
                !dayAssignments[day].find(assigned => assigned.id == client.id)
            ).slice(0, 5);

            if (availableForDay.length === 0) {
                showAlert('info', 'تنبيه', 'لا يوجد عملاء متاحين للإضافة');
                return;
            }

            availableForDay.forEach(client => addClientToDay(day, client));
            showAlert('success', 'تم!', `تمت إضافة ${availableForDay.length} عميل`);
        }

        function handleClearDay() {
            const day = $(this).data('day');
            if (dayAssignments[day].length === 0) {
                showAlert('info', 'تنبيه', 'لا يوجد عملاء في هذا اليوم');
                return;
            }

            showConfirm(
                'تأكيد المسح',
                `هل تريد مسح ${dayAssignments[day].length} عميل من ${getDayNameAr(day)}؟`,
                'نعم، امسح',
                'danger'
            ).then((result) => {
                if (result.isConfirmed) {
                    dayAssignments[day] = [];
                    updateDayDisplay(day);
                    updateDayClientSelects();
                    updateAvailableClientsList();
                    showAlert('success', 'تم!', 'تم مسح العملاء بنجاح');
                }
            });
        }

        function handleAutoDistribute() {
            if (availableClients.length === 0) {
                showAlert('warning', 'تنبيه', 'لا يوجد عملاء متاحين للتوزيع');
                return;
            }

            Swal.fire({
                title: 'التوزيع التلقائي',
                html: `
                    <div class="text-right">
                        <p>سيتم توزيع ${availableClients.length} عميل على أيام الأسبوع</p>
                        <div class="form-group mt-3">
                            <label>استثناء يوم:</label>
                            <select id="exclude-day" class="form-control">
                                <option value="">لا يوجد</option>
                                <option value="friday">الجمعة (إجازة)</option>
                                <option value="saturday">السبت</option>
                            </select>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'ابدأ التوزيع',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#0056b3',
                reverseButtons: true,
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
            showAlert('success', 'تم!', 'تم توزيع العملاء بنجاح على أيام الأسبوع');
        }

        function handleClearAll() {
            const totalClients = Object.values(dayAssignments).reduce((sum, day) => sum + day.length, 0);
            if (totalClients === 0) {
                showAlert('info', 'تنبيه', 'لا يوجد عملاء لمسحهم');
                return;
            }

            showConfirm(
                'تأكيد مسح الكل',
                `هل تريد مسح جميع العملاء (${totalClients} عميل) من كل الأيام؟`,
                'نعم، امسح الكل',
                'danger'
            ).then((result) => {
                if (result.isConfirmed) {
                    Object.keys(dayAssignments).forEach(day => {
                        dayAssignments[day] = [];
                    });
                    updateAllDayDisplays();
                    updateDayClientSelects();
                    updateAvailableClientsList();
                    showAlert('success', 'تم!', 'تم مسح جميع العملاء بنجاح');
                }
            });
        }

        function initializeSortable() {
            // جعل قوائم العملاء المحددين قابلة للسحب والترتيب
            Object.keys(dayAssignments).forEach(day => {
                const el = document.getElementById(`clients-${day}`);
                new Sortable(el, {
                    group: 'clients',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    handle: '.selected-client-card',
                    onEnd: function(evt) {
                        // تحديث مصفوفة العملاء بناءً على الترتيب الجديد
                        const dayFrom = evt.from.id.replace('clients-', '');
                        const dayTo = evt.to.id.replace('clients-', '');

                        // إذا تم السحب إلى يوم مختلف
                        if (dayFrom !== dayTo) {
                            const clientId = evt.item.dataset.clientId;
                            const client = dayAssignments[dayFrom].find(c => c.id == clientId);

                            // إزالة العميل من اليوم المصدر
                            removeClientFromDay(dayFrom, clientId, false);

                            // إضافة العميل إلى اليوم الهدف في الموضع الجديد
                            if (!dayAssignments[dayTo].find(c => c.id == clientId)) {
                                dayAssignments[dayTo].splice(evt.newIndex, 0, client);
                            }

                            // تحديث عرض الأيام
                            updateDayDisplay(dayFrom);
                            updateDayDisplay(dayTo);
                            updateDayClientSelects();
                            updateAvailableClientsList();
                        } else {
                            // إعادة ترتيب العملاء في نفس اليوم
                            const newOrder = Array.from(evt.to.children)
                                .filter(el => el.classList.contains('selected-client-card'))
                                .map(el => parseInt(el.dataset.clientId));

                            if (newOrder.length) {
                                // إعادة ترتيب المصفوفة
                                const tempClients = [...dayAssignments[dayTo]];
                                dayAssignments[dayTo] = [];

                                newOrder.forEach(clientId => {
                                    const client = tempClients.find(c => c.id == clientId);
                                    if (client) {
                                        dayAssignments[dayTo].push(client);
                                    }
                                });
                            }
                        }
                    }
                });
            });
        }

        function initializeDragAndDrop() {
            let draggedClient = null;

            // تهيئة السحب والإفلات للعملاء المتاحين
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

            // إظهار تحميل
            showLoading(true);

            $.ajax({
                url: `/api/employees/${employeeId}/itinerary`,
                method: 'GET',
                data: {
                    year: currentYear,
                    week: currentWeek
                },
                success: function(itinerary) {
                    showLoading(false);
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
                    showLoading(false);
                    console.error('خطأ في جلب البيانات:', xhr.responseJSON);
                    showError('خطأ', 'فشل في تحميل خط السير');
                    updateAllDayDisplays();
                }
            });
        }

        function fetchClientsForGroup(groupId) {
            showLoading(true);
            $('.day-client-select').prop('disabled', true);

            $.ajax({
                url: `/api/groups/${groupId}/clients`,
                method: 'GET',
                success: function(clients) {
                    showLoading(false);
                    availableClients = mergeClients(clients, dayAssignments);
                    updateAvailableClientsList();
                    updateDayClientSelects();

                    if (availableClients.length > 0) {
                        $('.day-client-select').prop('disabled', false);
                    } else {
                        availableClientsList.html(`
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-exclamation-circle fa-2x mb-3 d-block"></i>
                                لا يوجد عملاء متاحين في هذه المجموعة
                            </div>
                        `);
                    }
                },
                error: function(xhr) {
                    showLoading(false);
                    showError('خطأ', xhr.responseJSON?.message || 'فشل في جلب العملاء');
                }
            });
        }

        function saveItinerary() {
            const employeeId = employeeSelect.val();
            if (!employeeId) {
                showAlert('error', 'خطأ', 'الرجاء اختيار مندوب أولاً');
                return;
            }

            // تحضير البيانات للإرسال
            const visits = {};
            Object.keys(dayAssignments).forEach(day => {
                visits[day] = dayAssignments[day]
                    .filter(client => client && client.id)
                    .map(client => client.id);
            });

            showConfirm(
                'تأكيد الحفظ',
                'هل أنت متأكد من حفظ خط السير؟',
                'نعم، احفظ'
            ).then((result) => {
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

        function removeClientFromDay(day, clientId, updateUI = true) {
            dayAssignments[day] = dayAssignments[day].filter(c => c.id != clientId);
            if (updateUI) {
                updateDayDisplay(day);
                updateDayClientSelects();
                updateAvailableClientsList();
            }
        }

        function updateDayDisplay(day) {
            const container = $(`#clients-${day}`);
            const countBadge = $(`#count-${day}`);

            container.empty();
            countBadge.text(`${dayAssignments[day].length} عميل`);

            if (dayAssignments[day].length === 0) {
                container.html(`
                    <div class="empty-day-message">
                        <i class="fas fa-calendar-plus text-muted mb-3 fa-2x d-block"></i>
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
                    options += `<option value="${client.id}">${client.trade_name} - ${client.code}</option>`;
                });

                $(this).html(options).prop('disabled', availableForDay.length === 0);
            });

            // تحديث Select2 بعد تغيير الخيارات
            $('.select2').select2();
        }