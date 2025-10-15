@extends('master')

@section('title')
 سجل النشاطات
@stop

@section('css')
<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
<style>
    .timeline {
        position: relative;
        margin: 20px 0;
        padding: 0;
        list-style: none;
    }
    .timeline::before {
        content: '';
        position: absolute;
        top: 50px;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #28a745 0%, #218838 100%);
        right: 50px;
        margin-right: -2px;
    }
    .timeline-item {
        margin: 0 0 20px;
        padding-right: 100px;
        position: relative;
        text-align: right;
    }
    .timeline-item::before {
        content: "\f067";
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: 30px;
        top: 15px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(145deg, #28a745, #218838);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #ffffff;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }
    .timeline-content {
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        position: relative;
    }
    .timeline-content .time {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .filter-bar {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }
    .timeline-day {
        background-color: #ffffff;
        padding: 10px 20px;
        border-radius: 30px;
        text-align: center;
        margin-bottom: 20px;
        font-weight: bold;
        color: #333;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: inline-block;
        position: relative;
        top: 0;
        right: 50px;
        transform: translateX(50%);
    }
    .filter-bar .form-control {
        border-radius: 8px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }
    .filter-bar .btn {
        border-radius: 8px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }
    .timeline-date {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin: 20px 0;
        color: #333;
    }

    /* Loading Spinner */
    .loading-spinner {
        display: none;
        text-align: center;
        padding: 40px;
    }
    .spinner-border {
        width: 3rem;
        height: 3rem;
        border-width: 0.3em;
    }

    /* Pagination Styles */
    .pagination-container {
        background: #ffffff;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 14px;
        font-weight: 500;
    }

    .pagination {
        margin: 0;
        display: flex;
        gap: 5px;
    }

    .page-item {
        list-style: none;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        color: #495057;
        background: #fff;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .page-link:hover:not(.disabled) {
        background: #28a745;
        border-color: #28a745;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
    }

    .page-item.active .page-link {
        background: #007bff;
        border-color: #007bff;
        color: white;
        font-weight: bold;
    }

    .page-item.disabled .page-link {
        background: #f8f9fa;
        border-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Animation for new content */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Date Inputs */
    .date-filter-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .date-filter-group label {
        margin: 0;
        font-weight: 600;
        white-space: nowrap;
    }

    .date-filter-group input {
        max-width: 200px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .pagination-wrapper {
            flex-direction: column;
            text-align: center;
        }

        .filter-bar {
            flex-direction: column;
            gap: 15px;
        }

        .date-filter-group {
            flex-direction: column;
            width: 100%;
        }

        .date-filter-group input {
            max-width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">سجل النشاطات</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">الرئيسيه</a></li>
                        <li class="breadcrumb-item active">عرض</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="container-fluid">
            <div class="row mt-4">
                <div class="col-12">
                    <!-- شريط التصفية والبحث -->
                    <div class="filter-bar">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <input type="text" id="searchInput" class="form-control" placeholder="ابحث في الأحداث...">
                            </div>
                            <div class="col-md-8">
                                <div class="date-filter-group">
                                    <label>من تاريخ:</label>
                                    <input type="date" id="fromDate" class="form-control">

                                    <label>إلى تاريخ:</label>
                                    <input type="date" id="toDate" class="form-control">

                                    <button class="btn btn-primary" id="filterBtn">
                                        <i class="fas fa-filter"></i> تصفية
                                    </button>

                                    <button class="btn btn-secondary" id="resetBtn">
                                        <i class="fas fa-redo"></i> إعادة تعيين
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading Spinner -->
                    <div class="loading-spinner" id="loadingSpinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only"></span>
                        </div>
                        <p class="mt-2">جاري التحميل...</p>
                    </div>

                    <!-- محتوى السجلات -->
                    <div id="logsContent">
                        <!-- سيتم تحميل البيانات هنا بواسطة AJAX -->
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-container" id="paginationContainer" style="display: none;">
                        <div class="pagination-wrapper">
                            <div class="pagination-info" id="paginationInfo">
                                عرض 1 إلى 50 من 100 نتيجة
                            </div>
                            <nav aria-label="صفحات النتائج">
                                <ul class="pagination mb-0" id="paginationList">
                                    <!-- سيتم إنشاء أزرار Pagination هنا -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let currentPage = 1;
    let totalPages = 1;
    let currentSearch = '';
    let fromDate = '';
    let toDate = '';
    let isLoading = false;

    // تحميل البيانات الأولية
    loadLogs();

    // البحث المباشر أثناء الكتابة
    let searchTimeout;
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            currentSearch = $('#searchInput').val();
            currentPage = 1;
            loadLogs();
        }, 500);
    });

    // زر التصفية
    $('#filterBtn').on('click', function() {
        fromDate = $('#fromDate').val();
        toDate = $('#toDate').val();
        currentPage = 1;
        loadLogs();
    });

    // زر إعادة التعيين
    $('#resetBtn').on('click', function() {
        $('#searchInput').val('');
        $('#fromDate').val('');
        $('#toDate').val('');
        currentSearch = '';
        fromDate = '';
        toDate = '';
        currentPage = 1;
        loadLogs();
    });

    // التعامل مع النقر على أزرار Pagination
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page && page !== currentPage) {
            currentPage = page;
            loadLogs();
            // التمرير للأعلى
            $('html, body').animate({ scrollTop: $('#logsContent').offset().top - 100 }, 500);
        }
    });

    function loadLogs() {
        if (isLoading) return;

        isLoading = true;
        showLoading();

        $.ajax({
            url: '{{ route("logs.index") }}',
            method: 'GET',
            data: {
                page: currentPage,
                search: currentSearch,
                from_date: fromDate,
                to_date: toDate
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    renderLogs(response.data);
                    updatePagination(response.pagination);
                } else {
                    showError('حدث خطأ في تحميل البيانات');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                showError('حدث خطأ في الاتصال بالخادم');
            },
            complete: function() {
                isLoading = false;
                hideLoading();
            }
        });
    }

    function renderLogs(logs) {
        let html = '';

        if (Object.keys(logs).length === 0) {
            html = `
                <div class="alert alert-info text-center fade-in" role="alert">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>لا توجد سجلات</h4>
                    <p class="mb-0">لا توجد سجلات تطابق معايير البحث الحالية</p>
                </div>
            `;
        } else {
            let previousDate = null;

            Object.keys(logs).forEach(function(date) {
                const currentDate = new Date(date);
                const dayLogs = logs[date];

                if (previousDate) {
                    const diffInDays = Math.abs((currentDate - previousDate) / (1000 * 60 * 60 * 24));
                    if (diffInDays > 7) {
                        html += `<div class="timeline-date fade-in">
                                    <h4>${formatDate(currentDate)}</h4>
                                </div>`;
                    }
                }

                html += `<div class="timeline-day fade-in">${getDayName(currentDate)} - ${formatDate(currentDate)}</div>`;
                html += '<ul class="timeline fade-in">';

                dayLogs.forEach(function(log) {
                    if (log) {
                        const logTime = new Date(log.created_at);
                        const userName = log.user ? log.user.name : 'مستخدم غير معروف';
                        const branchName = (log.user && log.user.branch) ? log.user.branch.name : 'فرع غير معروف';
                        const description = log.description || 'لا يوجد وصف';

                        html += `
                            <li class="timeline-item">
                                <div class="timeline-content">
                                    <div class="time">
                                        <i class="far fa-clock"></i> ${formatTime(logTime)}
                                    </div>
                                    <div>
                                        <strong>${userName}</strong>
                                        <div>${parseMarkdown(description)}</div>
                                        <div class="text-muted"><i class="fas fa-building"></i> ${branchName}</div>
                                    </div>
                                </div>
                            </li>
                        `;
                    }
                });

                html += '</ul>';
                previousDate = currentDate;
            });
        }

        $('#logsContent').html(html);
    }

    function updatePagination(pagination) {
        currentPage = pagination.current_page;
        totalPages = pagination.last_page;

        // تحديث معلومات التصفح
        const from = pagination.from || 0;
        const to = pagination.to || 0;
        const total = pagination.total || 0;
        $('#paginationInfo').text(`عرض ${from} إلى ${to} من ${total} نتيجة`);

        // إنشاء أزرار Pagination
        let paginationHtml = '';

        // زر الصفحة الأولى
        if (pagination.has_previous_pages) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="1" aria-label="الأول">
                        <i class="fa fa-angle-double-right"></i>
                    </a>
                </li>
            `;
        } else {
            paginationHtml += `
                <li class="page-item disabled">
                    <span class="page-link"><i class="fa fa-angle-double-right"></i></span>
                </li>
            `;
        }

        // زر الصفحة السابقة
        if (pagination.has_previous_pages) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="السابق">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
            `;
        } else {
            paginationHtml += `
                <li class="page-item disabled">
                    <span class="page-link"><i class="fa fa-angle-right"></i></span>
                </li>
            `;
        }

        // رقم الصفحة الحالية
        paginationHtml += `
            <li class="page-item active">
                <span class="page-link">${currentPage}</span>
            </li>
        `;

        // زر الصفحة التالية
        if (pagination.has_more_pages) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="التالي">
                        <i class="fa fa-angle-left"></i>
                    </a>
                </li>
            `;
        } else {
            paginationHtml += `
                <li class="page-item disabled">
                    <span class="page-link"><i class="fa fa-angle-left"></i></span>
                </li>
            `;
        }

        // زر الصفحة الأخيرة
        if (pagination.has_more_pages) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${totalPages}" aria-label="الأخير">
                        <i class="fa fa-angle-double-left"></i>
                    </a>
                </li>
            `;
        } else {
            paginationHtml += `
                <li class="page-item disabled">
                    <span class="page-link"><i class="fa fa-angle-double-left"></i></span>
                </li>
            `;
        }

        $('#paginationList').html(paginationHtml);

        // إظهار/إخفاء التصفح
        $('#paginationContainer').toggle(totalPages > 1);
    }

    function showLoading() {
        $('#loadingSpinner').fadeIn(300);
        $('#logsContent').fadeTo(300, 0.3);
    }

    function hideLoading() {
        $('#loadingSpinner').fadeOut(300);
        $('#logsContent').fadeTo(300, 1);
    }

    function showError(message) {
        const errorHtml = `
            <div class="alert alert-danger text-center fade-in" role="alert">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h4>خطأ</h4>
                <p class="mb-0">${message}</p>
                <button class="btn btn-outline-danger mt-3" onclick="location.reload()">
                    <i class="fas fa-redo"></i> إعادة المحاولة
                </button>
            </div>
        `;
        $('#logsContent').html(errorHtml);
    }

    // دوال مساعدة
    function formatDate(date) {
        return date.toLocaleDateString('ar-SA', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    function formatTime(date) {
        return date.toLocaleTimeString('ar-SA', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }

    function getDayName(date) {
        const days = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
        return days[date.getDay()];
    }

    function parseMarkdown(text) {
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/\n/g, '<br>');
    }
});
</script>
@endsection