@extends('master')

@section('title')
    تقرير مبيعات العملاء
@stop

@section('css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('assets/css/customer.css') }}">
@endsection

@section('content')
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">تقرير مبيعات العملاء</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">الرئيسية</a></li>
                            <li class="breadcrumb-item active">عرض</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form id="filterForm" method="GET" action="{{ route('ClientReport.customerSales') }}">
                    <div class="row">
                        <!-- تحسين حقول التاريخ -->
                        <div class="col-md-3">
                            <label for="date-from" class="form-label">التاريخ من:</label>
                            <input type="date" id="date-from" name="date_from" class="form-control"
                                   value="{{ request('date_from') }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date-to" class="form-label">التاريخ إلى:</label>
                            <input type="date" id="date-to" name="date_to" class="form-control"
                                   value="{{ request('date_to') }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="client" class="form-label">العميل:</label>
                            <select id="client" name="client" class="form-control select2">
                                <option value="">الكل</option>
                                @foreach ($clients ?? [] as $client)
                                    <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                        {{ $client->trade_name ?? $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="branch" class="form-label">الفرع:</label>
                            <select id="branch" name="branch" class="form-control select2">
                                <option value="">الكل</option>
                                @foreach($branches  as $branch)
                                    <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="added_by" class="form-label"> اضيفت بواسطة </label>
                            <select id="added_by" name="added_by" class="form-control select2">
                                <option value="">الكل</option>
                                @foreach($employees  as $employee)
                                    <option value="{{ $employee->id }}" {{ request('added_by') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter"></i> تصفية النتائج
                            </button>
                            <a href="{{ route('ClientReport.customerSales') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إلغاء الفلتر
                            </a>
                            @if(request()->anyFilled(['date_from', 'date_to', 'client', 'branch']))
                                <button type="button" id="exportBtn" class="btn btn-success ms-2">
                                    <i class="fas fa-file-excel"></i> تصدير إلى Excel
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="card mb-4">
            <div class="card-body">
                <div class="action-buttons text-end">
                    <button class="btn btn-success" onclick="window.print()"><i class="fas fa-print"></i> طباعة</button>
                    <button class="btn btn-primary" onclick="exportToExcel()"><i class="fas fa-file-excel"></i> تصدير إلى Excel</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="header-section">
                    <h5>مبيعات العملاء - تجميع حسب العميل</h5>
                    <p>الوقت: {{ now()->format('H:i') }} | التاريخ: {{ now()->format('d/m/Y') }}</p>
                    <p>مؤسسة أعمال خاصة للتجارة - الرياض</p>
                </div>
            </div>
        </div>
        <div class="table-container">
            <div class="table-responsive">
                @foreach ($groupedInvoices ?? [] as $clientName => $invoices)
                    <div class="client-section mb-4">
                        <h5 class="client-name mb-3" style="color: #0077b6; font-weight: 600;">{{ $clientName }}</h5>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>النوع</th>
                                    <th>الاسم</th>
                                    <th>رقم المستند</th>
                                    <th>فرع</th>
                                    <th>مصاريف الشحن</th>
                                    <th>القيمة (SAR)</th>
                                    <th>الضرائب (SAR)</th>
                                    <th>المجموعات</th>
                                    <th>الإجمالي (SAR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->date }}</td>
                                        <td>{{ $invoice->type }}</td>
                                        <td>{{ $invoice->client_name }}</td>
                                        <td>{{ $invoice->document_number }}</td>
                                        <td>{{ $invoice->branch }}</td>
                                        <td>{{ number_format($invoice->shipping_cost, 2) }}</td>
                                        <td>{{ number_format($invoice->value, 2) }}</td>
                                        <td>{{ number_format($invoice->taxes, 2) }}</td>
                                        <td>{{ number_format($invoice->groups, 2) }}</td>
                                        <td>{{ number_format($invoice->total, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="total-row">
                                    <td colspan="6">المجموع</td>
                                    <td>{{ number_format($invoices->sum('value'), 2) }}</td>
                                    <td>{{ number_format($invoices->sum('taxes'), 2) }}</td>
                                    <td>{{ number_format($invoices->sum('groups'), 2) }}</td>
                                    <td>{{ number_format($invoices->sum('total'), 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection

@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Select2 للقوائم المنسدلة


            // إعادة تعيين الفلتر
            $('#resetFilters').click(function() {
                $('#filterForm')[0].reset();
                $('.form-control').trigger('change');
                window.location.href = "{{ route('ClientReport.customerSales') }}";
            });
        });

        function printReport() {
            window.print();
        }

        function exportToCSV() {
            // تنفيذ تصدير CSV
        }

        function exportToExcel() {
            const table = document.querySelector('table');
            const workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
            XLSX.writeFile(workbook, 'دليل_العملاء.xlsx');
        }

        function exportToPDF() {
            // تنفيذ تصدير PDF
        }

        $(document).ready(function() {
            // تهيئة Select2
            $('.select2').select2();

            // التحقق من صحة التواريخ
            $('#filterForm').submit(function(e) {
                const dateFrom = $('#date-from').val();
                const dateTo = $('#date-to').val();

                if (dateFrom && dateTo && new Date(dateFrom) > new Date(dateTo)) {
                    alert('تاريخ البداية يجب أن يكون قبل تاريخ النهاية');
                    e.preventDefault();
                }
            });

            // زر التصدير

        });
    </script>
@endsection
