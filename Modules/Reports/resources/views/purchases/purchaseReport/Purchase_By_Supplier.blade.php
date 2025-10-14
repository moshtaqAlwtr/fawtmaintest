@extends('master')

@section('title')
    تقرير المشتريات بحسب الموردين
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --primary-color: #7367F0;
            --secondary-color: #82868B;
            --success-color: #28C76F;
            --danger-color: #EA5455;
            --warning-color: #FF9F43;
            --info-color: #00CFE8;
            --dark-color: #4B4B4B;
            --light-color: #F8F8F8;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
            border: none;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }

        .card-header h3 {
            margin-bottom: 0;
            font-weight: 600;
        }

        .btn-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s;
            margin: 0 5px;
        }

        .btn-custom:hover {
            background-color: #5d50e8;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(115, 103, 240, 0.3);
        }

        .btn-custom:active {
            transform: translateY(0);
        }

        .btn-custom-outline {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-custom-outline:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .filter-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-control, .select2-container--default .select2-selection--single {
            border-radius: 5px;
            height: 40px;
            border: 1px solid #EBE9F1;
        }

        .form-control:focus, .select2-container--default .select2-selection--single:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(115, 103, 240, 0.25);
        }

        label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 15px;
            text-align: center;
            vertical-align: middle;
        }

        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-top: 1px solid #EBE9F1;
        }

        .table tbody tr:nth-child(even) {
            background-color: #F9F9F9;
        }

        .table tbody tr:hover {
            background-color: rgba(115, 103, 240, 0.1);
        }

        .supplier-header {
            background-color: #E8E7FC;
            font-weight: bold;
            color: var(--dark-color);
        }

        .supplier-total {
            background-color: #F0EFFD;
            font-weight: 600;
        }

        .grand-total {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
        }

        .return-row {
            background-color: rgba(234, 84, 85, 0.1);
            color: var(--danger-color);
        }

        .text-paid {
            color: var(--success-color);
        }

        .text-unpaid {
            color: var(--warning-color);
        }

        .text-return {
            color: var(--danger-color);
        }

        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
            margin-bottom: 20px;
            height: 350px;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .col-md-3 {
                margin-bottom: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-custom {
                width: 100%;
                margin-bottom: 10px;
            }

            .chart-container {
                height: 300px;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>تقرير المشتريات بحسب الموردين</h3>
        </div>

        {{-- Filter Section --}}
        <div class="card-body">
            <div class="filter-card">
                <form action="{{ route('ReportsPurchases.bySupplier') }}" method="GET" id="reportForm">
                    <div class="row g-3">
                        {{-- First Row of Filters --}}
                        <div class="col-md-3">
                            <label class="form-label">المورد</label>
                            <select name="supplier" class="form-control">
                                <option value="">جميع الموردين</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->trade_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">الفرع</label>
                            <select name="branch" class="form-control">
                                <option value="">جميع الفروع</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ request('branch') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">حالة الدفع</label>
                            <select name="status" class="form-control">
                                <option value="">الكل</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>مدفوعة</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>غير مدفوعة</option>
                                <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>مرتجعة</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">الفترة</label>
                            <select name="report_period" class="form-control">
                                <option value="daily" {{ $reportPeriod == 'daily' ? 'selected' : '' }}>يومي</option>
                                <option value="weekly" {{ $reportPeriod == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                                <option value="monthly" {{ $reportPeriod == 'monthly' ? 'selected' : '' }}>شهري</option>
                                <option value="yearly" {{ $reportPeriod == 'yearly' ? 'selected' : '' }}>سنوي</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">من تاريخ</label>
                            <input type="date" name="from_date" class="form-control"
                                value="{{ $fromDate->format('Y-m-d') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">إلى تاريخ</label>
                            <input type="date" name="to_date" class="form-control"
                                value="{{ $toDate->format('Y-m-d') }}">
                        </div>

                        <div class="col-md-6">
                            <button type="button" id="exportExcel" class="btn btn-success">
                                <i class="fas fa-file-excel me-2"></i> تصدير إكسل
                            </button>
                            <button type="submit" class="btn btn-primary w-80">
                                <i class="fas fa-filter me-2"></i> تصفية التقرير
                            </button>
                            <a href="{{ route('ReportsPurchases.bySupplier') }}" class="btn btn-primary w-20">
                                <i class="fas fa-filter me-2"></i> الغاء الفلتر
                            </a>
                        </div>
              </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- مخطط حالة الدفع --}}
                <div class="col-md-6">
                    <div class="chart-container">
                        <canvas id="paymentStatusChart"></canvas>
                    </div>
                </div>

                {{-- مخطط الموردين --}}
                <div class="col-md-6">
                    <div class="chart-container">
                        <canvas id="supplierChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="purchaseReportTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                @switch($reportPeriod)
                                    @case('daily')
                                        التاريخ (يومي)
                                    @break
                                    @case('weekly')
                                        الأسبوع
                                    @break
                                    @case('monthly')
                                        الشهر
                                    @break
                                    @case('yearly')
                                        السنة
                                    @break
                                    @default
                                        التاريخ
                                @endswitch
                            </th>
                            <th>المورد</th>
                            <th>رقم الفاتورة</th>
                            <th>الموظف</th>
                            <th>مدفوعة (SAR)</th>
                            <th>غير مدفوعة (SAR)</th>
                            <th>مرتجع (SAR)</th>
                            <th>الإجمالي (SAR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Initialize grand totals --}}
                        @php
                            $grandPaidTotal = 0;
                            $grandUnpaidTotal = 0;
                            $grandReturnedTotal = 0;
                            $grandOverallTotal = 0;
                        @endphp

                        @foreach ($groupedPurchaseInvoices as $supplierId => $invoices)
                            {{-- Supplier Group Header --}}
                            <tr class="table-secondary">
                                <td colspan="8">
                                    {{ $invoices->first()->supplier->trade_name ?? 'مورد ' . $supplierId }}
                                </td>
                            </tr>

                            {{-- Initialize totals for each supplier --}}
                            @php
                                $supplierPaidTotal = 0;
                                $supplierUnpaidTotal = 0;
                                $supplierReturnedTotal = 0;
                                $supplierOverallTotal = 0;
                            @endphp

                            {{-- Invoices for this supplier --}}
                            @foreach ($invoices as $invoice)
                                <tr class="{{ in_array($invoice->type, ['return', 'returned']) ? 'table-return text-return' : '' }}">
                                    <td>
                                        @switch($reportPeriod)
                                            @case('daily')
                                                {{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}
                                            @break
                                            @case('weekly')
                                                الأسبوع {{ \Carbon\Carbon::parse($invoice->date)->weekOfYear }}
                                                ({{ \Carbon\Carbon::parse($invoice->date)->startOfWeek()->format('d/m/Y') }}
                                                - {{ \Carbon\Carbon::parse($invoice->date)->endOfWeek()->format('d/m/Y') }})
                                            @break
                                            @case('monthly')
                                                {{ \Carbon\Carbon::parse($invoice->date)->format('m/Y') }}
                                            @break
                                            @case('yearly')
                                                {{ \Carbon\Carbon::parse($invoice->date)->format('Y') }}
                                            @break
                                            @default
                                                {{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}
                                        @endswitch
                                    </td>
                                    <td>{{ $invoice->supplier->trade_name ?? 'مورد ' . $supplierId }}</td>
                                    <td>{{ str_pad($invoice->code, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $invoice->createdByUser->name ?? 'غير محدد' }}</td>

                                    @php
                                        // Calculate total paid amount
                                        $paidAmount = $invoice->payments_process->sum('amount');
                                        $remainingAmount = $invoice->grand_total - $paidAmount;
                                    @endphp

                                    @if (in_array($invoice->type, ['return', 'returned']))
                                        <td>-</td>
                                        <td>-</td>
                                        <td class="text-return">
                                            {{ number_format($invoice->grand_total, 2) }}
                                        </td>
                                        <td class="text-return">
                                            {{ number_format($invoice->grand_total, 2) }}
                                        </td>

                                        @php
                                            $supplierReturnedTotal += $invoice->grand_total;
                                            $grandReturnedTotal += $invoice->grand_total;
                                        @endphp
                                    @else
                                        <td>
                                            {{ number_format($paidAmount, 2) }}
                                        </td>
                                        <td>
                                            {{ number_format($remainingAmount > 0 ? $remainingAmount : 0, 2) }}
                                        </td>
                                        <td>-</td>
                                        <td>{{ number_format($invoice->grand_total, 2) }}</td>

                                        @php
                                            $supplierPaidTotal += $paidAmount;
                                            $supplierUnpaidTotal += max($remainingAmount, 0);
                                            $grandPaidTotal += $paidAmount;
                                            $grandUnpaidTotal += max($remainingAmount, 0);
                                            $supplierOverallTotal += $invoice->grand_total;
                                            $grandOverallTotal += $invoice->grand_total;
                                        @endphp
                                    @endif
                                </tr>
                            @endforeach

                            {{-- Supplier Total Summary Row --}}
                            <tr class="table-info">
                                <td colspan="4">مجموع المورد</td>
                                <td>{{ number_format($supplierPaidTotal, 2) }}</td>
                                <td>{{ number_format($supplierUnpaidTotal, 2) }}</td>
                                <td>{{ number_format($supplierReturnedTotal, 2) }}</td>
                                <td>{{ number_format($supplierPaidTotal + $supplierUnpaidTotal + $supplierReturnedTotal, 2) }}</td>
                            </tr>
                        @endforeach

                        {{-- Grand Total Row --}}
                        <tr class="table-dark">
                            <td colspan="4">المجموع الكلي</td>
                            <td>{{ number_format($grandPaidTotal, 2) }}</td>
                            <td>{{ number_format($grandUnpaidTotal, 2) }}</td>
                            <td>{{ number_format($grandReturnedTotal, 2) }}</td>
                            <td>{{ number_format($grandPaidTotal + $grandUnpaidTotal + $grandReturnedTotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card mt-4" id="detailedReportTable" style="display: none;">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">
                التفاصيل الكاملة للتقرير
            </h6>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>
                            @switch($reportPeriod)
                                @case('daily')
                                    التاريخ (يومي)
                                @break
                                @case('weekly')
                                    الأسبوع
                                @break
                                @case('monthly')
                                    الشهر
                                @break
                                @case('yearly')
                                    السنة
                                @break
                                @default
                                    التاريخ
                            @endswitch
                        </th>
                        <th>رقم الفاتورة</th>
                        <th>المورد</th>
                        <th>الموظف</th>
                        <th>مدفوعة (SAR)</th>
                        <th>غير مدفوعة (SAR)</th>
                        <th>مرتجع (SAR)</th>
                        <th>الإجمالي (SAR)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupedPurchaseInvoices as $supplierId => $invoices)
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>
                                    @switch($reportPeriod)
                                        @case('daily')
                                            {{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}
                                        @break
                                        @case('weekly')
                                            الأسبوع {{ \Carbon\Carbon::parse($invoice->date)->weekOfYear }}
                                            ({{ \Carbon\Carbon::parse($invoice->date)->startOfWeek()->format('d/m/Y') }}
                                            - {{ \Carbon\Carbon::parse($invoice->date)->endOfWeek()->format('d/m/Y') }})
                                        @break
                                        @case('monthly')
                                            {{ \Carbon\Carbon::parse($invoice->date)->format('m/Y') }}
                                        @break
                                        @case('yearly')
                                            {{ \Carbon\Carbon::parse($invoice->date)->format('Y') }}
                                        @break
                                        @default
                                            {{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}
                                    @endswitch
                                </td>
                                <td>{{ str_pad($invoice->code, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $invoice->supplier->trade_name ?? 'غير محدد' }}</td>
                                <td>{{ $invoice->employee->full_name ?? 'غير محدد' }}</td>
                                <td>
                                    {{ number_format($invoice->is_paid == 1 ? $invoice->grand_total : $invoice->paid_amount, 2) }}
                                </td>
                                <td>
                                    {{ number_format($invoice->is_paid == 1 ? 0 : $invoice->due_value, 2) }}
                                </td>
                                <td>
                                    {{ number_format(in_array($invoice->type, ['return', 'returned']) ? $invoice->grand_total : 0, 2) }}
                                </td>
                                <td>{{ number_format($invoice->grand_total, 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
ً@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        // بيانات المخطط البياني لحالة الدفع
        const paymentStatusChartData = @json($paymentStatusChartData);

        // إنشاء مخطط دائري لحالة الدفع
        const paymentStatusChart = new Chart(document.getElementById('paymentStatusChart'), {
            type: 'pie',
            data: {
                labels: paymentStatusChartData.labels,
                datasets: [{
                    data: paymentStatusChartData.values,
                    backgroundColor: paymentStatusChartData.colors,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'حالة الدفع'
                    }
                }
            }
        });

        // بيانات المخطط البياني للموردين
        const supplierChartData = @json($supplierChartData);

        // إنشاء مخطط شريطي للموردين
        const supplierChart = new Chart(document.getElementById('supplierChart'), {
            type: 'bar',
            data: {
                labels: supplierChartData.labels,
                datasets: [{
                    label: 'إجمالي المشتريات',
                    data: supplierChartData.values,
                    backgroundColor: supplierChartData.colors,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: true,
                        text: 'المشتريات بحسب الموردين'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // تصدير الجدول إلى Excel
        $('#exportExcel').on('click', function() {
            const table = document.getElementById('purchaseReportTable');
            const wb = XLSX.utils.table_to_book(table, { raw: true });
            const today = new Date();
            const fileName = `تقرير_المشتريات_${today.getFullYear()}-${today.getMonth() + 1}-${today.getDate()}.xlsx`;
            XLSX.writeFile(wb, fileName);
        });
    });
</script>
@endsection
