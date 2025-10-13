<?php

use App\Http\Controllers\Manufacturing\SettingsController;
use App\Http\Controllers\Task\TaskController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\HR\Http\Controllers\Attendance\AttendanceDaysController;
use Modules\HR\Http\Controllers\Attendance\AttendanceRecordsController;
use Modules\HR\Http\Controllers\Attendance\AttendanceSessionsRecordController;
use Modules\HR\Http\Controllers\Attendance\AttendanceSheetsController;
use Modules\HR\Http\Controllers\Attendance\BarcodeAttendanceController;
use Modules\HR\Http\Controllers\Attendance\CustomShiftsController;
use Modules\HR\Http\Controllers\Attendance\LeavePermissionsController;
use Modules\HR\Http\Controllers\Attendance\LeaveRequestsController;
use Modules\HR\Http\Controllers\Attendance\Settings\AttendanceDeterminantsController;
use Modules\HR\Http\Controllers\Attendance\Settings\AttendanceRuleController;
use Modules\HR\Http\Controllers\Attendance\Settings\BasicController;
use Modules\HR\Http\Controllers\Attendance\Settings\EmployeeLeaveBalanceController;
use Modules\HR\Http\Controllers\Attendance\Settings\FlagsController;
use Modules\HR\Http\Controllers\Attendance\Settings\HolidayController;
use Modules\HR\Http\Controllers\Attendance\Settings\LeavePoliciesController;
use Modules\HR\Http\Controllers\Attendance\Settings\LeaveTypesController;
use Modules\HR\Http\Controllers\Attendance\Settings\MachinesController;
use Modules\HR\Http\Controllers\Attendance\Settings\PrintableTemplatesController;
use Modules\HR\Http\Controllers\Attendance\SettingsController as AttendanceSettingsController;
use Modules\HR\Http\Controllers\CommissionController;
use Modules\HR\Http\Controllers\EmployeeController;

