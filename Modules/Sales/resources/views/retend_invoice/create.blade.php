@extends('master')

@section('title')
    إنشاء فاتورة مرتجعة
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/purch.css') }}">
@endsection

@section('content')
    <div class="content-body">
        <form id="return-invoice-form" action="{{ route('ReturnIInvoices.store') }}" method="post" onsubmit="return confirmSubmit(event)">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- كارد الأزرار الرئيسية -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="text-danger mb-2">
                                <i class="fa fa-undo"></i> فاتورة مرتجعة
                            </h5>
                            <label>الحقول التي عليها علامة <span style="color: red">*</span> إلزامية</label>
                        </div>

                        <div class="d-flex">
                            <div class="btn-group mr-2" style="margin-left: 10px;">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyLastReturn()"
                                    title="نسخ آخر مرتجع">
                                    <i class="fa fa-copy"></i> نسخ
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('ReturnIInvoices.index') }}" class="btn btn-outline-danger">
                                    <i class="fa fa-ban"></i> إلغاء
                                </a>
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fa fa-save"></i> حفظ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- كارد الإعدادات المفعلة -->
            @if (isset($salesSettings) && count($salesSettings) > 0)
                <div class="card mb-3">
                    <div class="card-body py-3">
                        <div class="settings-card p-3">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fas fa-cogs text-primary pulse-animation" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-2 text-primary font-weight-bold">
                                        <i class="fas fa-check-circle me-1"></i>
                                        الإعدادات المفعلة للمرتجعات
                                    </h6>
                                    <div class="d-flex flex-wrap">
                                        @if (in_array('auto_inventory_update', $salesSettings))
                                            <span class="setting-badge bg-warning text-dark">
                                                <i class="fas fa-boxes"></i>
                                                إرجاع للمخزون تلقائياً
                                            </span>
                                        @endif

                                        @if (in_array('default_paid_invoices', $salesSettings))
                                            <span class="setting-badge bg-success text-white">
                                                <i class="fas fa-money-bill-wave"></i>
                                                إرجاع المبلغ تلقائياً
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- كارد معلومات الفاتورة الأصلية -->
            <div class="card mb-3 border-danger">
                <div class="card-body py-3 bg-light">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-invoice text-danger me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h6 class="mb-1 text-danger">الفاتورة الأصلية</h6>
                            <p class="mb-0">
                                <strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number ?? 'غير محدد' }} |
                                <strong>التاريخ:</strong> {{ $invoice->invoice_date ?? 'غير محدد' }} |
                                <strong>المبلغ:</strong> {{ number_format($invoice->grand_total ?? 0, 2) }} ريال
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- صف بيانات العميل والمرتجع -->
            <div class="row">
                <!-- بيانات العميل -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>العميل:<span class="text-danger">*</span></span>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2" id="clientSelect" name="client_id"
                                                    required onchange="showClientBalance(this)">
                                                    <option value="">اختر العميل</option>
                                                    @foreach ($clients as $c)
                                                        <option value="{{ $c->id }}"
                                                            data-balance="{{ $c->account->balance ?? 0 }}"
                                                            data-name="{{ $c->trade_name }}"
                                                            {{ $c->id == $invoice->client_id ? 'selected' : '' }}>
                                                            {{ $c->trade_name }} - {{ $c->code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="{{ route('clients.create') }}" type="button"
                                                    class="btn btn-secondary mr-1 mb-1 waves-effect waves-light">
                                                    <i class="fa fa-user-plus"></i> جديد
                                                </a>
                                            </div>
                                        </div>

                                        <!-- عرض رصيد العميل -->
                                        <div id="clientBalanceCard" class="card mt-2" style="display: none;">
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted">
                                                        <i class="fas fa-wallet me-2"></i>
                                                        رصيد العميل:
                                                    </span>
                                                    <span class="font-weight-bold">
                                                        <span id="clientBalance">0.00</span> ريال
                                                        (<span id="balanceStatus"></span>)
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- بيانات المرتجع -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row add_item">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <span>رقم المرتجع:</span>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control text-danger font-weight-bold"
                                                    value="سيتم إنشاؤه تلقائياً" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <span>تاريخ المرتجع:<span class="text-danger">*</span></span>
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control" type="date" name="invoice_date"
                                                    value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <span>تاريخ الإصدار:</span>
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control" type="date" name="issue_date"
                                                    value="{{ old('issue_date', date('Y-m-d')) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <span>سبب الإرجاع:</span>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="form-control" name="return_reason">
                                                    <option value="">اختر السبب</option>
                                                    <option value="damaged">منتج تالف</option>
                                                    <option value="wrong_item">منتج خاطئ</option>
                                                    <option value="customer_request">طلب العميل</option>
                                                    <option value="quality_issue">مشكلة جودة</option>
                                                    <option value="other">أخرى</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

            <!-- جدول البنود -->
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i>
                            يمكنك تعديل الكميات المرتجعة لكل منتج. الكميات الافتراضية مأخوذة من الفاتورة الأصلية.
                        </div>

                        <input type="hidden" id="products-data" value="{{ json_encode($items) }}">
                        <div class="table-responsive">
                            <table class="table" id="items-table">
                                <thead>
                                    <tr>
                                        <th>المنتج</th>
                                        <th>الوصف</th>
                                        <th>الكمية المرتجعة</th>
                                        <th>السعر</th>
                                        <th>الخصم</th>
                                        <th>الضريبة 1</th>
                                        <th>الضريبة 2</th>
                                        <th>المجموع</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items as $index => $item)
                                        <tr class="item-row">
                                            <td style="width:18%" data-label="المنتج">
                                                <select name="items[{{ $index }}][product_id]"
                                                    class="form-control product-select" required>
                                                    <option value="">اختر المنتج</option>
                                                    @foreach ($items as $product)
                                                        <option value="{{ $product->id }}"
                                                            {{ $product->id == $item->product_id ? 'selected' : '' }}
                                                            data-price="{{ $product->sale_price }}">
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td data-label="الوصف">
                                                <input type="text" name="items[{{ $index }}][description]"
                                                    class="form-control item-description"
                                                    value="{{ $item->description }}"
                                                    placeholder="أدخل الوصف">
                                            </td>
                                            <td data-label="الكمية">
                                                <input type="number" name="items[{{ $index }}][quantity]"
                                                    class="form-control quantity" value="{{ $item->quantity }}"
                                                    min="1" max="{{ $item->quantity }}" required>
                                                <small class="text-muted">الكمية الأصلية: {{ $item->quantity }}</small>
                                            </td>
                                            <td data-label="السعر">
                                                <input type="number" name="items[{{ $index }}][unit_price]"
                                                    class="form-control price" value="{{ $item->unit_price }}"
                                                    step="0.01" required placeholder="0.00">
                                            </td>
                                            <td data-label="الخصم">
                                                <div class="input-group">
                                                    <input type="number" name="items[{{ $index }}][discount]"
                                                        class="form-control discount-amount" value="{{ $item->discount }}"
                                                        min="0" step="0.01">
                                                    <input type="number" name="items[{{ $index }}][discount_percentage]"
                                                        class="form-control discount-percentage" value="0"
                                                        min="0" max="100" step="0.01" style="display: none;">
                                                    <div class="input-group-append">
                                                        <select name="items[{{ $index }}][discount_type]"
                                                            class="form-control discount-type">
                                                            <option value="amount" {{ $item->discount_type == 'amount' ? 'selected' : '' }}>ريال</option>
                                                            <option value="percentage" {{ $item->discount_type == 'percentage' ? 'selected' : '' }}>%</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="الضريبة 1">
                                                <div class="input-group">
                                                    <select name="items[{{ $index }}][tax_1]" class="form-control tax-select"
                                                        data-target="tax_1" onchange="updateHiddenInput(this)">
                                                        <option value="">لا يوجد</option>
                                                        @foreach ($taxs as $tax)
                                                            <option value="{{ $tax->tax }}"
                                                                data-id="{{ $tax->id }}"
                                                                data-name="{{ $tax->name }}"
                                                                data-type="{{ $tax->type }}">
                                                                {{ $tax->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="items[{{ $index }}][tax_1_id]">
                                                </div>
                                            </td>
                                            <td data-label="الضريبة 2">
                                                <div class="input-group">
                                                    <select name="items[{{ $index }}][tax_2]" class="form-control tax-select"
                                                        data-target="tax_2" onchange="updateHiddenInput(this)">
                                                        <option value="">لا يوجد</option>
                                                        @foreach ($taxs as $tax)
                                                            <option value="{{ $tax->tax }}"
                                                                data-id="{{ $tax->id }}"
                                                                data-name="{{ $tax->name }}"
                                                                data-type="{{ $tax->type }}">
                                                                {{ $tax->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="items[{{ $index }}][tax_2_id]">
                                                </div>
                                            </td>
                                            <td data-label="المجموع">
                                                <span class="row-total text-danger font-weight-bold">{{ number_format($item->total, 2) }}</span>
                                            </td>
                                            <td data-label="الإجراءات">
                                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot id="tax-rows">
                                    <tr>
                                        <td colspan="9" class="text-right">
                                            <button type="button" id="add-row" class="btn btn-success">
                                                <i class="fa fa-plus"></i> إضافة صف
                                            </button>
                                        </td>
                                    </tr>
                                    @php
                                        $currency = $account_setting->currency ?? 'SAR';
                                        $currencySymbol =
                                            $currency == 'SAR' || empty($currency)
                                                ? '<img src="' .
                                                    asset('assets/images/Saudi_Riyal.svg') .
                                                    '" alt="ريال سعودي" width="13" style="display: inline-block; margin-left: 5px; vertical-align: middle;">'
                                                : $currency;
                                    @endphp
                                    <tr>
                                        <td colspan="7" class="text-right">المجموع الفرعي</td>
                                        <td><span id="subtotal" class="text-danger font-weight-bold">0.00</span> {!! $currencySymbol !!}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right">مجموع الخصومات</td>
                                        <td><span id="total-discount">0.00</span> {!! $currencySymbol !!}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <small id="tax-details"></small>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right">المبلغ المرتجع</td>
                                        <td><span id="grand-total" class="text-danger font-weight-bold" style="font-size: 1.2em;">0.00</span> {!! $currencySymbol !!}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- كارد التفاصيل الإضافية -->
            <div class="card">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs align-items-center">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-discount" href="#">الخصم</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-documents" href="#">إرفاق المستندات</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <!-- القسم الأول: الخصم -->
                    <div id="section-discount" class="tab-section">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">قيمة الخصم الإضافي</label>
                                <div class="input-group">
                                    <input type="number" name="discount_amount" class="form-control" value="0"
                                        min="0" step="0.01">
                                    <select name="discount_type" class="form-control">
                                        <option value="amount">ريال</option>
                                        <option value="percentage">نسبة مئوية</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- القسم الثاني: المستندات -->
                    <div id="section-documents" class="tab-section d-none">
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-file-upload text-primary me-2"></i>
                                رفع مستندات الإرجاع:
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <input type="file" class="form-control" name="attachments[]" multiple>
                            </div>
                            <small class="text-muted">يمكنك رفع صور المنتجات التالفة أو أي مستندات أخرى</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- كارد الملاحظات -->
            <div class="card shadow-sm border-0">
                <div class="card-header border-bottom" style="background-color: transparent;">
                    <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.2rem;">
                        📝 ملاحظات الإرجاع / سبب تفصيلي
                    </h5>
                </div>
                <div class="card-body">
                    <textarea id="tinyMCE" name="notes" class="form-control" rows="6"
                        placeholder="أضف تفاصيل حول سبب الإرجاع..."
                        style="font-size: 1.05rem;"></textarea>
                </div>
            </div>

            <!-- كارد الدفع/الإرجاع -->
            <div class="card border-success">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-start" style="direction: rtl;">
                        <div class="form-check">
                            <input class="form-check-input payment-toggle" type="checkbox" name="is_paid" value="1"
                                id="full-payment-check" @if (in_array('default_paid_invoices', $salesSettings ?? [])) checked disabled @endif>
                            <label class="form-check-label" for="full-payment-check">
                                تم إرجاع المبلغ للعميل؟
                                @if (in_array('default_paid_invoices', $salesSettings ?? []))
                                    <span class="text-success">
                                        <i class="fas fa-magic"></i> (تلقائي)
                                    </span>
                                @endif
                            </label>
                        </div>
                    </div>

                    <!-- حقول الإرجاع -->
                    <div id="payment-fields" class="mt-3" style="display: none;">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="treasury_id">الخزينة<span class="text-danger">*</span></label>
                                <select class="form-control" name="treasury_id">
                                    <option value="">اختر الخزينة</option>
                                    @foreach ($treasury as $treasur)
                                        <option value="{{ $treasur->id }}">{{ $treasur->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="payment_method">وسيلة الإرجاع</label>
                                <select class="form-control" name="payment_method">
                                    <option value="">اختر وسيلة الإرجاع</option>
                                    <option value="cash">نقداً</option>
                                    <option value="credit_card">بطاقة ائتمان</option>
                                    <option value="bank_transfer">تحويل بنكي</option>
                                    <option value="account_credit">إضافة لحساب العميل</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">رقم المرجع</label>
                                <input type="text" class="form-control" name="reference_number"
                                    placeholder="رقم العملية/الإيصال">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/invoice-calculator.js') }}"></script>

    <script>
        // متغير الإعدادات
        const activeSettings = @json($salesSettings ?? []);
        const defaultPaidEnabled = activeSettings.includes('default_paid_invoices');
        const autoInventoryEnabled = activeSettings.includes('auto_inventory_update');

        document.addEventListener('DOMContentLoaded', function() {
            setupTabs();
            setupEventHandlers();

            // عرض رصيد العميل إذا كان محدد
            const clientSelect = document.getElementById('clientSelect');
            if (clientSelect && clientSelect.value) {
                showClientBalance(clientSelect);
            }

            // حساب الإجماليات عند تحميل الصفحة
            if (typeof calculateTotals === 'function') {
                calculateTotals();
            }
        });

        function setupTabs() {
            document.querySelectorAll('[id^="tab-"]').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
                    document.querySelectorAll('.tab-section').forEach(section => section.classList.add('d-none'));

                    this.classList.add('active');
                    const targetSection = document.getElementById('section-' + this.id.replace('tab-', ''));
                    if (targetSection) {
                        targetSection.classList.remove('d-none');
                    }
                });
            });
        }

        function setupEventHandlers() {
            // معالج تبديل حقول الدفع
            const paymentToggle = document.querySelector('.payment-toggle');
            const paymentFields = document.getElementById('payment-fields');

            if (paymentToggle && paymentFields && !defaultPaidEnabled) {
                paymentToggle.addEventListener('change', function() {
                    paymentFields.style.display = this.checked ? 'block' : 'none';
                });
            }

            // إذا كانت الإعدادات مفعلة، أظهر الحقول تلقائياً
            if (defaultPaidEnabled && paymentFields) {
                paymentFields.style.display = 'block';
            }
        }

        // دالة عرض رصيد العميل
        window.showClientBalance = function(selectElement) {
            const balanceCard = document.getElementById('clientBalanceCard');

            if (!selectElement || !selectElement.value || selectElement.value === '' || selectElement.value === '0') {
                if (balanceCard) {
                    balanceCard.style.display = 'none';
                }
                return;
            }

            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const clientName = selectedOption.text.split(' - ')[0];
            const clientBalance = parseFloat(selectedOption.getAttribute('data-balance')) || 0;

            const balanceElement = document.getElementById('clientBalance');
            const statusElement = document.getElementById('balanceStatus');

            if (balanceElement) balanceElement.textContent = Math.abs(clientBalance).toFixed(2);

            if (statusElement && balanceElement) {
                if (clientBalance > 0) {
                    statusElement.textContent = 'دائن';
                    statusElement.style.color = '#4CAF50';
                    balanceElement.style.color = '#4CAF50';
                } else if (clientBalance < 0) {
                    statusElement.textContent = 'مدين';
                    statusElement.style.color = '#f44336';
                    balanceElement.style.color = '#f44336';
                } else {
                    statusElement.textContent = 'متوازن';
                    statusElement.style.color = '#FFC107';
                    balanceElement.style.color = '#FFC107';
                }
            }

            if (balanceCard) {
                balanceCard.style.display = 'block';
                balanceCard.style.opacity = '0';
                balanceCard.style.transform = 'translateY(-20px)';

                setTimeout(() => {
                    balanceCard.style.transition = 'all 0.3s ease';
                    balanceCard.style.opacity = '1';
                    balanceCard.style.transform = 'translateY(0)';
                }, 10);
            }
        };

        // دالة نسخ آخر مرتجع
        window.copyLastReturn = function() {
            Swal.fire({
                title: 'نسخ آخر مرتجع',
                text: 'هل تريد نسخ بيانات آخر فاتورة مرتجعة؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، انسخ',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    performCopyLastReturn();
                }
            });
        };

        function performCopyLastReturn() {
            fetch('/sales/return-invoices/get-last', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.invoice) {
                    fillReturnData(data.invoice);
                    Swal.fire('تم!', 'تم نسخ بيانات آخر مرتجع بنجاح', 'success');
                } else {
                    Swal.fire('تنبيه', 'لم يتم العثور على مرتجعات سابقة', 'info');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('خطأ', 'حدث خطأ أثناء جلب البيانات', 'error');
            });
        }

        function fillReturnData(returnData) {
            // ملء بيانات العميل
            if (returnData.client_id) {
                const clientSelect = document.getElementById('clientSelect');
                if (clientSelect) {
                    clientSelect.value = returnData.client_id;
                    showClientBalance(clientSelect);
                }
            }

            // ملء الحقول الأساسية
            const basicFields = ['discount_amount', 'discount_type', 'return_reason', 'notes'];

            basicFields.forEach(fieldName => {
                if (returnData[fieldName] !== undefined) {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        field.value = returnData[fieldName];
                    }
                }
            });

            // إعادة حساب الإجماليات
            if (typeof calculateTotals === 'function') {
                calculateTotals();
            }
        }

        // دالة تأكيد الحفظ
        function confirmSubmit(event) {
            event.preventDefault();

            let settingsMessage = '';
            if (activeSettings.length > 0) {
                settingsMessage = '<div class="alert alert-info mt-3 text-start"><strong>الإعدادات المفعلة:</strong><br>';

                if (defaultPaidEnabled) {
                    settingsMessage += '• سيتم إرجاع المبلغ تلقائياً<br>';
                }
                if (autoInventoryEnabled) {
                    settingsMessage += '• سيتم إرجاع المنتجات للمخزون تلقائياً<br>';
                }

                settingsMessage += '</div>';
            }

            Swal.fire({
                title: 'تأكيد حفظ المرتجع',
                html: `<p>هل أنت متأكد من حفظ فاتورة المرتجع؟</p>${settingsMessage}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احفظه!',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('return-invoice-form').submit();
                }
            });
        }

        // دالة تحديث الحقول المخفية للضرائب
        function updateHiddenInput(selectElement) {
            const row = selectElement.closest('tr');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const taxId = selectedOption.getAttribute('data-id');

            // تحديد اسم الحقل المخفي بناءً على الضريبة (tax_1 أو tax_2)
            const name = selectElement.getAttribute('name');
            const hiddenInputName = name.replace('[tax_', '[tax_').replace(']', '_id]');
            const hiddenInput = row.querySelector(`[name="${hiddenInputName}"]`);

            if (hiddenInput) {
                hiddenInput.value = taxId || '';
            }
        }

        // إضافة صف جديد
        document.addEventListener('click', function(e) {
            if (e.target && (e.target.id === 'add-row' || e.target.closest('#add-row'))) {
                const table = document.getElementById('items-table').querySelector('tbody');
                const rowCount = table.querySelectorAll('.item-row').length;
                const newRow = table.querySelector('.item-row').cloneNode(true);

                // تحديث أسماء الحقول
                newRow.querySelectorAll('input, select').forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/\[\d+\]/, `[${rowCount}]`));
                        input.value = input.type === 'number' ? '0' : '';
                    }
                });

                // إعادة تعيين القيم
                newRow.querySelector('.quantity').value = '1';
                newRow.querySelector('.price').value = '';
                newRow.querySelector('.row-total').textContent = '0.00';

                table.appendChild(newRow);

                if (typeof calculateTotals === 'function') {
                    calculateTotals();
                }
            }

            // حذف صف
            if (e.target && (e.target.classList.contains('remove-row') || e.target.closest('.remove-row'))) {
                const table = document.getElementById('items-table').querySelector('tbody');
                if (table.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('.item-row').remove();
                    if (typeof calculateTotals === 'function') {
                        calculateTotals();
                    }
                } else {
                    Swal.fire('تنبيه', 'يجب أن يحتوي المرتجع على منتج واحد على الأقل', 'warning');
                }
            }
        });

        // حساب الإجماليات عند تغيير القيم
        document.addEventListener('input', function(e) {
            if (e.target.matches('.quantity, .price, .discount-amount, .discount-percentage, [name="discount_amount"]')) {
                if (typeof calculateTotals === 'function') {
                    calculateTotals();
                }
            }
        });

        // حساب الإجماليات عند تغيير الضرائب
        document.addEventListener('change', function(e) {
            if (e.target.matches('.tax-select, .discount-type, [name="discount_type"]')) {
                if (typeof calculateTotals === 'function') {
                    calculateTotals();
                }
            }
        });
    </script>
@endsection
