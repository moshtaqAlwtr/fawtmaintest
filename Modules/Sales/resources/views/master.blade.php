<html class="loading" dir="{{ App::getLocale() == 'ar' || App::getLocale() == 'ur' ? 'rtl' : 'ltr' }}">
<!-- BEGIN: Head-->

@if (App::getLocale() == 'ar')
    @include('layouts.head_rtl')
@elseif (App::getLocale() == 'ur')
    @include('layouts.head_rtl')
@else
    @include('layouts.head_ltr')
@endif

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0-alpha1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.10.1/css/jquery.fileupload.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.10.1/js/jquery.fileupload.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous" />

    <style>
        #DataTable_filter input {
            border-radius: 5px;
        }

        button span {
            font-family: 'Cairo', sans-serif !important;
        }

        .profile-picture-header {
            width: 40px;
            height: 40px;
            background-color: #7367F0;
            color: white;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        #location-permission-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            z-index: 9999;
            color: white;
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        #location-permission-content {
            max-width: 600px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            color: #333;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        #location-permission-overlay h3 {
            color: #7367F0;
            margin-bottom: 20px;
        }

        #location-permission-overlay p {
            margin-bottom: 20px;
            font-size: 16px;
        }

        #location-status {
            margin: 15px 0;
        }

        .tracking-status {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9998;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            transition: opacity 0.5s ease;
        }

        .tracking-active {
            background-color: #28a745;
            color: white;
        }

        .tracking-inactive {
            background-color: #dc3545;
            color: white;
        }

        .tracking-paused {
            background-color: #ffc107;
            color: #212529;
        }

        .fade-out {
            opacity: 0;
        }

        .loading-message {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 18px;
        }

        .loading-spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #7367F0;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .custom-toast {
            background-color: #28a745;
            color: white;
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            max-width: 350px;
            margin: 0 auto;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99999;
            transition: all 0.5s ease;
            opacity: 0;
            transform: translateY(20px);
        }

        .custom-toast i {
            font-size: 24px;
            margin-left: 10px;
        }

        .custom-toast-content {
            flex: 1;
        }

        .custom-toast-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .custom-toast-text {
            font-size: 14px;
        }

        .webview-instructions {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: right;
        }

        .manual-location-form {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            display: none;
        }

        .location-alternatives {
            display: none;
            margin-top: 20px;
        }

        .detection-status {
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            text-align: center;
        }

        .status-webview {
            background-color: #17a2b8;
            color: white;
        }

        .status-browser {
            background-color: #28a745;
            color: white;
        }

        .status-unknown {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>

<body class="vertical-layout vertical-menu-modern 2-columns navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- طبقة حجب التطبيق حتى يتم تفعيل الموقع -->
    <div id="location-permission-overlay">
        <div id="location-permission-content">
            <h3><i class="fas fa-map-marker-alt"></i> تفعيل خدمة الموقع</h3>

            <div id="environment-status" class="detection-status"></div>

            <p>يتطلب نظامنا تفعيل خدمة الموقع للوصول إلى موقعك.</p>

            <ul class="text-start mb-3">
                <li>سيتم تسجيل موقعك أثناء وقت العمل فقط</li>
                <li>لن يتم مشاركة موقعك مع أي جهات خارجية</li>
                <li>يمكنك إيقاف التتبع في أي وقت من الإعدادات</li>
            </ul>

            <div id="webview-instructions" class="webview-instructions" style="display: none;">
                <h5><i class="fas fa-mobile-alt"></i> تعليمات للتطبيق</h5>
                <p>يبدو أنك تستخدم التطبيق. لتفعيل الموقع:</p>
                <ol class="text-start">
                    <li>اذهب إلى إعدادات التطبيق في جهازك</li>
                    <li>ابحث عن "الأذونات" أو "Permissions"</li>
                    <li>تأكد من تفعيل إذن "الموقع" أو "Location"</li>
                    <li>أعد تشغيل التطبيق</li>
                </ol>
                <p class="text-danger"><strong>مهم:</strong> إذا لم تظهر رسالة طلب الإذن، فقد تحتاج لتفعيل الموقع من إعدادات التطبيق مباشرة.</p>
            </div>

            <div id="manual-location-form" class="manual-location-form">
                <h5><i class="fas fa-map-marker"></i> إدخال الموقع يدوياً</h5>
                <p>يمكنك إدخال موقعك يدوياً كحل بديل:</p>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">خط الطول (Longitude)</label>
                        <input type="number" class="form-control" id="manual-longitude" step="any" placeholder="مثال: 46.7219">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">خط العرض (Latitude)</label>
                        <input type="number" class="form-control" id="manual-latitude" step="any" placeholder="مثال: 24.6877">
                    </div>
                </div>
                <button id="use-manual-location" class="btn btn-warning mt-3">
                    <i class="fas fa-check"></i> استخدام هذا الموقع
                </button>
                <p class="small mt-2 text-muted">يمكنك الحصول على إحداثيات موقعك من خرائط جوجل</p>
            </div>

            <div class="form-check mb-3 text-start">
                <input class="form-check-input" type="checkbox" id="remember-choice">
                <label class="form-check-label" for="remember-choice">تذكر اختياري ولا تسألني مرة أخرى</label>
            </div>

            <div class="alert alert-warning" id="location-status">جاري فحص إمكانية الوصول للموقع...</div>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <button id="enable-location-btn" class="btn btn-primary">
                    <i class="fas fa-check-circle"></i> موافق وتفعيل
                </button>
                <button id="retry-location-btn" class="btn btn-info" style="display: none;">
                    <i class="fas fa-redo"></i> إعادة المحاولة
                </button>
                <button id="show-alternatives-btn" class="btn btn-secondary" style="display: none;">
                    <i class="fas fa-cog"></i> خيارات أخرى
                </button>
                <button id="open-in-browser-btn" class="btn btn-success" style="display: none;">
                    <i class="fas fa-external-link-alt"></i> فتح في المتصفح
                </button>
                <button id="cancel-location-btn" class="btn btn-danger" style="display: none;">
                    <i class="fas fa-times-circle"></i> رفض (تسجيل الخروج)
                </button>
            </div>

            <div id="location-alternatives" class="location-alternatives">
                <div class="alert alert-info">
                    <h6>خيارات بديلة:</h6>
                    <button id="manual-location-btn" class="btn btn-sm btn-warning me-2">
                        <i class="fas fa-edit"></i> إدخال الموقع يدوياً
                    </button>
                    <button id="skip-location-btn" class="btn btn-sm btn-secondary">
                        <i class="fas fa-skip-forward"></i> تخطي
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="tracking-status" class="tracking-status tracking-inactive" style="display: none;">
        <i class="fas fa-map-marker-alt"></i> <span id="tracking-status-text">جاري التتبع</span>
    </div>

    @include('sales::layouts.header')
    @include('sales::layouts.sidebar')

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('layouts.footer')

    <script src="{{ asset('app-assets/vendors/js/vendors.min.js')}}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js')}}"></script>
    <script src="{{ asset('app-assets/js/core/app-menu.js')}}"></script>
    <script src="{{ asset('app-assets/js/core/app.js')}}"></script>
    <script src="{{ asset('app-assets/js/scripts/components.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/select/form-select2.js')}}"></script>
    <script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.js')}}"></script>
    <script src="{{ asset('app-assets/js/scripts/pages/app-chat.js')}}"></script>
    <script src="https://cdn.tiny.cloud/1/61l8sbzpodhm6pvdpqdk0vlb1b7wazt4fbq47y376qg6uslq/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('.loading-message').fadeOut(500);

            $('#fawtra').DataTable({
                dom: 'Bfrtip',
                "pagingType": "full_numbers",
                buttons: [
                    {
                        "extend": 'excel',
                        "text": ' اكسيل',
                        'className': 'btn btn-success fa fa-plus'
                    },
                    {
                        "extend": 'print',
                        "text": ' طباعه',
                        'className': 'btn btn-warning fa fa-print'
                    },
                    {
                        "extend": 'copy',
                        "text": ' نسخ',
                        'className': 'btn btn-info fa fa-copy'
                    }
                ],
                initComplete: function() {
                    var btns = $('.dt-button');
                    btns.removeClass('dt-button');
                },
            });

            $('').selectize({
                sortField: 'text'
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('location-permission-overlay');
            const enableBtn = document.getElementById('enable-location-btn');
            const retryBtn = document.getElementById('retry-location-btn');
            const alternativesBtn = document.getElementById('show-alternatives-btn');
            const browserBtn = document.getElementById('open-in-browser-btn');
            const cancelBtn = document.getElementById('cancel-location-btn');
            const manualLocationBtn = document.getElementById('manual-location-btn');
            const skipLocationBtn = document.getElementById('skip-location-btn');
            const useManualLocationBtn = document.getElementById('use-manual-location');
            const statusElement = document.getElementById('location-status');
            const trackingStatusElement = document.getElementById('tracking-status');
            const trackingStatusText = document.getElementById('tracking-status-text');
            const rememberChoice = document.getElementById('remember-choice');
            const environmentStatus = document.getElementById('environment-status');
            const webviewInstructions = document.getElementById('webview-instructions');
            const manualLocationForm = document.getElementById('manual-location-form');
            const locationAlternatives = document.getElementById('location-alternatives');

            let lastLocation = null;
            let permissionDenied = false;
            let isWebView = false;
            let permissionAttempts = 0;
            let maxRetries = 3;

            function detectEnvironment() {
                const userAgent = navigator.userAgent.toLowerCase();
                const isAndroidWebView = userAgent.includes('wv') ||
                                       userAgent.includes('android') && !userAgent.includes('chrome') ||
                                       userAgent.includes('webview');
                const isIOSWebView = userAgent.includes('mobile/') && !userAgent.includes('safari');
                const isInApp = window.navigator.standalone === true ||
                               window.matchMedia('(display-mode: standalone)').matches;

                isWebView = isAndroidWebView || isIOSWebView || isInApp;

                if (isWebView) {
                    environmentStatus.textContent = 'تم اكتشاف: تطبيق WebView';
                    environmentStatus.className = 'detection-status status-webview';
                    webviewInstructions.style.display = 'block';
                } else {
                    environmentStatus.textContent = 'تم اكتشاف: متصفح عادي';
                    environmentStatus.className = 'detection-status status-browser';
                }

                return isWebView;
            }

            detectEnvironment();
            checkLocationPermission();

            function showToastNotification(title, text, type) {
                const toast = document.createElement('div');
                toast.className = `custom-toast toast-${type}`;
                toast.innerHTML = `
                    <div class="custom-toast-content">
                        <div class="custom-toast-title">${title}</div>
                        <div class="custom-toast-text">${text}</div>
                    </div>
                    <i class="fas fa-check-circle"></i>
                `;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateY(0)';
                }, 100);

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        if (document.body.contains(toast)) {
                            document.body.removeChild(toast);
                        }
                    }, 500);
                }, 5000);
            }

            function checkLocationPermission() {
                if (localStorage.getItem('locationPermission') === 'denied') {
                    showPermissionDenied();
                    return;
                }

                if (localStorage.getItem('locationPermission') === 'manual') {
                    const manualLocation = JSON.parse(localStorage.getItem('manualLocation'));
                    if (manualLocation) {
                        lastLocation = manualLocation;
                        updateTrackingStatus('active', 'الموقع محفوظ');
                        setTimeout(() => fadeOutTrackingStatus(), 3000);
                        return;
                    }
                }

                if (localStorage.getItem('locationPermission') === 'granted') {
                    getLocation();
                    return;
                }

                if (localStorage.getItem('locationPermission') === 'skipped') {
                    updateTrackingStatus('inactive', 'تم تخطي الموقع');
                    setTimeout(() => fadeOutTrackingStatus(), 3000);
                    return;
                }

                showPermissionRequest();
            }

            function showPermissionRequest() {
                if (permissionDenied) return;

                overlay.style.display = 'flex';
                statusElement.textContent = 'جاري فحص إمكانية الوصول للموقع...';
                statusElement.className = 'alert alert-info';

                retryBtn.style.display = 'none';
                alternativesBtn.style.display = 'none';
                browserBtn.style.display = 'none';
                cancelBtn.style.display = 'none';
                locationAlternatives.style.display = 'none';

                enableBtn.style.display = 'block';
            }

            function showPermissionDenied() {
                overlay.style.display = 'flex';
                statusElement.textContent = 'تم رفض إذن الوصول إلى الموقع. يرجى تفعيله في إعدادات المتصفح أو التطبيق.';
                statusElement.className = 'alert alert-danger';

                enableBtn.style.display = 'none';
                retryBtn.style.display = 'block';
                alternativesBtn.style.display = 'block';
                if (isWebView) {
                    browserBtn.style.display = 'block';
                }
                cancelBtn.style.display = 'block';
                permissionDenied = true;

                updateTrackingStatus('inactive', 'إذن الموقع مرفوض');
            }

            function requestLocationPermission() {
                permissionAttempts++;
                statusElement.textContent = `جاري طلب إذن الموقع... (المحاولة ${permissionAttempts})`;
                statusElement.className = 'alert alert-info';

                if (!navigator.geolocation) {
                    statusElement.textContent = 'المتصفح لا يدعم ميزة تحديد الموقع';
                    statusElement.className = 'alert alert-danger';
                    showPermissionDenied();
                    return;
                }

                const options = isWebView ? {
                    enableHighAccuracy: false,
                    timeout: 15000,
                    maximumAge: 300000
                } : {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                };

                navigator.geolocation.getCurrentPosition(
                    locationPermissionGranted,
                    locationPermissionDenied,
                    options
                );
            }

            function locationPermissionGranted(position) {
                overlay.style.display = 'none';
                permissionAttempts = 0;

                if (rememberChoice.checked) {
                    localStorage.setItem('locationPermission', 'granted');
                }

                lastLocation = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };

                console.log('تم الحصول على الموقع:', lastLocation);
                updateTrackingStatus('active', 'تم الحصول على الموقع');
                showToastNotification('تم تفعيل الموقع', 'تم الحصول على موقعك بنجاح', 'success');

                setTimeout(() => fadeOutTrackingStatus(), 5000);
            }

            function locationPermissionDenied(error) {
                let errorMessage = 'حدث خطأ غير معروف';
                let showAlternatives = false;

                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        if (isWebView) {
                            errorMessage = 'تم رفض إذن الوصول إلى الموقع. في التطبيق، تحتاج لتفعيل إذن الموقع من إعدادات جهازك.';
                        } else {
                            errorMessage = 'تم رفض إذن الوصول إلى الموقع. يرجى تفعيله في إعدادات المتصفح.';
                        }
                        showAlternatives = true;
                        if (rememberChoice.checked) {
                            localStorage.setItem('locationPermission', 'denied');
                        }
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'معلومات الموقع غير متوفرة حالياً. تأكد من تفعيل GPS.';
                        showAlternatives = true;
                        break;
                    case error.TIMEOUT:
                        if (permissionAttempts < maxRetries) {
                            errorMessage = `انتهت مهلة طلب الموقع. جاري إعادة المحاولة... (${permissionAttempts}/${maxRetries})`;
                            setTimeout(() => {
                                requestLocationPermission();
                            }, 2000);
                            return;
                        } else {
                            errorMessage = 'انتهت مهلة طلب الموقع بعد عدة محاولات. يمكنك استخدام الخيارات البديلة.';
                            showAlternatives = true;
                        }
                        break;
                }

                statusElement.textContent = errorMessage;
                statusElement.className = 'alert alert-danger';

                if (showAlternatives) {
                    showPermissionDenied();
                } else {
                    retryBtn.style.display = 'block';
                }

                updateTrackingStatus('inactive', 'خطأ في الموقع');
            }

            function getLocation() {
                if (!navigator.geolocation) return;

                const options = isWebView ? {
                    enableHighAccuracy: false,
                    timeout: 15000,
                    maximumAge: 300000
                } : {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                };

                navigator.geolocation.getCurrentPosition(
                    position => {
                        lastLocation = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        };
                        console.log('الموقع الحالي:', lastLocation);
                        updateTrackingStatus('active', 'تم الحصول على الموقع');
                        setTimeout(() => fadeOutTrackingStatus(), 3000);
                    },
                    error => {
                        console.error('خطأ في الحصول على الموقع:', error);
                        updateTrackingStatus('inactive', 'خطأ في الموقع');
                    },
                    options
                );
            }

            function updateTrackingStatus(status, text) {
                trackingStatusElement.style.display = 'block';
                trackingStatusElement.classList.remove('fade-out');
                trackingStatusText.textContent = text;

                trackingStatusElement.classList.remove('tracking-active', 'tracking-inactive', 'tracking-paused');

                if (status === 'active') {
                    trackingStatusElement.classList.add('tracking-active');
                } else if (status === 'paused') {
                    trackingStatusElement.classList.add('tracking-paused');
                } else {
                    trackingStatusElement.classList.add('tracking-inactive');
                }
            }

            function fadeOutTrackingStatus() {
                if (trackingStatusElement.style.display !== 'none') {
                    trackingStatusElement.classList.add('fade-out');
                    setTimeout(() => {
                        trackingStatusElement.style.display = 'none';
                    }, 500);
                }
            }

            // معالجة الأزرار
            enableBtn.addEventListener('click', requestLocationPermission);

            retryBtn.addEventListener('click', () => {
                permissionAttempts = 0;
                showPermissionRequest();
                requestLocationPermission();
            });

            alternativesBtn.addEventListener('click', () => {
                locationAlternatives.style.display = locationAlternatives.style.display === 'none' ? 'block' : 'none';
            });

            browserBtn.addEventListener('click', () => {
                const currentUrl = window.location.href;
                window.open(currentUrl, '_blank');
                showToastNotification('تم فتح المتصفح', 'يتم الآن فتح التطبيق في متصفح منفصل', 'info');
            });

            cancelBtn.addEventListener('click', () => {
                window.location.href = "{{ route('logout') }}";
            });

            manualLocationBtn.addEventListener('click', () => {
                manualLocationForm.style.display = 'block';
                locationAlternatives.style.display = 'none';
            });

            skipLocationBtn.addEventListener('click', () => {
                overlay.style.display = 'none';
                updateTrackingStatus('inactive', 'تم تخطي الموقع');
                if (rememberChoice.checked) {
                    localStorage.setItem('locationPermission', 'skipped');
                }

                setTimeout(() => {
                    fadeOutTrackingStatus();
                }, 3000);
            });

            useManualLocationBtn.addEventListener('click', () => {
                const longitude = parseFloat(document.getElementById('manual-longitude').value);
                const latitude = parseFloat(document.getElementById('manual-latitude').value);

                if (!longitude || !latitude) {
                    Swal.fire({
                        icon: 'error',
                        title: 'بيانات ناقصة',
                        text: 'يرجى إدخال خط الطول والعرض بشكل صحيح',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                if (latitude < -90 || latitude > 90 || longitude < -180 || longitude > 180) {
                    Swal.fire({
                        icon: 'error',
                        title: 'إحداثيات غير صحيحة',
                        text: 'يرجى التأكد من صحة الإحداثيات المدخلة',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                const manualLocation = { latitude, longitude };

                if (rememberChoice.checked) {
                    localStorage.setItem('locationPermission', 'manual');
                    localStorage.setItem('manualLocation', JSON.stringify(manualLocation));
                }

                lastLocation = manualLocation;
                overlay.style.display = 'none';

                console.log('تم حفظ الموقع اليدوي:', lastLocation);
                updateTrackingStatus('active', 'تم حفظ الموقع');
                showToastNotification('تم حفظ الموقع', 'سيتم استخدام الموقع المحدد', 'success');

                setTimeout(() => {
                    fadeOutTrackingStatus();
                }, 5000);
            });
        });
    </script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
