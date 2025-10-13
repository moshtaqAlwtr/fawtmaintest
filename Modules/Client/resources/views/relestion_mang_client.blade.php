@extends('layouts.blank')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/crm-client.css') }}">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- القائمة الجانبية للعملاء -->
            <div class="col-md-4 col-12" id="clientSidebar">
                <div class="d-flex flex-column h-100">
                    <!-- شريط البحث -->
                    <div class="search-bar">
                        <div class="d-flex gap-1 mb-1 flex-wrap">
                            <button class="btn btn-light border btn-sm" type="button" id="toggleSidebar">
                                <i class="fas fa-bars"></i>
                            </button>
                            <div class="input-group flex-grow-1">
                                <input type="text" class="form-control border-end-0" id="searchInput"
                                    placeholder="البحث عن عميل بالاسم او البريد او الكود او رقم الهاتف...">
                                <span class="input-group-text bg-white border-start-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                            </div>
                            <button class="btn btn-primary btn-sm" type="button" data-toggle="modal"
                                data-target="#addCustomerModal">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- قائمة المجموعات والعملاء -->
                    <div class="clients-list">
                        @if ($clientGroups->count() > 0)
                            <div class="accordion" id="clientGroupsAccordion">
                                @foreach ($clientGroups as $group)
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header" id="heading{{ $group->id }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $group->id }}"
                                                aria-expanded="false" aria-controls="collapse{{ $group->id }}">
                                                <span>{{ $group->name }}</span>
                                                <span class="badge bg-secondary ms-1">
                                                    {{ $group->neighborhoods->pluck('client')->filter()->count() }}
                                                </span>
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $group->id }}" class="accordion-collapse collapse"
                                            aria-labelledby="heading{{ $group->id }}"
                                            data-bs-parent="#clientGroupsAccordion">
                                            <div class="accordion-body p-0">
                                                @php
                                                    $groupClients = $group->neighborhoods->pluck('client')->filter();
                                                @endphp

                                                @if ($groupClients->count() > 0)
                                                    @foreach ($groupClients as $client)
                                                        <div class="client-item p-3 border-bottom"
                                                            data-client-id="{{ $client->id }}"
                                                            onclick="selectClient({{ $client->id }})">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                                                        <span class="badge country-badge">
                                                                            {{ $client->country_code ?? 'SA' }}
                                                                        </span>
                                                                        <span
                                                                            class="client-number text-muted small">#{{ $client->code }}</span>
                                                                        <span
                                                                            class="client-name text-primary fw-medium">{{ $client->trade_name }}</span>
                                                                    </div>
                                                                    <div class="client-info small text-muted mt-1">
                                                                        <i class="far fa-clock me-1"></i>
                                                                        {{ $client->created_at->format('H:i') }} |
                                                                        {{ $client->created_at->format('M d,Y') }}
                                                                    </div>
                                                                    @if ($client->phone)
                                                                        <div class="client-contact small text-muted mt-1">
                                                                            <i class="fas fa-phone-alt me-1"></i>
                                                                            {{ $client->phone }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="p-3 text-center text-muted">
                                                        <i class="fas fa-users-slash"></i>
                                                        <p class="mb-0 small">لا يوجد عملاء في هذه المجموعة</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-search"></i>
                                <h6>لا توجد مجموعات عملاء</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- تفاصيل العميل -->
            <div class="col-md-8 col-12" id="clientDetails">
                <!-- شريط التحكم -->
                <div class="control-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="btn-group btn-group-sm w-100 w-md-auto" role="group">
                        <button type="button" class="btn btn-outline-primary" id="prevButton">
                            <i class="fas fa-chevron-right"></i> السابق
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="nextButton">
                            التالي <i class="fas fa-chevron-left"></i>
                        </button>
                    </div>

                    <div class="d-flex gap-2 justify-content-center w-100 w-md-auto">
                        <button type="button" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-print"></i>
                        </button>
                        <a href="#" id="editClientButton" class="btn btn-outline-primary btn-sm disabled">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>

                <div class="p-3">
                    <!-- رسالة افتراضية -->
                    <div id="defaultMessage" class="empty-state">
                        <i class="fas fa-user-circle"></i>
                        <h5 class="mb-2">اختر عميلاً لعرض تفاصيله</h5>
                        <p class="mb-0">انقر على أي عميل من القائمة الجانبية لعرض معلوماته والملاحظات الخاصة به</p>
                    </div>

                    <!-- تفاصيل العملاء -->
                    @foreach ($clients as $clientData)
                        <div class="client-details-section" id="client-{{ $clientData['id'] }}" style="display: none;">
                            <!-- معلومات العميل الأساسية -->
                            <div class="client-basic-info">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 col-12 info-item">
                                        <div class="info-value">{{ $clientData['name'] ?? '--' }}</div>
                                        <div class="info-label">الاسم التجاري</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 info-item">
                                        <div class="info-value">{{ $clientData['phone'] ?? '--' }}</div>
                                        <div class="info-label">رقم الهاتف</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 info-item">
                                        <div class="info-value"
                                            style="color: {{ $clientData['balance'] >= 0 ? 'var(--success)' : 'var(--danger)' }}">
                                            {{ number_format($clientData['balance'], 2) }} ر.س
                                        </div>
                                        <div class="info-label">الرصيد</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 info-item">
                                        <div class="info-value">{{ count($clientData['invoices']) }}</div>
                                        <div class="info-label">إجمالي الفواتير</div>
                                    </div>
                                </div>
                            </div>

                            <!-- علامات التبويب -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#invoices-{{ $clientData['id'] }}" type="button"
                                        role="tab">
                                        الفواتير
                                        <span class="badge bg-primary ms-1">{{ count($clientData['invoices']) }}</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#notes-{{ $clientData['id'] }}" type="button" role="tab">
                                        الملاحظات
                                        <span
                                            class="badge bg-secondary ms-1">{{ count($clientData['clientRelations']) }}</span>
                                    </button>
                                </li>
                            </ul>

                            <!-- محتوى التبويبات -->
                            <div class="tab-content">
                                <!-- تبويب الفواتير -->
                                <div class="tab-pane fade show active" id="invoices-{{ $clientData['id'] }}"
                                    role="tabpanel">
                                    @if (count($clientData['invoices']) > 0)
                                        <!-- إحصائيات سريعة -->
                                        <div class="stats-row mb-4">
                                            @php
                                                $totalAmount = collect($clientData['invoices'])->sum('amount');
                                                $totalPaid = collect($clientData['invoices'])->sum('total_payments');
                                                $totalRemaining = $totalAmount - $totalPaid; //collect($clientData['invoices'])->sum('remaining');
                                                $paidInvoices = collect($clientData['invoices'])
                                                    ->where('is_paid', true)
                                                    ->count();
                                            @endphp
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h6>إجمالي المبلغ</h6>
                                                    <h4>{{ number_format($totalAmount, 2) }}</h4>
                                                </div>
                                            </div>
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h6>المدفوع</h6>
                                                    <h4 style="color: var(--success)">{{ number_format($totalPaid, 2) }}
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h6>المتبقي</h6>
                                                    <h4 style="color: var(--warning)">
                                                        {{ number_format($totalRemaining, 2) }}</h4>
                                                </div>
                                            </div>
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h6>الفواتير المدفوعة</h6>
                                                    <h4>{{ $paidInvoices }}/{{ count($clientData['invoices']) }}</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover invoice-table">
                                                <thead>
                                                    <tr>
                                                        <th>رقم الفاتورة</th>
                                                        <th>تاريخ الإصدار</th>
                                                        <th>تاريخ الاستحقاق</th>
                                                        <th>المبلغ الإجمالي</th>
                                                        <th>المدفوع</th>
                                                        <th>المتبقي</th>
                                                        <th>الحالة</th>
                                                        <th>الموظف</th>
                                                        <th>التفاصيل</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($clientData['invoices'] as $invoice)
                                                        <tr class="invoice-row">
                                                            <td>
                                                                <strong
                                                                    class="text-primary">#{{ $invoice['number'] }}</strong>
                                                                @if ($invoice['type'])
                                                                    <br><small
                                                                        class="text-muted">{{ $invoice['type'] }}</small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ $invoice['date'] }}
                                                                <br><small
                                                                    class="text-muted">{{ $invoice['created_at'] }}</small>
                                                            </td>
                                                            <td>
                                                                {{ $invoice['issue_date'] ?? 'غير محدد' }}
                                                                @if ($invoice['payment_terms'])
                                                                    <br><small
                                                                        class="text-muted">{{ $invoice['payment_terms'] }}
                                                                        يوم</small>
                                                                @endif
                                                            </td>
                                                            <td class="amount-cell">
                                                                <strong>{{ number_format($invoice['amount'], 2) }}</strong>
                                                                @if ($invoice['currency'] && $invoice['currency'] != 'SAR')
                                                                    <small
                                                                        class="text-muted">{{ $invoice['currency'] }}</small>
                                                                @endif
                                                                @if ($invoice['discount_amount'] > 0)
                                                                    <br><small class="text-success">خصم:
                                                                        {{ number_format($invoice['discount_amount'], 2) }}</small>
                                                                @endif
                                                            </td>
                                                            <td class="amount-cell text-success">
                                                                {{ number_format($invoice['total_payments'], 2) }}
                                                                @if ($invoice['total_payments'] > 0)
                                                                    <div class="payment-progress">
                                                                        <div class="payment-progress-bar"
                                                                            style="width: {{ min(100, ($invoice['total_payments'] / $invoice['amount']) * 100) }}%">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td class="amount-cell text-warning">
                                                                {{ number_format($invoice['amount'] - $invoice['total_payments'], 2) }}
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-{{ $invoice['status_class'] }} status-badge">
                                                                    {{ $invoice['status_text'] }}
                                                                </span>
                                                                @if ($invoice['paymentMethod'])
                                                                    <br><small
                                                                        class="text-muted">{{ $invoice['paymentMethod'] }}</small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ $invoice['employee'] }}
                                                                @if ($invoice['treasury'])
                                                                    <br><small
                                                                        class="text-muted">{{ $invoice['treasury'] }}</small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="d-flex flex-column gap-1">
                                                                    <button class="btn btn-sm btn-outline-primary"
                                                                        onclick="toggleInvoiceDetails({{ $invoice['id'] }})">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    @if (Route::has('invoices.show'))
                                                                        <a href="{{ route('invoices.show', $invoice['id']) }}" 
                                                                           class="btn btn-sm btn-outline-info" 
                                                                           target="_blank">
                                                                            <i class="fas fa-external-link-alt"></i>
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                                @if ($invoice['reference_number'])
                                                                    <br><small class="text-muted">مرجع:
                                                                        {{ $invoice['reference_number'] }}</small>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <!-- تفاصيل إضافية للفاتورة -->
                                                        <tr id="invoice-details-{{ $invoice['id'] }}"
                                                            style="display: none;">
                                                            <td colspan="9">
                                                                <div class="invoice-details">
                                                                    <div class="row">
                                                                        <div class="col-md-4 col-sm-12 col-12">
                                                                            <strong>تفاصيل المبالغ:</strong>
                                                                            <ul class="list-unstyled mt-2">
                                                                                <li>المبلغ الفرعي:
                                                                                    {{ number_format($invoice['subtotal'], 2) }}
                                                                                </li>
                                                                                @if ($invoice['tax_total'] > 0)
                                                                                    <li>الضريبة:
                                                                                        {{ number_format($invoice['tax_total'], 2) }}
                                                                                    </li>
                                                                                @endif
                                                                                @if ($invoice['discount_amount'] > 0)
                                                                                    <li class="text-success">الخصم:
                                                                                        {{ number_format($invoice['discount_amount'], 2) }}
                                                                                    </li>
                                                                                @endif
                                                                                @if ($invoice['shipping_cost'] > 0)
                                                                                    <li>تكلفة الشحن:
                                                                                        {{ number_format($invoice['shipping_cost'], 2) }}
                                                                                    </li>
                                                                                @endif
                                                                                @if ($invoice['adjustment_label'] && $invoice['adjustment_value'] != 0)
                                                                                    <li>{{ $invoice['adjustment_label'] }}:
                                                                                        {{ number_format($invoice['adjustment_value'], 2) }}
                                                                                    </li>
                                                                                @endif
                                                                            </ul>
                                                                        </div>
                                                                        <div class="col-md-4 col-sm-12 col-12">
                                                                            <strong>معلومات إضافية:</strong>
                                                                            <ul class="list-unstyled mt-2">
                                                                                <li>عدد الأصناف:
                                                                                    {{ $invoice['items_count'] }}</li>
                                                                                <li>آخر تحديث: {{ $invoice['updated_at'] }}
                                                                                </li>
                                                                                @if ($invoice['items_count'] > 0)
                                                                                    <li>
                                                                                        <button class="btn btn-sm btn-outline-secondary mt-2" 
                                                                                                onclick="toggleInvoiceItems({{ $invoice['id'] }})">
                                                                                            عرض الأصناف
                                                                                        </button>
                                                                                    </li>
                                                                                @endif
                                                                            </ul>
                                                                        </div>
                                                                        <div class="col-md-4 col-sm-12 col-12">
                                                                            @if ($invoice['notes'])
                                                                                <strong>ملاحظات:</strong>
                                                                                <p class="mt-2 text-muted">
                                                                                    {{ $invoice['notes'] }}</p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <!-- تفاصيل الأصناف -->
                                                                    <div id="invoice-items-{{ $invoice['id'] }}" style="display: none;" class="mt-3">
                                                                        <h6>الأصناف:</h6>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-sm table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>المنتج</th>
                                                                                        <th>الوصف</th>
                                                                                        <th>الكمية</th>
                                                                                        <th>السعر</th>
                                                                                        <th>الخصم</th>
                                                                                        <th>الضرائب</th>
                                                                                        <th>الإجمالي</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($invoice['items'] as $item)
                                                                                        <tr>
                                                                                            <td>{{ $item['product_name'] }}</td>
                                                                                            <td>{{ $item['description'] ?? '-' }}</td>
                                                                                            <td>{{ $item['quantity'] }}</td>
                                                                                            <td>{{ number_format($item['unit_price'], 2) }}</td>
                                                                                            <td>{{ number_format($item['discount'], 2) }}</td>
                                                                                            <td>
                                                                                                @if ($item['tax_1'] > 0 || $item['tax_2'] > 0)
                                                                                                    {{ $item['tax_1'] }}% + {{ $item['tax_2'] }}%
                                                                                                @else
                                                                                                    -
                                                                                                @endif
                                                                                            </td>
                                                                                            <td>{{ number_format($item['total'], 2) }}</td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
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
                                            <i class="fas fa-file-invoice"></i>
                                            <h6>لا توجد فواتير لهذا العميل</h6>
                                        </div>
                                    @endif
                                </div>

                                <!-- تبويب الملاحظات -->
                                <div class="tab-pane fade" id="notes-{{ $clientData['id'] }}" role="tabpanel">
                                    @if (count($clientData['clientRelations']) > 0)
                                        <div class="timeline">
                                            @foreach ($clientData['clientRelations'] as $relation)
                                                <div class="timeline-item">
                                                    <div class="timeline-content">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                                                            <div>
                                                                <span class="text-muted small">
                                                                    <i class="fas fa-calendar me-1"></i>
                                                                    {{ $relation['created_at'] ? $relation['created_at']->format('d/m/Y h:i A') : 'تاريخ غير معروف' }}
                                                                </span>
                                                                <span class="text-muted small ms-2">
                                                                    <i class="fas fa-user me-1"></i>
                                                                    بواسطة: {{ $relation['employee'] }}
                                                                </span>
                                                            </div>
                                                            <div class="mt-1">
                                                                @if ($relation['process'])
                                                                    <span class="badge bg-primary me-1">
                                                                        <i class="fas fa-tasks me-1"></i>
                                                                        {{ $relation['process'] }}
                                                                    </span>
                                                                @endif
                                                                @if ($relation['status'])
                                                                    <span class="badge bg-secondary">
                                                                        <i class="fas fa-info-circle me-1"></i>
                                                                        {{ $relation['status'] }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        @if ($relation['description'])
                                                            <div class="mb-2 p-2 bg-white border rounded">
                                                                <p class="mb-0">{{ $relation['description'] }}</p>
                                                            </div>
                                                        @endif

                                                        <div class="row g-2">
                                                            @if ($relation['date'] || $relation['time'])
                                                                <div class="col-md-6 col-12">
                                                                    <div class="d-flex align-items-center text-muted small">
                                                                        <i class="fas fa-clock me-2"></i>
                                                                        <div>
                                                                            @if ($relation['date'])
                                                                                <div>التاريخ: {{ $relation['date'] }}</div>
                                                                            @endif
                                                                            @if ($relation['time'])
                                                                                <div>الوقت: {{ $relation['time'] }}</div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($relation['site_type'])
                                                                <div class="col-md-6 col-12">
                                                                    <div class="d-flex align-items-center text-muted small">
                                                                        <i class="fas fa-map-marker-alt me-2"></i>
                                                                        <span>نوع الموقع: {{ $relation['site_type_text'] ?? $relation['site_type'] }}</span>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($relation['deposit_count'])
                                                                <div class="col-md-6 col-12">
                                                                    <div class="d-flex align-items-center text-muted small">
                                                                        <i class="fas fa-coins me-2"></i>
                                                                        <span>عدد الودائع: {{ $relation['deposit_count'] }}</span>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($relation['invoice_id'])
                                                                <div class="col-md-6 col-12">
                                                                    <div class="d-flex align-items-center text-muted small">
                                                                        <i class="fas fa-file-invoice me-2"></i>
                                                                        <span>رقم الفاتورة: {{ $relation['invoice_id'] }}</span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        @if (!empty($relation['attachments_array']) && is_array($relation['attachments_array']))
                                                            <div class="mt-3">
                                                                <h6 class="text-muted small mb-2">
                                                                    <i class="fas fa-paperclip me-1"></i>
                                                                    المرفقات:
                                                                </h6>
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach ($relation['attachments_array'] as $attachment)
                                                                        @if (!empty($attachment))
                                                                            @php
                                                                                $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                                                                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                                                                            @endphp
                                                                            @if ($isImage)
                                                                                <div class="attachment-item">
                                                                                    <a href="{{ asset('assets/uploads/notes/' . $attachment) }}" 
                                                                                       target="_blank" 
                                                                                       class="attachment-link">
                                                                                        <img src="{{ asset('assets/uploads/notes/' . $attachment) }}" 
                                                                                             alt="مرفق" 
                                                                                             class="attachment-img img-thumbnail">
                                                                                    </a>
                                                                                </div>
                                                                            @else
                                                                                <div class="attachment-item">
                                                                                    <a href="{{ asset('assets/uploads/notes/' . $attachment) }}" 
                                                                                       target="_blank" 
                                                                                       class="attachment-link btn btn-outline-secondary btn-sm">
                                                                                        <i class="fas fa-file me-1"></i>
                                                                                        {{ $extension }}
                                                                                    </a>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($relation['additional_data'] && is_array($relation['additional_data']))
                                                            <div class="mt-3 p-2 bg-light rounded">
                                                                <h6 class="text-muted small mb-2">
                                                                    <i class="fas fa-info-circle me-1"></i>
                                                                    تفاصيل إضافية:
                                                                </h6>
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach ($relation['additional_data'] as $key => $value)
                                                                        @if (!is_null($value) && $value !== '')
                                                                            <span class="badge bg-info-subtle text-info-emphasis">
                                                                                <strong>{{ $key }}:</strong>
                                                                                {{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}
                                                                            </span>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($relation['competitor_documents'])
                                                            <div class="mt-3 p-2 bg-warning-subtle rounded">
                                                                <h6 class="text-muted small mb-2">
                                                                    <i class="fas fa-file-alt me-1"></i>
                                                                    وثائق المنافسين:
                                                                </h6>
                                                                <div class="text-dark">
                                                                    {{ $relation['competitor_documents'] }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="empty-state">
                                            <i class="fas fa-sticky-note"></i>
                                            <h6>لا توجد ملاحظات لهذا العميل</h6>
                                            <p class="mb-0 text-muted">لا توجد أي ملاحظات أو تفاعلات مع هذا العميل حتى الآن</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Mobile toggle button -->
            <button class="mobile-toggle-btn" id="mobileToggleBtn">
                <i class="fas fa-users"></i>
            </button>
        </div>
    </div>

    <script>
        // دالة لاختيار العميل وعرض تفاصيله
        function selectClient(clientId) {
            // إخفاء جميع تفاصيل العملاء
            document.querySelectorAll('.client-details-section').forEach(section => {
                section.style.display = 'none';
            });

            // إخفاء الرسالة الافتراضية
            const defaultMessage = document.getElementById('defaultMessage');
            if (defaultMessage) {
                defaultMessage.style.display = 'none';
            }

            // عرض تفاصيل العميل المحدد
            const clientSection = document.getElementById('client-' + clientId);
            if (clientSection) {
                clientSection.style.display = 'block';
            }

            // تحديث العناصر المحددة في القائمة الجانبية
            document.querySelectorAll('.client-item').forEach(item => {
                item.classList.remove('selected');
                if (item.dataset.clientId == clientId) {
                    item.classList.add('selected');
                }
            });

            // تحديث زر التعديل
            const editButton = document.getElementById('editClientButton');
            if (editButton) {
                editButton.classList.remove('disabled');
                editButton.href = `/clients/${clientId}/edit`;
            }

            // إغلاق القائمة الجانبية على الأجهزة المحمولة بعد اختيار العميل
            if (window.innerWidth <= 768) {
                document.getElementById('clientSidebar').classList.remove('show');
                // تحديث زر التبديل
                const toggleBtn = document.getElementById('mobileToggleBtn');
                if (toggleBtn) {
                    toggleBtn.classList.remove('active');
                }
            }
        }

        // دالة لإظهار/إخفاء تفاصيل الفاتورة
        function toggleInvoiceDetails(invoiceId) {
            const detailsRow = document.getElementById('invoice-details-' + invoiceId);
            const button = event.target.closest('button');
            const icon = button.querySelector('i');

            if (detailsRow.style.display === 'none') {
                detailsRow.style.display = 'table-row';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                detailsRow.style.display = 'none';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // دالة لإظهار/إخفاء تفاصيل الأصناف
        function toggleInvoiceItems(invoiceId) {
            const itemsDiv = document.getElementById('invoice-items-' + invoiceId);
            const button = event.target;

            if (itemsDiv.style.display === 'none') {
                itemsDiv.style.display = 'block';
                button.textContent = 'إخفاء الأصناف';
            } else {
                itemsDiv.style.display = 'none';
                button.textContent = 'عرض الأصناف';
            }
        }

        // دالة البحث المحسنة
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const toggleSidebar = document.getElementById('toggleSidebar');
            const mobileToggleBtn = document.getElementById('mobileToggleBtn');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    const clientItems = document.querySelectorAll('.client-item');
                    const accordionItems = document.querySelectorAll('.accordion-item');

                    if (searchTerm === '') {
                        // إذا كان البحث فارغ، أظهر جميع العناصر
                        clientItems.forEach(item => {
                            item.style.display = 'block';
                        });
                        accordionItems.forEach(item => {
                            item.style.display = 'block';
                        });
                        return;
                    }

                    // البحث في العملاء
                    clientItems.forEach(item => {
                        const clientName = item.querySelector('.client-name')?.textContent
                            .toLowerCase() || '';
                        const clientNumber = item.querySelector('.client-number')?.textContent
                            .toLowerCase() || '';
                        const clientContact = item.querySelector('.client-contact')?.textContent
                            .toLowerCase() || '';

                        if (clientName.includes(searchTerm) ||
                            clientNumber.includes(searchTerm) ||
                            clientContact.includes(searchTerm)) {
                            item.style.display = 'block';
                            // إظهار المجموعة التي تحتوي على العميل
                            const accordionItem = item.closest('.accordion-item');
                            if (accordionItem) {
                                accordionItem.style.display = 'block';
                            }
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // إخفاء المجموعات التي لا تحتوي على نتائج بحث
                    accordionItems.forEach(accordionItem => {
                        const visibleClients = accordionItem.querySelectorAll(
                            '.client-item[style*="block"], .client-item:not([style])');
                        const hasVisibleClients = Array.from(visibleClients).some(client =>
                            client.style.display !== 'none'
                        );

                        if (!hasVisibleClients) {
                            accordionItem.style.display = 'none';
                        }
                    });
                });
            }

            // تحسين أزرار التنقل
            const prevButton = document.getElementById('prevButton');
            const nextButton = document.getElementById('nextButton');

            if (prevButton && nextButton) {
                prevButton.addEventListener('click', function() {
                    const currentSelected = document.querySelector('.client-item.selected');
                    if (currentSelected) {
                        const allVisibleClients = Array.from(document.querySelectorAll('.client-item'))
                            .filter(item => item.style.display !== 'none');
                        const currentIndex = allVisibleClients.indexOf(currentSelected);

                        if (currentIndex > 0) {
                            const prevClient = allVisibleClients[currentIndex - 1];
                            const clientId = prevClient.dataset.clientId;
                            selectClient(clientId);
                        }
                    }
                });

                nextButton.addEventListener('click', function() {
                    const currentSelected = document.querySelector('.client-item.selected');
                    if (currentSelected) {
                        const allVisibleClients = Array.from(document.querySelectorAll('.client-item'))
                            .filter(item => item.style.display !== 'none');
                        const currentIndex = allVisibleClients.indexOf(currentSelected);

                        if (currentIndex < allVisibleClients.length - 1) {
                            const nextClient = allVisibleClients[currentIndex + 1];
                            const clientId = nextClient.dataset.clientId;
                            selectClient(clientId);
                        }
                    }
                });
            }

            // زر تبديل القائمة الجانبية
            if (toggleSidebar) {
                toggleSidebar.addEventListener('click', function() {
                    const sidebar = document.getElementById('clientSidebar');
                    sidebar.classList.toggle('show');
                });
            }

            // تهيئة العرض بناءً على حجم الشاشة عند التحميل
            const sidebar = document.getElementById('clientSidebar');

            // زر تبديل الجوال
            if (mobileToggleBtn) {
                mobileToggleBtn.addEventListener('click', function() {
                    const sidebar = document.getElementById('clientSidebar');
                    sidebar.classList.toggle('show');
                    this.classList.toggle('active');
                });
            }

            if (window.innerWidth > 768) {
                // على الشاشات الكبيرة، إظهار القائمة الجانبية
                sidebar.classList.add('show');
                // إخفاء زر التبديل على الشاشات الكبيرة
                if (mobileToggleBtn) {
                    mobileToggleBtn.style.display = 'none';
                }
            } else {
                // على الشاشات الصغيرة، إظهار زر التبديل
                if (mobileToggleBtn) {
                    mobileToggleBtn.style.display = 'flex';
                }
            }
        });

        // تحسين الأكورديون لفتح المجموعة عند البحث
        function expandGroupWithVisibleClients() {
            const accordionItems = document.querySelectorAll('.accordion-item');

            accordionItems.forEach(accordionItem => {
                const visibleClients = accordionItem.querySelectorAll(
                    '.client-item[style*="block"], .client-item:not([style])');
                const hasVisibleClients = Array.from(visibleClients).some(client =>
                    client.style.display !== 'none'
                );

                if (hasVisibleClients) {
                    const collapseElement = accordionItem.querySelector('.accordion-collapse');
                    const button = accordionItem.querySelector('.accordion-button');

                    if (collapseElement && button) {
                        collapseElement.classList.add('show');
                        button.classList.remove('collapsed');
                        button.setAttribute('aria-expanded', 'true');
                    }
                }
            });
        }

        // إغلاق القائمة الجانبية عند النقر خارجها على الأجهزة المحمولة
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('clientSidebar');
            const toggleButton = document.getElementById('toggleSidebar');
            const mobileBtn = document.getElementById('mobileToggleBtn');

            if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                // التحقق مما إذا كان النقر خارج القائمة الجانبية
                if (!sidebar.contains(event.target) && event.target !== toggleButton && event.target !== mobileBtn) {
                    sidebar.classList.remove('show');
                    // تحديث زر التبديل
                    if (mobileBtn) {
                        mobileBtn.classList.remove('active');
                    }
                }
            }
        });

        // معالج تغيير حجم الشاشة
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('clientSidebar');
            const mobileBtn = document.getElementById('mobileToggleBtn');

            // إدارة العرض بناءً على حجم الشاشة
            if (window.innerWidth <= 768) {
                // على الشاشات الصغيرة، نبقي القائمة مغلقة حتى يتم فتحها يدويًا
                // لا نقوم بتغيير حالة العرض تلقائيًا
                // إظهار زر التبديل على الشاشات الصغيرة
                if (mobileBtn) {
                    mobileBtn.style.display = 'flex';
                }
            } else {
                // فتح القائمة الجانبية تلقائيًا على الشاشات الكبيرة
                sidebar.classList.add('show');
                // إخفاء زر التبديل على الشاشات الكبيرة
                if (mobileBtn) {
                    mobileBtn.style.display = 'none';
                }
            }

            // إعادة تحميل الخريطة إذا كانت موجودة
            if (typeof mapManager !== 'undefined' && mapManager.map) {
                setTimeout(() => {
                    google.maps.event.trigger(mapManager.map, 'resize');
                }, 300);
            }
        });
    </script>

@endsection