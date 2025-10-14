@extends('master')

@section('content')
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">إرسال إشعار إلى الموظفين</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="send-notification-section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">إرسال إشعار جديد</h4>
                        </div>
                        <div class="card-body">
                            <form id="sendNotificationForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">عنوان الإشعار <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" name="title" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="message">نص الإشعار <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="message" name="description" rows="5" required></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>إرسال إلى <span class="text-danger">*</span></label>
                                            <div class="custom-control custom-radio mb-1">
                                                <input type="radio" id="sendToAll" name="sendTo" class="custom-control-input" value="all" checked>
                                                <label class="custom-control-label" for="sendToAll">جميع الموظفين</label>
                                            </div>
                                            <div class="custom-control custom-radio mb-1">
                                                <input type="radio" id="sendToSpecific" name="sendTo" class="custom-control-input" value="specific">
                                                <label class="custom-control-label" for="sendToSpecific">موظفين محددين</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12" id="employeesSelection" style="display: none;">
                                        <div class="form-group">
                                            <label for="employees">اختر الموظفين <span class="text-danger">*</span></label>
                                            <select class="form-control select2" id="employees" name="employees[]" multiple>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notification_type">نوع الإشعار</label>
                                            <select class="form-control" id="notification_type" name="notification_type">
                                                <option value="general">عام</option>
                                                <option value="collection">تحصيل</option>
                                                <option value="visit">زيارة عميل</option>
                                                <option value="task">مهمة</option>
                                                <option value="reminder">تذكير</option>
                                                <option value="urgent">عاجل</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" id="sendNotificationBtn">
                                            <i class="feather icon-send"></i> إرسال الإشعار
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container {
    width: 100% !important;
}

.select2-container .select2-selection--multiple {
    min-height: 45px;
    padding: 5px;
    font-size: 14px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    font-size: 14px;
    padding: 5px 10px;
    margin: 3px;
}

.select2-dropdown {
    font-size: 14px;
    width: 100% !important;
}

.select2-container--default .select2-results__option {
    padding: 8px 12px;
}

.select2-container--open .select2-dropdown {
    width: 100% !important;
    min-width: 100%;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Initialize Select2
    $('.select2').select2({
        placeholder: "اختر الموظفين",
        allowClear: true,
        width: '100%',
        dir: 'rtl'
    });

    // Toggle employee selection based on radio buttons
    $('input[name="sendTo"]').change(function() {
        if ($(this).val() === 'specific') {
            $('#employeesSelection').show();
        } else {
            $('#employeesSelection').hide();
        }
    });

    // Handle form submission
    $('#sendNotificationForm').on('submit', function(e) {
        e.preventDefault();

        const title = $('#title').val().trim();
        const description = $('#message').val().trim();
        const sendTo = $('input[name="sendTo"]:checked').val();
        const employees = $('#employees').val();
        const notificationType = $('#notification_type').val();

        // Validation
        if (!title) {
            toastr.error('يرجى إدخال عنوان الإشعار');
            return;
        }

        if (!description) {
            toastr.error('يرجى إدخال نص الإشعار');
            return;
        }

        if (sendTo === 'specific' && (!employees || employees.length === 0)) {
            toastr.error('يرجى اختيار موظف واحد على الأقل');
            return;
        }

        // Disable submit button
        const submitBtn = $('#sendNotificationBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="feather icon-loader"></i> جاري الإرسال...');

        // Prepare data
        const data = {
            title: title,
            description: description,
            send_to: sendTo,
            notification_type: notificationType,
            _token: csrfToken
        };

        if (sendTo === 'specific') {
            data.employees = employees;
        }

        // Send AJAX request
        $.ajax({
            url: '/notifications/send',
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#sendNotificationForm')[0].reset();
                    $('.select2').val(null).trigger('change');
                    $('#employeesSelection').hide();
                    $('input[name="sendTo"][value="all"]').prop('checked', true);
                } else {
                    toastr.error(response.message || 'حدث خطأ أثناء إرسال الإشعار');
                }
            },
            error: function(xhr) {
                console.log('Error sending notification:', xhr);
                let errorMessage = 'حدث خطأ أثناء إرسال الإشعار';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                // Re-enable submit button
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
@endsection
