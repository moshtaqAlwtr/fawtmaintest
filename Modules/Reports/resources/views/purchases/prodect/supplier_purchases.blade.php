@extends('master')

@section('title')
    تقرير مشتريات المنتجات حسب المورد
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
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            color: white;
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
                    <h2 class="content-header-title mb-0 me-3">تقرير مشتريات حسب المورد</h2>
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
        <div class="card-header">فلترة تقرير المورد</div>
        <div class="card-body">
            <form id="supplierFilterForm" method="GET" action="{{ route('ReportsPurchases.supplierPurchases') }}">
                <div class="row mb-3">
                    <!-- فلترة حسب المورد -->
                    <div class="col-md-3">
                        <label for="supplier_id" class="form-label">المورد</label>
                        <select id="supplier_id" name="supplier_id" class="custom-select">
                            <option value="">الكل</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->trade_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- فلترة حسب الموظف -->
                    <div class="col-md-3">
                        <label for="employee_id" class="form-label">الموظف</label>
                        <select id="employee_id" name="employee_id" class="custom-select">
                            <option value="">الكل</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- فلترة حسب المنتج -->
                    <div class="col-md-3">
                        <label for="product_id" class="form-label">المنتج</label>
                        <select id="product_id" name="product_id" class="custom-select">
                            <option value="">الكل</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- فلترة حسب التاريخ من -->
                    <div class="col-md-3">
                        <label for="from_date" class="form-label">التاريخ من </label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                    </div>

                    <!-- فلترة حسب التاريخ إلى -->
                    <div class="col-md-3">
                        <label for="to_date" class="form-label">التاريخ إلى </label>
                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                    </div>

                    <!-- فلترة حسب الترتيب -->
                    <div class="col-md-3">
                        <label for="order_by"> ترتيب حسب</label>
                        <select name="order_by" class="form-control">
                            <option value="desc">التاريخ تنازلي</option>
                            <option value="asc">التاريخ تصاعدي</option>
                        </select>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">عرض التقرير</button>
                    <a href="{{ route('ReportsPurchases.supplierPurchases') }}" class="btn btn-secondary" id="resetFilter">إلغاء الفلترة</a>
                </div>
            </form>
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
                        <th>المعرف</th>
                        <th>الاسم</th>
                        <th>البند</th>
                        <th>كود المنتج</th>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>الموظف</th>
                        <th>سعر الوحدة</th>
                        <th>اجمالي الضرائب</th>
                        <th>الكمية</th>
                        <th>الاجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupedInvoices as $supplierId => $invoices)
                        @php
                            $supplier = $invoices->first()->purchaseInvoice->supplier;
                            $supplierTotal = $supplierTotals[$supplierId] ?? null;
                        @endphp

                        <!-- صف اسم المورد -->
                        <tr style="background-color: #f8f9fa; font-weight: bold;">
                            <td colspan="11">{{ $supplier->trade_name }}</td>
                        </tr>

                        <!-- تفاصيل الفواتير -->
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->product->name }}</td>
                                <td>{{ $invoice->item }}</td>
                                <td>{{ $invoice->product->serial_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d') }}</td>
                                <td>
                                    @if ($invoice->purchaseInvoice->type == 1)
                                        طلب شراء
                                    @elseif ($invoice->purchaseInvoice->type == 2)
                                        فاتورة الشراء
                                    @elseif ($invoice->purchaseInvoice->type == 3)
                                        فاتورة مرتجع
                                    @elseif ($invoice->purchaseInvoice->type == 4)
                                        اشعار مدين
                                    @endif
                                </td>
                                <td>{{ $invoice->purchaseInvoice->creator->name ?? 'غير معروف' }}</td>
                                <td>{{ number_format($invoice->unit_price, 2) }}</td>
                                <td>{{ number_format($invoice->tax_1 + $invoice->tax_2, 2) }}</td>
                                <td>{{ $invoice->quantity }}</td>
                                <td>{{ number_format($invoice->total, 2) }}</td>
                            </tr>
                        @endforeach

                        <!-- صف إجمالي المورد -->
                        @if ($supplierTotal)
                            <tr style="background-color: #e9ecef; font-weight: bold;">
                                <td colspan="8" style="text-align: right;">إجمالي المورد</td>
                                <td>{{ number_format($supplierTotal['total_tax'], 2) }}</td>
                                <td>{{ number_format($supplierTotal['total_quantity'], 2) }}</td>
                                <td>{{ number_format($supplierTotal['total_amount'], 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #d4edda; font-weight: bold;">
                        <td colspan="8" style="text-align: right;">الإجمالي الكلي</td>
                        <td>{{ number_format($totalTax, 2) }}</td>
                        <td>{{ number_format($totalQuantity, 2) }}</td>
                        <td>{{ number_format($totalAmount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);

            const ctx = document.getElementById('supplierPurchaseChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
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
                            'rgba(153, 102, 255, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'المبلغ (SAR)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'الموردين'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.parsed.y.toLocaleString() + ' SAR';
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            // Export functionality (placeholder)
            document.getElementById('exportExcel').addEventListener('click', function() {
                alert('تصدير Excel قيد التطوير');
            });

            document.getElementById('exportPDF').addEventListener('click', function() {
                alert('تصدير PDF قيد التطوير');
            });
        });
    </script>
@endsection
