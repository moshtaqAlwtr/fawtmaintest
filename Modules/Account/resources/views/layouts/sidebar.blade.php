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














            {{-- المالية --}}
            @if (auth()->user()->hasAnyPermission([
                        'finance_view_all_expenses',
                        'finance_view_all_receipts',
                        'finance_view_own_cashboxes',
                        'finance_edit_default_cashbox',
                    ]))
                @if (isset($settings['finance']) && $settings['finance'] === 'active')
                    <li class=" nav-item {{ request()->is("$getLocal/finance/*") ? 'active open' : '' }}">
                        <a href="index.html">
                            <i class="feather icon-dollar-sign">
                            </i><span class="menu-title"
                                data-i18n="Dashboard">{{ trans('main_trans.Financial') }}</span>
                        </a>

                        <ul class="menu-content">
                            @can('finance_view_all_expenses')
                                <li><a href="{{ route('expenses.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/finance/expenses/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Expenses') }}</span></a>
                                </li>
                            @endcan

                            @can('finance_view_all_receipts')
                                <li><a href="{{ route('incomes.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/finance/incomes/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Receipts') }}</span></a>
                                </li>
                            @endcan

                            @can('finance_view_own_cashboxes')
                                <li><a href="{{ route('treasury.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item" data-i18n="eCommerce">
                                            {{ trans('main_trans.Cash_and_Bank_Accounts') }}</span></a>
                                </li>
                            @endcan

                            @can('finance_edit_default_cashbox')
                                <li><a href="{{ route('finance_settings.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/finance/finance_settings/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Financial_Settings') }}</span></a>
                                </li>
                            @endcan
                        </ul>

                    </li>
                @endif
            @endif
            {{-- الحسابات العامة --}}
            @if (auth()->user()->hasAnyPermission([
                        'g_a_d_r_view_all_journal_entries',
                        'g_a_d_r_add_edit_delete_all_journal_entries',
                        'g_a_d_r_manage_journal_entries',
                        'g_a_d_r_view_cost_centers',
                        'g_a_d_r_add_new_assets',
                    ]))
                @if (isset($settings['general_accounts_journal_entries']) && $settings['general_accounts_journal_entries'] === 'active')
                    <li class=" nav-item {{ request()->is("$getLocal/Accounts/*") ? 'active open' : '' }}"><a
                            href="index.html">
                            <i class="feather icon-pie-chart">
                            </i><span class="menu-title" data-i18n="Dashboard">
                                {{ trans('main_trans.General_Accounts') }}</span>
                        </a>

                        <ul class="menu-content">
                            @can('g_a_d_r_view_all_journal_entries')
                                <li><a href="{{ route('journal.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Journal_Entries') }}</span></a>
                                </li>
                            @endcan

                            @can('g_a_d_r_add_edit_delete_all_journal_entries')
                                <li><a href="{{ route('journal.create') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Add_Entry') }}</span></a>
                                </li>
                            @endcan

                            @can('g_a_d_r_manage_journal_entries')
                                <li><a href="{{ route('accounts_chart.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/Accounts/accounts_chart/*") ? 'active' : '' }}"></i><span
                                            class="menu-item" data-i18n="eCommerce">
                                            {{ trans('main_trans.Chart_of_Accounts') }}</span></a>
                                </li>
                            @endcan

                            @can('g_a_d_r_view_cost_centers')
                                <li><a href="{{ route('cost_centers.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/Accounts/cost_centers/*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Cost_Centers') }}</span></a>
                                </li>
                                <li><a href="{{ route('journal.generalLedger') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/finance/expenses/*") ? 'active' : '' }}"></i><span
                                            class="menu-item" data-i18n="Analytics">حساب الاستاذ</span></a>
                                </li>
                            @endcan

                            @can('g_a_d_r_add_new_assets')
                                <li><a href="{{ route('Assets.index') }}"><i class="feather icon-circle"></i><span
                                            class="menu-item" data-i18n="eCommerce">
                                            {{ trans('main_trans.Assets') }}</span></a>
                                </li>
                            @endcan

                            <li><a href="{{ route('accounts_settings.index') }}"><i
                                        class="feather icon-circle"></i><span class="menu-item"
                                        data-i18n="eCommerce">{{ trans('main_trans.General_Accounts_Settings') }}</span></a>
                            </li>
                            <li><a href="{{ route('GeneralAccountReports.index') }}"><i class="feather icon-circle"></i><span
                                class="menu-item"
                                data-i18n="eCommerce">{{ trans('main_trans.General_Accounts_Report') }}</span></a>
                    </li>


                        </ul>

                    </li>
                @endif
            @endif
            {{-- الشيكات --}}
            @if (auth()->user()->hasAnyPermission(['check_cycle_view_checkbook', 'check_cycle_manage_received_checks']))
                @if (isset($settings['cheque_cycle']) && $settings['cheque_cycle'] === 'active')
                    <li class=" nav-item {{ request()->is("$getLocal/cheques*") ? 'active open' : '' }}"><a
                            href="index.html">
                            <i class="feather icon-dollar-sign">
                            </i><span class="menu-title" data-i18n="Dashboard">
                                {{ trans('main_trans.Cheques_Cycle') }}</span>
                        </a>
                        <ul class="menu-content">
                            @can('check_cycle_view_checkbook')
                                <li><a href="{{ route('payable_cheques.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/cheques/payable_cheques*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="Analytics">{{ trans('main_trans.Paid_Cheques') }}</span></a>
                                </li>
                            @endcan

                            @can('check_cycle_manage_received_checks')
                                <li><a href="{{ route('received_cheques.index') }}"><i
                                            class="feather icon-circle {{ request()->is("$getLocal/cheques/received_cheques*") ? 'active' : '' }}"></i><span
                                            class="menu-item"
                                            data-i18n="eCommerce">{{ trans('main_trans.Received_Cheques') }}</span></a>
                                </li>


                    <li><a href="{{ route('checksReports.index') }}"><i class="feather icon-circle"></i><span class="menu-item"
                                data-i18n="eCommerce">{{ trans('main_trans.Cheques_Report') }}</span></a></li>

                    <li><a href=""><i class="feather icon-circle"></i><span class="menu-item"
                                data-i18n="eCommerce">{{ trans('main_trans.SMS_Report') }}</span></a>
                    </li>
                            @endcan
                        </ul>

                    </li>
                @endif
            @endif


        </ul>
    </div>
</div>
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // عناصر واجهة المستخدم
            const statusElement = document.getElementById('location-status');
            const lastUpdateElement = document.getElementById('last-update');
            const nearbyClientsElement = document.getElementById('nearby-clients');
            const startTrackingBtn = document.getElementById('start-tracking');
            const stopTrackingBtn = document.getElementById('stop-tracking');

            // متغيرات التتبع
            let watchId = null;
            let lastLocation = null;
            let isTracking = false;
            let trackingInterval = null;

            // ========== دوال الواجهة ========== //

            // تحديث حالة الواجهة
            function updateUI(status, message) {
                statusElement.textContent = message;
                statusElement.className = `alert alert-${status}`;
                lastUpdateElement.textContent = new Date().toLocaleTimeString();
            }

            // عرض العملاء القريبين
            function displayNearbyClients(count) {
                if (count > 0) {
                    nearbyClientsElement.innerHTML = `
                <div class="alert alert-info mt-3">
                    <i class="feather icon-users mr-2"></i>
                    يوجد ${count} عميل قريب من موقعك الحالي
                </div>
            `;
                } else {
                    nearbyClientsElement.innerHTML = '';
                }
            }

            // ========== دوال التتبع ========== //

            // إرسال بيانات الموقع إلى الخادم
            async function sendLocationToServer(position) {
                const {
                    latitude,
                    longitude,
                    accuracy
                } = position.coords;

                try {
                    const response = await fetch("", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            latitude,
                            longitude,
                            accuracy: accuracy || null
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        updateUI('success', 'تم تحديث موقعك بنجاح');
                        displayNearbyClients(data.nearby_clients || 0);
                        return true;
                    } else {
                        throw new Error(data.message || 'خطأ في الخادم');
                    }
                } catch (error) {
                    console.error('❌ خطأ في إرسال الموقع:', error);
                    updateUI('danger', `خطأ في تحديث الموقع: ${error.message}`);
                    return false;
                }
            }

            // معالجة أخطاء الموقع
            function handleGeolocationError(error) {
                let errorMessage;
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = "تم رفض إذن الوصول إلى الموقع. يرجى تفعيله في إعدادات المتصفح.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = "معلومات الموقع غير متوفرة حالياً.";
                        break;
                    case error.TIMEOUT:
                        errorMessage = "انتهت مهلة طلب الموقع. يرجى المحاولة مرة أخرى.";
                        break;
                    case error.UNKNOWN_ERROR:
                        errorMessage = "حدث خطأ غير معروف أثناء محاولة الحصول على الموقع.";
                        break;
                }

                updateUI('danger', errorMessage);
                if (isTracking) stopTracking();
            }

            // بدء تتبع الموقع
            function startTracking() {
                if (!navigator.geolocation) {
                    updateUI('danger', 'المتصفح لا يدعم ميزة تحديد الموقع');
                    return;
                }

                updateUI('info', 'جاري طلب إذن الموقع...');

                // طلب الموقع الحالي أولاً
                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                            const {
                                latitude,
                                longitude
                            } = position.coords;
                            lastLocation = {
                                latitude,
                                longitude
                            };

                            // إرسال الموقع الأولي
                            await sendLocationToServer(position);

                            // بدء التتبع المستمر
                            watchId = navigator.geolocation.watchPosition(
                                async (position) => {
                                        const {
                                            latitude,
                                            longitude
                                        } = position.coords;

                                        // التحقق من تغير الموقع بشكل كافي (أكثر من 10 أمتار)
                                        if (!lastLocation ||
                                            getDistance(latitude, longitude, lastLocation.latitude,
                                                lastLocation.longitude) > 10) {

                                            lastLocation = {
                                                latitude,
                                                longitude
                                            };
                                            await sendLocationToServer(position);
                                        }
                                    },
                                    (error) => {
                                        console.error('❌ خطأ في تتبع الموقع:', error);
                                        handleGeolocationError(error);
                                    }, {
                                        enableHighAccuracy: true,
                                        timeout: 10000,
                                        maximumAge: 0,
                                        distanceFilter: 10 // تحديث عند التحرك أكثر من 10 أمتار
                                    }
                            );

                            // بدء التتبع الدوري (كل دقيقة)
                            trackingInterval = setInterval(async () => {
                                if (lastLocation) {
                                    const fakePosition = {
                                        coords: {
                                            latitude: lastLocation.latitude,
                                            longitude: lastLocation.longitude,
                                            accuracy: 20
                                        }
                                    };
                                    await sendLocationToServer(fakePosition);
                                }
                            }, 60000);

                            isTracking = true;
                            updateUI('success', 'جاري تتبع موقعك...');
                            if (startTrackingBtn) startTrackingBtn.disabled = true;
                            if (stopTrackingBtn) stopTrackingBtn.disabled = false;
                        },
                        (error) => {
                            console.error('❌ خطأ في الحصول على الموقع:', error);
                            handleGeolocationError(error);
                        }, {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 0
                        }
                );
            }

            // إيقاف تتبع الموقع
            function stopTracking() {
                if (watchId) {
                    navigator.geolocation.clearWatch(watchId);
                    watchId = null;
                }

                if (trackingInterval) {
                    clearInterval(trackingInterval);
                    trackingInterval = null;
                }

                isTracking = false;
                updateUI('warning', 'تم إيقاف تتبع الموقع');
                if (startTrackingBtn) startTrackingBtn.disabled = false;
                if (stopTrackingBtn) stopTrackingBtn.disabled = true;
                nearbyClientsElement.innerHTML = '';
            }

            // حساب المسافة بين موقعين (بالمتر)
            function getDistance(lat1, lon1, lat2, lon2) {
                const R = 6371000; // نصف قطر الأرض بالمتر
                const φ1 = lat1 * Math.PI / 180;
                const φ2 = lat2 * Math.PI / 180;
                const Δφ = (lat2 - lat1) * Math.PI / 180;
                const Δλ = (lon2 - lon1) * Math.PI / 180;

                const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                    Math.cos(φ1) * Math.cos(φ2) *
                    Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                return R * c;
            }

            // ========== تهيئة الأحداث ========== //

            // أحداث الأزرار
            if (startTrackingBtn) {
                startTrackingBtn.addEventListener('click', startTracking);
            }

            if (stopTrackingBtn) {
                stopTrackingBtn.addEventListener('click', stopTracking);
            }

            // بدء التتبع تلقائياً عند تحميل الصفحة
            startTracking();

            // إيقاف التتبع عند إغلاق الصفحة
            window.addEventListener('beforeunload', function() {
                if (isTracking) {
                    // إرسال بيانات الإغلاق إلى الخادم إذا لزم الأمر
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const fakePosition = {
                                coords: {
                                    latitude: position.coords.latitude,
                                    longitude: position.coords.longitude,
                                    accuracy: position.coords.accuracy,
                                    isExit: true
                                }
                            };
                            sendLocationToServer(fakePosition);
                        },
                        () => {}, {
                            enableHighAccuracy: true
                        }
                    );
                    stopTracking();
                }
            });
        });
    </script>
@endsection
