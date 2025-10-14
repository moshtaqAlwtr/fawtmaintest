@extends('master')

@section('title')
    اعدادات الفواتير
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/sitting.css') }}">
@endsection

@section('content')

<div class="content-wrapper">
    <!-- رأس الصفحة -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="main-title">⚙️ إعدادات الفواتير</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-custom">
                                <li class="breadcrumb-item">
                                    <a href="">🏠 الرئيسية</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    📄 إعدادات الفواتير
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('settings.update_invoices') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- بطاقة معلومات الحفظ -->
        <div class="custom-card">
            <div class="card-header-custom">
                <h5 class="mb-0">💾 إعدادات الحفظ</h5>
            </div>
            <div class="card-body-custom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="required-text">
                        <i class="fas fa-info-circle me-2"></i>
                        الحقول التي عليها علامة <span class="required-star">*</span> إلزامية
                    </div>
                    <div>
                        <button type="submit" class="btn btn-save">
                            <i class="fa fa-save me-2"></i> حفظ الإعدادات
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- رسالة النجاح -->
        @if (Session::has('success'))
            <div class="alert alert-success-custom" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-3" style="font-size: 24px;"></i>
                    <p class="mb-0 font-weight-bold">
                        {{ Session::get('success') }}
                    </p>
                </div>
            </div>
        @endif

        @php
        // عناصر select
        $selectFields = [
            'min_price_calculation' => [
                'label' => 'حساب الحد الأدنى لسعر البيع',
                'options' => ['بالضريبة', 'بدون الضريبة', 'كلاهما'],
                'icon' => 'fa-calculator'
            ],
            'last_price_display' => [
                'label' => 'عرض سعر البيع الأخير والحد الأدنى للسعر',
                'options' => ['لا شئ', 'آخر سعر بيع', 'الحد الأدنى للسعر', 'كلاهما'],
                'icon' => 'fa-eye'
            ],
        ];
        // عناصر checkbox
        $checkboxFields = [
            'allow_free_entry' => ['label' => 'إيقاف الإدخال الحر للمنتجات في الفاتورة', 'icon' => 'fa-ban'],
            'disable_quotes' => ['label' => 'تعطيل عروض الأسعار', 'icon' => 'fa-file-invoice'],
            'manual_invoice_status' => ['label' => 'إعطاء الفواتير حالات يدوية', 'icon' => 'fa-hand-paper'],
            'manual_quote_status' => ['label' => 'إعطاء عروض الأسعار حالات يدوية', 'icon' => 'fa-clipboard-list'],
            'disable_delivery_options' => ['label' => 'تعطيل خيارات التوصيل', 'icon' => 'fa-truck'],
            'enable_max_discount' => ['label' => 'تفعيل الحد الأقصى للخصم', 'icon' => 'fa-percent'],
            'enable_sales_adjustment' => ['label' => 'تفعيل تسوية المبيعات', 'icon' => 'fa-balance-scale'],
            'default_paid_status' => ['label' => 'إجعل الفواتير مدفوعه بالفعل افتراضياً', 'icon' => 'fa-money-check'],
            'preview_before_save' => ['label' => 'تفعيل معاينة الفاتورة قبل الحفظ', 'icon' => 'fa-search'],
            'auto_pay_if_balance' => ['label' => 'دفع الفاتورة تلقائيا في حالة وجود رصيد للعميل', 'icon' => 'fa-wallet'],
            'select_price_list' => ['label' => 'اختيار قائمه الاسعار فى الفواتير', 'icon' => 'fa-list-alt'],
            'send_on_social' => ['label' => 'إرسال المعاملات عبر وسائل التواصل الاجتماعي', 'icon' => 'fa-share-alt'],
            'show_invoice_profit' => ['label' => 'إظهار ربح الفاتورة', 'icon' => 'fa-chart-line'],
            'custom_journal_description' => ['label' => 'وصف مخصص للقيود اليومية', 'icon' => 'fa-book'],
            'no_sell_below_cost' => ['label' => 'عدم البيع باقل من سعر التكلفة', 'icon' => 'fa-exclamation-triangle'],
            'apply_offers_to_quotes' => ['label' => 'تطبيق العروض علي عروض الأسعار', 'icon' => 'fa-tags'],
            'enable_sales_orders' => ['label' => 'تفعيل أوامر البيع', 'icon' => 'fa-shopping-cart'],
            'manual_sales_order_status' => ['label' => 'إعطاء أوامر البيع حالات يدوية', 'icon' => 'fa-tasks'],
            'enable_debit_notification' => ['label' => 'تفعيل الإشعار المدين', 'icon' => 'fa-bell'],
            'copy_notes_on_conversion' => ['label' => 'نسخ الملاحظات/الشروط عند تحويل أمر مبيعات أو عرض السعر إلى فاتورة', 'icon' => 'fa-copy'],
        ];
        @endphp

        <!-- بطاقة حقول Select -->
        <div class="custom-card">
            <div class="card-header-custom">
                <h5 class="mb-0">📋 الإعدادات الأساسية</h5>
            </div>
            <div class="card-body-custom">
                <div class="row">
                    @foreach ($selectFields as $key => $field)
                    <div class="col-md-6 mb-4">
                        <label class="form-label" style="font-weight: 600; color: #4a5568;">
                            <i class="fas {{ $field['icon'] }} me-2" style="color: #667eea;"></i>
                            {{ $field['label'] }} <span class="required-star">*</span>
                        </label>
                        <select class="form-control" name="{{ $key }}" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px;">
                            @foreach ($field['options'] as $option)
                                <option value="{{ $option }}" {{ (isset($settings[$key]) && $settings[$key] == $option) ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- بطاقة الإعدادات المتقدمة -->
        <div class="custom-card">
            <div class="card-header-custom">
                <h5 class="mb-0">🔧 الإعدادات المتقدمة</h5>
            </div>
            <div class="card-body-custom">
                <div class="permissions-container">
                    <h6 class="text-muted mb-4">
                        <i class="fas fa-cogs me-2"></i>
                        قم بتفعيل أو إلغاء الإعدادات التالية حسب احتياجاتك:
                    </h6>

                    <div class="permissions-grid">
                        @foreach ($checkboxFields as $key => $field)
                            <div class="permission-item {{ (isset($settings[$key]) && $settings[$key] == '1') ? 'checked' : '' }}"
                                onclick="toggleCheckbox('setting_{{ $key }}')">
                                <input type="hidden" name="{{ $key }}" value="0">
                                <input type="checkbox" class="custom-checkbox" id="setting_{{ $key }}"
                                    name="{{ $key }}" value="1"
                                    {{ (isset($settings[$key]) && $settings[$key] == '1') ? 'checked' : '' }}
                                    onchange="updateItemStyle(this)">
                                <span class="checkmark"></span>
                                <label class="permission-label" for="setting_{{ $key }}">
                                    <i class="fas {{ $field['icon'] }} me-2" style="color: #667eea;"></i>
                                    {{ $field['label'] }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // وظيفة لتبديل حالة الـ checkbox
    function toggleCheckbox(id) {
        const checkbox = document.getElementById(id);
        checkbox.checked = !checkbox.checked;
        updateItemStyle(checkbox);
    }

    // وظيفة لتحديث مظهر العنصر
    function updateItemStyle(checkbox) {
        const item = checkbox.closest('.permission-item');
        if (checkbox.checked) {
            item.classList.add('checked');
        } else {
            item.classList.remove('checked');
        }
    }

    // تهيئة المظهر عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.custom-checkbox');
        checkboxes.forEach(checkbox => {
            updateItemStyle(checkbox);
        });
    });
</script>

@endsection
