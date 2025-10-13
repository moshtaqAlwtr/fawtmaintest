@php
    $getLocal = App::getLocale();
@endphp

<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="#">
                    <div class="brand-logo"></div>
                    <h2 class="brand-text mb-0">فوترة</h2>
                </a>
            </li>

            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                        class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i
                        class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary"
                        data-ticon="icon-disc"></i></a></li>
        </ul>
    </div>

    <div class="shadow-bottom"></div>

    <div class="main-menu-content">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            {{-- الرئيسيه --}}


            @if (auth()->user()->hasAnyPermission([
                        'sales_add_invoices',
                        'sales_add_own_invoices',
                        'sales_edit_delete_all_invoices',
                        'sales_edit_delete_own_invoices',
                        'sales_view_own_invoices',
                        'sales_view_all_invoices',
                        'sales_create_tax_report',
                        'sales_change_seller',
                        'sales_invoice_all_products',
                        'sales_view_invoice_profit',
                        'sales_add_credit_notice_all',
                        'sales_add_credit_notice_own',
                        'sales_edit_invoice_date',
                        'sales_add_payments_all',
                        'sales_add_payments_own',
                        'sales_edit_payment_options',
                        'sales_edit_delete_all_payments',
                        'sales_edit_delete_own_payments',
                        'sales_add_quote_all',
                        'sales_add_quote_own',
                        'sales_view_all_quotes',
                        'sales_view_own_quotes',
                        'sales_edit_delete_all_quotes',
                        'sales_edit_delete_own_quotes',
                        'sales_view_all_sales_orders',
                        'sales_view_own_sales_orders',
                        'sales_add_sales_order_all',
                        'sales_add_sales_order_own',
                        'sales_edit_delete_all_sales_orders',
                        'sales_edit_delete_own_sales_orders',
                        'sales_edit_delete_all_credit_notices',
                        'sales_edit_delete_own_credit_notices',
                        'sales_view_all_credit_notices',
                        'sales_view_own_credit_notices',
                    ]))
                @php
                    // جلب جميع الإعدادات من جدول application_settings
                    $settings = \App\Models\ApplicationSetting::pluck('status', 'key')->toArray();
                @endphp

                @if (isset($settings['sales']) && $settings['sales'] === 'active')
                @endif

            @endif

