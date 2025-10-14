@extends('master')

@section('title')
    تقرير الميزانية العمومية
@stop

@section('css')
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/report.css') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="slide-in">
                        <i class="fas fa-balance-scale me-3"></i>
                        تقرير الميزانية العمومية
                    </h1>
                    <nav class="breadcrumb-custom">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="#"><i class="fas fa-home me-2"></i>الرئيسية</a>
                            </li>
                            <li class="breadcrumb-item active">تقرير الميزانية العمومية</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-4 text-end">
                    <div class="stats-icon primary">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Filters Section -->
        <div class="card-modern fade-in">
            <div class="card-header-modern">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2"></i>
                    فلاتر التقرير
                </h5>
            </div>
            <div class="card-body-modern">
                <form action="{{ route('GeneralAccountReports.BalanceSheet') }}" method="GET" id="filterForm">
                    <div class="row g-4">
                        <!-- First Row -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-calendar-alt me-2"></i>كل التواريخ قبل
                            </label>
                            <input type="date" name="before_date" class="form-control" value="{{ request('before_date') }}">
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-building me-2"></i>فرع الحسابات
                            </label>
                            <select name="cost_center" class="form-control select2">
                                <option value="">اختر فرع الحسابات</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request('cost_center') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-code-branch me-2"></i>فرع القيود
                            </label>
                            <select name="entries_branch" class="form-control select2">
                                <option value="">اختر فرع القيود</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request('entries_branch') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-calendar me-2"></i>السنة المالية
                            </label>
                            <select name="financial_year[]" class="form-control select2" multiple>
                                <option value="current" {{ in_array('current', request('financial_year', [])) ? 'selected' : '' }}>
                                    السنة المفتوحة
                                </option>
                                <option value="all" {{ in_array('all', request('financial_year', [])) ? 'selected' : '' }}>
                                    جميع السنوات
                                </option>
                                @for ($year = date('Y'); $year >= date('Y') - 10; $year--)
                                    <option value="{{ $year }}" {{ in_array($year, request('financial_year', [])) ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Second Row -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-eye me-2"></i>عرض الحسابات
                            </label>
                            <select name="account" class="form-control select2">
                                <option value="">عرض جميع الحسابات</option>
                                <option value="1" {{ request('account') == '1' ? 'selected' : '' }}>
                                    عرض الحسابات التي عليها معاملات
                                </option>
                                <option value="2" {{ request('account') == '2' ? 'selected' : '' }}>
                                    اخفاء الحسابات الصفرية
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-layer-group me-2"></i>المستويات
                            </label>
                            <select name="branch" class="form-control select2">
                                <option value="">المستويات الافتراضية</option>
                                <option value="1" {{ request('branch') == '1' ? 'selected' : '' }}>مستوى 1</option>
                                <option value="2" {{ request('branch') == '2' ? 'selected' : '' }}>مستوى 2</option>
                                <option value="3" {{ request('branch') == '3' ? 'selected' : '' }}>مستوى 3</option>
                                <option value="4" {{ request('branch') == '4' ? 'selected' : '' }}>مستوى 4</option>
                                <option value="5" {{ request('branch') == '5' ? 'selected' : '' }}>مستوى 5</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-project-diagram me-2"></i>مركز التكلفة
                            </label>
                            <select name="cost_center_filter" class="form-control select2">
                                <option value="">اختر مركز التكلفة</option>
                                @foreach ($costCenters as $costCenter)
                                    <option value="{{ $costCenter->id }}" {{ request('cost_center_filter') == $costCenter->id ? 'selected' : '' }}>
                                        {{ $costCenter->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-lg-3 col-md-12 align-self-end">
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn-modern btn-primary-modern">
                                    <i class="fas fa-search"></i>
                                    عرض التقرير
                                </button>
                                <a href="{{ route('GeneralAccountReports.BalanceSheet') }}" class="btn-modern btn-outline-modern">
                                    <i class="fas fa-refresh"></i>
                                    إعادة تعيين
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card-modern no-print fade-in">
            <div class="card-body-modern">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn-modern btn-success-modern" onclick="exportTableToExcel()">
                            <i class="fas fa-file-excel"></i>
                            تصدير إكسل
                        </button>
                        <button class="btn-modern btn-warning-modern" onclick="window.print()">
                            <i class="fas fa-print"></i>
                            طباعة
                        </button>
                        <button class="btn-modern btn-info-modern" onclick="exportTableToPDF()">
                            <i class="fas fa-file-pdf"></i>
                            تصدير PDF
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary fs-6 p-2">
                            <i class="fas fa-calendar me-1"></i>
                            تاريخ التقرير: {{ now()->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="row mb-4 fade-in">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card success">
                    <div class="stats-icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="stats-value">
                        {{ number_format($assets ? $assets->childrenRecursive->sum('balance') : 0, 2) }}
                    </div>
                    <div class="stats-label">إجمالي الأصول (ريال)</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card danger">
                    <div class="stats-icon">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="stats-value">
                        {{ number_format($liabilities ? $liabilities->childrenRecursive->sum('balance') : 0, 2) }}
                    </div>
                    <div class="stats-label">إجمالي الخصوم (ريال)</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card info">
                    <div class="stats-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <div class="stats-value">
                        {{ number_format(($assets ? $assets->childrenRecursive->sum('balance') : 0) - ($liabilities ? $liabilities->childrenRecursive->sum('balance') : 0), 2) }}
                    </div>
                    <div class="stats-label">صافي الأصول (ريال)</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card warning">
                    <div class="stats-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="stats-value">
                        {{ ($assets ? $assets->childrenRecursive->count() : 0) + ($liabilities ? $liabilities->childrenRecursive->count() : 0) }}
                    </div>
                    <div class="stats-label">عدد الحسابات</div>
                </div>
            </div>
        </div>

        <!-- Balance Sheet Report -->
        <div class="card-modern fade-in" id="balanceSheetTable">
            <!-- Loading Overlay -->
            <div class="loading-overlay">
                <div class="spinner"></div>
            </div>

            <div class="card-header-modern">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    التقرير المالي التفصيلي - الميزانية العمومية
                </h5>
            </div>
            <div class="card-body-modern p-0">
                <div class="table-responsive">
                    <!-- Assets Table -->
                    <table class="table table-modern mb-4">
                        <thead>
                            <tr class="assets-header">
                                <th colspan="3" class="text-center">
                                    <h4 class="mb-0">
                                        <i class="fas fa-arrow-up me-2"></i>الأصول
                                    </h4>
                                </th>
                            </tr>
                            <tr class="assets-header">
                                <th><i class="fas fa-list me-2"></i>اسم الحساب</th>
                                <th class="text-center"><i class="fas fa-barcode me-2"></i>الكود</th>
                                <th class="text-end"><i class="fas fa-money-bill me-2"></i>المبلغ (ريال)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($assets)
                                @php
                                    function displayAccountsRecursive($accounts, $level = 0, $parentBalance = 0) {
                                        foreach ($accounts as $account) {
                                            $hasChildren = $account->childrenRecursive->count() > 0;
                                            $accountClass = '';

                                            if ($level == 0) {
                                                $accountClass = 'account-main';
                                            } elseif ($level == 1) {
                                                $accountClass = 'account-level-1';
                                            } elseif ($level == 2) {
                                                $accountClass = 'account-level-2';
                                            } elseif ($level == 3) {
                                                $accountClass = 'account-level-3';
                                            } else {
                                                $accountClass = 'account-level-4';
                                            }

                                            echo "<tr class='{$accountClass}'>";
                                            echo "<td>";

                                            // Add indentation based on level
                                            for ($i = 0; $i < $level; $i++) {
                                                echo "<span class='me-3'></span>";
                                            }

                                            // Add expand/collapse icon if has children
                                            if ($hasChildren) {
                                                echo "<i class='fas fa-chevron-down me-2 text-primary'></i>";
                                            } else {
                                                echo "<i class='fas fa-circle me-2' style='font-size: 0.5rem;'></i>";
                                            }

                                            echo "<strong>{$account->name}</strong>";
                                            echo "</td>";
                                            echo "<td class='text-center'>";
                                            echo "<span class='account-code'>{$account->code}</span>";
                                            echo "</td>";
                                            echo "<td class='text-end account-balance'>";
                                            echo number_format($account->balance, 2) . " ر.س";
                                            echo "</td>";
                                            echo "</tr>";

                                            // Recursively display children
                                            if ($hasChildren) {
                                                displayAccountsRecursive($account->childrenRecursive, $level + 1, $account->balance);
                                            }
                                        }
                                    }
                                @endphp

                                @php displayAccountsRecursive($assets->childrenRecursive); @endphp
                            @else
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        لا توجد بيانات أصول متاحة
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="total-assets">
                                <th colspan="2">
                                    <i class="fas fa-calculator me-2"></i>
                                    <span class="h5">إجمالي الأصول</span>
                                </th>
                                <td class="text-end h5 font-weight-bold">
                                    {{ number_format($assets ? $assets->childrenRecursive->sum('balance') : 0, 2) }} ر.س
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Liabilities Table -->
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr class="liabilities-header">
                                <th colspan="3" class="text-center">
                                    <h4 class="mb-0">
                                        <i class="fas fa-arrow-down me-2"></i>الخصوم
                                    </h4>
                                </th>
                            </tr>
                            <tr class="liabilities-header">
                                <th><i class="fas fa-list me-2"></i>اسم الحساب</th>
                                <th class="text-center"><i class="fas fa-barcode me-2"></i>الكود</th>
                                <th class="text-end"><i class="fas fa-money-bill me-2"></i>المبلغ (ريال)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($liabilities)
                                @php displayAccountsRecursive($liabilities->childrenRecursive); @endphp
                            @else
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        لا توجد بيانات خصوم متاحة
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="total-liabilities">
                                <th colspan="2">
                                    <i class="fas fa-calculator me-2"></i>
                                    <span class="h5">إجمالي الخصوم</span>
                                </th>
                                <td class="text-end h5 font-weight-bold">
                                    {{ number_format($liabilities ? $liabilities->childrenRecursive->sum('balance') : 0, 2) }} ر.س
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Summary Table -->
                    <table class="table table-modern mt-4">
                        <thead>
                            <tr class="account-main">
                                <th colspan="3" class="text-center">
                                    <h4 class="mb-0">
                                        <i class="fas fa-chart-bar me-2"></i>ملخص الميزانية العمومية
                                    </h4>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="total-assets">
                                <td class="fw-bold">
                                    <i class="fas fa-arrow-up me-2"></i>
                                    إجمالي الأصول
                                </td>
                                <td class="text-center">-</td>
                                <td class="text-end fw-bold fs-5">
                                    {{ number_format($assets ? $assets->childrenRecursive->sum('balance') : 0, 2) }} ر.س
                                </td>
                            </tr>
                            <tr class="total-liabilities">
                                <td class="fw-bold">
                                    <i class="fas fa-arrow-down me-2"></i>
                                    إجمالي الخصوم
                                </td>
                                <td class="text-center">-</td>
                                <td class="text-end fw-bold fs-5">
                                    {{ number_format($liabilities ? $liabilities->childrenRecursive->sum('balance') : 0, 2) }} ر.س
                                </td>
                            </tr>
                            <tr class="account-main">
                                <td class="fw-bold">
                                    <i class="fas fa-balance-scale me-2"></i>
                                    صافي الأصول (الأصول - الخصوم)
                                </td>
                                <td class="text-center">-</td>
                                <td class="text-end fw-bold fs-4">
                                    {{ number_format(($assets ? $assets->childrenRecursive->sum('balance') : 0) - ($liabilities ? $liabilities->childrenRecursive->sum('balance') : 0), 2) }} ر.س
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <!-- Export Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                dir: 'rtl',
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                },
                allowClear: true,
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder') || 'اختر...';
                }
            });

            // Add fade-in animation
            setTimeout(() => {
                $('.fade-in').addClass('show');
            }, 100);

            // Add hover effects to table rows
            $('.table-modern tbody tr').hover(
                function() {
                    if (!$(this).hasClass('account-main')) {
                        $(this).css('transform', 'translateX(5px)');
                    }
                },
                function() {
                    $(this).css('transform', 'translateX(0)');
                }
            );

            // Add hover effects to stats cards
            $('.stats-card').hover(
                function() {
                    $(this).css('transform', 'translateY(-8px) scale(1.02)');
                },
                function() {
                    $(this).css('transform', 'translateY(0) scale(1)');
                }
            );

            // Add loading effect to form submission
            $('#filterForm').on('submit', function() {
                $('.loading-overlay').fadeIn();
                setTimeout(() => {
                    $('.loading-overlay').fadeOut();
                }, 1000);
            });

            // Add click effect to expand/collapse accounts (for future enhancement)
            $('.table-modern tbody tr').on('click', function() {
                if ($(this).find('.fa-chevron-down').length > 0) {
                    const icon = $(this).find('.fa-chevron-down, .fa-chevron-right');
                    if (icon.hasClass('fa-chevron-down')) {
                        icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
                        // Hide child rows logic can be added here
                    } else {
                        icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
                        // Show child rows logic can be added here
                    }
                }
            });

            // Animate numbers on load
            animateNumbers();
        });

        // Function to animate numbers
        function animateNumbers() {
            $('.stats-value').each(function() {
                const $this = $(this);
                const countTo = parseFloat($this.text().replace(/,/g, ''));

                $({ countNum: 0 }).animate({
                    countNum: countTo
                }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function() {
                        $this.text(formatNumber(Math.floor(this.countNum)));
                    },
                    complete: function() {
                        $this.text(formatNumber(this.countNum));
                    }
                });
            });
        }

        // Function to format numbers
        function formatNumber(number) {
            return parseFloat(number).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Export to Excel function
        function exportTableToExcel() {
            showAlert('جاري تصدير الملف...', 'info');

            try {
                const tables = document.querySelectorAll('#balanceSheetTable table');
                const wb = XLSX.utils.book_new();

                // Export Assets table
                if (tables[0]) {
                    const assetsWs = XLSX.utils.table_to_sheet(tables[0]);
                    XLSX.utils.book_append_sheet(wb, assetsWs, "الأصول");
                }

                // Export Liabilities table
                if (tables[1]) {
                    const liabilitiesWs = XLSX.utils.table_to_sheet(tables[1]);
                    XLSX.utils.book_append_sheet(wb, liabilitiesWs, "الخصوم");
                }

                // Export Summary table
                if (tables[2]) {
                    const summaryWs = XLSX.utils.table_to_sheet(tables[2]);
                    XLSX.utils.book_append_sheet(wb, summaryWs, "الملخص");
                }

                const today = new Date();
                const fileName = `تقرير_الميزانية_العمومية_${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}.xlsx`;

                XLSX.writeFile(wb, fileName);
                showAlert('تم تصدير الملف بنجاح!', 'success');
            } catch (error) {
                console.error('خطأ في تصدير الملف:', error);
                showAlert('حدث خطأ أثناء تصدير الملف', 'danger');
            }
        }

        // Export to PDF function
        function exportTableToPDF() {
            showAlert('جاري تصدير ملف PDF...', 'info');

            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('p', 'mm', 'a4');

                // Add title
                doc.setFontSize(18);
                doc.text('تقرير الميزانية العمومية', 105, 20, { align: 'center' });

                // Add date
                doc.setFontSize(12);
                const today = new Date();
                doc.text(`تاريخ التقرير: ${today.toLocaleDateString('ar-SA')}`, 105, 30, { align: 'center' });

                // Use html2canvas to capture the table
                html2canvas(document.getElementById('balanceSheetTable')).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const imgWidth = 190;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;

                    let heightLeft = imgHeight;
                    let position = 40;

                    doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                    heightLeft -= 250;

                    while (heightLeft >= 0) {
                        position = heightLeft - imgHeight + 40;
                        doc.addPage();
                        doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                        heightLeft -= 250;
                    }

                    const fileName = `تقرير_الميزانية_العمومية_${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}.pdf`;
                    doc.save(fileName);
                    showAlert('تم تصدير ملف PDF بنجاح!', 'success');
                }).catch(error => {
                    console.error('خطأ في تصدير PDF:', error);
                    showAlert('حدث خطأ أثناء تصدير ملف PDF', 'danger');
                });
            } catch (error) {
                console.error('خطأ في تصدير PDF:', error);
                showAlert('حدث خطأ أثناء تصدير ملف PDF', 'danger');
            }
        }

        // Show alert function
        function showAlert(message, type) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show position-fixed"
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            $('body').append(alertHtml);

            // Auto remove after 3 seconds
            setTimeout(() => {
                $('.alert').alert('close');
            }, 3000);
        }

        // Add smooth scrolling to page
        $('html').css('scroll-behavior', 'smooth');

        // Add tooltip functionality
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
@endsection