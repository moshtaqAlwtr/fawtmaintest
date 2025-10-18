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



            {{-- المبيعات --}}
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
                    <li class="nav-item {{ request()->is("$getLocal/sales/*") ? 'active open' : '' }}">
                        <a href="index.html"><i class="feather icon-align-justify">
                            </i><span class="menu-title" data-i18n="Dashboard">{{ trans('main_trans.sales') }}</span>
                        </a>
                        <ul class="menu-content">

                            <li><a href="{{ route('dashboard.kpis') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/sales/invoices/index") ? 'active' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="Analytics">مؤشرات الاداء</span></a>
                            </li>
                            <li><a href="{{ route('invoices.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/sales/invoices/index") ? 'active' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="Analytics">{{ trans('main_trans.invoice_management') }}</span></a>
                            </li>


                            @can('sales_add_invoices')
                                <li><a href="{{ route('invoices.create') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/sales/invoices/create") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.creaat_invoice') }}</span></a>
                                </li>
                            @endcan

                            @can('sales_view_all_quotes')
                                <li><a href="{{ route('questions.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/sales/questions/index") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Quotation_Management') }}</span></a>
                                </li>
                            @endcan

                            @can('sales_add_quote_all')
                                <li><a href="{{ route('questions.create') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/sales/questions/create") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Create_quote') }}</span></a>
                                </li>
                            @endcan

                            @can('sales_view_all_credit_notices')
                                <li><a href="{{ route('CreditNotes.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/sales/CreditNotes/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Credit_notes') }}</span></a>
                                </li>
                            @endcan


                            <li><a href="{{ route('ReturnIInvoices.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/sales/invoices/invoices_returned") ? 'active' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Returned_invoices') }}</span></a>
                            </li>


                            <li><a href="{{ route('periodic_invoices.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/sales/periodic-invoices/*") ? 'active' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="Analytics">{{ trans('main_trans.Periodic_invoices') }}</span></a>
                            </li>


                            @can('sales_add_payments_all')
                                <li><a href="{{ route('paymentsClient.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/sales/paymentsClient/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Customer_payments') }}</span></a>
                                </li>
                            @endcan

                            @can('sales_edit_payment_options')
                                <li><a href="{{ route('sittingInvoice.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Sales_Settings') }}</span></a>
                                </li>
                            @endcan
                            @if (auth()->user()->role != 'employee')
                                <li><a href="{{ route('templates.test_print') }}"><i
                                            class="feather icon-circle"></i><span class="menu-item"
                                            data-i18n="eCommerce">اختبار الطباعة على الفواتير</span></a>
                                </li>
                            @endif
                            <li><a href="{{ route('salesReports.index') }}"><i class="feather icon-circle"></i><span
                                class="menu-item" data-i18n="Analytics">
                                {{ trans('main_trans.Sales_Report') }}</span></a>
                    </li>

                        </ul>

                    </li>
                @endif
            @endif

            @if (auth()->user()->hasAnyPermission([
                        'clients_view_all_clients',
                        'client_follow_up_add_notes_attachments_appointments_all',
                        'clients_edit_client_settings',
                    ]))
                @if (isset($settings['customers']) && $settings['customers'] === 'active')
                    <li class="nav-item">
                        <a href="#">
                            <i class="feather icon-user"></i> <!-- أيقونة العملاء -->
                            <span class="menu-title" data-i18n="Dashboard">{{ trans('main_trans.Customers') }}</span>
                        </a>
                        <ul class="menu-content">
                            @can('clients_view_all_clients')
                                <li><a href="{{ route('clients.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Customer_management') }}</span>
                                    </a>
                                </li>
                            @endcan

                            @can('clients_add_client')
                                <li><a href="{{ route('clients.create') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Add_a_new_customer') }}</span>
                                    </a>
                                </li>
                            @endcan

                            @can('client_follow_up_add_notes_attachments_appointments_all')
                                <li><a href="{{ route('appointments.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Appointments') }}</span>
                                    </a>
                                </li>
                            @endcan

                            <!--<li><a href="{{ route('clients.contacts') }}"><i class="feather icon-circle"></i><span-->
                            <!--            class="menu-item"-->
                            <!--            data-i18n="eCommerce">{{ trans('main_trans.Contact_list') }}</span>-->
                            <!--    </a>-->
                            <!--</li>-->

                            <li><a href="{{ route('clients.mang_client') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Customer_relationship_management') }}</span>
                                </a>
                            </li>
                            @can('clients_edit_client_settings')
                                <li><a href="{{ route('itinerary.list') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item" data-i18n="eCommerce"> خط السير</span>
                                    </a>
                                </li>
                            @endcan

                            @can('clients_edit_client_settings')
                                <li><a href="{{ route('visits.tracktaff') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item" data-i18n="eCommerce">تتبع الزيارات </span>
                                    </a>
                                </li>
                            @endcan

                            <!-- رابط تبريرات الزيارات للموظفين -->
                            @if (auth()->user()->role === 'employee')
                                <li><a href="{{ route('employee.visit-justifications.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item">تبريراتي</span>
                                    </a>
                                </li>
                            @endif

                            <!-- رابط إدارة تبريرات الزيارات للمديرين -->
                            @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                                <li><a href="{{ route('admin.visit-justifications.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item">إدارة تبريرات الزيارات</span>
                                    </a>
                                </li>
                            @endif

                            @can('clients_edit_client_settings')
                                <li><a href="{{ route('groups.group_client') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item" data-i18n="eCommerce">اعدادات المجموعات</span>
                                    </a>
                                </li>
                                
                            @endcan
                    @endif
 <li><a href=" {{ route('ClientReport.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item" data-i18n="eCommerce">تقرير العملاء</span>
                                    </a>
                                </li>
                               
                    @can('clients_edit_client_settings')
                        <li><a href="{{ route('clients.setting') }}"><i class="feather icon-circle"></i><span
                                    class="menu-item" data-i18n="eCommerce">{{ trans('main_trans.Client_settings') }}</span>
                            </a>
                        </li>
                    @endcan

            </ul>
            </li>
            @endif




            <!-- نقاط  البيع -->
            @if (auth()->user()->hasAnyPermission([
                        'points_sale_edit_product_prices',
                        'points_sale_add_discount',
                        'points_sale_open_sessions_all',
                        'points_sale_open_sessions_own',
                        'points_sale_close_sessions_all',
                        'points_sale_close_sessions_own',
                        'points_sale_view_all_sessions',
                        'points_sale_confirm_close_sessions_all',
                        'points_sale_confirm_close_sessions_own',
                        'points_sale_confirm_close_sessions_own',
                    ]))
                {{-- نقاط البيع --}}
                @if (isset($settings['pos']) && $settings['pos'] === 'active')
                    <li class="nav-item">
                        <a href="#">
                            <i class="feather icon-monitor"></i>
                            <span class="menu-title" data-i18n="POS">{{ trans('main_trans.Point_of_Sale') }}</span>
                        </a>
                        <ul class="menu-content">
                            <li>
                                <a href="">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item"
                                        data-i18n="Start Sale">{{ trans('main_trans.Start_Sale') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item"
                                        data-i18n="Sessions">{{ trans('main_trans.Sessions') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item"
                                        data-i18n="POS Reports">{{ trans('main_trans.POS_Reports') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item"
                                        data-i18n="POS Settings">{{ trans('main_trans.POS_Settings') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif



            <!-- إدارة الأقساط -->
            @can('salaries_loans_manage')
                @if (isset($settings['installments_management']) && $settings['installments_management'] === 'active')
                    <li class="nav-item"><a href="index.html">
                            <i class="feather icon-credit-card"></i> <!-- أيقونة الأقساط -->
                            <span class="menu-title"
                                data-i18n="Dashboard">{{ trans('main_trans.Installment_Management') }}</span>
                        </a>
                        <ul class="menu-content">

                            <li><a href="{{ route('installments.index') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item"
                                        data-i18n="Analytics">{{ trans('main_trans.Installment_agreements') }}</span></a>
                            </li>

                            @can('salaries_loans_manage')
                                <li><a href="{{ route('installments.agreement_installments') }}"><i
                                            class="feather icon-circle"></i><span class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Installments') }}</span></a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            @endcan

            <!-- إدارة المبيعات المستهدفة والعمولات -->
            @if (auth()->user()->hasAnyPermission([
                        'targeted_sales_commissions_manage_commission_rules',
                        'targeted_sales_commissions_view_all_sales_commissions',
                        'targeted_sales_commissions_manage_sales_periods',
                    ]))
                @if (isset($settings['target_sales_commissions']) && $settings['target_sales_commissions'] === 'active')
                    <li class="nav-item"><a href="index.html">
                            <i class="feather icon-pie-chart"></i> <!-- أيقونة المبيعات والعمولات -->
                            <span class="menu-title"
                                data-i18n="Dashboard">{{ trans('main_trans.Targeted_sales_and_commissions') }}</span>
                        </a>
                        <ul class="menu-content">
                            @can('targeted_sales_commissions_manage_commission_rules')
                                <li><a href="{{ route('CommissionRules.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Commission_rules') }}</span></a>
                                </li>
                            @endcan

                            @can('targeted_sales_commissions_view_all_sales_commissions')
                                <li><a href="{{ route('SalesCommission.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Sales_commissions') }}</span></a>
                                </li>
                            @endcan

                            @can('targeted_sales_commissions_manage_sales_periods')
                                <li><a href="{{ route('SalesPeriods.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Sales_periods') }}</span></a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            @endif



            <!-- أوامر التوريد -->

            @if (Auth::user()->hasAnyPermission(['supply_orders_view_all', 'supply_orders_add']))
                @if (isset($settings['work_orders']) && $settings['work_orders'] === 'active')
                    <li class="nav-item"><a href="{{ route('SupplyOrders.index') }}">
                            <i class="feather icon-truck"></i> <!-- أيقونة أوامر التوريد -->
                            <span class="menu-title"
                                data-i18n="Dashboard">{{ trans('main_trans.Supply_orders') }}</span>
                        </a>
                        <ul class="menu-content">
                            @can('supply_orders_view_all')
                                <li><a href="{{ route('SupplyOrders.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Supply_orders') }}</span></a>
                                </li>
                            @endcan

                            @can('supply_orders_add')
                                <li><a href="{{ route('SupplyOrders.create') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Add_a_job_order') }}</span></a>
                                </li>
                            @endcan

                            <li><a href="{{ route('SupplySittings.index') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Supply_Orders_Settings') }}</span></a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif




            <!-- نقاط الارصدة -->
            @if (auth()->user()->hasAnyPermission([
                        'points_credits_credit_recharge_manage',
                        'points_credits_credit_usage_manage',
                        'points_credits_packages_manage',
                        'points_credits_transactions_manage',
                    ]))
                @if (isset($settings['points_balances']) && $settings['points_balances'] === 'active')
                    <li class="nav-item">
                        <a href="#">
                            <i class="feather icon-layers"></i> <!-- أيقونة نقاط الارصدة -->
                            <span class="menu-title"
                                data-i18n="Dashboard">{{ trans('main_trans.Points_and_credits') }}</span>
                        </a>
                        <ul class="menu-content">
                            @can('points_credits_credit_recharge_manage')
                                <li><a href="{{ route('MangRechargeBalances.index') }}"><i
                                            class="feather icon-circle"></i><span class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Managing_balance_transfers') }}</span></a>
                                </li>
                            @endcan

                            @can('points_credits_credit_usage_manage')
                                <li><a href="{{ route('ManagingBalanceConsumption.index') }}"><i
                                            class="feather icon-circle"></i><span class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Managing_consumption_balances') }}</span></a>
                                </li>
                            @endcan

                            @can('points_credits_packages_manage')
                                <li><a href="{{ route('PackageManagement.index') }}"><i
                                            class="feather icon-circle"></i><span class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Package_management') }}</span>
                                    </a>
                                </li>
                            @endcan

                            @can('points_credits_credit_settings_manage')
                                <li><a href="{{ route('sitting.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Sittings') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            @endif

            <!-- نقاط الولاء -->
            @if (auth()->user()->hasAnyPermission([
                        'customer_loyalty_points_managing_customer_bases',
                        'customer_loyalty_points_redeem_loyalty_points',
                    ]))
                @if (isset($settings['customer_loyalty_points']) && $settings['customer_loyalty_points'] === 'active')
                    <li class="nav-item">
                        <a href="#">
                            <i class="feather icon-layers"></i> <!-- أيقونة نقاط الولاء -->
                            <span class="menu-title"
                                data-i18n="Dashboard">{{ trans('main_trans.Loyalty_points') }}</span>
                        </a>
                        <ul class="menu-content">
                            @can('customer_loyalty_points_managing_customer_bases')
                                <li><a href="{{ route('loyalty_points.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Customer_loyalty_rules') }}</span>
                                    </a>
                                </li>
                            @endcan

                            @can('customer_loyalty_points_redeem_loyalty_points')
                                <li><a href="{{ route('sittingLoyalty.sitting') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Sittings') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            @endif









            {{-- العضويات --}}
            @if (auth()->user()->hasAnyPermission(['membership_management', 'membership_setting_management']))
                @if (isset($settings['membership']) && $settings['membership'] === 'active')
                    <li class="nav-item">
                        <a href="index.html">
                            <i class="feather icon-users"></i>
                            <span class="menu-title" data-i18n="Dashboard">
                                {{ trans('main_trans.Memberships') }}
                            </span>
                        </a>
                        <ul class="menu-content">
                            @can('membership_management')
                                <li>
                                    <a href="{{ route('Memberships.index') }}">
                                        <i class="feather icon-circle"></i>
                                        <span class="menu-item" data-i18n="Analytics">
                                            {{ trans('main_trans.Membership_management') }}
                                        </span>
                                    </a>
                                </li>
                            @endcan


                            <li>
                                <a href="{{ route('Memberships.subscriptions') }}">
                                    <i class="feather icon-circle"></i>
                                    <span class="menu-item" data-i18n="eCommerce">
                                        {{ trans('main_trans.Subscription_management') }}
                                    </span>
                                </a>


                                {{-- <li><a href="{{ route('Memberships.subscriptions') }}"><i class="feather icon-circle"></i><span class="menu-item" --}}


                                {{-- <li><a href="{{route('Memberships.subscriptions.index')}}"><i class="feather icon-circle"></i><span class="menu-item"

                                    data-i18n="eCommerce">{{ trans('main_trans.Subscription_management') }}</span></a>

                        </li> --}}

                                {{-- <li>
                            <a href="{{ route('Memberships.subscriptions.index') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-item" data-i18n="eCommerce">
                                    {{ trans('main_trans.Subscription_management') }}
                                </span>
                            </a>
                        </li> --}}

                                @can('membership_setting_management')
                                <li>
                                    <a href="{{ route('SittingMemberships.index') }}">
                                        <i class="feather icon-circle"></i>
                                        <span class="menu-item" data-i18n="eCommerce">
                                            {{ trans('main_trans.Sittings') }}
                                        </span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            @endif

            {{-- حضور العملاء --}}
            @can('customer_attendance_display')
                @if (isset($settings['customer_attendance']) && $settings['customer_attendance'] === 'active')
                    <li class=" nav-item"><a href="index.html">
                            <i class="feather icon-user-check">
                            </i><span class="menu-title" data-i18n="Dashboard">
                                {{ trans('main_trans.Customer_attendance') }}</span>

                        </a>
                        <ul class="menu-content">
                            @can('customer_attendance_display')
                                <li><a href="{{ route('customer_attendance.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Customer_attendance_records') }}</span></a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            @endcan

            @if (Auth::user()->hasAnyPermission(['management_of_insurance_agents']))
                {{-- وكلاء التامين --}}
                @if (isset($settings['insurance']) && $settings['insurance'] === 'active')
                    <li class=" nav-item"><a href="index.html">
                            <i class="feather icon-users">
                            </i><span class="menu-title" data-i18n="Dashboard">
                                {{ trans('main_trans.Insurance_Agents') }}</span>

                        </a>
                        <ul class="menu-content">
                            <li><a href="{{ route('Insurance_Agents.index') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item"
                                        data-i18n="Analytics">{{ trans('main_trans.Insurance_Agents_Management') }}</span></a>
                            </li>

                            <li><a href="{{ route('Insurance_Agents.create') }}"><i
                                        class="feather icon-circle"></i><span class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Add_Insurance_Company') }}</span></a>
                            </li>
                        </ul>

                    </li>
                @endif
            @endif







            </ul>
        </div>
    </div>
