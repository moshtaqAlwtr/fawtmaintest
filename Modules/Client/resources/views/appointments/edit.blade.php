@extends('master')

@section('title')
    تعديل موعد
@stop

@section('head')
    <!-- تضمين ملفات Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">تعديل موعد</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('sales.department.dashboard')}}">الرئيسيه</a>
                            </li>
                            <li class="breadcrumb-item active">عرض
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('appointments.update', $appointment->id) }}" method="POST" id="appointment-form">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- كارد واحد يحتوي على جميع العناصر -->
                    <div class="card">
                        <div class="card-body">
                            <!-- أزرار الحفظ والإلغاء -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 pb-3 border-bottom">
                                <div>
                                    <label>الحقول التي عليها علامة <span style="color: red">*</span> الزامية</label>
                                </div>
                                <div>
                                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-danger">
                                        <i class="fa fa-ban"></i> الغاء
                                    </a>
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fa fa-save"></i> حفظ
                                    </button>
                                </div>
                            </div>

                            <!-- معلومات الموعد الأساسية -->
                            <div class="mb-4">
                                <h6 class="mb-3 text-primary"><i class="fas fa-calendar-alt"></i> معلومات الموعد</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="client_id">العميل <span class="text-danger">*</span></label>
                                            <select class="form-control select2" id="client_id" name="client_id" required>
                                                <option value="">اختر العميل</option>
                                                @if (@isset($clients) && !@empty($clients) && count($clients) > 0)
                                                    @foreach ($clients as $client)
                                                        <option value="{{ $client->id }}" {{ $appointment->client_id == $client->id ? 'selected' : '' }}>
                                                            {{ $client->trade_name }}-{{ $client->code ?? '' }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">لا يوجد عملاء متاحين حاليا</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date">التاريخ <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control datepicker" id="date" name="date"
                                                value="{{ $appointment->appointment_date }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="time">الوقت <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control timepicker" id="time" name="time"
                                                value="{{ $appointment->time }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="duration">المدة <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="duration" name="duration"
                                                value="{{ $appointment->duration ?? '00:00' }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- فاصل -->
                            <hr class="my-4">

                            <!-- حالة الموعد -->
                            <div class="mb-4">
                                <h6 class="mb-3 text-primary"><i class="fas fa-info-circle"></i> حالة الموعد</h6>
                                <div class="form-group">
                                    <label for="status">الحالة <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="status" name="status" required>
                                        <option value="1" {{ (int)$appointment->status === 1 || empty($appointment->status) ? 'selected' : '' }}>تم جدولته</option>
                                        <option value="2" {{ (int)$appointment->status === 2 ? 'selected' : '' }}>تم</option>
                                        <option value="3" {{ (int)$appointment->status === 3 ? 'selected' : '' }}>صرف النظر عنه</option>
                                        <option value="4" {{ (int)$appointment->status === 4 ? 'selected' : '' }}>تم جدولته مجددا</option>
                                    </select>
                                </div>
                            </div>

                            <!-- فاصل -->
                            <hr class="my-4">

                            <!-- نوع الإجراء -->
                            <div class="mb-4">
                                <h6 class="mb-3 text-primary"><i class="fas fa-tasks"></i> نوع الإجراء</h6>
                                <div class="form-group">
                                    <label for="action_type">نوع الإجراء <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="action_type" name="action_type" required>
                                        <option value="">-- اختر الإجراء --</option>
                                        <option value="1" {{ $appointment->action_type == 1 ? 'selected' : '' }}>متابعة</option>
                                        <option value="2" {{ $appointment->action_type == 2 ? 'selected' : '' }}>تدقيق</option>
                                        <option value="3" {{ $appointment->action_type == 3 ? 'selected' : '' }}>مراجعة</option>
                                        <option value="4" {{ $appointment->action_type == 4 ? 'selected' : '' }}>اجتماع</option>
                                        <option value="5" {{ $appointment->action_type == 5 ? 'selected' : '' }}>زيارة</option>
                                        <option value="6" {{ $appointment->action_type == 6 ? 'selected' : '' }}>ملاحظة</option>
                                    </select>
                                </div>
                            </div>

                            <!-- فاصل -->
                            <hr class="my-4">

                            <!-- الملاحظات -->
                            <div class="mb-4">
                                <h6 class="mb-3 text-primary"><i class="fas fa-sticky-note"></i> الملاحظات/الشروط</h6>
                                <textarea id="tinyMCE" name="notes" class="form-control" rows="4">{{ $appointment->notes }}</textarea>
                            </div>

                            <!-- فاصل -->
                            <hr class="my-4">

                            <!-- مشاركة مع العميل -->
                            <div class="mb-4">
                                <div class="vs-checkbox-con vs-checkbox-primary">
                                    <input type="checkbox" id="share_with_client" name="share_with_client" value="1" {{ $appointment->share_with_client ? 'checked' : '' }}>
                                    <span class="vs-checkbox">
                                        <span class="vs-checkbox--check">
                                            <i class="vs-icon feather icon-check"></i>
                                        </span>
                                    </span>
                                    <span><i class="fas fa-share-alt me-1"></i> مشاركة مع العميل</span>
                                </div>
                            </div>

                            <!-- فاصل -->
                            <hr class="my-4">

                            <!-- الموعد المتكرر -->
                            <div class="mb-4">
                                <div class="vs-checkbox-con vs-checkbox-primary">
                                    <input type="checkbox" id="recurring" name="is_recurring" value="1" onchange="toggleRecurringFields(this)" {{ $appointment->is_recurring ? 'checked' : '' }}>
                                    <span class="vs-checkbox">
                                        <span class="vs-checkbox--check">
                                            <i class="vs-icon feather icon-check"></i>
                                        </span>
                                    </span>
                                    <span><i class="fas fa-redo me-1"></i> موعد متكرر</span>
                                </div>

                                <div id="recurring-fields" class="mt-3" style="{{ $appointment->is_recurring ? '' : 'display: none;' }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="recurrence_type">نوع التكرار</label>
                                                <select class="form-control select2" id="recurrence_type" name="recurrence_type">
                                                    <option value="1" {{ $appointment->recurrence_type == 1 ? 'selected' : '' }}>أسبوعي</option>
                                                    <option value="2" {{ $appointment->recurrence_type == 2 ? 'selected' : '' }}>كل أسبوعين</option>
                                                    <option value="3" {{ $appointment->recurrence_type == 3 ? 'selected' : '' }}>شهري</option>
                                                    <option value="4" {{ $appointment->recurrence_type == 4 ? 'selected' : '' }}>كل شهرين</option>
                                                    <option value="5" {{ $appointment->recurrence_type == 5 ? 'selected' : '' }}>سنوي</option>
                                                    <option value="6" {{ $appointment->recurrence_type == 6 ? 'selected' : '' }}>كل سنتين</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="recurrence_date">تاريخ نهاية التكرار</label>
                                                <input type="date" class="form-control" id="recurrence_date" name="recurrence_date"
                                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ $appointment->recurrence_date }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- فاصل -->
                            <hr class="my-4">

                            <!-- تعيين موظف -->
                            <div class="mb-4">
                                <div class="vs-checkbox-con vs-checkbox-primary">
                                    <input type="checkbox" id="assign_employee" name="assign_staff" value="1" onchange="toggleStaffFields(this)" {{ isset($appointment->created_by) && !empty($appointment->created_by) ? 'checked' : '' }}>
                                    <span class="vs-checkbox">
                                        <span class="vs-checkbox--check">
                                            <i class="vs-icon feather icon-check"></i>
                                        </span>
                                    </span>
                                    <span><i class="fas fa-user-tie me-1"></i> تعيين موظف</span>
                                </div>

                                <div id="staff-fields" class="mt-3" style="{{ isset($appointment->created_by) && !empty($appointment->created_by) ? '' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label for="created_by">اختر الموظف</label>
                                        <select class="form-control select2" id="created_by" name="created_by">
                                            <option value="">اختر الموظف</option>
                                            @foreach ($employees as $user)
                                                <option value="{{ $user->id }}" {{ $appointment->created_by == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        // دالة إظهار/إخفاء حقول التكرار
        function toggleRecurringFields(checkbox) {
            const recurringFields = document.getElementById('recurring-fields');
            if (checkbox.checked) {
                recurringFields.style.display = 'block';
            } else {
                recurringFields.style.display = 'none';
            }
        }

        // دالة إظهار/إخفاء حقول الموظفين
        function toggleStaffFields(checkbox) {
            const staffFields = document.getElementById('staff-fields');
            if (checkbox.checked) {
                staffFields.style.display = 'block';
            } else {
                staffFields.style.display = 'none';
            }
        }
        
        // تهيئة Select2 للقوائم المنسدلة
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });
        });
    </script>
@endsection