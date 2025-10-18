@extends('sales::master')

@section('title')
    إعدادات العميل
@stop

@section('content')
    <style>
        /* تخصيص عام للصفحة */
        .content-wrapper {

        }

        /* تخصيص البطاقات */
        .custom-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 25px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .custom-card:hover {
            transform: translateY(-5px);
        }

        .card-header-custom {

            color: rgb(3, 3, 3);
            padding: 20px;
            border: none;
        }

        .card-body-custom {
            padding: 30px;
        }

        /* تخصيص العنوان الرئيسي */
        .main-title {
            color: #2c3e50;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        /* تخصيص مسار التنقل */
        .breadcrumb-custom {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 10px 15px;
            backdrop-filter: blur(10px);
        }

        .breadcrumb-custom .breadcrumb-item a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-custom .breadcrumb-item.active {
            color: #2c3e50;
            font-weight: 600;
        }

        /* تخصيص الأزرار */
        .btn-save {
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            color: white;
        }

        .btn-cancel {

            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            color: rgb(0, 0, 0);
            font-weight: 600;
            transition: all 0.3s ease;
         box-shadow: 0 4px 15px rgba(255, 170, 13, 0.4);

            text-decoration: none;
            margin-left: 10px;
        }

        .btn-cancel:hover {
            transform: translateY(-2px);

            color: rgb(0, 0, 0);
            text-decoration: none;
        }

        /* تخصيص رسائل النجاح */
        .alert-success-custom {

            border: none;
            border-radius: 10px;
            color: rgb(0, 0, 0);
            padding: 15px 20px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
        }

        /* تخصيص منطقة الحقول الإضافية */
        .additional-fields-container {
            background: #f8f9ff;
            border-radius: 15px;
            padding: 25px;
            margin-top: 20px;
        }

        .fields-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        /* تخصيص منطقة الإعدادات */
        .client-settings-container {

            border-radius: 15px;
            padding: 25px;
            margin-top: 20px;
        }

        /* تخصيص عناصر الاختيار */
        .field-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            border: 2px solid #f0f0f0;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .field-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .field-item:hover {
            border-color: #667eea;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
            transform: translateY(-3px);
        }

        .field-item:hover::before {
            transform: scaleX(1);
        }

        .field-item.checked {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }

        .field-item.checked::before {
            transform: scaleX(1);
        }

        /* تخصيص الـ checkbox المخفي */
        .custom-checkbox {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* تخصيص الـ checkbox المخصص */
        .checkmark {
            position: absolute;
            top: 20px;
            right: 20px;
            height: 25px;
            width: 25px;
            background: #f0f0f0;
            border-radius: 50%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .field-item:hover .checkmark {
            background: #667eea;
        }

        .custom-checkbox:checked~.checkmark {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .checkmark::after {
            content: "✓";
            color: white;
            font-weight: bold;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .custom-checkbox:checked~.checkmark::after {
            opacity: 1;
        }

        /* تخصيص نص الحقل */
        .field-label {
            color: #2c3e50;
            font-weight: 600;
            font-size: 16px;
            margin: 0;
            padding-right: 50px;
            line-height: 1.4;
        }

        /* تخصيص النص الإلزامي */
        .required-text {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 500;
            border-left: 4px solid #e74c3c;
        }

        .required-star {
            color: #e74c3c;
            font-weight: bold;
            font-size: 18px;
        }

        /* تخصيص عنصر الاختيار */
        .form-control-custom {
            background: white;
            border: 2px solid #f0f0f0;
            border-radius: 12px;
            padding: 15px 20px;
            transition: all 0.3s ease;
            font-size: 16px;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-control-custom:focus {
            border-color: #667eea;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.15);
            outline: none;
        }

        .form-group-custom {
            margin-bottom: 20px;
        }

        .form-group-custom label {
            color: #2c3e50;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }

        /* تأثيرات الحركة */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .field-item {
            animation: slideInUp 0.6s ease forwards;
        }

        .field-item:nth-child(odd) {
            animation-delay: 0.1s;
        }

        .field-item:nth-child(even) {
            animation-delay: 0.2s;
        }

        /* تخصيص للشاشات الصغيرة */
        @media (max-width: 768px) {
            .fields-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .card-body-custom {
                padding: 20px;
            }

            .additional-fields-container,
            .client-settings-container {
                padding: 15px;
            }

            .btn-save,
            .btn-cancel {
                margin: 5px 0;
                width: 100%;
            }
        }
    </style>

    <div class="content-wrapper">
        <!-- رأس الصفحة -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="main-title"> إعدادات العميل</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-custom">
                                    <li class="breadcrumb-item">
                                        <a href=""> الرئيسية</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        إعدادات العميل
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="clientForm" action="{{ route('clients.store_general') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- رسالة النجاح -->
            @if (session('success'))
                <div class="alert alert-success-custom" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-3" style="font-size: 24px;"></i>
                        <p class="mb-0 font-weight-bold">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- بطاقة معلومات الحفظ -->
            <div class="custom-card">

                <div class="card-body-custom">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="required-text">
                            <i class="fas fa-info-circle me-2"></i>
                            الحقول التي عليها علامة <span class="required-star">*</span> إلزامية
                        </div>
                        <div>
                            <a href="{{ route('clients.index') }}" class="btn btn-cancel">
                                <i class="fa fa-ban me-2"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-save">
                                <i class="fa fa-save me-2"></i> حفظ الإعدادات
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- الحقول الإضافية -->
                <div class="col-lg-8 col-md-12">
                    <div class="custom-card">
                        <div class="card-header-custom">
                            <h5 class="mb-0"> الحقول الإضافية للعميل</h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="additional-fields-container">
                                <h6 class="text-muted mb-4">
                                    <i class="fas fa-list me-2"></i>
                                    اختر الحقول الإضافية المطلوبة:
                                </h6>

                                <!-- مثال على الحقول الإضافية -->
                                <div class="fields-grid">
                                    @foreach ($settings as $index => $setting)
                                        <div class="vs-checkbox-con vs-checkbox-primary mb-2">
                                            <input type="checkbox" id="setting_{{ $setting->id }}" name="settings[]"
                                                value="{{ $setting->id }}" {{ $setting->is_active ? 'checked' : '' }}
                                                onchange="updateItemStyle(this)">
                                            <span class="vs-checkbox">
                                                <span class="vs-checkbox--check">
                                                    <i class="vs-icon feather icon-check"></i>
                                                </span>
                                            </span>
                                            <label for="setting_{{ $setting->id }}">
                                                <i class="fas fa-cog me-1"></i> {{ $setting->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- إعدادات العميل -->
                <div class="col-lg-4 col-md-12">
                    <div class="custom-card">
                        <div class="card-header-custom">
                            <h5 class="mb-0"> إعدادات العميل</h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="client-settings-container">
                                <h6 class="text-muted mb-4">
                                    <i class="fas fa-user me-2"></i>
                                    تحديد نوع العميل:
                                </h6>

                                <div class="form-group-custom">
                                    <label for="type">
                                        نوع العميل
                                        <span class="required-star">*</span>
                                    </label>
                                    <select name="type" id="type" class="form-control-custom" required>
                                        <option value="Both" {{ $selectedType === 'Both' ? 'selected' : '' }}>
                                            كلاهما
                                        </option>
                                        <option value="individual" {{ $selectedType === 'individual' ? 'selected' : '' }}>
                                            فردي
                                        </option>
                                        <option value="commercial" {{ $selectedType === 'commercial' ? 'selected' : '' }}>
                                            تجاري
                                        </option>
                                    </select>
                                </div>

                                <!-- إحصائيات سريعة -->
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <div
                                            style="background: white; padding: 15px; border-radius: 10px; text-align: center; border: 2px solid #f0f0f0;">
                                            <div style="font-size: 24px; font-weight: bold; color: #667eea;">
                                                {{ count($settings) }}</div>
                                            <div style="font-size: 12px; color: #666;">إجمالي الحقول</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            style="background: white; padding: 15px; border-radius: 10px; text-align: center; border: 2px solid #f0f0f0;">
                                            <div style="font-size: 24px; font-weight: bold; color: #11998e;">
                                                {{ $settings->where('is_active', true)->count() }}</div>
                                            <div style="font-size: 12px; color: #666;">حقول نشطة</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script>
        // وظيفة لتبديل حالة الـ checkbox
        function toggleCheckbox(id) {
            const checkbox = document.getElementById(id);
            checkbox.checked = !checkbox.checked;
            updateItemStyle(checkbox);
        }

        // وظيفة لتحديث مظهر العنصر
        function updateItemStyle(checkbox) {
            const item = checkbox.closest('.field-item');
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

        // Form submission handling
        document.getElementById('clientForm').addEventListener('submit', function(e) {
            console.log('تم تقديم النموذج');

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> جاري الحفظ...';
            submitBtn.disabled = true;

            // Reset button after 3 seconds (in case of error)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);

            // طباعة جميع البيانات المرسلة
            const formData = new FormData(this);
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
        });
    </script>

@endsection
