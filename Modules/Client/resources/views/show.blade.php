@extends('sales::master')

@section('title')
    عرض العميل
@stop

@section('head')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/logs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/show_client.css') }}">

@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (session('toast_message'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.{{ session('toast_type', 'success') }}('{{ session('toast_message') }}', '', {
                    positionClass: 'toast-bottom-left',
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000
                });
            });
        </script>
    @endif

    <!-- رأس الصفحة -->
    <div class="content-header row">
        <div class="content-header-left col-12 mb-3">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">
                        <i class="fa fa-user-circle me-2" style="color: var(--primary-color);"></i>
                        عرض العميل
                    </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">الرئيسيه</a></li>
                            <li class="breadcrumb-item active">عرض العميل</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.alerts.error')
    @include('layouts.alerts.success')

    <!-- بطاقة معلومات العميل الرئيسية -->
    <div class="client-card">
        <div class="floating-elements"></div>
        <div class="floating-elements"></div>
        <div class="floating-elements"></div>

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start client-card-content flex-wrap">
                <!-- معلومات الدفعة -->
                <div class="payment-section mx-2">
                    @php
                        $balance = $due ?? 0;
                        $currencySymbol = $account_setting->currency ?? 'SAR';
                        $currencySymbol =
                            $currencySymbol == 'SAR' || empty($currencySymbol)
                                ? '<img src="' .
                                    asset('assets/images/Saudi_Riyal.svg') .
                                    '" alt="ريال سعودي" width="15" style="vertical-align: middle;">'
                                : $currencySymbol;

                        if ($balance > 50000) {
                            $paymentClass = 'payment-info-danger';
                            $badgeClass = 'bg-danger';
                            $badgeText = 'عاجل';
                            $iconClass = 'fa-exclamation-triangle';
                            $textColor = '#dc3545';
                        } elseif ($balance > 0) {
                            $paymentClass = 'payment-info-warning';
                            $badgeClass = 'bg-warning text-dark';
                            $badgeText = 'متأخر';
                            $iconClass = 'fa-credit-card';
                            $textColor = '#e67e22';
                        } else {
                            $paymentClass = 'payment-info-success';
                            $badgeClass = 'bg-success';
                            $badgeText = 'مُسوّى';
                            $iconClass = 'fa-check-circle';
                            $textColor = '#28a745';
                        }
                    @endphp

                    <div class="payment-info {{ $paymentClass }}">
                        <div class="icon-wrapper" style="color: {{ $textColor }}">
                            <i class="fa {{ $iconClass }}"></i>
                        </div>
                        <small class="text-muted d-block">
                            {{ $balance > 0 ? 'مطلوب دفعة' : 'الرصيد' }}
                        </small>
                        <div class="payment-amount" style="color: {{ $textColor }}">
                            {{ number_format(abs($balance), 2) }} {!! $currencySymbol !!}
                        </div>
                        <div class="mt-2">
                            <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                        </div>
                    </div>
                </div>

                <!-- تفاصيل العميل -->
                <div class="client-section">
                    <div class="client-details text-end">
                        <h3 class="mb-2" style="color: var(--primary-color);">
                            {{ $client->trade_name ?: $client->first_name . ' ' . $client->last_name }}
                            <span class="text-muted fs-6">{{ $client->code }}</span>
                        </h3>

                        <div class="account-info mt-3">
                            <small class="text-muted d-block mb-2">
                                <i class="fa fa-university me-1"></i>
                                حساب الأستاذ:
                            </small>

                            @if ($client->account_client && $client->account_client->client_id == $client->id)
                                <div class="d-flex align-items-center justify-content-end">
                                    <a href="{{ route('journal.generalLedger', ['account_id' => $client->account_client->id]) }}"
                                        class="text-decoration-none" style="color: var(--primary-color);">
                                        {{ $client->account_client->name }} - {{ $client->account_client->code }}
                                        <i class="fa fa-external-link-alt ms-1"></i>
                                    </a>
                                </div>
                            @else
                                <span class="text-danger">
                                    <i class="fa fa-exclamation-triangle me-1"></i>
                                    لا يوجد حساب مرتبط
                                </span>
                            @endif
                        </div>

                        <div class="account-info mt-2">
                            <small class="text-muted">
                                <i class="fa fa-map-marker-alt me-1"></i>
                                {{ $client->neighborhood->Region->name ?? 'غير محدد' }}
                            </small>
                        </div>

                        <!-- قائمة الحالة -->
                        <div class="mt-3 status-dropdown">
                            <form method="POST" action="{{ route('clients.updateStatusClient') }}">
                                @csrf
                                <input type="hidden" name="client_id" value="{{ $client->id }}">
                                <select name="status_id" class="form-select" onchange="this.form.submit()"
                                    style="border-color: var(--primary-color); color: var(--primary-color);">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}"
                                            @if ($client->status_id == $status->id) selected @endif>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بطاقة الأزرار -->
    <div class="actions-card">
        <div class="actions-grid">
            @if (auth()->user()->hasPermissionTo('Edit_Client'))
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-outline-primary">
                    <i class="fa fa-edit"></i>
                    <span>تعديل البيانات</span>
                </a>
            @endif

            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal_opening_balance">
                <i class="fa fa-plus-circle"></i>
                <span>رصيد افتتاحي</span>
            </button>

            <a href="{{ route('clients.statement', $client->id) }}" class="btn btn-outline-primary">
                <i class="fa fa-file-text"></i>
                <span>كشف حساب</span>
            </a>

            <a href="{{ route('appointment.notes.create', $client->id) }}" class="btn btn-outline-primary">
                <i class="fa fa-sticky-note"></i>
                <span>إضافة ملاحظة</span>
            </a>

            <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="btn btn-outline-primary">
                <i class="fa fa-file-invoice"></i>
                <span>إنشاء فاتورة</span>
            </a>

            <a href="{{ route('incomes.create') }}" class="btn btn-outline-primary">
                <i class="fa fa-money-bill-wave"></i>
                <span>سند قبض</span>
            </a>

            <a href="{{ route('appointments.create') }}" class="btn btn-outline-primary">
                <i class="fa fa-calendar-plus"></i>
                <span>ترتيب موعد</span>
            </a>

            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignEmployeeModal">
                <i class="fa fa-user-plus"></i>
                <span>تعيين موظفين</span>
            </button>

            <a href="{{ route('CreditNotes.create') }}" class="btn btn-outline-primary">
                <i class="fa fa-file-invoice-dollar"></i>
                <span>إشعار دائن</span>
            </a>

            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal_DELETE1">
                <i class="fa fa-trash"></i>
                <span>حذف</span>
            </button>
        </div>
    </div>

    <!-- التبويبات -->
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#details" role="tab">
                        <i class="fa fa-info-circle me-1"></i>
                        التفاصيل
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#invoices" role="tab">
                        <i class="fa fa-file-invoice me-1"></i>
                        الفواتير
                        @if (isset($invoices) && $invoices->count() > 0)
                            <span class="badge">{{ $invoices->count() }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#payments" role="tab">
                        <i class="fa fa-credit-card me-1"></i>
                        المدفوعات
                        @if (isset($payments) && $payments->where('type', 'client payments')->count() > 0)
                            <span class="badge">{{ $payments->where('type', 'client payments')->count() }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#appointments" role="tab">
                        <i class="fa fa-calendar-alt me-1"></i>
                        المواعيد
                        @if ($client->appointments->count() > 0)
                            <span class="badge">{{ $client->appointments->count() }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#notes" role="tab">
                        <i class="fa fa-sticky-note me-1"></i>
                        الملاحظات
                        @if (isset($ClientRelations) && count($ClientRelations) > 0)
                            <span class="badge">{{ count($ClientRelations) }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#visits" role="tab">
                        <i class="fa fa-walking me-1"></i>
                        الزيارات
                        @if (isset($visits) && $visits->count() > 0)
                            <span class="badge">{{ $visits->count() }}</span>
                        @endif
                    </a>
                </li>
            </ul>

            <div class="tab-content p-3">
                <!-- تبويب التفاصيل -->
                <div class="tab-pane active" id="details" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="card-header">
                                    <h5><i class="fa fa-user me-2"></i>معلومات العميل الأساسية</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>الاسم التجاري:</strong></td>
                                            <td>{{ $client->trade_name ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الاسم الأول:</strong></td>
                                            <td>{{ $client->first_name ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الاسم الأخير:</strong></td>
                                            <td>{{ $client->last_name ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>رقم الهاتف:</strong></td>
                                            <td>{{ $client->phone ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الجوال:</strong></td>
                                            <td>{{ $client->mobile ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>البريد الإلكتروني:</strong></td>
                                            <td>{{ $client->email ?? 'غير محدد' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="card-header">
                                    <h5><i class="fa fa-map-marker-alt me-2"></i>العنوان والمعلومات الإضافية</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>العنوان:</strong></td>
                                            <td>{{ $client->street1 }} {{ $client->street2 }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>المدينة:</strong></td>
                                            <td>{{ $client->city ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>المنطقة:</strong></td>
                                            <td>{{ $client->region ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الرمز البريدي:</strong></td>
                                            <td>{{ $client->postal_code ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الدولة:</strong></td>
                                            <td>{{ $client->country ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الرقم الضريبي:</strong></td>
                                            <td>{{ $client->tax_number ?? 'غير محدد' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تبويب الفواتير -->
                <div class="tab-pane" id="invoices" role="tabpanel">
                    @if (isset($invoices) && $invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم الفاتورة</th>
                                        <th>التاريخ</th>
                                        <th>المبلغ</th>
                                        <th>الحالة</th>
                                        <th>المبلغ المستحق</th>
                                        <th>خيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td><strong>#{{ $invoice->code ?? $invoice->id }}</strong></td>
                                            <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                            <td>{{ number_format($invoice->grand_total ?? $invoice->total, 2) }}
                                                {!! $currencySymbol !!}</td>
                                            <td>
                                                @php
                                                    $statusClass = match ($invoice->payment_status) {
                                                        1 => 'bg-success',
                                                        2 => 'bg-warning',
                                                        3 => 'bg-danger',
                                                        default => 'bg-secondary',
                                                    };
                                                    $statusText = match ($invoice->payment_status) {
                                                        1 => 'مدفوعة',
                                                        2 => 'جزئية',
                                                        3 => 'غير مدفوعة',
                                                        default => 'غير معروف',
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td>
                                                @if (isset($invoice->due_value) && $invoice->due_value > 0)
                                                    <span
                                                        class="text-danger fw-bold">{{ number_format($invoice->due_value, 2) }}
                                                        {!! $currencySymbol !!}</span>
                                                @else
                                                    <span class="text-success">--</span>
                                                @endif
                                            </td>
                                          <td>
    <div class="dropdown">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="dropdown">
            <i class="fa fa-ellipsis-v"></i>
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('invoices.show', $invoice->id) }}">
                <i class="fa fa-eye me-2 text-primary"></i>عرض
            </a>

            <a class="dropdown-item" href="{{ route('invoices.edit', $invoice->id) }}">
                <i class="fa fa-edit me-2 text-success"></i>تعديل
            </a>

            <a class="dropdown-item" href="{{ route('invoices.generatePdf', $invoice->id) }}">
                <i class="fa fa-file-pdf me-2 text-danger"></i>PDF
            </a>

            <hr class="dropdown-divider">

            <a class="dropdown-item" href="{{ route('paymentsClient.create',  $invoice->id) }}">
                <i class="fa fa-money-bill me-2 text-info"></i>إضافة دفعة
            </a>
        </div>
    </div>
</td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-file-invoice"></i>
                            <h5>لا توجد فواتير</h5>
                            <p>لم يتم إنشاء أي فواتير لهذا العميل بعد</p>
                            <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}"
                                class="btn btn-unified">
                                <i class="fas fa-plus me-1"></i>إنشاء فاتورة جديدة
                            </a>
                        </div>
                    @endif
                </div>

                <!-- تبويب المدفوعات -->
                <div class="tab-pane" id="payments" role="tabpanel">
                    @if (isset($payments) && $payments->where('type', 'client payments')->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم الدفع</th>
                                        <th>الفاتورة</th>
                                        <th>المبلغ</th>
                                        <th>تاريخ الدفع</th>
                                        <th>الحالة</th>
                                        <th>خيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments->where('type', 'client payments') as $payment)
                                        <tr>
                                            <td><strong>#{{ $payment->id }}</strong></td>
                                            <td>{{ $payment->invoice ? $payment->invoice->code : '--' }}</td>
                                            <td class="text-success fw-bold">{{ number_format($payment->amount, 2) }}
                                                {!! $currencySymbol !!}</td>
                                            <td>{{ $payment->payment_date ?? $payment->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                @php
                                                    $statusClass = match ($payment->payment_status) {
                                                        1 => 'bg-success',
                                                        2 => 'bg-warning',
                                                        default => 'bg-secondary',
                                                    };
                                                    $statusText = match ($payment->payment_status) {
                                                        1 => 'مكتمل',
                                                        2 => 'غير مكتمل',
                                                        default => 'غير معروف',
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                           <td>
                                                <div class="btn-group">
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn bg-gradient-info fa fa-ellipsis-v mr-1 mb-1 btn-sm"
                                                            type="button" data-toggle="dropdown" aria-expanded="false">
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item"
                                                                href="{{ route('paymentsClient.show', $payment->id) }}">
                                                                <i class="fas fa-eye me-2 text-primary"></i>عرض التفاصيل
                                                            </a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('paymentsClient.edit', $payment->id) }}">
                                                                <i class="fas fa-edit me-2 text-success"></i>تعديل الدفع
                                                            </a>

                                                            <hr class="dropdown-divider">

                                                            <button type="button"
                                                                class="dropdown-item text-danger {{ auth()->user()->role === 'employee' ? 'disabled-action' : '' }}"
                                                                onclick="{{ auth()->user()->role === 'employee' ? 'showPermissionError()' : 'confirmCancelPayment(' . $payment->id . ')' }}">
                                                                <i class="fa fa-times me-2"></i>إلغاء عملية الدفع
                                                            </button>

                                                            <a class="dropdown-item"
                                                                href="{{ route('paymentsClient.rereceipt', ['id' => $payment->id]) }}?type=a4"
                                                                target="_blank">
                                                                <i class="fas fa-file-pdf me-2 text-warning"></i>إيصال (A4)
                                                            </a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('paymentsClient.rereceipt', ['id' => $payment->id]) }}?type=thermal"
                                                                target="_blank">
                                                                <i class="fas fa-receipt me-2 text-warning"></i>إيصال
                                                                (حراري)
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-credit-card"></i>
                            <h5>لا توجد مدفوعات</h5>
                            <p>لم يتم إجراء أي مدفوعات لهذا العميل بعد</p>
                            <a href="{{ route('incomes.create') }}" class="btn btn-unified">
                                <i class="fas fa-plus me-1"></i>إضافة دفعة جديدة
                            </a>
                        </div>
                    @endif
                </div>

                <!-- تبويب المواعيد -->
                <div class="tab-pane" id="appointments" role="tabpanel">
                    @if ($client->appointments->count() > 0)
                        <div class="mb-3">
                            <button class="btn btn-sm btn-outline-primary active filter-appointments" data-filter="all">
                                الكل <span class="badge bg-primary">{{ $client->appointments->count() }}</span>
                            </button>
                            <button class="btn btn-sm btn-outline-primary filter-appointments" data-filter="2">
                                تم <span
                                    class="badge bg-primary">{{ $client->appointments->where('status', 2)->count() }}</span>
                            </button>
                            <button class="btn btn-sm btn-outline-primary filter-appointments" data-filter="1">
                                مجدول <span
                                    class="badge bg-primary">{{ $client->appointments->where('status', 1)->count() }}</span>
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم الموعد</th>
                                        <th>العنوان</th>
                                        <th>التاريخ</th>
                                        <th>الموظف</th>
                                        <th>الحالة</th>
                                        <th>خيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($client->appointments as $appointment)
                                        <tr data-status="{{ $appointment->status }}">
                                            <td><strong>#{{ $appointment->id }}</strong></td>
                                            <td>{{ $appointment->title }}</td>
                                            <td>{{ $appointment->created_at->format('Y-m-d') }}</td>
                                            <td>{{ $appointment->employee->name ?? 'غير محدد' }}</td>
                                            <td>
                                                @php
                                                    $statusClass = match ($appointment->status) {
                                                        1 => 'bg-warning',
                                                        2 => 'bg-success',
                                                        3 => 'bg-danger',
                                                        4 => 'bg-info',
                                                        default => 'bg-secondary',
                                                    };
                                                    $statusText = match ($appointment->status) {
                                                        1 => 'مجدول',
                                                        2 => 'تم',
                                                        3 => 'صرف النظر',
                                                        4 => 'معاد جدولته',
                                                        default => 'غير محدد',
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-unified" data-bs-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <form
                                                            action="{{ route('appointments.update-status', $appointment->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="2">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fa fa-check me-2 text-success"></i>تم
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-alt"></i>
                            <h5>لا توجد مواعيد</h5>
                            <p>لم يتم ترتيب أي مواعيد لهذا العميل بعد</p>
                            <a href="{{ route('appointments.create') }}" class="btn btn-unified">
                                <i class="fas fa-plus me-1"></i>ترتيب موعد جديد
                            </a>
                        </div>
                    @endif
                </div>

                <!-- تبويب الملاحظات -->
                <div class="tab-pane" id="notes" role="tabpanel">
                    @if (isset($ClientRelations) && count($ClientRelations) > 0)
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="mb-0">
                                <i class="fa fa-sticky-note me-2" style="color: var(--primary-color);"></i>
                                إجمالي {{ count($ClientRelations) }} ملاحظة
                            </h6>
                            <div class="d-flex gap-2">
                                <a href="{{ route('clients.notes.pdf', $client->id) }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-file-pdf me-1"></i>طباعة PDF
                                </a>
                                <a href="{{ route('appointment.notes.create', $client->id) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus me-1"></i>إضافة ملاحظة
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            @foreach ($ClientRelations as $note)
                                <div class="col-12 mb-3">
                                    <div class="info-card note-card-item">
                                        <div class="card-body">
                                            <!-- رأس الملاحظة -->
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <i class="fa fa-user me-2"
                                                            style="color: var(--primary-color);"></i>
                                                        {{ $note->employee->name ?? 'غير معروف' }}
                                                    </h6>
                                                    @if ($note->process)
                                                        <span class="badge"
                                                            style="background-color: var(--primary-color);">
                                                            <i class="fa fa-tag me-1"></i>{{ $note->process }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <small class="text-muted">
                                                    <i class="fa fa-clock me-1"></i>
                                                    {{ $note->created_at->format('Y-m-d H:i') }}
                                                </small>
                                            </div>

                                            <hr>

                                            <!-- وصف الملاحظة -->
                                            <div class="note-description mb-3">
                                                <p class="mb-0">{{ $note->description ?? 'لا يوجد وصف' }}</p>
                                            </div>

                                            <!-- معلومات إضافية -->
                                            @if ($note->deposit_count || $note->site_type || $note->competitor_documents)
                                                <div class="additional-info bg-light rounded p-3 mb-3">
                                                    <div class="row">
                                                        @if ($note->deposit_count)
                                                            <div class="col-md-4 mb-2">
                                                                <small style="color: var(--primary-color);">
                                                                    <i class="fa fa-boxes me-1"></i>عدد العهدة:
                                                                </small>
                                                                <strong
                                                                    class="d-block">{{ $note->deposit_count }}</strong>
                                                            </div>
                                                        @endif
                                                        @if ($note->site_type)
                                                            <div class="col-md-4 mb-2">
                                                                <small style="color: var(--primary-color);">
                                                                    <i class="fa fa-store me-1"></i>نوع الموقع:
                                                                </small>
                                                                <strong class="d-block">
                                                                    @switch($note->site_type)
                                                                        @case('independent_booth')
                                                                            بسطة مستقلة
                                                                        @break

                                                                        @case('grocery')
                                                                            بقالة
                                                                        @break

                                                                        @case('supplies')
                                                                            تموينات
                                                                        @break

                                                                        @case('markets')
                                                                            أسواق
                                                                        @break

                                                                        @case('station')
                                                                            محطة
                                                                        @break

                                                                        @default
                                                                            {{ $note->site_type }}
                                                                    @endswitch
                                                                </strong>
                                                            </div>
                                                        @endif
                                                        @if ($note->competitor_documents)
                                                            <div class="col-md-4 mb-2">
                                                                <small style="color: var(--primary-color);">
                                                                    <i class="fa fa-file-contract me-1"></i>استندات
                                                                    المنافسين:
                                                                </small>
                                                                <strong
                                                                    class="d-block">{{ $note->competitor_documents }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- المرفقات -->
                                            @php
                                                $files = json_decode($note->attachments, true);
                                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
                                            @endphp

                                            @if (is_array($files) && count($files) > 0)
                                                <div class="attachments-section mb-3">
                                                    <h6 class="mb-3">
                                                        <i class="fa fa-paperclip me-2"
                                                            style="color: var(--primary-color);"></i>
                                                        المرفقات ({{ count($files) }})
                                                    </h6>
                                                    <div class="row g-3">
                                                        @foreach ($files as $file)
                                                            @php
                                                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                                $fileUrl = asset('assets/uploads/notes/' . $file);
                                                                $fileName = basename($file);
                                                            @endphp

                                                            @if (in_array($ext, $imageExtensions))
                                                                <!-- عرض الصور -->
                                                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                                                    <a href="{{ $fileUrl }}"
                                                                        data-fancybox="gallery-{{ $note->id }}"
                                                                        data-caption="{{ $fileName }}">
                                                                        <div class="attachment-image-wrapper">
                                                                            <img src="{{ $fileUrl }}"
                                                                                alt="{{ $fileName }}"
                                                                                class="img-thumbnail attachment-img">
                                                                            <div class="attachment-overlay">
                                                                                <i class="fa fa-search-plus"></i>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <!-- عرض الملفات الأخرى -->
                                                                <div class="col-12 col-sm-6 col-md-4">
                                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                                        class="btn btn-unified btn-sm w-100 text-truncate"
                                                                        title="{{ $fileName }}">
                                                                        <i
                                                                            class="fa fa-file-{{ $ext == 'pdf' ? 'pdf' : 'alt' }} me-2"></i>
                                                                        {{ Str::limit($fileName, 20) }}
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- أزرار الإجراءات -->
                                            <div class="note-actions pt-3 border-top">
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <button class="btn btn-outline-primary btn-sm print-note"
                                                        data-note-id="{{ $note->id }}">
                                                        <i class="fa fa-print me-1"></i>طباعة
                                                    </button>
                                                    <button class="btn btn-outline-primary btn-sm edit-note"
                                                        data-note-id="{{ $note->id }}">
                                                        <i class="fa fa-edit me-1"></i>تعديل
                                                    </button>
                                                    <form action="{{ route('appointment.notes.destroy', $note->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                            onclick="return confirm('هل أنت متأكد من حذف هذه الملاحظة؟')">
                                                            <i class="fa fa-trash me-1"></i>حذف
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-sticky-note"></i>
                            <h5>لا توجد ملاحظات</h5>
                            <p>لم يتم إضافة أي ملاحظات لهذا العميل بعد</p>
                            <a href="{{ route('appointment.notes.create', $client->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>إضافة ملاحظة جديدة
                            </a>
                        </div>
                    @endif
                </div>

                <!-- تبويب الزيارات -->
                <div class="tab-pane" id="visits" role="tabpanel">
                    @if (isset($visits) && $visits->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم الزيارة</th>
                                        <th>تاريخ الزيارة</th>
                                        <th>وقت الانصراف</th>
                                        <th>الموظف</th>
                                        <th>ملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($visits as $visit)
                                        <tr>
                                            <td><strong>#{{ $visit->id }}</strong></td>
                                            <td>{{ $visit->visit_date }}</td>
                                            <td>{{ $visit->departure_time ?? 'لم ينصرف بعد' }}</td>
                                            <td>{{ $visit->employee->name ?? 'غير محدد' }}</td>
                                            <td>{{ Str::limit($visit->notes, 50) ?? '--' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-walking"></i>
                            <h5>لا توجد زيارات</h5>
                            <p>لم يتم تسجيل أي زيارات لهذا العميل بعد</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal حذف العميل -->
    <div class="modal fade" id="modal_DELETE1" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--danger-color); color: white;">
                    <h5 class="modal-title">حذف العميل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد من حذف هذا العميل؟</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>تحذير:</strong> سيؤدي حذف العميل إلى حذف جميع البيانات المرتبطة به
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">حذف نهائي</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal الرصيد الافتتاحي -->
    <div class="modal fade" id="modal_opening_balance" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--success-color); color: white;">
                    <h5 class="modal-title">إضافة رصيد افتتاحي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="openingBalanceForm" action="{{ route('clients.updateOpeningBalance', $client->id) }}"
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">المبلغ ({!! $currencySymbol !!})</label>
                            <input type="number" step="0.01" class="form-control" name="opening_balance"
                                value="{{ $client->opening_balance ?? 0 }}" required>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            سيتم إضافة هذا المبلغ كرصيد افتتاحي للعميل
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal تعيين الموظفين -->
    <div class="modal fade" id="assignEmployeeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--primary-color); color: white;">
                    <h5 class="modal-title">تعيين موظفين للعميل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('clients.assign-employees', $client->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اختر الموظفين</label>
                            <select name="employee_id[]" multiple class="form-control select2">
                                @if (isset($employees))
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            @if ($client->employees && $client->employees->contains('id', $employee->id)) selected @endif>
                                            {{ $employee->full_name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i>تعيين
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    <script>
        $(document).ready(function() {
            // تفعيل Fancybox للصور
            Fancybox.bind("[data-fancybox]", {
                Toolbar: {
                    display: {
                        left: ["infobar"],
                        middle: [],
                        right: ["slideshow", "zoom", "fullscreen", "download", "close"],
                    },
                },
                Images: {
                    zoom: true,
                },
            });

            // فلترة المواعيد
            $('.filter-appointments').click(function() {
                const filter = $(this).data('filter');
                $('.filter-appointments').removeClass('active');
                $(this).addClass('active');

                $('#appointments tbody tr').each(function() {
                    const rowStatus = $(this).data('status');
                    if (filter === 'all' || rowStatus == filter) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // تفعيل Select2
            if ($.fn.select2) {
                $('.select2').select2({
                    placeholder: 'اختر الموظفين',
                    allowClear: true,
                    dropdownParent: $('#assignEmployeeModal')
                });
            }

            // طباعة الملاحظة
            $('.print-note').on('click', function() {
                const noteCard = $(this).closest('.note-card-item');
                printNote(noteCard);
            });

            // معالجة نموذج الرصيد الافتتاحي
            $('#openingBalanceForm').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'جاري الحفظ...',
                    text: 'يرجى الانتظار',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'تم بنجاح!',
                                text: 'تم إضافة الرصيد الافتتاحي بنجاح',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                $('#modal_opening_balance').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'خطأ!',
                                text: response.message || 'حدث خطأ أثناء الحفظ',
                                icon: 'error',
                                confirmButtonText: 'موافق',
                                confirmButtonColor: '#5B51D8'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'حدث خطأ غير متوقع';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            title: 'خطأ!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'موافق',
                            confirmButtonColor: '#5B51D8'
                        });
                    }
                });
            });
        });

        // وظيفة طباعة الملاحظة
        function printNote(noteElement) {
            const printContent = noteElement.clone();

            // إزالة الأزرار
            printContent.find('.note-actions').remove();
            printContent.find('.attachment-overlay').remove();

            // إنشاء نافذة طباعة
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html dir="rtl" lang="ar">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>طباعة الملاحظة</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                    <style>
                        body {
                            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                            padding: 20px;
                            background: white;
                        }
                        .print-header {
                            text-align: center;
                            border-bottom: 3px solid #5B51D8;
                            padding-bottom: 20px;
                            margin-bottom: 30px;
                        }
                        .print-header h2 {
                            color: #5B51D8;
                            font-weight: bold;
                            margin: 0;
                        }
                        .print-date {
                            color: #666;
                            font-size: 14px;
                            margin-top: 10px;
                        }
                        .note-card-item {
                            border: 2px solid #5B51D8;
                            border-radius: 10px;
                            padding: 20px;
                        }
                        .attachment-img {
                            max-width: 200px;
                            height: auto;
                            margin: 5px;
                        }
                        @media print {
                            .no-print {
                                display: none !important;
                            }
                            body {
                                padding: 0;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h2><i class="fa fa-sticky-note me-2"></i>ملاحظة العميل</h2>
                        <div class="print-date">
                            <i class="fa fa-calendar me-1"></i>
                            تاريخ الطباعة: ${new Date().toLocaleDateString('ar-SA')}
                            <i class="fa fa-clock me-1 ms-3"></i>
                            ${new Date().toLocaleTimeString('ar-SA')}
                        </div>
                    </div>
                    <div class="container">
                        ${printContent.html()}
                    </div>
                    <div class="text-center mt-4 no-print">
                        <button onclick="window.print()" class="btn btn-primary" style="background-color: #5B51D8; border: none;">
                            <i class="fa fa-print me-1"></i>طباعة
                        </button>
                        <button onclick="window.close()" class="btn btn-secondary ms-2">
                            <i class="fa fa-times me-1"></i>إغلاق
                        </button>
                    </div>
                </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.focus();

            // طباعة تلقائية بعد تحميل الصفحة
            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                }, 250);
            };
        }

        // معالجة التوست
        @if (session('toast_message'))
            toastr.{{ session('toast_type', 'success') }}('{{ session('toast_message') }}', '', {
                positionClass: 'toast-bottom-left',
                closeButton: true,
                progressBar: true,
                timeOut: 5000,
                progressBar: true
            });
        @endif
    </script>
@endsection