use Modules\HR\Http\Controllers\ManagingEmployeeRolesController;
use Modules\HR\Http\Controllers\OrganizationalStructure\DepartmentController;
use Modules\HR\Http\Controllers\OrganizationalStructure\JobTitleController;
use Modules\HR\Http\Controllers\OrganizationalStructure\ManagingFunctionalLevelsController;
use Modules\HR\Http\Controllers\OrganizationalStructure\ManagingJobTypesController;
use Modules\HR\Http\Controllers\Salaries\AncestorController;
use Modules\HR\Http\Controllers\Salaries\ContractsController;
use Modules\HR\Http\Controllers\Salaries\PayrollProcessController;
use Modules\HR\Http\Controllers\Salaries\RelatedModelsController;
use Modules\HR\Http\Controllers\Salaries\SalaryItemsController;
use Modules\HR\Http\Controllers\Salaries\SalarySittingController;
use Modules\HR\Http\Controllers\Salaries\SalarySlipController;
use Modules\HR\Http\Controllers\Salaries\SalaryTemplatesController;
use Modules\HR\Http\Controllers\ShiftManagementController;
use Modules\HR\Http\Controllers\TargetSales\CommissionRulesController;
use Modules\HR\Http\Controllers\TargetSales\SalesCommissionController;
use Modules\HR\Http\Controllers\TargetSales\SalesPeriodsController;
use Modules\HR\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    Route::group(
        [
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'check.branch'],
        ],
        function () {
            Route::prefix('hr')
                ->middleware(['auth'])
                ->group(function () {
                    # employee routes
                    Route::prefix('employee')->group(function () {
                        Route::get('/index', [EmployeeController::class, 'index'])->name('employee.index');
                        Route::get('/create', [EmployeeController::class, 'create'])->name('employee.create');
                        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
                        Route::get('/show/{id}', [EmployeeController::class, 'show'])->name('employee.show');
                        Route::get('/updateStatus/{id}', [EmployeeController::class, 'updateStatus'])->name('employee.updateStatus');
                        Route::post('/store', [EmployeeController::class, 'store'])->name('employee.store');
                        Route::post('/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
                        Route::post('/updatePassword/{id}', [EmployeeController::class, 'updatePassword'])->name('employee.updatePassword');
                        Route::get('/delete/{id}', [EmployeeController::class, 'delete'])->name('employee.delete');
                        Route::get('/login/to/{id}', [EmployeeController::class, 'login_to'])->name('employee.login_to');
                        Route::get('/export/view', [EmployeeController::class, 'export_view'])->name('employee.export_view');
                        Route::post('/export', [EmployeeController::class, 'export'])->name('employee.export');

                        Route::get('/send_email/{id}', [EmployeeController::class, 'send_email'])->name('employee.send_email');
                    });
                Route::prefix('user')->group(function () {
                        Route::get('/', [UserController::class, 'index'])->name('hr.user.index');
                        Route::post('/', [UserController::class, 'store'])->name('hr.user.store');
                        Route::get('/sync-permissions', [UserController::class, 'syncManagerPermissions'])->name('hr.user.sync-permissions');
                     });
                    # employee managing employee roles
                    Route::prefix('managing_employee_roles')->group(function () {
                        Route::get('/index', [ManagingEmployeeRolesController::class, 'index'])->name('managing_employee_roles.index');
                        Route::get('/create_test', [ManagingEmployeeRolesController::class, 'create_test'])->name('managing_employee_roles.create_test');
                        Route::get('/create', [ManagingEmployeeRolesController::class, 'create'])->name('managing_employee_roles.create');
                        Route::post('/store', [ManagingEmployeeRolesController::class, 'store'])->name('managing_employee_roles.store');
                        Route::get('/edit/{id}', [ManagingEmployeeRolesController::class, 'edit'])->name('managing_employee_roles.edit');
                        Route::post('/update/{id}', [ManagingEmployeeRolesController::class, 'update'])->name('managing_employee_roles.update');
                        Route::get('/delete/{id}', [ManagingEmployeeRolesController::class, 'delete'])->name('managing_employee_roles.delete');
                    });

                    # employee shift management
                    Route::prefix('shift_management')->group(function () {
                        Route::get('/index', [ShiftManagementController::class, 'index'])->name('shift_management.index');
                        Route::post('/search', [ShiftManagementController::class, 'search'])->name('shift_management.search');
                        Route::get('/create', [ShiftManagementController::class, 'create'])->name('shift_management.create');
                        Route::post('/store', [ShiftManagementController::class, 'store'])->name('shift_management.store');
                        Route::get('/edit/{id}', [ShiftManagementController::class, 'edit'])->name('shift_management.edit');
                        Route::post('/update/{id}', [ShiftManagementController::class, 'update'])->name('shift_management.update');
                        Route::post('/export', [ShiftManagementController::class, 'export'])->name('shift_management.export');
                        Route::get('/delete/{id}', [ShiftManagementController::class, 'delete'])->name('shift_management.delete');
                        Route::get('/show/{id}', [ShiftManagementController::class, 'show'])->name('shift_management.show');
                    });

                    # employee routes
                    Route::prefix('employee')->group(function () {
                        Route::get('/employee_role_management', [EmployeeController::class, 'employee_management'])->name('employee.employee_role_management');
                        Route::get('/manage_shifts', [EmployeeController::class, 'manage_shifts'])->name('employee.shifts');
                        Route::get('/add_shift', [EmployeeController::class, 'add_shift'])->name('add_shift');
                        Route::get('/employee_role_management/add_new_role', [EmployeeController::class, 'add_new_role'])->name('employee.employee_role_management.add_new_role');
                    });
                    # task
                    Route::prefix('task')->group(function () {
                        Route::get('/task', [TaskController::class, 'index'])->name('task.index');
                    });

                    Route::prefix('department')->group(function () {
                        Route::get('/index', [DepartmentController::class, 'index'])->name('department.index');
                        Route::get('/create', [DepartmentController::class, 'create'])->name('department.create');
                        Route::get('/show/{id}', [DepartmentController::class, 'show'])->name('department.show');
                        Route::get('/edit/{id}', [DepartmentController::class, 'edit'])->name('department.edit');
                        Route::post('/store', [DepartmentController::class, 'store'])->name('department.store');
                        Route::post('/update/{id}', [DepartmentController::class, 'update'])->name('department.update');
                        Route::get('/delete/{id}', [DepartmentController::class, 'delete'])->name('department.delete');
                        Route::get('/updateStatus/{id}', [DepartmentController::class, 'updateStatus'])->name('department.updateStatus');
                        Route::get('/export/view', [DepartmentController::class, 'export_view'])->name('department.export_view');
                        Route::post('/export', [DepartmentController::class, 'export'])->name('department.export');
                    });

                    Route::prefix('JobTitles')->group(function () {
                        Route::get('/index', [JobTitleController::class, 'index'])->name('JobTitles.index');
                        Route::get('/create', [JobTitleController::class, 'create'])->name('JobTitles.create');
                        Route::get('/show/{id}', [JobTitleController::class, 'show'])->name('JobTitles.show');
                        Route::get('/edit/{id}', [JobTitleController::class, 'edit'])->name('JobTitles.edit');
                        Route::post('/store', [JobTitleController::class, 'store'])->name('JobTitles.store');
                        Route::post('/update/{id}', [JobTitleController::class, 'update'])->name('JobTitles.update');
                        Route::get('/delete/{id}', [JobTitleController::class, 'delete'])->name('JobTitles.delete');
                        Route::get('/updateStatus/{id}', [JobTitleController::class, 'updateStatus'])->name('JobTitles.updateStatus');
                    });

                    Route::prefix('ManagingFunctionalLevels')->group(function () {
                        Route::get('/index', [ManagingFunctionalLevelsController::class, 'index'])->name('ManagingFunctionalLevels.index');
                        Route::get('/create', [ManagingFunctionalLevelsController::class, 'create'])->name('ManagingFunctionalLevels.create');
                        Route::get('/show/{id}', [ManagingFunctionalLevelsController::class, 'show'])->name('ManagingFunctionalLevels.show');
                        Route::get('/edit/{id}', [ManagingFunctionalLevelsController::class, 'edit'])->name('ManagingFunctionalLevels.edit');
                        Route::post('/store', [ManagingFunctionalLevelsController::class, 'store'])->name('ManagingFunctionalLevels.store');
                        Route::post('/update/{id}', [ManagingFunctionalLevelsController::class, 'update'])->name('ManagingFunctionalLevels.update');
                        Route::get('/delete/{id}', [ManagingFunctionalLevelsController::class, 'delete'])->name('ManagingFunctionalLevels.delete');
                    });

                    Route::prefix('ManagingJobTypes')->group(function () {
                        Route::get('/index', [ManagingJobTypesController::class, 'index'])->name('ManagingJobTypes.index');
                        Route::get('/create', [ManagingJobTypesController::class, 'create'])->name('ManagingJobTypes.create');
                        Route::get('/show/{id}', [ManagingJobTypesController::class, 'show'])->name('ManagingJobTypes.show');
                        Route::get('/edit/{id}', [ManagingJobTypesController::class, 'edit'])->name('ManagingJobTypes.edit');
                        Route::post('/store', [ManagingJobTypesController::class, 'store'])->name('ManagingJobTypes.store');
                        Route::post('/update/{id}', [ManagingJobTypesController::class, 'update'])->name('ManagingJobTypes.update');
                        Route::get('/delete/{id}', [ManagingJobTypesController::class, 'delete'])->name('ManagingJobTypes.delete');
                        Route::get('/updateStatus/{id}', [ManagingJobTypesController::class, 'updateStatus'])->name('ManagingJobTypes.updateStatus');
                    });
                    Route::prefix('attendance-records')->group(function () {
                        Route::get('/index', [AttendanceRecordsController::class, 'index'])->name('attendance_records.index');
                        Route::get('create', [AttendanceRecordsController::class, 'create'])->name('Attendance.attendance-records.create');
                    });

                    Route::prefix('attendance-sheets')->group(function () {
                        Route::get('/index', [AttendanceSheetsController::class, 'index'])->name('attendance_sheets.index');
                        Route::get('/create', [AttendanceSheetsController::class, 'create'])->name('attendance_sheets.create');
                        Route::get('/edit/{id}', [AttendanceSheetsController::class, 'edit'])->name('attendance_sheets.edit');
                        Route::get('/show/{id}', [AttendanceSheetsController::class, 'show'])->name('attendance_sheets.show');
                        Route::get('/attendance-sheets/ajax', [AttendanceSheetsController::class, 'getAttendanceSheetsAjax'])->name('attendance_sheets.ajax.index');
                        Route::get('/filter-attendance-days/{id}', [AttendanceSheetsController::class, 'filterAttendanceDays'])->name('attendance_sheets.filter_days');

                        Route::post('/store', [AttendanceSheetsController::class, 'store'])->name('attendance_sheets.store');
                        Route::post('/update/{id}', [AttendanceSheetsController::class, 'update'])->name('attendance_sheets.update');
                        Route::get('/delete/{id}', [AttendanceSheetsController::class, 'delete'])->name('attendance_sheets.delete');
                    });

                    Route::prefix('attendanceDays')->group(function () {
                        Route::get('/index', [AttendanceDaysController::class, 'index'])->name('attendanceDays.index');

                        Route::get('/attendance-days/filter', [AttendanceDaysController::class, 'filter'])->name('attendanceDays.filter');
                        Route::get('/create', [AttendanceDaysController::class, 'create'])->name('attendanceDays.create');
                        Route::get('/edit/{id}', [AttendanceDaysController::class, 'edit'])->name('attendanceDays.edit');
                        Route::get('/show/{id}', [AttendanceDaysController::class, 'show'])->name('attendanceDays.show');
                        Route::post('/store', [AttendanceDaysController::class, 'store'])->name('attendanceDays.store');
                        Route::put('/update/{id}', [AttendanceDaysController::class, 'update'])->name('attendanceDays.update');
                        Route::get('/calculation', [AttendanceDaysController::class, 'calculation'])->name('attendanceDays.calculation');
                        Route::get('/delete/{id}', [AttendanceDaysController::class, 'delete'])->name('attendanceDays.delete');
                        Route::post('/calculateAttendance', [AttendanceDaysController::class, 'calculateAttendance'])->name('calculateAttendance');
                    });

                    Route::prefix('leave-permissions')->group(function () {
                        Route::get('/ajax', [LeavePermissionsController::class, 'getLeavePermissionsAjax'])->name('leave_permissions.ajax.index');
                        Route::get('/index', [LeavePermissionsController::class, 'index'])->name('leave_permissions.index');
                        Route::get('/create', [LeavePermissionsController::class, 'create'])->name('leave_permissions.create');
                        Route::post('/store', [LeavePermissionsController::class, 'store'])->name('leave_permissions.store');
                        Route::get('/show/{id}', [LeavePermissionsController::class, 'show'])->name('leave_permissions.show');
                        Route::get('/filter-leave-records/{id}', [LeavePermissionsController::class, 'filterLeaveRecords'])->name('leave_permissions.filter_records');
                        Route::get('/edit/{id}', [LeavePermissionsController::class, 'edit'])->name('leave_permissions.edit');
                        Route::post('/update/{id}', [LeavePermissionsController::class, 'update'])->name('leave_permissions.update');
                        Route::delete('/delete/{id}', [LeavePermissionsController::class, 'destroy'])->name('leave_permissions.destroy');
                    });

                    Route::prefix('leave_requests')->group(function () {
                        // المسارات الأساسية الموجودة
                        Route::get('/index', [LeaveRequestsController::class, 'index'])->name('attendance.leave_requests.index');
                        Route::post('store', [LeaveRequestsController::class, 'store'])->name('attendance.leave_requests.store');
                        Route::get('create', [LeaveRequestsController::class, 'create'])->name('attendance.leave_requests.create');
                        Route::get('edit/{id}', [LeaveRequestsController::class, 'edit'])->name('attendance.leave_requests.edit');
                        Route::get('show/{id}', [LeaveRequestsController::class, 'show'])->name('attendance.leave_requests.show');
                        Route::put('/update/{id}', [LeaveRequestsController::class, 'update'])->name('attendance.leave_requests.update');
                        Route::delete('/delete/{id}', [LeaveRequestsController::class, 'destroy'])->name('attendance.leave_requests.destroy');

                        // مسارات البحث والتصفية
                        Route::get('/search', [LeaveRequestsController::class, 'search'])->name('attendance.leave_requests.search');
                        Route::get('/list', [LeaveRequestsController::class, 'getEmployeesList'])->name('attendance.employees.list');
                        Route::get('/balance', [LeaveRequestsController::class, 'getEmployeeLeaveBalance'])->name('leave_requests.employee_balance');
                        Route::get('leave-balance/show', [LeaveRequestsController::class, 'getEmployeeLeaveBalance'])->name('leave_balance.show');

                        // المسارات الأساسية للموافقة والرفض (المحدثة)

                        Route::post('/{id}/approve', [LeaveRequestsController::class, 'approve'])->name('attendance.leave_requests.approve');
                        Route::post('/{id}/reject', [LeaveRequestsController::class, 'reject'])->name('attendance.leave_requests.reject');
                        Route::post('/{id}/cancel-approval', [LeaveRequestsController::class, 'cancelApproval'])->name('attendance.leave_requests.cancelApproval');
                        Route::post('/{id}/undo-rejection', [LeaveRequestsController::class, 'undoRejection'])->name('attendance.leave_requests.undoRejection');
                        Route::get('/{id}/check-balance', [LeaveRequestsController::class, 'checkBalance'])->name('attendance.leave_requests.checkBalance');
                        Route::get('/employee/{employeeId}/balance', [LeaveRequestsController::class, 'getBalance'])->name('getBalance');
                        Route::post('/{id}/add-note', [LeaveRequestsController::class, 'addNote'])->name('attendance.leave_requests.addNote');
                        Route::get('/{id}/notes', [LeaveRequestsController::class, 'getNotes'])->name('attendance.leave_requests.getNotes');

                        Route::delete('/notes/{noteId}', [LeaveRequestsController::class, 'deleteNote'])->name('attendance.leave_requests.deleteNote');
                        Route::post('return-from-rejection/{id}', [LeaveRequestsController::class, 'returnFromRejection'])->name('attendance.leave_requests.return_from_rejection');
                        Route::get('/{id}/notes', [LeaveRequestsController::class, 'getNotes'])->name('attendance.leave_requests.getNotes');
                        Route::post('/update-status/{id}', [LeaveRequestsController::class, 'updateStatus'])->name('attendance.leave_requests.update_status');
                        // معالج موحد للعمليات (اختياري - يمكن استخدامه بدلاً من المسارات المنفصلة)
                        Route::post('process-action/{id}', [LeaveRequestsController::class, 'processAction'])->name('attendance.leave_requests.process_action');
                    });
                    Route::prefix('employee-leave-balances')
                        ->name('employee_leave_balances.')
                        ->group(function () {
                            Route::get('/', [EmployeeLeaveBalanceController::class, 'index'])->name('index');

                            Route::get('show/{id}', [EmployeeLeaveBalanceController::class, 'show'])->name('show');

                            Route::get('/create', [EmployeeLeaveBalanceController::class, 'create'])->name('create');

                            Route::post('/', [EmployeeLeaveBalanceController::class, 'store'])->name('store');

                            Route::get('/{employeeLeaveBalance}/edit', [EmployeeLeaveBalanceController::class, 'edit'])->name('edit');

                            Route::put('/{employeeLeaveBalance}', [EmployeeLeaveBalanceController::class, 'update'])->name('update');

                            Route::delete('/{employeeLeaveBalance}', [EmployeeLeaveBalanceController::class, 'destroy'])->name('destroy');

                            // Routes إضافية للوظائف المساعدة
                            Route::post('/check-existing', [EmployeeLeaveBalanceController::class, 'checkExisting'])->name('check_existing');

                            Route::post('/recalculate', [EmployeeLeaveBalanceController::class, 'recalculateBalances'])->name('recalculate');
                        });
                    Route::prefix('custom-shifts')->group(function () {
                        Route::get('/index', [CustomShiftsController::class, 'index'])->name('custom_shifts.index');
                        Route::get('custom_shifts/filter', [CustomShiftsController::class, 'filter'])->name('custom_shifts.filter');
                        Route::get('/create', [CustomShiftsController::class, 'create'])->name('custom_shifts.create');
                        Route::post('/store', [CustomShiftsController::class, 'store'])->name('custom_shifts.store');
                        Route::get('/show/{id}', [CustomShiftsController::class, 'show'])->name('custom_shifts.show');
                        Route::get('/edit/{id}', [CustomShiftsController::class, 'edit'])->name('custom_shifts.edit');
                        Route::post('/update/{id}', [CustomShiftsController::class, 'update'])->name('custom_shifts.update');
                        Route::get('/delete/{id}', [CustomShiftsController::class, 'delete'])->name('custom_shifts.delete');
                    });

                    Route::prefix('attendance-sessions-record')->group(function () {
                        Route::get('/index', [AttendanceSessionsRecordController::class, 'index'])->name('Attendance.attendance-sessions-record.index');
                    });
                    Route::prefix('barcode')->group(function () {
                        Route::get('/scan', [BarcodeAttendanceController::class, 'scanPage'])->name('barcode.scan');

                        // معالجة الباركود الممسوح
                        Route::post('/process', [BarcodeAttendanceController::class, 'processBarcode'])->name('barcode.process');

                        // تسجيل حضور
                        Route::post('/check-in', [BarcodeAttendanceController::class, 'checkIn'])->name('barcode.checkin');

                        // تسجيل انصراف
                        Route::post('/check-out', [BarcodeAttendanceController::class, 'checkOut'])->name('barcode.checkout');

                        // إحصائيات الداشبورد
                        Route::get('/stats', [BarcodeAttendanceController::class, 'dashboardStats'])->name('barcode.stats');

                        // توليد باركود لموظف واحد
                        Route::get('/employee/{id}/generate', [BarcodeAttendanceController::class, 'generateBarcode'])->name('barcode.generate');

                        // توليد باركود لجميع الموظفين
                        Route::post('/generate-all', [BarcodeAttendanceController::class, 'generateAllBarcodes'])->name('barcode.generate.all');

                        // تقرير الحضور اليومي
                        Route::get('/daily-report', [BarcodeAttendanceController::class, 'dailyReport'])->name('barcode.daily.report');

                        // البحث عن موظف بالباركود (للاختبار)
                        Route::post('/search-employee', [BarcodeAttendanceController::class, 'searchEmployeeByBarcode'])->name('barcode.search.employee');

                        // طباعة باركود الموظف
                        Route::get('/employee/{id}/print', [BarcodeAttendanceController::class, 'printEmployeeBarcode'])->name('barcode.print');

                        // تصدير تقرير إكسل
                        Route::get('/export/{date?}', [BarcodeAttendanceController::class, 'exportAttendance'])->name('barcode.export');
                    });

                    Route::prefix('settings')->group(function () {
                        Route::get('/index', [AttendanceSettingsController::class, 'index'])->name('attendance.settings.index');
                        # Holiday lists
                        Route::prefix('holiday-lists')->group(function () {
                            Route::get('/index', [HolidayController::class, 'index'])->name('holiday_lists.index');
                            Route::get('/create', [HolidayController::class, 'create'])->name('holiday_lists.create');

                            Route::get('/search', [HolidayController::class, 'search'])->name('holiday_lists.search');
                            Route::get('/employees_count/{id}', [HolidayController::class, 'employeesCount'])->name('holiday_lists.employees_count');

                            Route::get('/employees_list/{id}', [HolidayController::class, 'employeesList'])->name('holiday_lists.employees_list');
                            Route::delete('/remove_employee/{id}', [HolidayController::class, 'removeEmployee'])->name('holiday_lists.remove_employee');

                            Route::post('/store', [HolidayController::class, 'store'])->name('holiday_lists.store');
                            Route::get('/show/{id}', [HolidayController::class, 'show'])->name('holiday_lists.show');
                            Route::get('/edit/{id}', [HolidayController::class, 'edit'])->name('holiday_lists.edit');
                            Route::post('/update/{id}', [HolidayController::class, 'update'])->name('holiday_lists.update');
                            Route::get('/delete/{id}', [HolidayController::class, 'delete'])->name('holiday_lists.delete');
                            Route::get('/holyday_employees/{id}', [HolidayController::class, 'holyday_employees'])->name('holiday_lists.holyday_employees');
                            Route::post('/holyday_employees/add/{id}', [HolidayController::class, 'add_holyday_employees'])->name('holiday_lists.add_holyday_employees');
                        });
                        # Leave Types
                        Route::prefix('leave-types')->group(function () {
                            Route::get('/index', [LeaveTypesController::class, 'index'])->name('leave_types.index');
                            Route::get('/create', [LeaveTypesController::class, 'create'])->name('leave_types.create');
                            Route::post('/store', [LeaveTypesController::class, 'store'])->name('leave_types.store');
                            Route::get('/show/{id}', [LeaveTypesController::class, 'show'])->name('leave_types.show');
                            Route::get('/edit/{id}', [LeaveTypesController::class, 'edit'])->name('leave_types.edit');
                            Route::post('/update/{id}', [LeaveTypesController::class, 'update'])->name('leave_types.update');
                            Route::get('/delete/{id}', [LeaveTypesController::class, 'delete'])->name('leave_types.delete');
                        });
                        # Leave Policy
                        Route::prefix('leave-policies')->group(function () {
                            Route::get('/index', [LeavePoliciesController::class, 'index'])->name('leave_policy.index');
                            Route::get('/search', [LeavePoliciesController::class, 'search'])->name('leave_policy.search');
                            Route::get('/employees_count/{id}', [LeavePoliciesController::class, 'employeesCount'])->name('leave_policy.employeesCount');
                            Route::get('/getStatistics/{id}', [LeavePoliciesController::class, 'getStatistics'])->name('leave_policy.getStatistics');

                            Route::get('/employees_list-leave/{id}', [LeavePoliciesController::class, 'employeesList'])->name('leave_policy.employeesList');
                            Route::delete('/remove_employee/{id}', [LeavePoliciesController::class, 'removeEmployee'])->name('leave_policy.removeEmployee');

                            Route::get('create', [LeavePoliciesController::class, 'create'])->name('leave_policy.create');
                            Route::post('store', [LeavePoliciesController::class, 'store'])->name('leave_policy.store');
                            Route::get('show/{id}', [LeavePoliciesController::class, 'show'])->name('leave_policy.show');
                            Route::get('edit/{id}', [LeavePoliciesController::class, 'edit'])->name('leave_policy.edit');
                            Route::post('update/{id}', [LeavePoliciesController::class, 'update'])->name('leave_policy.update');
                            Route::get('delete/{id}', [LeavePoliciesController::class, 'delete'])->name('leave_policy.delete');
                            Route::get('updateStatus/{id}', [LeavePoliciesController::class, 'updateStatus'])->name('leave_policy.updateStatus');
                            Route::get('leave_policy_employees/{id}', [LeavePoliciesController::class, 'leave_policy_employees'])->name('leave_policy.leave_policy_employees');
                            Route::post('/leave_policy_employees/add/{id}', [LeavePoliciesController::class, 'add_leave_policy_employees'])->name('leave_policy.add_leave_policy_employees');
                        });
                        # Basic settings
                        Route::prefix('basic-settings')->group(function () {
                            Route::get('/index', [BasicController::class, 'index'])->name('settings_basic.index');
                            Route::post('/update', [BasicController::class, 'update'])->name('settings_basic.update');
                        });
                        # Attendance Determinants
                        Route::prefix('attendance_determinants')->group(function () {
                            Route::get('/index', [AttendanceDeterminantsController::class, 'index'])->name('attendance_determinants.index');
                            Route::get('/ajax', [AttendanceDeterminantsController::class, 'ajaxData'])->name('attendance_determinants.ajax');
                            Route::get('/create', [AttendanceDeterminantsController::class, 'create'])->name('attendance_determinants.create');
                            Route::post('/store', [AttendanceDeterminantsController::class, 'store'])->name('attendance_determinants.store');
                            Route::get('/show/{id}', [AttendanceDeterminantsController::class, 'show'])->name('attendance_determinants.show');
                            Route::get('/edit/{id}', [AttendanceDeterminantsController::class, 'edit'])->name('attendance_determinants.edit');
                            Route::get('/edit/{id}', [AttendanceDeterminantsController::class, 'edit'])->name('attendance_determinants.edit');

                            Route::get('/{id}/manage-employees', [AttendanceDeterminantsController::class, 'manageEmployees'])->name('attendance_determinants.manage_employees');
                            Route::get('/{id}/employees-count', [AttendanceDeterminantsController::class, 'employeesCount'])->name('attendance_determinants.employees_count');

                            Route::post('/{id}/add-employees', [AttendanceDeterminantsController::class, 'addEmployees'])->name('attendance_determinants.add_employees');
                            Route::post('/{id}/employees-list', [AttendanceDeterminantsController::class, 'employeesList'])->name('attendance_determinants.employees_list');
                            Route::post('/{id}/remove-employee', [AttendanceDeterminantsController::class, 'removeEmployee'])->name('attendance_determinants.remove_employee');
                            Route::get('/{id}/statistics', [AttendanceDeterminantsController::class, 'getStatistics'])->name('attendance_determinants.statistics');
                            Route::post('/preview-rules', [AttendanceDeterminantsController::class, 'previewRules'])->name('attendance_determinants.preview_rules');
                            Route::delete('/{id}/customizations/{customization_id}', [AttendanceDeterminantsController::class, 'deleteCustomization'])->name('attendance_determinants.delete_customization');
                            Route::post('/{id}/apply', [AttendanceDeterminantsController::class, 'applyDeterminant'])->name('attendance_determinants.apply');
                            Route::post('/update/{id}', [AttendanceDeterminantsController::class, 'update'])->name('attendance_determinants.update');
                            Route::get('/delete/{id}', [AttendanceDeterminantsController::class, 'destroy'])->name('attendance_determinants.destroy');

                            Route::get('updateStatus/{id}', [AttendanceDeterminantsController::class, 'updateStatus'])->name('attendance_determinants.updateStatus');
                        });
                    });

                    Route::prefix('machines')->group(function () {
                        Route::get('/index', [MachinesController::class, 'index'])->name('machines.index');
                        Route::get('/search', [MachinesController::class, 'search'])->name('machines.search');

                        Route::get('create', [MachinesController::class, 'create'])->name('machines.create');
                        Route::post('store', [MachinesController::class, 'store'])->name('machines.store');
                        Route::get('edit/{id}', [MachinesController::class, 'edit'])->name('machines.edit');
                        Route::put('update/{id}', [MachinesController::class, 'update'])->name('machines.update');
                        Route::get('show/{id}', [MachinesController::class, 'show'])->name('machines.show');
                        Route::delete('destroy/{id}', [MachinesController::class, 'destroy'])->name('machines.destroy');
                        Route::post('toggle-status/{id}', [MachinesController::class, 'toggleStatus'])->name('machines.toggle-status');
                    });

                    Route::prefix('attendance.Settings.printable-templates')->group(function () {
                        Route::get('/index', [PrintableTemplatesController::class, 'index'])->name('attendance.settings.printable-templates.index');
                        Route::get('create', [PrintableTemplatesController::class, 'create'])->name('attendance.settings.printable-templates.create');
                    });
                    Route::prefix('Contracts')->group(function () {
                        Route::get('/index', [ContractsController::class, 'index'])->name('Contracts.index');
                        Route::get('/create', [ContractsController::class, 'create'])->name('Contracts.create');
                        Route::post('/store', [ContractsController::class, 'store'])->name('Contracts.store');
                        Route::get('/show/{id}', [ContractsController::class, 'show'])->name('Contracts.show');
                        Route::get('/edit/{id}', [ContractsController::class, 'edit'])->name('Contracts.edit');
                        Route::put('/update/{id}', [ContractsController::class, 'update'])->name('Contracts.update');
                        Route::delete('/destroy/{id}', [ContractsController::class, 'destroy'])->name('Contracts.destroy');
                        Route::get('/contracts/print/{id}', [ContractsController::class, 'printContract'])->name('Contracts.print');
                        Route::get('/contracts/print1/{id}', [ContractsController::class, 'printContract1'])->name('Contracts.print1');
                    });
                    Route::prefix('PayrollProcess')->group(function () {
                        Route::get('/index', [PayrollProcessController::class, 'index'])->name('PayrollProcess.index');
                        Route::get('/create', [PayrollProcessController::class, 'create'])->name('PayrollProcess.create');
                        Route::post('/store', [PayrollProcessController::class, 'store'])->name('PayrollProcess.store');
                        Route::get('/show', [PayrollProcessController::class, 'show'])->name('PayrollProcess.show');
                        Route::get('/edit/{id}', [PayrollProcessController::class, 'edit'])->name('PayrollProcess.edit');
                        Route::delete('/destroy/{id}', [PayrollProcessController::class, 'destroy'])->name('PayrollProcess.destroy');
                    });
                    Route::prefix('SalarySlip')->group(function () {
                        Route::get('/index', [SalarySlipController::class, 'index'])->name('salarySlip.index');
                        Route::get('/create', [SalarySlipController::class, 'create'])->name('salarySlip.create');
                        Route::post('/store', [SalarySlipController::class, 'store'])->name('salarySlip.store');
                        Route::get('/show/{id}', [SalarySlipController::class, 'show'])->name('salarySlip.show');
                        Route::get('/salary/{id}/approve', [SalarySlipController::class, 'approve'])->name('salary.approve');
                        Route::get('/salary/{id}/cancel', [SalarySlipController::class, 'cancel'])->name('salary.cancel');

                        Route::get('/edit/{id}', [SalarySlipController::class, 'edit'])->name('salarySlip.edit');
                        Route::put('/update/{id}', [SalarySlipController::class, 'update'])->name('salarySlip.update');
                        Route::delete('/destroy/{id}', [SalarySlipController::class, 'destroy'])->name('salarySlip.destroy');
                        Route::get('salary-slip/{id}/printPayslip1', [SalarySlipController::class, 'printPayslip1'])->name('salarySlip.printPayslip1');
                        Route::get('salary-slip/{id}/printPayslip2', [SalarySlipController::class, 'printPayslip2'])->name('salarySlip.printPayslip2');
                        Route::get('salary-slip/{id}/printPayslip3', [SalarySlipController::class, 'printPayslip3'])->name('salarySlip.printPayslip3');
                        Route::get('salary-slip/{id}/printPayslipAr1', [SalarySlipController::class, 'printPayslipAr1'])->name('salarySlip.printPayslipAr1');
                        Route::get('salary-slip/{id}/printPayslipAr2', [SalarySlipController::class, 'printPayslipAr2'])->name('salarySlip.printPayslipAr2');
                        Route::get('salary-slip/{id}/printPayslipAr3', [SalarySlipController::class, 'printPayslipAr3'])->name('salarySlip.printPayslipAr3');
                    });
                    Route::prefix('ancestor')->group(function () {
                        Route::get('/index', [AncestorController::class, 'index'])->name('ancestor.index');
                        Route::get('/create', [AncestorController::class, 'create'])->name('ancestor.create');
                        Route::post('/store', [AncestorController::class, 'store'])->name('ancestor.store');
                        Route::get('/show/{id}', [AncestorController::class, 'show'])->name('ancestor.show');
                        Route::get('/edit/{id}', [AncestorController::class, 'edit'])->name('ancestor.edit');
                        Route::get('/pay/{id}', [AncestorController::class, 'pay'])->name('ancestor.pay');
                        Route::get('/salary-advance/{id}/pay', [AncestorController::class, 'pay'])->name('salary-advance.pay');
                        Route::post('/salary-advance/{id}/pay', [AncestorController::class, 'storePayments'])->name('salary-advance.store-payments');
                        Route::put('/update/{id}', [AncestorController::class, 'update'])->name('ancestor.update');
                        Route::delete('/destroy/{id}', [AncestorController::class, 'destroy'])->name('ancestor.destroy');
                    });

                    Route::prefix('SalaryItems')->group(function () {
                        Route::get('/index', [SalaryItemsController::class, 'index'])->name('SalaryItems.index');
                        Route::get('/create', [SalaryItemsController::class, 'create'])->name('SalaryItems.create');
                        Route::post('/store', [SalaryItemsController::class, 'store'])->name('SalaryItems.store');
                        Route::get('/show/{id}', [SalaryItemsController::class, 'show'])->name('SalaryItems.show');
                        Route::get('/edit/{id}', [SalaryItemsController::class, 'edit'])->name('SalaryItems.edit');
                        Route::put('/update/{id}', [SalaryItemsController::class, 'update'])->name('SalaryItems.update');
                        Route::delete('/destroy/{id}', [SalaryItemsController::class, 'destroy'])->name('SalaryItems.destroy');
                        Route::put('SalaryItems/{id}/toggle-status', [SalaryItemsController::class, 'toggleStatus'])->name('SalaryItems.toggleStatus');
                    });
                    Route::prefix('SalaryTemplates')->group(function () {
                        Route::get('/index', [SalaryTemplatesController::class, 'index'])->name('SalaryTemplates.index');
                        Route::get('/create', [SalaryTemplatesController::class, 'create'])->name('SalaryTemplates.create');
                        Route::post('/store', [SalaryTemplatesController::class, 'store'])->name('SalaryTemplates.store');
                        Route::get('/show/{id}', [SalaryTemplatesController::class, 'show'])->name('SalaryTemplates.show');
                        Route::get('/edit/{id}', [SalaryTemplatesController::class, 'edit'])->name('SalaryTemplates.edit');
                        Route::put('/update/{id}', [SalaryTemplatesController::class, 'update'])->name('SalaryTemplates.update');
                        Route::delete('/destroy/{id}', [SalaryTemplatesController::class, 'destroy'])->name('SalaryTemplates.destroy');
                    });

                    Route::prefix('SalarySittings')->group(function () {
                        Route::get('/index', [SalarySittingController::class, 'index'])->name('SalarySittings.index');
                    });

                    Route::prefix('RelatedModels')->group(function () {
                        Route::get('/index', [RelatedModelsController::class, 'index'])->name('RelatedModels.index');
                        Route::get('/create', [RelatedModelsController::class, 'create'])->name('RelatedModels.create');
                        Route::get('/show/{id}', [RelatedModelsController::class, 'show'])->name('RelatedModels.show');
                        Route::get('/edit/{id}', [RelatedModelsController::class, 'edit'])->name('RelatedModels.edit');
                    });
                    Route::prefix('CommissionRules')->group(function () {
                        Route::get('/index', [CommissionRulesController::class, 'index'])->name('CommissionRules.index');
                        Route::get('/create', [CommissionRulesController::class, 'create'])->name('CommissionRules.create');
                        Route::get('/show/{id}', [CommissionRulesController::class, 'show'])->name('CommissionRules.show');
                        Route::get('/edit/{id}', [CommissionRulesController::class, 'edit'])->name('CommissionRules.edit');
                    });
                    Route::prefix('SalesPeriods')->group(function () {
                        Route::get('/index', [SalesPeriodsController::class, 'index'])->name('SalesPeriods.index');
                        Route::get('/create', [SalesPeriodsController::class, 'create'])->name('SalesPeriods.create');
                        Route::get('/show/{id}', [SalesPeriodsController::class, 'show'])->name('SalesPeriods.show');
                        Route::get('/edit/{id}', [SalesPeriodsController::class, 'edit'])->name('SalesPeriods.edit');
                    });

                    Route::prefix('attendance-rules')
                        ->name('attendance-rules.')
                        ->group(function () {
                            Route::get('/', [AttendanceRuleController::class, 'index'])->name('index');
                            Route::get('/search', [AttendanceRuleController::class, 'search'])->name('search');
                            Route::get('/create', [AttendanceRuleController::class, 'create'])->name('create');
                            Route::post('/', [AttendanceRuleController::class, 'store'])->name('store');
                            Route::get('/{attendanceRule}', [AttendanceRuleController::class, 'show'])->name('show');
                            Route::get('/{attendanceRule}/edit', [AttendanceRuleController::class, 'edit'])->name('edit');
                            Route::put('/{attendanceRule}', [AttendanceRuleController::class, 'update'])->name('update');
                            Route::delete('/{attendanceRule}', [AttendanceRuleController::class, 'destroy'])->name('destroy');
                            Route::patch('/{attendanceRule}/toggle-status', [AttendanceRuleController::class, 'toggleStatus'])->name('toggle-status');
                        });
                    Route::prefix('SalesCommission')->group(function () {
                        Route::get('/index', [SalesCommissionController::class, 'index'])->name('SalesCommission.index');

                        Route::get('/show/{id}', [SalesCommissionController::class, 'show'])->name('SalesCommission.show');
                    });
                    Route::get('/index', [CommissionController::class, 'index']);
                });
        },
    );
});

 Route::get('/dashboardhr', [EmployeeController::class, 'dashboardhr'])->name('dashboardhr');

