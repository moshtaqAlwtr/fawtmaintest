@extends('master')

@section('title')
    تقرير المبيعات بحسب العملاء
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .table-return {
            background-color: #ffdddd !important;
        }
        .text-return {
            color: #dc3545 !important;
        }
        .filter-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>تقرير المبيعات بحسب العملاء</h3>
        </div>

        {{-- Filter Section --}}
        <div class="card-body">
            <div class="filter-card">
                <form action="{{ route('salesReports.byCustomer') }}" method="GET" id="reportForm">
                    <div class="row g-3">
                        {{-- First Row of Filters --}}


                        <div class="col-md-3">
                            <label class="form-label">العميل</label>

                            <select name="client" class="form-select select2">

                                <option value="">جميع العملاء</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ request('client') == $client->id ? 'selected' : '' }}>
                                        {{ $client->trade_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">الفرع</label>
                            <select name="branch" class="form-control">
                                <option value="">جميع الفروع</option>
                                @foreach ($branches as $branch)
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
                                <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>غير مدفوعة</option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>مدفوعة جزئياً</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">نوع الفاتورة</label>
                            <select name="invoice_type" class="form-control">
                                <option value="">الكل</option>
                                <option value="normal" {{ request('invoice_type') == 'sale' ? 'selected' : '' }}>مبيعات</option>
                                <option value="returned" {{ request('invoice_type') == 'returned' ? 'selected' : '' }}>مرتجع</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">تمت الإضافة بواسطة</label>
                            <select name="added_by" class="form-control">
                                <option value="">الكل</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ request('added_by') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
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

                        {{-- Third Row of Filters --}}
                        <div class="col-md-3">
                            <label class="form-label">نوع التقرير</label>


                            <select name="report_type" class="form-control">

                                <option value="">الكل</option>
                                <option value="daily" {{ request('report_type') == 'daily' ? 'selected' : '' }}>يومي
                                </option>
                                <option value="weekly" {{ request('report_type') == 'weekly' ? 'selected' : '' }}>أسبوعي
                                </option>
                                <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>شهري
                                </option>
                                <option value="yearly" {{ request('report_type') == 'yearly' ? 'selected' : '' }}>سنوي
                                </option>
                                <option value="sales_manager"
                                    {{ request('report_type') == 'sales_manager' ? 'selected' : '' }}>مدير مبيعات</option>
                                <option value="employee" {{ request('report_type') == 'employee' ? 'selected' : '' }}>
                                    موظفين</option>
                                <option value="returns" {{ request('report_type') == 'returns' ? 'selected' : '' }}>مرتجعات
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3 align-self-end">



                            <button type="submit" class="btn btn-primary ">

                                <i class="fas fa-filter me-2"></i> تصفية التقرير
                            </button>
                            <a href="{{ route('salesReports.byCustomer') }}" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i> الغاء الفلتر
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sales Report Table --}}
        <div class="card-body">
            <div class="table-responsive">
                <table id="salesReportTable" class="table table-bordered table-striped">
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
                            <th>العميل</th>
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

                        @foreach ($groupedInvoices as $clientId => $invoices)
                            {{-- Client Group Header --}}
                            <tr class="table-secondary">
                                <td colspan="8">
                                    {{ $invoices->first()->client->trade_name ?? 'عميل ' . $clientId }}
                                </td>
                            </tr>

                            {{-- Initialize totals for each client --}}
                            @php
                                $clientPaidTotal = 0;
                                $clientUnpaidTotal = 0;
                                $clientReturnedTotal = 0;
                                $clientOverallTotal = 0;
                            @endphp

                            {{-- Invoices for this client --}}
                            @foreach ($invoices as $invoice)
                                <tr class="{{ in_array($invoice->type, ['return', 'returned']) ? 'table-return text-return' : '' }}">
                                    <td>
                                        @switch($reportPeriod)
                                            @case('daily')
                                                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                                            @break
                                            @case('weekly')
                                                الأسبوع {{ \Carbon\Carbon::parse($invoice->invoice_date)->weekOfYear }}
                                                ({{ \Carbon\Carbon::parse($invoice->invoice_date)->startOfWeek()->format('d/m/Y') }}
                                                - {{ \Carbon\Carbon::parse($invoice->invoice_date)->endOfWeek()->format('d/m/Y') }})
                                            @break
                                            @case('monthly')
                                                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('m/Y') }}
                                            @break
                                            @case('yearly')
                                                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y') }}
                                            @break
                                            @default
                                                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                                        @endswitch
                                    </td>
                                    <td>{{ $invoice->client->trade_name ?? 'عميل ' . $clientId }}</td>
                                    <td>{{ str_pad($invoice->code, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $invoice->createdByUser->name ?? 'غير محدد' }}</td>

                                    @php
                                        // Calculate total paid amount
                                        $paidAmount = $invoice->payments->sum('amount');
                                        $remainingAmount = $invoice->grand_total - $paidAmount;
                                    @endphp

                                    @if (in_array($invoice->type, ['return', 'returned']))
                                        <td>-</td>
                                        <td>-</td>
                                        <td class="text-return">
                                            -{{ number_format($invoice->grand_total, 2) }}
                                        </td>
                                        <td class="text-return">
                                            -{{ number_format($invoice->grand_total, 2) }}
                                        </td>

                                        @php
                                            $clientReturnedTotal += $invoice->grand_total;
                                            $grandReturnedTotal += $invoice->grand_total;
                                            $clientOverallTotal -= $invoice->grand_total;
                                            $grandOverallTotal -= $invoice->grand_total;
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
                                            $clientPaidTotal += $paidAmount;
                                            $clientUnpaidTotal += max($remainingAmount, 0);
                                            $grandPaidTotal += $paidAmount;
                                            $grandUnpaidTotal += max($remainingAmount, 0);
                                            $clientOverallTotal += $invoice->grand_total;
                                            $grandOverallTotal += $invoice->grand_total;
                                        @endphp
                                    @endif
                                </tr>
                            @endforeach

                            {{-- Client Total Summary Row --}}
                            <tr class="table-info">
                                <td colspan="4">مجموع العميل</td>
                                <td>{{ number_format($clientPaidTotal, 2) }}</td>
                                <td>{{ number_format($clientUnpaidTotal, 2) }}</td>
                                <td>-{{ number_format($clientReturnedTotal, 2) }}</td>
                                <td>{{ number_format($clientPaidTotal + $clientUnpaidTotal - $clientReturnedTotal, 2) }}</td>
                            </tr>
                        @endforeach

                        {{-- Grand Total Row --}}
                        <tr class="table-dark">
                            <td colspan="4">المجموع الكلي</td>
                            <td>{{ number_format($grandPaidTotal, 2) }}</td>
                            <td>{{ number_format($grandUnpaidTotal, 2) }}</td>
                            <td>-{{ number_format($grandReturnedTotal, 2) }}</td>
                            <td>{{ number_format($grandPaidTotal + $grandUnpaidTotal - $grandReturnedTotal, 2) }}</td>
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
                        <th>العميل</th>
                        <th>الموضف</th>
                        <th>مدفوعة (SAR)</th>
                        <th>غير مدفوعة (SAR)</th>
                        <th>مرتجع (SAR)</th>
                        <th>الإجمالي (SAR)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupedInvoices as $clientId => $invoices)
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>
                                    @switch($reportPeriod)
                                        @case('daily')
                                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                                        @break
                                        @case('weekly')
                                            الأسبوع {{ \Carbon\Carbon::parse($invoice->invoice_date)->weekOfYear }}
                                            ({{ \Carbon\Carbon::parse($invoice->invoice_date)->startOfWeek()->format('d/m/Y') }}
                                            - {{ \Carbon\Carbon::parse($invoice->invoice_date)->endOfWeek()->format('d/m/Y') }})
                                        @break
                                        @case('monthly')
                                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('m/Y') }}
                                        @break
                                        @case('yearly')
                                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y') }}
                                        @break
                                        @default
                                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                                    @endswitch
                                </td>
                                <td>{{ str_pad($invoice->code, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $invoice->client->trade_name ?? 'غير محدد' }}</td>
                                <td>{{ $invoice->user->name ?? 'غير محدد' }}</td>
                                <td>
                                    {{ number_format($invoice->payment_status == 1 ? $invoice->grand_total : $invoice->paid_amount, 2) }}
                                </td>
                                <td>
                                    {{ number_format($invoice->payment_status == 1 ? 0 : $invoice->due_value, 2) }}
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

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        // Excel Export
        $('#exportExcel').on('click', function() {
            // Get the table
            const table = document.getElementById('salesReportTable');

            // Create a new workbook and worksheet
            const wb = XLSX.utils.table_to_book(table, {
                raw: true,
                cellDates: true
            });

            // Generate file name with current date
            const today = new Date();
            const fileName = `تقرير_المبيعات_${today.getFullYear()}-${today.getMonth() + 1}-${today.getDate()}.xlsx`;

            // Export the workbook
            XLSX.writeFile(wb, fileName);
        });
    });
</script>
@endsection
