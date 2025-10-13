@extends('master')

@section('title')
    انشاء فاتورة دورية
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/purch.css') }}">
@endsection

@section('content')
    <div class="content-body">
        <form id="periodic-invoice-form" action="{{ route('periodic_invoices.store') }}" method="post" onsubmit="return confirmSubmit(event)">
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
                            <label>الحقول التي عليها علامة <span style="color: red">*</span> الزامية</label>
                        </div>

                        <div class="d-flex">
                            <div class="btn-group mr-2" style="margin-left: 10px;">
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="saveAsDraft()"
                                    title="حفظ كمسودة">
                                    <i class="fa fa-save"></i> مسودة
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyLastInvoice()"
                                    title="نسخ آخر فاتورة دورية">
                                    <i class="fa fa-copy"></i> نسخ
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllItems()"
                                    title="مسح الكل">
                                    <i class="fa fa-trash"></i> مسح
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="showQuickPreview()"
                                    title="معاينة سريعة">
                                    <i class="fa fa-eye"></i> معاينة
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('periodic_invoices.index') }}" class="btn btn-outline-danger">
                                    <i class="fa fa-ban"></i>الغاء
                                </a>
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fa fa-save"></i>حفظ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- كارد خيارات الإصدار الآلي -->
            <div class="card shadow-lg mb-3">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0 text-white" style="font-weight: 600;">
                        <i class="fas fa-cog me-2"></i>خيارات الإصدار الآلي
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- الاشتراك -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold mb-2">
                                    <i class="fas fa-file-contract me-1"></i>الاشتراك
                                </label>
                                <input type="text" class="form-control" placeholder="أدخل تفاصيل الاشتراك"
                                    name="details_subscription" style="border-radius: 10px; padding: 12px;">
                            </div>
                        </div>

                        <!-- إصدار فاتورة كل -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold mb-2">
                                    <i class="fas fa-sync me-1"></i>إصدار فاتورة كل
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" value="1" min="1"
                                        name="repeat_interval" style="border-radius: 10px 0 0 10px; padding: 12px;">
                                    <select class="form-control" name="repeat_type"
                                        style="border-radius: 0 10px 10px 0; padding: 12px;">
                                        <option value="1">يوم</option>
                                        <option value="2">أسبوع</option>
                                        <option value="3">شهر</option>
                                        <option value="4">سنة</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- عدد مرات التكرار -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold mb-2">
                                    <i class="fas fa-redo me-1"></i>عدد مرات التكرار
                                </label>
                                <input type="number" class="form-control" min="1"
                                    placeholder="أدخل عدد التكرار" name="repeat_count"
                                    style="border-radius: 10px; padding: 12px;">
                            </div>
                        </div>

                        <!-- تاريخ أول فاتورة -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold mb-2">
                                    <i class="fas fa-calendar-alt me-1"></i>تاريخ أول فاتورة
                                </label>
                                <input type="date" class="form-control" name="first_invoice_date"
                                    value="{{ date('Y-m-d') }}" style="border-radius: 10px; padding: 12px;">
                            </div>
                        </div>

                        <!-- أصدر الفاتورة قبل -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold mb-2">
                                    <i class="fas fa-clock me-1"></i>أصدر الفاتورة قبل
                                </label>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="number" class="form-control" value="0"
                                        name="invoice_days_offset"
                                        style="border-radius: 10px; width: 120px; padding: 12px;">
                                    <span class="mx-2 fw-bold">أيام</span>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active"
                                            value="1" checked style="width: 3rem; height: 1.5rem;">
                                        <label class="form-check-label fw-bold"
                                            style="margin-right: 20px">نشط</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Checkboxes Section -->
                    <div class="mt-4 p-4" style="background-color: #f8f9fa; border-radius: 15px;">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="auto_generate"
                                style="width: 1.25rem; height: 1.25rem;">
                            <label class="form-check-label fw-bold ms-2">
                                <i class="fas fa-envelope me-1"></i>أرسل لي نسخة من الفاتورة المنشأة
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="show_from_to_dates"
                                style="width: 1.25rem; height: 1.25rem;">
                            <label class="form-check-label fw-bold ms-2">
                                <i class="fas fa-calendar-week me-1"></i>عرض تاريخ "منذ" و "حتى" في الفاتورة
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="disable_partial_payment"
                                style="width: 1.25rem; height: 1.25rem;">
                            <label class="form-check-label fw-bold ms-2">
                                <i class="fas fa-money-bill-wave me-1"></i>تفعيل الدفع التلقائي لهذه الفاتورة
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- صف بيانات العميل والفاتورة -->
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
                                                <span>العميل :</span>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2" id="clientSelect" name="client_id"
                                                    required onchange="showClientBalance(this)">
                                                    <option value="">اختر العميل</option>
                                                    @foreach ($clients as $c)
                                                        <option value="{{ $c->id }}"
                                                            data-balance="{{ $c->account->balance ?? 0 }}"
                                                            data-name="{{ $c->trade_name }}">
                                                            {{ $c->trade_name ?: $c->first_name . ' ' . $c->last_name }} - {{ $c->code ?? '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="{{ route('clients.create') }}" type="button"
                                                    class="btn btn-secondary mr-1 mb-1 waves-effect waves-light">
                                                    <i class="fa fa-user-plus"></i>جديد
                                                </a>
                                            </div>
                                        </div>

                                        <!-- الطريقة -->
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <span>الطريقة :</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" id="methodSelect" name="printing_method">
                                                        <option value="طباعة">طباعة</option>
                                                        <option value="ارسل عبر البريد">ارسل عبر البريد</option>
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

                <!-- بيانات الفاتورة -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row add_item">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <span>شروط الدفع :</span>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control" type="text" name="payment_terms">
                                            </div>
                                            <div class="col-md-2">
                                                <span class="form-control-plaintext">أيام</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <input class="form-control" type="text" placeholder="عنوان إضافي">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" placeholder="بيانات إضافية">
                                                    <div class="input-group-append">
                                                        <button type="button"
                                                            class="btn btn-outline-success waves-effect waves-light addeventmore">
                                                            <i class="fa fa-plus-circle"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- جدول البنود -->
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <input type="hidden" id="products-data" value="{{ json_encode($items) }}">
                        <div class="table-responsive">
                            <table class="table" id="items-table">
                                <thead>
                                    <tr>
                                        <th>المنتج</th>
                                        <th>الوصف</th>
                                        <th>الكمية</th>
                                        <th>السعر</th>
                                        <th>الخصم</th>
                                        <th>الضريبة 1</th>
                                        <th>الضريبة 2</th>
                                        <th>المجموع</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="item-row">
                                        <td style="width:18%" data-label="المنتج">
                                            <select name="items[0][product_id]" class="form-control product-select" required>
                                                <option value="">اختر المنتج</option>
                                                @foreach ($items as $item)
                                                    <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td data-label="الوصف">
                                            <input type="text" name="items[0][description]"
                                                class="form-control item-description" placeholder="أدخل الوصف">
                                        </td>
                                        <td data-label="الكمية">
                                            <input type="number" name="items[0][quantity]" class="form-control quantity"
                                                value="1" min="1" required>
                                        </td>
                                        <td data-label="السعر">
                                            <input type="number" name="items[0][unit_price]" class="form-control price"
                                                value="" step="0.01" required placeholder="0.00">
                                        </td>
                                        <td data-label="الخصم">
                                            <div class="input-group">
                                                <input type="number" name="items[0][discount]"
                                                    class="form-control discount-amount" value="0" min="0" step="0.01">
                                                <input type="number" name="items[0][discount_percentage]"
                                                    class="form-control discount-percentage" value="0"
                                                    min="0" max="100" step="0.01" style="display: none;">
                                                <div class="input-group-append">
                                                    <select name="items[0][discount_type]" class="form-control discount-type">
                                                        <option value="amount">ريال</option>
                                                        <option value="percentage">%</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="الضريبة 1">
                                            <input type="number" name="items[0][tax_1]" class="form-control tax"
                                                value="15" min="0" max="100" step="0.01">
                                        </td>
                                        <td data-label="الضريبة 2">
                                            <input type="number" name="items[0][tax_2]" class="form-control tax"
                                                value="0" min="0" max="100" step="0.01">
                                        </td>
                                        <input type="hidden" name="items[0][store_house_id]" value="2">
                                        <td data-label="المجموع">
                                            <span class="row-total">0.00</span>
                                        </td>
                                        <td data-label="الإجراءات">
                                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
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
                                        $currencySymbol = $currency == 'SAR' || empty($currency)
                                            ? '<img src="' . asset('assets/images/Saudi_Riyal.svg') . '" alt="ريال سعودي" width="13" style="display: inline-block; margin-left: 5px; vertical-align: middle;">'
                                            : $currency;
                                    @endphp
                                    <tr>
                                        <td colspan="7" class="text-right">المجموع الفرعي</td>
                                        <td><span id="subtotal">0.00</span> {!! $currencySymbol !!}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right">مجموع الخصومات</td>
                                        <td><span id="total-discount">0.00</span> {!! $currencySymbol !!}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right">مجموع الضرائب</td>
                                        <td><span id="total-tax">0.00</span> {!! $currencySymbol !!}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right">تكلفة الشحن</td>
                                        <td><span id="shipping-cost">0.00</span> {!! $currencySymbol !!}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right">الدفعة القادمة</td>
                                        <td><span id="next-payment">0.00</span> {!! $currencySymbol !!}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right">المجموع الكلي</td>
                                        <td><span id="grand-total">0.00</span> {!! $currencySymbol !!}</td>
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
                            <a class="nav-link active" id="tab-discount" href="#">الخصم والتسوية</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-shipping" href="#">التوصيل</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-documents" href="#">إرفاق المستندات</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <!-- القسم الأول: الخصم والتسوية -->
                    <div id="section-discount" class="tab-section">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">قيمة الخصم</label>
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

                    <!-- القسم الثالث: التوصيل -->
                    <div id="section-shipping" class="tab-section d-none">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">نوع الضريبة</label>
                                <select class="form-control" id="taxSelect" name="tax_type">
                                    <option value="1">القيمة المضافة (15%)</option>
                                    <option value="2">صفرية</option>
                                    <option value="3">معفاة</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">تكلفة الشحن</label>
                                <input type="number" class="form-control" name="shipping_cost" id="shipping"
                                    value="0" min="0" step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- القسم الرابع: إرفاق المستندات -->
                    <div id="section-documents" class="tab-section d-none">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-new-document" href="#">رفع مستند جديد</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-uploaded-documents" href="#">بحث في الملفات</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3">
                            <div id="content-new-document" class="tab-pane active">
                                <div class="col-12 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-file-upload text-primary me-2"></i>
                                        رفع مستند جديد:
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-upload"></i>
                                        </span>
                                        <input type="file" class="form-control" id="uploadFile"
                                            aria-describedby="uploadButton" name="attachments[]" multiple>
                                        <button class="btn btn-primary" type="button" id="uploadButton">
                                            <i class="fas fa-cloud-upload-alt me-1"></i>
                                            رفع
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div id="content-uploaded-documents" class="tab-pane d-none">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2" style="width: 80%;">
                                                <label class="form-label mb-0" style="white-space: nowrap;">المستند:</label>
                                                <select class="form-select">
                                                    <option selected>اختر مستند</option>
                                                    <option value="1">مستند 1</option>
                                                    <option value="2">مستند 2</option>
                                                    <option value="3">مستند 3</option>
                                                </select>
                                                <button type="button" class="btn btn-success">أرفق</button>
                                            </div>
                                            <button type="button" class="btn btn-primary">
                                                <i class="fas fa-search me-1"></i>
                                                بحث متقدم
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- كارد الملاحظات -->
            <div class="card shadow-sm border-0">
                <div class="card-header border-bottom" style="background-color: transparent;">
                    <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.2rem;">
                        📝 الملاحظات / الشروط
                    </h5>
                </div>
                <div class="card-body">
                    <textarea id="tinyMCE" name="notes" class="form-control" rows="6"
                        style="font-size: 1.05rem;"></textarea>
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
        document.addEventListener('DOMContentLoaded', function() {
            console.log('صفحة إنشاء فاتورة دورية جاهزة');

            // إعداد تبديل التبويبات
            setupTabs();

            // إعداد معالجات الأحداث
            setupEventHandlers();
        });

        // دالة إعداد التبويبات
        function setupTabs() {
            document.querySelectorAll('[id^="tab-"]').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();

                    // إزالة الكلاس النشط من جميع التبويبات
                    document.querySelectorAll('.nav-link').forEach(link => {
                        link.classList.remove('active');
                    });

                    // إخفاء جميع الأقسام
                    document.querySelectorAll('.tab-section').forEach(section => {
                        section.classList.add('d-none');
                    });

                    // تفعيل التبويب المحدد
                    this.classList.add('active');

                    // إظهار القسم المطابق
                    const targetSection = document.getElementById('section-' + this.id.replace('tab-', ''));
                    if (targetSection) {
                        targetSection.classList.remove('d-none');
                    }
                });
            });

            // معالج التبويبات الداخلية للمستندات
            document.querySelectorAll('#section-documents [id^="tab-"]').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();

                    document.querySelectorAll('#section-documents .nav-link').forEach(link => {
                        link.classList.remove('active');
                    });

                    document.querySelectorAll('#section-documents .tab-pane').forEach(pane => {
                        pane.classList.add('d-none');
                        pane.classList.remove('active');
                    });

                    this.classList.add('active');

                    const targetPane = document.getElementById('content-' + this.id.replace('tab-', ''));
                    if (targetPane) {
                        targetPane.classList.remove('d-none');
                        targetPane.classList.add('active');
                    }
                });
            });
        }

        // دالة إعداد معالجات الأحداث
        function setupEventHandlers() {
            // معالج اختيار العميل
            const clientSelect = document.getElementById('clientSelect');
            if (clientSelect && clientSelect.value) {
                showClientBalance(clientSelect);
            }
        }

        // دالة إظهار رصيد العميل
        window.showClientBalance = function(selectElement) {
            const balanceCard = document.getElementById('clientBalanceCard');

            if (!selectElement || !selectElement.value || selectElement.value === '' ||
                selectElement.value === '0' || selectElement.selectedIndex === 0) {
                if (balanceCard) {
                    balanceCard.style.display = 'none';
                }
                return;
            }

            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const clientName = selectedOption.text.split(' - ')[0];
            const clientBalance = parseFloat(selectedOption.getAttribute('data-balance')) || 0;

            const nameElement = document.getElementById('clientName');
            const balanceElement = document.getElementById('clientBalance');
            const statusElement = document.getElementById('balanceStatus');

            if (nameElement) nameElement.textContent = clientName;
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

        // دالة نسخ آخر فاتورة
        window.copyLastInvoice = function() {
            Swal.fire({
                title: 'نسخ آخر فاتورة دورية',
                text: 'هل تريد نسخ بيانات آخر فاتورة دورية؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، انسخ',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    performCopyLastInvoice();
                }
            });
        };

        function performCopyLastInvoice() {
            fetch('/periodic-invoices/get-last-invoice', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.invoice) {
                    fillInvoiceData(data.invoice);
                    showNotification('تم نسخ بيانات آخر فاتورة دورية بنجاح', 'success');
                } else {
                    showNotification('لم يتم العثور على فواتير دورية سابقة', 'info');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('لم يتم العثور على فواتير دورية سابقة', 'info');
            });
        }

        function fillInvoiceData(invoiceData) {
            if (invoiceData.client_id) {
                const clientSelect = document.getElementById('clientSelect');
                if (clientSelect) {
                    clientSelect.value = invoiceData.client_id;
                    showClientBalance(clientSelect);
                }
            }

            const basicFields = [
                'payment_terms', 'discount_amount', 'discount_type', 'shipping_cost',
                'details_subscription', 'repeat_interval', 'repeat_type', 'repeat_count',
                'invoice_days_offset', 'printing_method'
            ];

            basicFields.forEach(fieldName => {
                if (invoiceData[fieldName] !== undefined) {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        field.value = invoiceData[fieldName];
                    }
                }
            });

            if (invoiceData.notes) {
                const notesField = document.getElementById('tinyMCE');
                if (notesField) {
                    notesField.value = invoiceData.notes;
                }
            }

            if (typeof calculateTotals === 'function') {
                calculateTotals();
            }
        }

        // دالة تأكيد الحفظ
        function confirmSubmit(event) {
            event.preventDefault();

            Swal.fire({
                title: 'تأكيد الحفظ',
                html: '<p>هل أنت متأكد من حفظ الفاتورة الدورية؟</p>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احفظها!',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('periodic-invoice-form').submit();
                }
            });
        }

        // دالة حفظ كمسودة
        function saveAsDraft() {
            const draftInput = document.createElement('input');
            draftInput.type = 'hidden';
            draftInput.name = 'is_draft';
            draftInput.value = '1';
            document.getElementById('periodic-invoice-form').appendChild(draftInput);
            document.getElementById('periodic-invoice-form').submit();
        }

        // دالة مسح جميع البنود
        function clearAllItems() {
            Swal.fire({
                title: 'تأكيد المسح',
                text: 'هل تريد مسح جميع البنود؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، امسح الكل',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    const tbody = document.querySelector('#items-table tbody');
                    if (tbody) {
                        tbody.innerHTML = '';
                        if (typeof calculateTotals === 'function') {
                            calculateTotals();
                        }
                        Swal.fire('تم المسح!', 'تم مسح جميع البنود بنجاح.', 'success');
                    }
                }
            });
        }

        // دالة معاينة سريعة
        function showQuickPreview() {
            Swal.fire({
                title: 'معاينة الفاتورة الدورية',
                html: '<p>هذه ميزة قيد التطوير</p>',
                icon: 'info',
                confirmButtonText: 'حسناً'
            });
        }

        // دالة إظهار الإشعارات
        function showNotification(message, type) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    </script>
@endsection
