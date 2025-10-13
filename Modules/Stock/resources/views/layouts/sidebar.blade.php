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



             @if (Auth::user()->hasAnyPermission([
                    'products_add_product',
                    'products_view_all_products',
                    'products_view_own_products',
                    'products_edit_delete_all_products',
                    'products_edit_delete_own_products',
                    'products_view_price_groups',
                    'products_add_edit_price_groups',
                    'products_delete_price_groups',
                ]))
                @if (isset($settings['inventory_management']) && $settings['inventory_management'] === 'active')
                    <li class=" nav-item {{ request()->is("$getLocal/stock/*") ? 'active open' : '' }}"><a
                            href="">
                            <i class="feather icon-box">
                            </i><span class="menu-title" data-i18n="Dashboard">
                                {{ trans('main_trans.Stock') }}</span>

                        </a>
                        <ul class="menu-content">
                            @can('products_view_all_products')
                                <li><a href="{{ route('products.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/stock/products/*") ? 'active' : '' }}"></i><span
                                            class="menu-item" data-i18n="Analytics">
                                            {{ trans('main_trans.products_management') }}</span></a>
                                </li>
                            @endcan

                            @can('inv_manage_inventory_permission_view')
                                <li><a href="{{ route('store_permits_management.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/stock/store_permits_management/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Store_permissions_management') }}</span></a>
                                </li>
                            @endcan


                            <li><a href="{{ route('products.traking') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.product_tracking') }}</span></a>
                            </li>

                            @can('products_view_price_groups')
                                <li><a href="{{ route('price_list.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/stock/price_list/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.price_lists') }}</span></a>
                                </li>
                            @endcan

                            {{-- @can('products_view_price_groups') --}}
                            <li><a href="{{ route('storehouse.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/stock/storehouse/*") ? 'active' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.warehouses') }}</span></a>
                            </li>
                            {{-- @endcan --}}

                            <li><a href="{{ route('inventory_management.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/stock/inventory_management/*") ? 'active' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.inventory_Management') }}</span></a>
                            </li>

                            <li><a href="{{ route('inventory_settings.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/stock/inventory_settings/*") ? 'active' : '' }}"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.inventory_settings') }}</span></a>
                            </li>

                            <li><a href="{{ route('product_settings.index') }}"><i
                                        class="feather icon-circle {{ request()->is("$getLocal/stock/product_settings/*") ? 'active' : '' }}"></i><span
                                        class="menu-item" data-i18n="eCommerce">
                                        {{ trans('main_trans.products_Settings') }}</span></a>
                            </li>

                        </ul>

                    </li>
                @endif
            @endif

            {{-- المشتريات --}}
            @if (auth()->user()->hasAnyPermission(['purchase_cycle_orders_manage_orders']))
                @if (isset($settings['purchase_cycle']) && $settings['purchase_cycle'] === 'active')
                    <li class=" nav-item"><a href="index.html">
                            <i class="fa fa-shopping-cart">
                            </i><span class="menu-title" data-i18n="Dashboard">
                                {{ trans('main_trans.Purchases') }}</span>
                        </a>
                        <ul class="menu-content">
                            @can('purchase_cycle_orders_manage_orders')
                                <li><a href="{{ route('OrdersPurchases.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item" data-i18n="Analytics">
                                            {{ trans('main_trans.Purchase_Orders') }}</span></a>
                                </li>
                            @endcan

                            <li><a href="{{ route('Quotations.index') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Quotation_Requests') }}

                                    </span></a>
                            </li>

                            <li><a href="{{ route('pricesPurchase.index') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item" data-i18n="eCommerce">
                                        {{ trans('main_trans.Purchase_Quotations') }}</span></a>
                            </li>
                            <li><a href="{{ route('OrdersRequests.index') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item" data-i18n="eCommerce">أوامر الشراء</span></a>
                            </li>
                            <li><a href="{{ route('invoicePurchases.index') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Purchase_Invoices') }}
                                    </span></a>
                            </li>
                            <li><a href="{{ route('ReturnsInvoice.index') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.Purchase_Returns') }}</span></a>
                            </li>
                            <li><a href="{{ route('CityNotices.index') }}"><i class="feather icon-circle"></i><span
                                        class="menu-item" data-i18n="eCommerce">
                                        {{ trans('main_trans.Creditor_notices') }}</span></a>
                            </li>
                            <li><a href="{{ route('SupplierManagement.index') }}"><i
                                        class="feather icon-circle"></i><span class="menu-item" data-i18n="eCommerce">
                                        {{ trans('main_trans.Supplier_Management') }}
                                    </span></a>
                            </li>
                            <li><a href="{{ route('PaymentSupplier.indexPurchase') }}"><i
                                        class="feather icon-circle"></i><span class="menu-item" data-i18n="eCommerce">
                                        {{ trans('main_trans.Supplier_Payments') }}

                                    </span></a>
                            </li>
                            <li><a href="{{ route('purchases.invoice_settings.index') }}"><i class="feather icon-circle"></i><span class="menu-item"
                                        data-i18n="eCommerce">
                                        {{ trans('main_trans.Purchase_Invoices_Settings') }}</span></a>
                            </li>
                            <li><a href=""><i class="feather icon-circle"></i><span class="menu-item"
                                        data-i18n="eCommerce">
                                        {{ trans('main_trans.Supplier_Settings') }}</span></a>

                            </li>
                        </ul>

                    </li>
                @endif
            @endif
   @can('online_store_content_management')
                @if (isset($settings['manufacturing']) && $settings['manufacturing'] === 'active')
                    <li class="nav-item {{ request()->is("$getLocal/Manufacturing/*") ? 'active open' : '' }}">
                        <a href="index.html">
                            <i class="feather icon-layers"></i>
                            <span class="menu-title" data-i18n="Dashboard">{{ trans('main_trans.Manufacturing') }}</span>
                        </a>
                        <ul class="menu-content">
                            <li>
                                <a href="{{ route('BOM.index') }}">
                                    <i
                                        class="feather icon-circle {{ request()->is("$getLocal/Manufacturing/BOM/*") ? 'active' : '' }}"></i>
                                    <span class="menu-item"
                                        data-i18n="Analytics">{{ trans('main_trans.Bill_of_Materials') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('manufacturing.orders.index') }}">
                                    <i
                                        class="feather icon-circle {{ request()->is("$getLocal/Manufacturing/Orders/*") ? 'active' : '' }}"></i>
                                    <span class="menu-item"
                                        data-i18n="Analytics">{{ trans('main_trans.Manufacturing_Orders') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('manufacturing.indirectcosts.index') }}">
                                    <i
                                        class="feather icon-circle {{ request()->is("$getLocal/Manufacturing/indirectcosts/*") ? 'active' : '' }}"></i>
                                    <span class="menu-item"
                                        data-i18n="Analytics">{{ trans('main_trans.Indirect_Costs') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('manufacturing.workstations.index') }}">
                                    <i
                                        class="feather icon-circle {{ request()->is("$getLocal/Manufacturing/Workstations/*") ? 'active' : '' }}"></i>
                                    <span class="menu-item"
                                        data-i18n="Analytics">{{ trans('main_trans.Workstations') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('Manufacturing.settings.index') }}">
                                    <i
                                        class="feather icon-circle {{ request()->is("$getLocal/Manufacturing/Settings/*") ? 'active' : '' }}"></i>
                                    <span class="menu-item" data-i18n="eCommerce">{{ trans('main_trans.Sittings') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endcan







            </ul>
        </div>
    </div>
