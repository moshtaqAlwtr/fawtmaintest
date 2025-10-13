@extends('master')

@section('title', 'تحليلات المشاريع')
@section('css')

<style>
.bulk-actions-bar {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 15px 25px;
    border-radius: 50px;
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
    z-index: 1000;
    display: none;
    animation: slideUpFade 0.3s ease;
}

@keyframes slideUpFade {
    from {
        opacity: 0;
        transform: translateX(-50%) translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

.bulk-actions-bar .selected-count {
    background: rgba(255, 255, 255, 0.2);
    padding: 5px 10px;
    border-radius: 15px;
    margin-left: 10px;
    font-weight: bold;
}

.project-actions-toolbar {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 10px 15px;
    margin-bottom: 15px;
    border: 1px solid #dee2e6;
}

/* تحسينات للجدول */
.project-row.selected {
    background-color: #f0f8ff !important;
    border-left: 4px solid #007bff;
}

.table-hover tbody tr.selected:hover {
    background-color: #e3f2fd !important;
}

/* تحسينات responsive للمودال */
@media (max-width: 768px) {
    .bulk-actions-bar {
        left: 10px;
        right: 10px;
        transform: none;
        border-radius: 10px;
        padding: 10px 15px;
    }

    .bulk-actions-bar .btn {
        padding: 8px 12px;
        font-size: 12px;
    }
}
</style>
@endsection
@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">تحليلات المشاريع</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">المشاريع</a></li>
                            <li class="breadcrumb-item active">التحليلات</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">

        <!-- شريط الأدوات العلوي -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <!-- زر التصدير -->
                        <button class="btn btn-outline-success btn-sm d-flex align-items-center rounded-pill px-3" id="exportAnalyticsBtn">
                            <i class="fas fa-file-excel me-1"></i>تصدير التحليلات
                        </button>

                        <!-- زر الطباعة -->
                        <button class="btn btn-outline-secondary btn-sm d-flex align-items-center rounded-pill px-3" id="printAnalyticsBtn">
                            <i class="fas fa-print me-1"></i>طباعة
                        </button>

                        <!-- زر التحديث -->
                        <button class="btn btn-outline-primary btn-sm d-flex align-items-center rounded-pill px-3" id="refreshAnalyticsBtn">
                            <i class="fas fa-sync-alt me-1"></i>تحديث
                        </button>
                    </div>

                    <!-- معلومات النتائج -->
                    <div class="d-flex align-items-center gap-2" id="top-pagination-info">
                        <span class="text-muted mx-2 results-info">0 مشروع</span>
                    </div>
                    <div class="d-flex align-items-center gap-2" id="top-pagination-info">
                        <a href="{{ route('projects.create') }}" class="btn btn-outline-primary btn-sm d-flex align-items-center rounded-pill px-3" id="refreshAnalyticsBtn">
                            <i class="fas fa-plus me-1"></i>إنشاء مشروع
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- نموذج البحث والفلترة -->
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
                    <div class="row g-3" id="basicSearchFields">
                        <!-- 1. عنوان المشروع -->
                        <div class="col-md-4 mb-3">
                            <input type="text" id="title" class="form-control" placeholder="عنوان المشروع"
                                name="title">
                        </div>

                        <!-- 2. مساحة العمل -->
                        <div class="col-md-4 mb-3">
                            <select name="workspace_id" class="form-control select2" id="workspace_id">
                                <option value="">اختر مساحة العمل</option>
                                @foreach ($workspaces as $workspace)
                                    <option value="{{ $workspace->id }}">{{ $workspace->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 3. الحالة -->
                        <div class="col-md-4 mb-3">
                            <select name="status" class="form-control select2" id="status">
                                <option value="">الحالة</option>
                                <option value="new">جديد</option>
                                <option value="in_progress">قيد التنفيذ</option>
                                <option value="completed">مكتمل</option>
                                <option value="on_hold">متوقف</option>
                            </select>
                        </div>
                    </div>

                    <!-- البحث المتقدم -->
                    <div class="collapse" id="advancedSearchForm">
                        <div class="row g-3 mt-2">
                            <!-- 4. الأولوية -->
                            <div class="col-md-4 mb-3">
                                <select name="priority" class="form-control select2" id="priority">
                                    <option value="">الأولوية</option>
                                    <option value="low">منخفضة</option>
                                    <option value="medium">متوسطة</option>
                                    <option value="high">عالية</option>
                                    <option value="urgent">عاجلة</option>
                                </select>
                            </div>

                            <!-- 5. منشئ المشروع -->
                            <div class="col-md-4 mb-3">
                                <select name="created_by" class="form-control select2" id="created_by">
                                    <option value="">منشئ المشروع</option>
                                    @foreach ($creators as $creator)
                                        <option value="{{ $creator->id }}">{{ $creator->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 6. من (التاريخ) -->
                            <div class="col-md-4 mb-3">
                                <input type="date" id="from_date" class="form-control" placeholder="من"
                                    name="from_date">
                            </div>

                            <!-- 7. إلى (التاريخ) -->
                            <div class="col-md-4 mb-3">
                                <input type="date" id="to_date" class="form-control" placeholder="إلى"
                                    name="to_date">
                            </div>

                            <!-- 8. الميزانية (من) -->
                            <div class="col-md-4 mb-3">
                                <input type="number" id="budget_min" class="form-control"
                                    placeholder="الميزانية (من)" name="budget_min" min="0" step="0.01">
                            </div>

                            <!-- 9. الميزانية (إلى) -->
                            <div class="col-md-4 mb-3">
                                <input type="number" id="budget_max" class="form-control"
                                    placeholder="الميزانية (إلى)" name="budget_max" min="0" step="0.01">
                            </div>

                            <!-- 10. نسبة الإكمال (من) -->
                            <div class="col-md-4 mb-3">
                                <input type="number" id="progress_min" class="form-control"
                                    placeholder="نسبة الإكمال (من %)" name="progress_min" min="0" max="100">
                            </div>

                            <!-- 11. نسبة الإكمال (إلى) -->
                            <div class="col-md-4 mb-3">
                                <input type="number" id="progress_max" class="form-control"
                                    placeholder="نسبة الإكمال (إلى %)" name="progress_max" min="0" max="100">
                            </div>
                        </div>
                    </div>

                    <!-- الأزرار -->
                    <div class="form-actions mt-2">
                        <button type="submit" class="btn btn-primary">بحث</button>
                        <button type="button" id="resetSearchBtn" class="btn btn-outline-warning">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- بطاقة النتائج -->
        <div class="card">
            <div class="card-body position-relative">
                <!-- مؤشر التحميل -->
                <div id="loadingIndicator" class="loading-overlay" style="display: none;">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">جاري التحميل...</span>
                        </div>
                        <p class="mt-2 text-muted">جاري تحميل البيانات...</p>
                    </div>
                </div>

                <!-- نتائج البحث -->
                <div id="resultsContainer">
                    @include('taskmanager::project.partials.table', [
                        'projects' => $projects,
                    ])
                </div>
            </div>
        </div>
    </div>

    <!-- شريط الإجراءات الجماعية -->
    <div class="bulk-actions-bar" id="bulkActionsBar">
        <div class="d-flex align-items-center">
            <span class="selected-count" id="selectedCount">0 مشروع محدد</span>
            <div class="d-flex gap-2 ms-3">
                <button class="btn btn-outline-light btn-sm" onclick="bulkStatusUpdate()">
                    <i class="fas fa-edit me-1"></i>تعديل الحالة
                </button>
                <button class="btn btn-outline-light btn-sm" onclick="clearSelection()">
                    <i class="fas fa-times me-1"></i>إلغاء التحديد
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // متغيرات النظام الجماعي
        let selectedProjects = new Set();
        let selectedUsersForBulkInvite = new Set();
        let availableUsersForBulkInvite = [];

        $(document).ready(function() {
            let currentPage = 1;
            let isLoading = false;
            let searchXHR = null;

            // تهيئة Select2
            $('.select2').select2({
                width: '100%',
                placeholder: 'اختر...',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    }
                }
            });

            // معالجة تحديد جميع المشاريع
            $(document).on('change', '#selectAll', function() {
                const isChecked = $(this).is(':checked');
                $('.project-checkbox').prop('checked', isChecked).trigger('change');
            });

            // معالجة تحديد مشروع واحد
            $(document).on('change', '.project-checkbox', function() {
                const projectId = $(this).val();
                const isChecked = $(this).is(':checked');
                const projectTitle = $(this).closest('tr').find('.text-primary').text();

                if (isChecked) {
                    selectedProjects.add({
                        id: projectId,
                        title: projectTitle
                    });
                    $(this).closest('tr').addClass('selected');
                } else {
                    selectedProjects.forEach(project => {
                        if (project.id === projectId) {
                            selectedProjects.delete(project);
                        }
                    });
                    $(this).closest('tr').removeClass('selected');
                }

                updateBulkActionsBar();
                updateSelectAllCheckbox();
            });

            // باقي الكود الأصلي...
            $('#searchForm').submit(function(e) {
                e.preventDefault();
                if (!isLoading) {
                    currentPage = 1;
                    loadData();
                }
            });

            $('#searchForm input, #searchForm select').on('change input', function() {
                if (searchXHR) {
                    searchXHR.abort();
                }

                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(function() {
                    if (!isLoading) {
                        currentPage = 1;
                        loadData();
                    }
                }, 500);
            });

            // إعادة تعيين البحث
            $('#resetSearch, #resetSearchBtn').click(function() {
                $('#searchForm')[0].reset();
                $('.select2').val(null).trigger('change');
                currentPage = 1;
                loadData();
            });

            // تحديث البيانات
            $('#refreshAnalyticsBtn').click(function() {
                if (!isLoading) {
                    loadData();
                    loadStats();
                }
            });

            // دالة تحميل البيانات
            function loadData() {
                if (isLoading) return;

                isLoading = true;
                showLoading();

                // جمع بيانات النموذج
                let formData = $('#searchForm').serializeArray()
                    .filter(item => item.name !== '_token')
                    .reduce((obj, item) => {
                        obj[item.name] = item.value;
                        return obj;
                    }, {});

                formData.page = currentPage;

                // إلغاء أي طلب سابق
                if (searchXHR) {
                    searchXHR.abort();
                }

                searchXHR = $.ajax({
                    url: "",
                    method: 'GET',
                    data: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#resultsContainer').html(response.html);
                            updatePaginationInfo(response);
                            // إعادة تعيين التحديدات بعد تحديث الجدول
                            clearSelection();
                        } else {
                            showError('حدث خطأ أثناء جلب البيانات');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.statusText !== 'abort') {
                            handleAjaxError(xhr);
                        }
                    },
                    complete: function() {
                        isLoading = false;
                        hideLoading();
                        searchXHR = null;
                    }
                });
            }

            function showLoading() {
                $('#loadingIndicator').show();
            }

            function hideLoading() {
                $('#loadingIndicator').hide();
            }

            function showError(message) {
                console.error(message);
                // يمكن إضافة عرض رسالة خطأ للمستخدم هنا
            }

            function handleAjaxError(xhr) {
                let message = 'حدث خطأ غير متوقع';
                if (xhr.status === 422) {
                    message = 'خطأ في البيانات المرسلة';
                } else if (xhr.status === 500) {
                    message = 'خطأ في الخادم';
                }
                showError(message);
            }

            function updatePaginationInfo(response) {
                if (response.pagination) {
                    $('.results-info').text(`${response.pagination.total} مشروع`);
                }
            }

            // تحميل البيانات الأولية
            loadData();
        });

        // دوال النظام الجماعي
        function updateBulkActionsBar() {
            const count = selectedProjects.size;
            if (count > 0) {
                $('#selectedCount').text(`${count} مشروع محدد`);
                $('#bulkActionsBar').fadeIn();
            } else {
                $('#bulkActionsBar').fadeOut();
            }
        }

        function updateSelectAllCheckbox() {
            const totalCheckboxes = $('.project-checkbox').length;
            const checkedCheckboxes = $('.project-checkbox:checked').length;

            if (checkedCheckboxes === 0) {
                $('#selectAll').prop('indeterminate', false).prop('checked', false);
            } else if (checkedCheckboxes === totalCheckboxes) {
                $('#selectAll').prop('indeterminate', false).prop('checked', true);
            } else {
                $('#selectAll').prop('indeterminate', true);
            }
        }

        function clearSelection() {
            selectedProjects.clear();
            $('.project-checkbox').prop('checked', false);
            $('.project-row').removeClass('selected');
            $('#selectAll').prop('checked', false).prop('indeterminate', false);
            updateBulkActionsBar();
        }

        // تحديث الحالة الجماعي
        function bulkStatusUpdate() {
            if (selectedProjects.size === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'لم يتم تحديد مشاريع',
                    text: 'الرجاء تحديد مشروع واحد على الأقل للمتابعة'
                });
                return;
            }

            Swal.fire({
                title: `تحديث حالة ${selectedProjects.size} مشروع`,
                html: `
                    <div class="text-start">
                        <label class="form-label">الحالة الجديدة</label>
                        <select id="new_status" class="form-control">
                            <option value="new">جديد</option>
                            <option value="in_progress">قيد التنفيذ</option>
                            <option value="completed">مكتمل</option>
                            <option value="on_hold">متوقف</option>
                        </select>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'تحديث',
                cancelButtonText: 'إلغاء',
                width: 500,
                preConfirm: () => {
                    const status = $('#new_status').val();
                    if (!status) {
                        Swal.showValidationMessage('الرجاء اختيار الحالة');
                        return false;
                    }
                    return { status };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    updateProjectsStatus(result.value.status);
                }
            });
        }

        function updateProjectsStatus(newStatus) {
            const projectIds = Array.from(selectedProjects).map(p => p.id);

            Swal.fire({
                title: 'جاري التحديث...',
                text: 'يرجى الانتظار',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ route("projects.bulk.status") }}',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    project_ids: projectIds,
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم التحديث!',
                            text: response.message,
                            timer: 3000
                        }).then(() => {
                            // إعادة تحميل البيانات
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'حدث خطأ أثناء التحديث';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: errorMsg
                    });
                }
            });
        }

        // دوال مساعدة
        function formatDate(date) {
            if (!date) return '';
            return new Date(date).toLocaleDateString('ar-SA');
        }

        // دوال إضافية للبحث والفلاتر
        function toggleSearchFields(button) {
            const fields = $('#basicSearchFields');
            if (fields.is(':visible')) {
                fields.hide();
                $(button).find('.hide-button-text').text('إظهار');
                $(button).find('i').removeClass('fa-times').addClass('fa-eye');
            } else {
                fields.show();
                $(button).find('.hide-button-text').text('اخفاء');
                $(button).find('i').removeClass('fa-eye').addClass('fa-times');
            }
        }

        function toggleSearchText(button) {
            const text = $(button).find('.button-text');
            if (text.text() === 'متقدم') {
                text.text('بسيط');
            } else {
                text.text('متقدم');
            }
        }
    </script>
@endsection