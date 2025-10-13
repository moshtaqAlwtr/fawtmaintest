@extends('master')

@section('title')
    تقرير مشتريات الموردين
@stop

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.css" rel="stylesheet">

    <style>
        .card {
            background-color: #ffffff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-header {
            font-weight: bold;
            font-size: 20px;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        .form-control,
        .custom-select {
            border-radius: 5px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #66BB6A, #388E3C);
        }

        .btn-export,
        .btn-print {
            background: linear-gradient(45deg, #2196F3, #1976D2);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            margin-right: 10px;
        }

        .btn-export:hover,
        .btn-print:hover {
            opacity: 0.9;
        }

        .form-check-label {
            font-weight: bold;
        }

        .table-container {
            margin-top: 20px;
        }

        .table thead th {
            background: linear-gradient(45deg, #1d8cf8, #d8e3e8);
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .table tbody td {
            text-align: center;
            vertical-align: middle;
        }

        .chart-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="content-header row mb-3">
        <div class="content-header-left col-md-9 col-12 d-flex align-items-center">
            <div class="row breadcrumbs-top flex-grow-1">
                <div class="col-12 d-flex align-items-center">
                    <h2 class="content-header-title mb-0 me-3">تقارير مشتريات الموردين </h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="">الرئيسية</a></li>
                            <li class="breadcrumb-item active">عرض</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">رصيد الموردين</div>
        <div class="card-body">
            <form id="inventoryForm" method="GET" action="{{ route('ReportsPurchases.purchaseSupplier') }}">
                <div class="row mb-3">
                    <!-- فلترة حسب المورد -->
                    <div class="col-md-4">
                        <label for="supplier_id" class="form-label">المورد</label>
                        <select id="supplier_id" name="supplier_id" class="custom-select">
                            <option value="">الكل</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- فلترة حسب التاريخ من -->
                    <div class="col-md-4">
                        <label for="from_date" class="form-label">التاريخ من </label>
                        <input type="date" class="form-control" id="from_date" name="from_date"
                            value="{{ request('from_date') }}">
                    </div>

                    <!-- فلترة حسب التاريخ إلى -->
                    <div class="col-md-4">
                        <label for="to_date" class="form-label">التاريخ إلى </label>
                        <input type="date" class="form-control" id="to_date" name="to_date"
                            value="{{ request('to_date') }}">
                    </div>

                    <!-- فلترة حسب الفرع -->
                    <div class="col-md-4">
                        <label for="branch_id" class="form-label">الفرع</label>
                        <select id="branch_id" name="branch_id" class="custom-select">
                            <option value="">كل الفروع</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- فلترة حسب التجميع -->
                    <div class="col-md-4">
                        <label for="group_by"> تجميع حسب</label>
                        <select name="group_by" class="form-control">
                            <option value="">اختر</option>
                            <option value="invoice" {{ request('group_by') == 'invoice' ? 'selected' : '' }}>فاتورة</option>
                            <option value="supplier" {{ request('group_by') == 'supplier' ? 'selected' : '' }}>المورد
                            </option>
                            <option value="branch" {{ request('group_by') == 'branch' ? 'selected' : '' }}>الفرع</option>
                        </select>
                    </div>

                    <!-- فلترة حسب الترتيب -->
                    <div class="col-md-4">
                        <label for="order_by"> ترتيب حسب</label>
                        <select name="order_by" class="form-control">
                            <option value="desc">التاريخ تنازلي</option>
                            <option value="asc">التاريخ تصاعدي</option>
                        </select>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">عرض التقرير</button>
                    <a href="{{ route('ReportsPurchases.purchaseSupplier') }}" class="btn btn-secondary"
                        id="resetFilter">إلغاء الفلترة</a>
                    <button type="button" class="btn btn-export" id="exportExcel">تصدير Excel</button>
                    <button type="button" class="btn btn-export" id="exportPDF">تصدير PDF</button>
                </div>
            </form>
        </div>
    </div>

<div class="chart-container card">
    <div class="card-body">
        <h5 class="card-title">مخطط مشتريات الموردين</h5>
        <canvas id="purchaseChart" width="300" height="300"></canvas> <!-- تحديد حجم المخطط -->
    </div>
</div>

    <!-- Table Section -->
    <div class="table-container card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p>تاريخ ووقت الطباعة: {{ now()->format('H:i d/m/Y') }}</p>
                <div>
                    <button class="btn btn-print"><i class="fas fa-print"></i> طباعة</button>
                    <button class="btn btn-export"><i class="fas fa-download"></i> خيارات التصدير</button>
                </div>
            </div>
            <table class="table table-bordered table-striped" id="suppliersTable">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>الاسم</th>
                        <th>رقم المستند</th>
                        <th>فرع</th>
                        <th>القيمة (SAR)</th>
                        <th>الخصومات (SAR)</th>
                        <th>الضرائب (SAR)</th>
                        <th>الإجمالي (SAR)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupedInvoices as $supplierId => $invoices)
                        @php
                            $supplier = $invoices->first()->supplier;
                            $totalSubtotalSupplier = $invoices->sum('subtotal');
                            $totalDiscountSupplier = $invoices->sum('total_discount');
                            $totalTaxSupplier = $invoices->sum('total_tax');
                            $totalGrandTotalSupplier = $invoices->sum('grand_total');
                        @endphp

                        <!-- صف اسم المورد -->
                        <tr style="background-color: #f8f9fa; font-weight: bold;">
                            <td colspan="9">{{ $supplier->trade_name }}</td>
                        </tr>

                        <!-- تفاصيل الفواتير -->
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($invoice->date)->format('Y-m-d') }}</td>
                                <td>
                                    @if ($invoice->type == 1)
                                        طلب شراء
                                    @elseif ($invoice->type == 2)
                                        فاتورة الشراء
                                    @elseif ($invoice->type == 3)
                                        فاتورة مرتجع
                                    @elseif ($invoice->type == 4)
                                        اشعار مدين
                                    @endif
                                </td>
                                <td>{{ $invoice->supplier->trade_name }}</td>
                                <td>{{ $invoice->code }}</td>
                                <td>{{ $invoice->branch->name ?? 'N/A' }}</td>
                                <td>{{ number_format($invoice->subtotal, 2) }}</td>
                                <td>{{ number_format($invoice->total_discount, 2) }}</td>
                                <td>{{ number_format($invoice->total_tax, 2) }}</td>
                                <td>{{ number_format($invoice->grand_total, 2) }}</td>
                            </tr>
                        @endforeach

                        <!-- صف إجمالي المورد -->
                        <tr style="background-color: #e9ecef; font-weight: bold;">
                            <td colspan="5" style="text-align: right;">إجمالي {{ $supplier->trade_name }}</td>
                            <td>{{ number_format($totalSubtotalSupplier, 2) }}</td>
                            <td>{{ number_format($totalDiscountSupplier, 2) }}</td>
                            <td>{{ number_format($totalTaxSupplier, 2) }}</td>
                            <td>{{ number_format($totalGrandTotalSupplier, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #d4edda; font-weight: bold;">
                        <td colspan="5" style="text-align: right;">الإجمالي الكلي</td>
                        <td>{{ number_format($totalSubtotal, 2) }}</td>
                        <td>{{ number_format($totalDiscount, 2) }}</td>
                        <td>{{ number_format($totalTax, 2) }}</td>
                        <td>{{ number_format($totalGrandTotal, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);

            const ctx = document.getElementById('purchaseChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie', // نوع المخطط (دائري)
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'إجمالي المشتريات (SAR)',
                        data: chartData.data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false, // تعطيل الاستجابة التلقائية لتحديد الحجم يدويًا
                    maintainAspectRatio: false, // تعطيل الحفاظ على النسبة
                    plugins: {
                        legend: {
                            position: 'top', // وضع وسيلة الإيضاح في الأعلى
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(2);
                                    label += value.toLocaleString() + ' SAR (' + percentage + '%)';
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
