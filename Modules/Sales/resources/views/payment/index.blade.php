@extends('sales::master')

@section('title')
    مدفوعات العملاء
@stop

@section('css')
    <style>
        .form-control {
            margin-bottom: 10px;
        }

        #loading-indicator {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 0.375rem;
        }

        .spinner-border {
            width: 2rem;
            height: 2rem;
        }

        @media (max-width: 768px) {
            .content-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .content-header-title {
                font-size: 1.5rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .card {
                margin: 10px;
                padding: 10px;
            }

            .table {
                font-size: 0.8rem;
                width: 100%;
                overflow-x: auto;
            }

            .table th,
            .table td {
                white-space: nowrap;
            }

            .form-check {
                margin-bottom: 10px;
            }

            .form-control {
                width: 100%;
            }

            .dropdown-menu {
                min-width: 200px;
            }
        }

        @media (max-width: 480px) {

            .table th,
            .table td {
                font-size: 0.7rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">ادارة مدفوعات العملاء</h2>
                    <div class="breadcrumb-wrapper col-12">
                 <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسيه</a></li>
                        <li class="breadcrumb-item active">عرض</li>
                    </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="content-body">
        <div class="container-fluid">
            <!-- شريط الأدوات العلوي -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <!-- معلومات الترقيم -->
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item mx-2">
                                        <span class="text-muted pagination-info">صفحة 1 من 1</span>
                                    </li>
                                </ul>
                            </nav>
                            <!-- عداد النتائج -->
                            <span class="text-muted mx-2 results-info">0 نتيجة</span>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <!-- زر المواعيد -->
                            <a href="{{ route('appointments.index') }}"
                                class="btn btn-outline-primary btn-sm d-flex align-items-center rounded-pill px-3">
                                <i class="fas fa-calendar-alt me-1"></i>المواعيد
                            </a>

                            <!-- زر استيراد -->
                            <button class="btn btn-outline-primary btn-sm d-flex align-items-center rounded-pill px-3">
                                <i class="fas fa-cloud-upload-alt me-1"></i>استيراد
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- نموذج البحث -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center p-2">
                    <div class="d-flex gap-2">
                        <span class="hide-button-text">بحث وتصفية</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-outline-secondary btn-sm" onclick="toggleSearchFields(this)">
                            <i class="fa fa-times"></i>
                            <span class="hide-button-text">اخفاء</span>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse"
                            data-bs-target="#advancedSearchForm" onclick="toggleSearchText(this)">
                            <i class="fa fa-filter"></i>
                            <span class="button-text">متقدم</span>
                        </button>
                        <button type="button" id="resetSearch" class="btn btn-outline-warning btn-sm">
                            <i class="fa fa-refresh"></i>
                            إعادة تعيين
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <form id="searchForm" class="form" method="GET">
                        @csrf
                        <div class="row g-3" id="basicSearchFields">
                            <!-- 1. رقم الفاتورة -->
                            <div class="col-md-4">
                                <label for="invoice_number" class="sr-only">رقم الفاتورة</label>
                                <input type="text" id="invoice_number" class="form-control" placeholder="رقم الفاتورة"
                                    name="invoice_number" value="{{ request('invoice_number') }}">
                            </div>

                            <!-- 2. رقم عملية الدفع -->
                            <div class="col-md-4">
                                <label for="payment_number" class="sr-only">رقم عملية الدفع</label>
                                <input type="text" id="payment_number" class="form-control" placeholder="رقم عملية الدفع"
                                    name="payment_number" value="{{ request('payment_number') }}">
                            </div>

                            <!-- 3. العميل -->
                            <div class="col-md-4">
                                <select name="client_id" class="form-control select2" id="client_id">
                                    <option value="">اختر العميل</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->trade_name }}-{{ $client->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- البحث المتقدم -->
                        <div class="collapse" id="advancedSearchForm">
                            <div class="row g-3 mt-2">
                                <!-- 4. حالة الدفع -->
                                <div class="col-md-4">
                                    <select name="payment_status" class="form-control" id="payment_status">
                                        <option value="">حالة الدفع</option>
                                        <option value="1" {{ request('payment_status') == '1' ? 'selected' : '' }}>
                                            مدفوعة</option>
                                        <option value="0" {{ request('payment_status') == '0' ? 'selected' : '' }}>غير
                                            مدفوعة</option>
                                    </select>
                                </div>

                                <!-- 5. التخصيص -->
                                <div class="col-md-2">
                                    <select name="customization" class="form-control" id="customization">
                                        <option value="">تخصيص</option>
                                        <option value="1" {{ request('customization') == '1' ? 'selected' : '' }}>
                                            شهريًا</option>
                                        <option value="0" {{ request('customization') == '0' ? 'selected' : '' }}>
                                            أسبوعيًا</option>
                                        <option value="2" {{ request('customization') == '2' ? 'selected' : '' }}>
                                            يوميًا</option>
                                    </select>
                                </div>

                                <!-- 6. من (التاريخ) -->
                                <div class="col-md-2">
                                    <input type="date" id="from_date" class="form-control" placeholder="من"
                                        name="from_date" value="{{ request('from_date') }}">
                                </div>

                                <!-- 7. إلى (التاريخ) -->
                                <div class="col-md-2">
                                    <input type="date" id="to_date" class="form-control" placeholder="إلى"
                                        name="to_date" value="{{ request('to_date') }}">
                                </div>

                                <!-- 8. رقم التعريفي -->
                                <div class="col-md-4">
                                    <input type="text" id="identifier" class="form-control"
                                        placeholder="رقم التعريفي" name="identifier"
                                        value="{{ request('identifier') }}">
                                </div>

                                <!-- 9. رقم معرف التحويل -->
                                <div class="col-md-4">
                                    <input type="text" id="transfer_id" class="form-control"
                                        placeholder="رقم معرف التحويل" name="transfer_id"
                                        value="{{ request('transfer_id') }}">
                                </div>

                                <!-- 10. الإجمالي أكبر من -->
                                <div class="col-md-4">
                                    <input type="text" id="total_greater_than" class="form-control"
                                        placeholder="الاجمالي اكبر من" name="total_greater_than"
                                        value="{{ request('total_greater_than') }}">
                                </div>

                                <!-- 11. الإجمالي أصغر من -->
                                <div class="col-md-4">
                                    <input type="text" id="total_less_than" class="form-control"
                                        placeholder="الاجمالي اصغر من" name="total_less_than"
                                        value="{{ request('total_less_than') }}">
                                </div>

                                <!-- 12. حقل مخصص -->
                                <div class="col-md-4">
                                    <input type="text" id="custom_field" class="form-control" placeholder="حقل مخصص"
                                        name="custom_field" value="{{ request('custom_field') }}">
                                </div>

                                <!-- 13. منشأ الفاتورة -->
                                <div class="col-md-4">
                                    <select name="invoice_origin" class="form-control select2" id="invoice_origin">
                                        <option value="">اختر الموظف (منشئ الفاتورة)</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ request('invoice_origin') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- 14. تم التحصيل بواسطة -->
                                <div class="col-md-4">
                                    <select name="collected_by" class="form-control select2" id="collected_by">
                                        <option value="">تم التحصيل بواسطة</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ request('collected_by') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- 15. الخزينة -->
                                <div class="col-md-4">
                                    <select name="treasury_id" class="form-control select2" id="treasury_id">
                                        <option value="">اختر الخزينة</option>
                                        @foreach ($treasuries as $treasury)
                                            <option value="{{ $treasury->id }}"
                                                {{ request('treasury_id') == $treasury->id ? 'selected' : '' }}>
                                                {{ $treasury->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- 16. طريقة الدفع -->
                                <div class="col-md-4">
                                    <select name="payment_method" class="form-control" id="payment_method">
                                        <option value="">طريقة الدفع</option>
                                        @foreach ($paymentMethods as $method)
                                            <option value="{{ $method->id }}"
                                                {{ request('payment_method') == $method->id ? 'selected' : '' }}>
                                                {{ $method->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- 17. العميل (إضافي) -->

                            </div>
                        </div>

                        <!-- الأزرار -->
                        <div class="form-actions mt-2">
                            <button type="submit" class="btn btn-primary">بحث</button>
                            <a class="btn btn-outline-secondary" data-toggle="collapse" href="#advancedSearchForm"
                                role="button">
                                <i class="bi bi-sliders"></i> بحث متقدم
                            </a>
                            <button type="reset" class="btn btn-outline-warning">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- جدول النتائج -->
            <div class="card">
                <!-- الترويسة -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-sm btn-outline-primary">الكل</button>
                        <button class="btn btn-sm btn-outline-success">متأخر</button>
                        <button class="btn btn-sm btn-outline-danger">مستحقة الدفع</button>
                        <button class="btn btn-sm btn-outline-danger">غير مدفوع</button>
                        <button class="btn btn-sm btn-outline-secondary">مسودة</button>
                        <button class="btn btn-sm btn-outline-success">مدفوع بزيادة</button>
                    </div>
                </div>

                <!-- بداية الصف -->
                <div class="card-body">
                    <div id="results-container">
                        <!-- سيتم تحميل الجدول هنا عبر AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('assets/js/search.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // تحميل البيانات الأولية
            loadData();

            // البحث عند إرسال النموذج
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                loadData();
            });

            // البحث الفوري عند تغيير قيم المدخلات
            $('#searchForm input, #searchForm select').on('change input', function() {
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(function() {
                    loadData();
                }, 500);
            });

            // إعادة تعيين الفلاتر
            $('#resetSearch, button[type="reset"]').on('click', function() {
                $('#searchForm')[0].reset();
                loadData();
            });

            // التعامل مع الترقيم
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                if (url) {
                    let page = new URL(url).searchParams.get('page');
                    loadData(page);
                }
            });

            // دالة تحميل البيانات
            function loadData(page = 1) {
                showLoading();

                let formData = $('#searchForm').serialize();
                if (page > 1) {
                    formData += '&page=' + page;
                }

                $.ajax({
                    url: '{{ route('paymentsClient.index') }}',
                    method: 'GET',
                    data: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#results-container').html(response.data);
                            updatePaginationInfo(response);
                            initializeEvents();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('خطأ في تحميل البيانات:', error);
                        $('#results-container').html(
                            '<div class="alert alert-danger text-center">' +
                            '<p class="mb-0">حدث خطأ في تحميل البيانات. يرجى المحاولة مرة أخرى.</p>' +
                            '</div>'
                        );
                    },
                    complete: function() {
                        hideLoading();
                    }
                });
            }

            function showLoading() {
                $('#results-container').css('opacity', '0.6');
                if ($('#loading-indicator').length === 0) {
                    $('#results-container').prepend(`
                <div id="loading-indicator" class="text-center p-3">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            `);
                }
            }

            function hideLoading() {
                $('#loading-indicator').remove();
                $('#results-container').css('opacity', '1');
            }

            function updatePaginationInfo(response) {
                $('.pagination-info').text(`صفحة ${response.current_page} من ${response.last_page}`);
                if (response.total > 0) {
                    $('.results-info').text(`${response.from}-${response.to} من ${response.total}`);
                } else {
                    $('.results-info').text('لا توجد نتائج');
                }
            }

            function initializeEvents() {
                // تحديد الكل
                $('#selectAll').off('change').on('change', function() {
                    $('.payment-checkbox').prop('checked', $(this).prop('checked'));
                });

                $('.payment-checkbox').off('change').on('change', function() {
                    let totalCheckboxes = $('.payment-checkbox').length;
                    let checkedCheckboxes = $('.payment-checkbox:checked').length;
                    $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
                });

                // أحداث الحذف
                $('.delete-payment').off('click').on('click', function(e) {
                    e.preventDefault();
                    const paymentId = $(this).data('id');

                    if (confirm('هل أنت متأكد من حذف هذه العملية؟')) {
                        deletePayment(paymentId);
                    }
                });
            }

            function deletePayment(paymentId) {
                const row = $(`.delete-payment[data-id="${paymentId}"]`).closest('tr');
                row.css('opacity', '0.5');

                $.ajax({
                    url: `/payments-client/${paymentId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف',
                            text: 'تم حذف العملية بنجاح',
                            confirmButtonText: 'حسناً'
                        });
                        loadData();
                    },
                    error: function() {
                        row.css('opacity', '1');
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: 'حدث خطأ أثناء حذف العملية',
                            confirmButtonText: 'حسناً'
                        });
                    }
                });
            }

            initializeEvents();
        });

        // تعريف المتغيرات العامة
        window.routes = {
            payments: {
                cancel: '{{ route('paymentsClient.cancel', ':id') }}'
            }
        };
        window.csrfToken = '{{ csrf_token() }}';

        function showPermissionError() {
            Swal.fire({
                icon: 'error',
                title: 'غير مصرح',
                text: 'ليس لديك صلاحية لإلغاء الدفع',
                confirmButtonText: 'حسناً'
            });
        }

        function confirmCancelPayment(id) {
            Swal.fire({
                title: 'هل أنت متأكد من إلغاء عملية الدفع؟',
                text: 'سيتم استعادة جميع الأرصدة كما كانت قبل عملية الدفع',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم، إلغاء العملية',
                cancelButtonText: 'لا، تراجع',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        const cancelUrl = window.routes.payments.cancel.replace(':id', id);

                        $.ajax({
                            url: cancelUrl,
                            method: 'POST',
                            data: {
                                _token: window.csrfToken
                            },
                            success: function(response) {
                                resolve(response);
                            },
                            error: function(xhr) {
                                let errorMsg = 'حدث خطأ أثناء الإلغاء';

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                } else if (xhr.status === 404) {
                                    errorMsg = 'عملية الدفع غير موجودة';
                                } else if (xhr.status === 403) {
                                    errorMsg = 'ليس لديك صلاحية لهذا الإجراء';
                                } else if (xhr.status === 419) {
                                    errorMsg = 'انتهت صلاحية الجلسة - يرجى تحديث الصفحة';
                                }

                                Swal.showValidationMessage(errorMsg);
                                resolve(false);
                            }
                        });
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    Swal.fire({
                        title: 'تم الإلغاء بنجاح',
                        text: 'تم إلغاء عملية الدفع واستعادة الأرصدة',
                        icon: 'success',
                        confirmButtonText: 'حسناً'
                    }).then(() => {
                        loadData();
                    });
                }
            });
        }

        // دوال التحكم في البحث المتقدم
        function toggleSearchText(button) {
            const buttonText = button.querySelector('.button-text');
            if (buttonText.textContent.trim() === 'متقدم') {
                buttonText.textContent = 'بحث بسيط';
            } else {
                buttonText.textContent = 'متقدم';
            }
        }

        function toggleSearchFields(button) {
            const searchForm = document.getElementById('searchForm');
            const buttonText = button.querySelector('.hide-button-text');
            const icon = button.querySelector('i');

            if (buttonText.textContent === 'اخفاء') {
                searchForm.style.display = 'none';
                buttonText.textContent = 'إظهار';
                icon.classList.remove('fa-times');
                icon.classList.add('fa-eye');
            } else {
                searchForm.style.display = 'block';
                buttonText.textContent = 'اخفاء';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-times');
            }
        }
    </script>
@endsection
