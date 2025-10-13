@extends('master')

@section('title')
    تقرير المدفوعات حسب العميل
@endsection

@section('css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Tajawal', sans-serif;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
        }
        .card-header {

            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px 20px;
        }
        .table thead th {
            background-color: #4a6cf7;
            color: white;
            font-weight: 500;
        }
        .table tbody tr:hover {
            background-color: rgba(74, 108, 247, 0.1);
        }
        .chart-container {
            position: relative;
            height: 350px;
            margin: 20px 0;
        }
        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .summary-card h5 {
            color: #4a6cf7;
            font-weight: 600;
        }
        .summary-card .amount {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
        }
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 600;
            color: #4a5568;
        }
        .export-btn {
            border-radius: 8px;
            margin-left: 10px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">تقرير المدفوعات حسب العميل</h4>
                </div>
                <div class="card-body">
                    <div class="filter-section">
                        <form method="GET" action="{{ route('salesReports.clientPaymentReport') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="customerCategory" class="form-label">فئة العميل</label>
                                    <select name="customer_category" id="customerCategory" class="form-control">
                                        <option value="">الكل</option>
                                        @foreach($customerCategories as $category)
                                            <option value="{{ $category->id }}" {{ request('customer_category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="client" class="form-label">العميل</label>
                                    <select name="client" id="client" class="form-control select2">
                                        <option value="">الكل</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                                {{ $client->trade_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="collector" class="form-label">تم التحصيل بواسطة</label>
                                    <select name="collector" id="collector" class="form-control">
                                        <option value="">الكل</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ request('collector') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="payment_method" class="form-label">وسيلة الدفع</label>
                                    <select name="payment_method" id="payment_method" class="form-control">
                                        <option value="">الكل</option>
                                        @foreach($paymentMethods as $method)
                                            <option value="{{ $method['id'] }}" {{ request('payment_method') == $method['id'] ? 'selected' : '' }}>
                                                {{ $method['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="branch" class="form-label">الفرع</label>
                                    <select name="branch" id="branch" class="form-control">
                                        <option value="">الكل</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="fromDate" class="form-label">من تاريخ</label>
                                    <input type="date" name="from_date" id="fromDate" class="form-control"
                                           value="{{ request('from_date', $fromDate->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="toDate" class="form-label">إلى تاريخ</label>
                                    <input type="date" name="to_date" id="toDate" class="form-control"
                                           value="{{ request('to_date', $toDate->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-2"></i>عرض التقرير
                                    </button>
                                    <a href="{{route('salesReports.clientPaymentReport')}}" type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-2"></i>الغاء الفلتر
                                    </a>

                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="row no-print mb-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-file-export me-2"></i> تصدير
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                        <li><a class="dropdown-item" href="#" onclick="exportTo('excel')"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="exportTo('csv')"><i class="fas fa-file-csv me-2"></i>CSV</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="exportTo('pdf')"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                                    </ul>
                                </div>
                                <button class="btn btn-secondary ms-2" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i> طباعة
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group">
                                <button class="btn btn-outline-primary active" data-view="summary">
                                    <i class="fas fa-chart-pie me-2"></i> الملخص
                                </button>
                                <button class="btn btn-outline-primary" data-view="details">
                                    <i class="fas fa-table me-2"></i> التفاصيل
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="summary-card text-center">
                                <h5>إجمالي المدفوعات</h5>
                                <div class="amount text-success">{{ number_format($summaryTotals['total_paid'], 2) }} ر.س</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-card text-center">
                                <h5>إجمالي المتبقي</h5>
                                <div class="amount text-danger">{{ number_format($summaryTotals['total_unpaid'], 2) }} ر.س</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-card text-center">
                                <h5>عدد المدفوعات</h5>
                                <div class="amount text-primary">{{ $payments->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="chart-container">
                        <canvas id="paymentChart"></canvas>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="paymentsTable">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">العميل</th>
                                    <th width="10%">التاريخ</th>
                                    <th width="15%">الموظف</th>
                                    <th width="10%">وسيلة الدفع</th>
                                    <th width="10%">المبلغ</th>
                                    <th width="15%">المرجع</th>
                                    <th width="15%">ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments->groupBy(fn($p) => optional($p->invoice?->client)->id) as $clientId => $clientPayments)
                                    @php
                                        $client = $clientPayments->first()?->invoice?->client;
                                        $clientTotal = $clientPayments->sum('amount');
                                    @endphp

                                    @if ($client)
                                        {{-- صف العميل --}}
                                        <tr class="table-secondary fw-bold">
                                            <td colspan="5">{{ $client->trade_name }}</td>
                                            <td colspan="3" class="text-end">إجمالي المدفوعات: <strong>{{ number_format($clientTotal, 2) }} ر.س</strong></td>
                                        </tr>

                                        {{-- صفوف المدفوعات لهذا العميل --}}
                                        @foreach ($clientPayments as $payment)
                                            @php
                                                $invoice = $payment->invoice;
                                            @endphp

                                            @if ($invoice)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td></td> {{-- العميل تم عرضه مسبقاً --}}
                                                    <td>{{ optional($payment->payment_date)->format('d/m/Y') ?? '--' }}</td>
                                                    <td>{{ $invoice->createdByUser->name ?? 'غير محدد' }}</td>
                                                    <td>
                                                        @switch($payment->Payment_method)
                                                            @case(1) نقدي @break
                                                            @case(2) شيك @break
                                                            @case(3) تحويل بنكي @break
                                                            @case(4) بطاقة ائتمان @break
                                                            @default غير محدد
                                                        @endswitch
                                                    </td>
                                                    <td class="text-end">{{ number_format($payment->amount, 2) }} ر.س</td>
                                                    <td>{{ $payment->reference_number ?? '--' }}</td>
                                                    <td>{{ $payment->notes ?? '--' }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">لا توجد مدفوعات في الفترة المحددة</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<script>
    // Initialize Chart
    const ctx = document.getElementById('paymentChart').getContext('2d');
    const paymentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'المدفوعات (ر.س)',
                data: @json($chartData['values']),
                backgroundColor: 'rgba(74, 108, 247, 0.7)',
                borderColor: 'rgba(74, 108, 247, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    rtl: true
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y.toLocaleString() + ' ر.س';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' ر.س';
                        }
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });

    // Export functions
    function exportTo(type) {
        const table = document.getElementById('paymentsTable');
        const fileName = `تقرير_المدفوعات_${new Date().toLocaleDateString()}`;

        if (type === 'excel' || type === 'csv') {
            const wb = XLSX.utils.table_to_book(table);
            XLSX.writeFile(wb, `${fileName}.${type}`, { bookType: type });
        } else if (type === 'pdf') {
            // PDF export implementation
            alert('سيتم تطبيق تصدير PDF في الإصدارات القادمة');
        }
    }

    // View toggle
    $('[data-view]').click(function() {
        $('[data-view]').removeClass('active');
        $(this).addClass('active');

        const view = $(this).data('view');
        if (view === 'summary') {
            $('.chart-container').show();
            $('.summary-card').parent().show();
            $('#paymentsTable').hide();
        } else {
            $('.chart-container').hide();
            $('.summary-card').parent().hide();
            $('#paymentsTable').show();
        }
    });
</script>
@endsection
