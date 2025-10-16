@extends('sales::master')

@section('title')
    الفواتير الدورية
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

        .table {
            font-size: 0.8rem;
            width: 100%;
            overflow-x: auto;
        }

        .table th,
        .table td {
            white-space: nowrap;
        }
    }
</style>
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">ادارة الفواتير الدورية</h2>
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

<div class="content-body">
    <div class="container-fluid">
        <!-- شريط الأدوات العلوي -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item mx-2">
                                    <span class="text-muted pagination-info">صفحة 1 من 1</span>
                                </li>
                            </ul>
                        </nav>
                        <span class="text-muted mx-2 results-info">0 نتيجة</span>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between">
                        <a href="{{ route('periodic_invoices.create') }}" class="btn btn-success btn-sm flex-fill me-1 mb-1">
                            <i class="fas fa-plus-circle me-1"></i>اشتراك جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقة التعليمات -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>تعليمات</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="card-body">
                <p>
                    تعرض هذه الصفحة جميع الفواتير الدورية والاشتراكات الجارية. يمكنك عرض كل فاتورة عبر الإنترنت وتعديلها أو
                    حذفها من هذه الصفحة.
                    يمكنك النقر على زر المزيد من الخيارات لتصفية قائمة الفواتير حسب الحاجة - بواسطة رقم الفاتورة، التاريخ،
                    الحالة، تاريخ الاستحقاق، إلخ.
                </p>
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
                <form class="form" id="searchForm">
                    @csrf
                    <div class="row g-3">
                        <!-- الحقول الأساسية -->
                        <div class="col-md-4">
                            <input type="text" name="name_subscription" class="form-control"
                                placeholder="اسم الاشتراك">
                        </div>

                        <div class="col-md-4">
                            <select name="client_id" class="form-control select2">
                                <option value="">أي العميل</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">
                                        {{ $client->trade_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <select name="repeat_type" class="form-control select2">
                                <option value="">نوع التكرار</option>
                                <option value="1">شهرياً</option>
                                <option value="0">أسبوعياً</option>
                                <option value="2">يومياً</option>
                            </select>
                        </div>
                    </div>

                    <!-- البحث المتقدم -->
                    <div class="collapse" id="advancedSearchForm">
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <input type="date" name="from_date" class="form-control"
                                    placeholder="التاريخ من">
                            </div>

                            <div class="col-md-3">
                                <input type="date" name="to_date" class="form-control"
                                    placeholder="التاريخ إلى">
                            </div>

                            <div class="col-md-3">
                                <input type="number" name="min_total" class="form-control"
                                    placeholder="الإجمالي أكبر من">
                            </div>

                            <div class="col-md-3">
                                <input type="number" name="max_total" class="form-control"
                                    placeholder="الإجمالي أصغر من">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions mt-2">
                        <button type="submit" class="btn btn-primary">بحث</button>
                        <button type="button" id="resetSearch" class="btn btn-outline-warning">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- جدول النتائج -->
        <div class="card">
            <div class="card-body">
                <div id="results-container">
                    <!-- سيتم تحميل الجدول هنا عبر AJAX -->
                </div>
            </div>
        </div>

        <!-- أزرار العمليات الجماعية -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <span class="results-summary">1-0 من النتائج المعروضة</span>
            </div>
            <div>
                <button class="btn btn-purple" id="bulk-action">
                    <i class="fa fa-cog"></i>
                    للمحدد
                </button>
                <button class="btn btn-danger" id="bulk-delete">
                    <i class="fa fa-trash"></i>
                    حذف
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">

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
    $('#resetSearch').on('click', function() {
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
            url: '{{ route("periodic_invoices.index") }}',
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
                    <div class="spinner-border text-primary" role="status">
                    </div>
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
            $('.results-summary').text(`${response.from}-${response.to} من النتائج المعروضة`);
        } else {
            $('.results-info').text('لا توجد نتائج');
            $('.results-summary').text('0 من النتائج المعروضة');
        }
    }

    function initializeEvents() {
        // تحديد الكل
        $('#select-all').off('change').on('change', function() {
            $('input[name="selected[]"]').prop('checked', $(this).prop('checked'));
        });

        $('input[name="selected[]"]').off('change').on('change', function() {
            let total = $('input[name="selected[]"]').length;
            let checked = $('input[name="selected[]"]:checked').length;
            $('#select-all').prop('checked', total === checked);
        });

        // حذف جماعي
        $('#bulk-delete').off('click').on('click', function() {
            let selected = [];
            $('input[name="selected[]"]:checked').each(function() {
                selected.push($(this).val());
            });

            if (selected.length === 0) {
                alert('الرجاء اختيار عنصر واحد على الأقل');
                return;
            }

            if (confirm('هل أنت متأكد من حذف العناصر المحددة؟')) {
                bulkDelete(selected);
            }
        });
    }

    function bulkDelete(ids) {
        $.ajax({
            url: '{{ route("periodic_invoices.bulk_delete") }}',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                ids: ids
            },
            success: function(response) {
                alert('تم الحذف بنجاح');
                loadData();
            },
            error: function() {
                alert('حدث خطأ أثناء الحذف');
            }
        });
    }

    initializeEvents();
});

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
    const searchForm = document.getElementById('searchForm').parentElement;
    const buttonText = button.querySelector('.hide-button-text');
    const icon = button.querySelector('i');

    if (buttonText.textContent === 'اخفاء') {
        searchForm.style.display = 'none';
        buttonText.textContent = 'اظهار';
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
