@extends('master')

@section('title')
    تقرير أرباح العملاء
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #4CAF50;
            --accent-color: #2196F3;
            --warning-color: #FF9800;
            --danger-color: #F44336;
            --light-bg: #F5F5F5;
        }

        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }

        .insights-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }

        .filter-card {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .filter-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .btn-filter {
            background-color: var(--primary-color);
            color: white;
            border-radius: 6px;
            padding: 0.5rem 1.25rem;
        }

        .btn-filter:hover {
            background-color: #1B5E20;
            color: white;
        }

        .employee-performance-card {
            background-color: white;
            border-radius: 8px;
            padding: 1.25rem;
            height: 100%;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.3s ease;
        }

        .employee-performance-card:hover {
            transform: translateY(-5px);
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 1rem;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table tfoot td {
            font-weight: bold;
            background-color: #e8f5e9;
        }

        .profit-positive {
            color: var(--primary-color);
            font-weight: bold;
        }

        .profit-negative {
            color: var(--danger-color);
            font-weight: bold;
        }


        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .insights-badge {
                margin-top: 0.5rem;
                margin-left: 0 !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            تقرير أرباح العملاء
                        </h3>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2 insights-badge">
                                <i class="fas fa-coins me-1"></i>
                                إجمالي الربح: {{ number_format($insights['total_profit'], 2) }} ر.س
                            </span>
                            <button id="exportExcel" class="btn btn-success">
                                <i class="fas fa-file-excel me-1"></i> تصدير إكسل
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- فلترة البيانات -->
                        <div class="filter-card">
                            <form method="GET" action="{{ route('salesReports.customerProfits') }}"
                                id="profitsFilterForm">
                                <div class="row g-3">
                                    <!-- فترة التقرير -->
                                    <div class="col-md-3">
                                        <label class="form-label filter-label">
                                            <i class="fas fa-calendar-alt me-2"></i>نوع التقرير
                                        </label>
                                        <select name="report_period" class="form-control select2"
                                            onchange="updateDateRange(this)">
                                            <option value="monthly"
                                                {{ request('report_period', 'monthly') == 'monthly' ? 'selected' : '' }}>
                                                شهري</option>
                                            <option value="weekly"
                                                {{ request('report_period') == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                                            <option value="daily"
                                                {{ request('report_period') == 'daily' ? 'selected' : '' }}>يومي</option>
                                            <option value="yearly"
                                                {{ request('report_period') == 'yearly' ? 'selected' : '' }}>سنوي</option>
                                        </select>
                                    </div>

                                    <!-- تاريخ البداية -->
                                    <div class="col-md-3">
                                        <label class="form-label filter-label">
                                            <i class="fas fa-calendar-alt me-2"></i>من تاريخ
                                        </label>
                                        <input type="date" name="from_date" class="form-control"
                                            value="{{ request('from_date', $fromDate) }}" id="fromDate">
                                    </div>

                                    <!-- تاريخ النهاية -->
                                    <div class="col-md-3">
                                        <label class="form-label filter-label">
                                            <i class="fas fa-calendar-alt me-2"></i>إلى تاريخ
                                        </label>
                                        <input type="date" name="to_date" class="form-control"
                                            value="{{ request('to_date', $toDate) }}" id="toDate">
                                    </div>

                                    <!-- فلترة العملاء -->
                                    <div class="col-md-3">
                                        <label class="form-label filter-label">
                                            <i class="fas fa-users me-2"></i>العميل
                                        </label>
                                        <select name="client" class="form-control select2">
                                            <option value="">اختر العميل</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}"
                                                    {{ request('client') == $client->id ? 'selected' : '' }}>
                                                    {{ $client->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- فلترة المنتجات -->
                                    <div class="col-md-3">
                                        <label class="form-label filter-label">
                                            <i class="fas fa-box me-2"></i>المنتج
                                        </label>
                                        <select name="product" class="form-control select2">
                                            <option value="">اختر المنتج</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ request('product') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- فلترة التصنيفات -->
                                    <div class="col-md-3">
                                        <label class="form-label filter-label">
                                            <i class="fas fa-tags me-2"></i>التصنيف
                                        </label>
                                        <select name="category" class="form-control select2">
                                            <option value="">اختر التصنيف</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- فلترة الماركات -->
                                    <div class="col-md-3">
                                        <label class="form-label filter-label">
                                            <i class="fas fa-trademark me-2"></i>الماركة
                                        </label>
                                        <select name="brand" class="form-control select2">
                                            <option value="">اختر الماركة</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand }}"
                                                    {{ request('brand') == $brand ? 'selected' : '' }}>
                                                    {{ $brand }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- فلترة الفروع -->
                                    <div class="col-md-3">
                                        <label class="form-label filter-label">
                                            <i class="fas fa-store me-2"></i>الفرع
                                        </label>
                                        <select name="branch" class="form-control select2">
                                            <option value="">اختر الفرع</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ request('branch') == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary me-2">
                                                    <i class="fas fa-filter me-1"></i> تطبيق الفلتر
                                                </button>
                                                <a href="{{ route('salesReports.customerProfits') }}"
                                                    class="btn btn-outline-secondary">
                                                    <i class="fas fa-redo me-1"></i> إعادة تعيين
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- بطاقات الأداء -->
                        <div class="row mb-4 g-3">
                            <div class="col-md-4">
                                <div class="employee-performance-card">
                                    <h5 class="text-center mb-3 text-warning">
                                        <i class="fas fa-trophy me-2"></i>
                                        أفضل أداء
                                    </h5>
                                    @if ($insights['top_performing_client'])
                                        <div class="text-center">
                                            <h6>{{ $insights['top_performing_client']['name'] }}</h6>
                                            <p class="text-success">
                                                <strong>الربح:
                                                    {{ number_format($insights['top_performing_client']['profit'], 2) }}
                                                    ر.س</strong>
                                            </p>
                                            <small class="text-muted">
                                                إجمالي المبيعات:
                                                {{ number_format($insights['top_performing_client']['total_value'], 2) }}
                                                ر.س
                                            </small>
                                        </div>
                                    @else
                                        <p class="text-center text-muted">لا توجد بيانات</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="employee-performance-card">
                                    <h5 class="text-center mb-3 text-info">
                                        <i class="fas fa-chart-pie me-2"></i>
                                        ملخص الأداء
                                    </h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>عدد العملاء</span>
                                        <strong>{{ $insights['total_clients'] }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>إجمالي الإيرادات</span>
                                        <strong>{{ number_format($insights['total_revenue'], 2) }} ر.س</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>هامش الربح المتوسط</span>
                                        <strong>{{ number_format($insights['avg_profit_margin'], 2) }}%</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="employee-performance-card">
                                    <h5 class="text-center mb-3 text-danger">
                                        <i class="fas fa-chart-line me-2"></i>
                                        الأداء الأقل
                                    </h5>
                                    @if ($insights['lowest_performing_client'])
                                        <div class="text-center">
                                            <h6>{{ $insights['lowest_performing_client']['name'] }}</h6>
                                            <p class="text-danger">
                                                <strong>الربح:
                                                    {{ number_format($insights['lowest_performing_client']['profit'], 2) }}
                                                    ر.س</strong>
                                            </p>
                                            <small class="text-muted">
                                                إجمالي المبيعات:
                                                {{ number_format($insights['lowest_performing_client']['total_value'], 2) }}
                                                ر.س
                                            </small>
                                        </div>
                                    @else
                                        <p class="text-center text-muted">لا توجد بيانات</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- جدول البيانات -->
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover mb-0" id="clientProfitsTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>اسم العميل</th>
                                                <th class="text-end">الكمية المباعة</th>
                                                <th class="text-end">القيمة الإجمالية</th>
                                                <th class="text-end">متوسط سعر البيع</th>
                                                <th class="text-end">التكلفة الإجمالية</th>
                                                <th class="text-end">صافي الربح</th>
                                                <th class="text-end">نسبة الربح</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($clientProfitsReport as $client)
                                                <tr>
                                                    <td>{{ $client['name'] }}</td>
                                                    <td class="text-end">{{ number_format($client['total_quantity'], 2) }}
                                                    </td>
                                                    <td class="text-end">{{ number_format($client['total_value'], 2) }}
                                                        ر.س</td>
                                                    <td class="text-end">
                                                        {{ number_format($client['avg_selling_price'], 2) }} ر.س</td>
                                                    <td class="text-end">{{ number_format($client['total_cost'], 2) }} ر.س
                                                    </td>
                                                    <td
                                                        class="text-end {{ $client['profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                                        {{ number_format($client['profit'], 2) }} ر.س
                                                    </td>
                                                    <td
                                                        class="text-end {{ $client['profit_percentage'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                                        {{ number_format($client['profit_percentage'], 2) }}%
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="alert alert-info mb-0">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            لا توجد بيانات للعرض في الفترة المحددة
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot class="table-secondary">
                                            <tr>
                                                <td class="text-end"><strong>الإجمالي</strong></td>
                                                <td class="text-end">
                                                    {{ number_format($clientProfitsReport->sum('total_quantity'), 2) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($clientProfitsReport->sum('total_value'), 2) }} ر.س
                                                </td>
                                                <td class="text-end">-</td>
                                                <td class="text-end">
                                                    {{ number_format($clientProfitsReport->sum('total_cost'), 2) }} ر.س
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($clientProfitsReport->sum('profit'), 2) }} ر.س</td>
                                                <td class="text-end">
                                                    {{ number_format(
                                                        $clientProfitsReport->sum('total_value') > 0
                                                            ? ($clientProfitsReport->sum('profit') / $clientProfitsReport->sum('total_value')) * 100
                                                            : 0,
                                                        2,
                                                    ) }}%
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2


            // Excel Export
            $('#exportExcel').on('click', function() {
                const table = document.getElementById('clientProfitsTable');
                const wb = XLSX.utils.table_to_book(table, {
                    raw: true,
                    cellDates: true
                });

                const today = new Date();
                const fileName =
                    `تقرير_أرباح_العملاء_${today.getFullYear()}-${today.getMonth() + 1}-${today.getDate()}.xlsx`;

                XLSX.writeFile(wb, fileName);
            });
        });
    </script>
@endsection