{{-- الموظفين --}}
            @if (auth()->user()->hasAnyPermission(['employees_view_profile', 'employees_roles_add']))
                @if (isset($settings['employees']) && $settings['employees'] === 'active')
                    <li class=" nav-item {{ request()->is("$getLocal/hr/*") ? 'active open' : '' }}">
                        <a href="index.html">
                            <i class="fa fa-users"></i><span class="menu-title" data-i18n="Dashboard">
                                {{ trans('main_trans.Employees') }}</span>
                        </a>
                        <ul class="menu-content">
                            @can('employees_view_profile')
                                <li><a href="{{ route('employee.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/hr/employee/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Employee_Management') }}</span></a>
                                </li>
                            @endcan

                            @can('employees_roles_add')
                                <li><a href="{{ route('managing_employee_roles.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/hr/managing_employee_roles/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Employee_Roles_Management') }}</span></a>
                                </li>
                            @endcan

                            <li><a href="{{ route('shift_management.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/hr/shift_management/*") ? 'active' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Shift_Management') }}</span></a>
                            </li>

                            <li><a href="dashboard-ecommerce.html"><i class="feather icon-circle"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Sittings') }}</span></a>
                            </li>
                    <li><a href="{{ route('employeeReports.index') }}"><i class="feather icon-circle"></i><span class="menu-item"
                                data-i18n="eCommerce">{{ trans('main_trans.Employees_Report') }}</span></a></li>



                        </ul>

                    </li>
                @endif
            @endif
            {{-- الهيكل التنظيمي --}}
            @can('hr_system_management')
                @if (isset($settings['organizational_structure']) && $settings['organizational_structure'] === 'active')
                    <li class=" nav-item {{ request()->is("$getLocal/OrganizationalStructure/*") ? 'active open' : '' }}">
                        <a href="index.html">
                            <i class="feather icon-layers">
                            </i><span class="menu-title" data-i18n="Dashboard">
                                {{ trans('main_trans.Organizational_Structure') }}</span>
                        </a>
                        <ul class="menu-content">
                            <li><a href="{{ route('JobTitles.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/OrganizationalStructure/JobTitles/*") ? 'active open' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="Analytics">{{ trans('main_trans.Job_Titles_Management') }}</span></a>
                            </li>

                            <li><a href="{{ route('department.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/OrganizationalStructure/department/*") ? 'active open' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Departments_Management') }}</span></a>
                            </li>

                            <li><a href="{{ route('ManagingFunctionalLevels.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/OrganizationalStructure/ManagingFunctionalLevels/*") ? 'active open' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Job_Levels_Management') }}</span></a>
                            </li>
                            <li><a href="{{ route('ManagingJobTypes.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/OrganizationalStructure/ManagingJobTypes/*") ? 'active open' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Job_Types_Management') }}</span></a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            {{-- الحضور --}}
            @if (auth()->user()->hasAnyPermission([
                        'staff_attendance_view_all',
                        'staff_attendance_edit_days',
                        'staff_attendance_view_other_books',
                        'staff_attendance_import',
                        'staff_attendance_settings_manage',
                    ]))
                @if (isset($settings['employee_attendance']) && $settings['employee_attendance'] === 'active')
                    <li class="nav-item {{ request()->is("$getLocal/presence/*") ? 'active open' : '' }}"><a
                            href="index.html">

                            <i class="feather icon-user-check">
                            </i><span class="menu-title" data-i18n="Dashboard">
                                {{ trans('main_trans.Attendance') }}</span>
                        </a>
                        <ul class="menu-content">
                            @can('staff_attendance_view_all')
                                <li><a href="{{ route('attendance_records.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/presence/attendance-records/*") ? 'active' : '' }}"></i><span
                                            class="menu-item" data-i18n="Analytics">
                                            {{ trans('main_trans.Attendance_Records') }}</span></a>

                                </li>
                            @endcan

                            @can('staff_attendance_edit_days')
                                <li><a href="{{ route('attendanceDays.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/presence/attendanceDays/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Attendance_Days') }}</span></a>
                                </li>
                            @endcan

                            @can('staff_attendance_view_other_books')
                                <li><a href="{{ route('attendance_sheets.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/presence/attendance-sheets/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Attendance_Books') }}</span></a>
                                </li>
                            @endcan

                            <li><a href="{{ route('leave_permissions.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/presence/leave-permissions/*") ? 'active' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Leave_Permissions') }}</span></a>
                            </li>

                            @can('staff_leave_requests_view_all')
                                <li><a href="{{ route('attendance.leave_requests.index') }}"><i
                                            class="feather icon-circle"></i><span class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Leave_Requests') }}</span></a>
                                </li>
                            @endcan

                            <li><a href="{{ route('shift_management.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/presence/shift_management/*") ? 'active' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Shift_Management') }}</span></a>
                            </li>

                            <li><a href="{{ route('custom_shifts.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/presence/custom-shifts/*") ? 'active' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Custom_Shifts') }}</span></a>
                            </li>

                            @can('staff_attendance_import')
                                <li><a href="{{ route('Attendance.attendance-sessions-record.index') }}"><i
                                            class="feather icon-circle"></i><span class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Attendance_Sessions_Log') }}</span></a>
                                </li>
                            @endcan

                            @can('staff_attendance_settings_manage')
                                <li><a href="{{ route('attendance.settings.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/presence/settings/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Sittings') }}</span></a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            @endif
            {{-- المرتبات --}}
            @if (auth()->user()->hasAnyPermission([
                        'salaries_contracts_view_all',
                        'salaries_payroll_view',
                        'salaries_payroll_approve',
                        'salaries_payroll_settings_manage',
                        'salaries_loans_manage',
                    ]))
                @if (isset($settings['salaries']) && $settings['salaries'] === 'active')
                    <li class=" nav-item"><a href="index.html">
                            <i class="feather icon-dollar-sign">
                            </i><span class="menu-title" data-i18n="Dashboard">
                                {{ trans('main_trans.Salaries') }}</span>
                        </a>
                        <ul class="menu-content">
                            @can('salaries_contracts_view_all')
                                <li><a href="{{ route('Contracts.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Contracts') }}</span></a>
                                </li>
                            @endcan

                            @can('salaries_payroll_view')
                                <li><a href="{{ route('PayrollProcess.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Payroll') }}</span></a>
                                </li>
                            @endcan

                            @can('salaries_payroll_approve')
                                <li><a href="{{ route('salarySlip.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item" data-i18n="eCommerce">
                                            {{ trans('main_trans.Salary_Slips') }}</span></a>
                                </li>
                            @endcan

                            @can('salaries_loans_manage')
                                <li><a href="{{ route('ancestor.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Advances') }}</span></a>
                                </li>
                            @endcan

                            <li><a href="{{ route('SalaryItems.index') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Salary_Items') }}</span></a>
                            </li>

                            <li><a href="{{ route('SalaryTemplates.index') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Salary_Templates') }}</span></a>
                            </li>

                            @can('salaries_payroll_settings_manage')
                                <li><a href="{{ route('SalarySittings.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Sittings') }}</span></a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            @endif


        </ul>
    </div>
</div>
