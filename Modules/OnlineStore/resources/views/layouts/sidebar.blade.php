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

 @can('online_store_content_management')
                @if (isset($settings['ecommerce']) && $settings['ecommerce'] === 'active')
                    <li class=" nav-item {{ request()->is("$getLocal/online_store/*") ? 'active open' : '' }}"><a
                            href="index.html">
                            <i class="feather icon-shopping-cart">
                            </i><span class="menu-title" data-i18n="Dashboard">{{ trans('main_trans.Online_store') }}</span>
                        </a>
                        <ul class="menu-content">
                            <li><a href="{{ route('content_management.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/online_store/content-management/*") ? 'active' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="Analytics">{{ trans('main_trans.Content_management') }}</span></a>
                            </li>
                            <li><a href="{{ route('store_settings.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/online_store/store_settings/*") ? 'active' : '' }}"></i><span
                                        class="menu-item" data-i18n="eCommerce">{{ trans('main_trans.Sittings') }}</span></a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endcan


        </ul>
    </div>
</div>
