@extends('master')

@section('content')
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Test Notification System</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="test-notification-section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Send Test Notifications</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Send Notification to All Employees</h5>
                                        </div>
                                        <div class="card-body">
                                            <button id="sendTestNotification" class="btn btn-primary">Send Test Notification</button>
                                            <div id="testNotificationResult" class="mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Send Notification to Specific Employee</h5>
                                        </div>
                                        <div class="card-body">
                                            <form id="sendToEmployeeForm">
                                                <div class="form-group">
                                                    <label for="employee_id">Select Employee</label>
                                                    <select class="form-control" id="employee_id" name="employee_id" required>
                                                        <option value="">Select an employee</option>
                                                        @foreach($employees as $employee)
                                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <input type="text" class="form-control" id="title" name="title" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="message">Message</label>
                                                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-success">Send Notification</button>
                                            </form>
                                            <div id="employeeNotificationResult" class="mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Send test notification to all employees
    $('#sendTestNotification').click(function() {
        $.ajax({
            url: '{{ route("test.notification") }}',
            method: 'GET',
            success: function(response) {
                $('#testNotificationResult').html('<div class="alert alert-success">' + response.success + '</div>');
            },
            error: function(xhr) {
                $('#testNotificationResult').html('<div class="alert alert-danger">Error: ' + xhr.responseJSON.error + '</div>');
            }
        });
    });

    // Send notification to specific employee
    $('#sendToEmployeeForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("send.notification.to.employee") }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#employeeNotificationResult').html('<div class="alert alert-success">' + response.success + '</div>');
                $('#sendToEmployeeForm')[0].reset();
            },
            error: function(xhr) {
                $('#employeeNotificationResult').html('<div class="alert alert-danger">Error sending notification</div>');
            }
        });
    });
});
</script>
@endsection