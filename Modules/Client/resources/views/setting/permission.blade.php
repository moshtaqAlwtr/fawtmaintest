@extends('sales::master')

@section('title')
    صلاحيات العميل
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

        /* تخصيص منطقة الصلاحيات */
        .permissions-container {
            background: #f8f9ff;
            border-radius: 15px;
            padding: 25px;
            margin-top: 20px;
        }

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        /* تخصيص عناصر الاختيار */
        .permission-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            border: 2px solid #f0f0f0;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .permission-item::before {
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

        .permission-item:hover {
            border-color: #667eea;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
            transform: translateY(-3px);
        }

        .permission-item:hover::before {
            transform: scaleX(1);
        }

        .permission-item.checked {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }

        .permission-item.checked::before {
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

        .permission-item:hover .checkmark {
            background: #667eea;
        }

        .custom-checkbox:checked ~ .checkmark {
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

        .custom-checkbox:checked ~ .checkmark::after {
            opacity: 1;
        }

        /* تخصيص نص الحقل */
        .permission-label {
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

        .permission-item {
            animation: slideInUp 0.6s ease forwards;
        }

        .permission-item:nth-child(odd) {
            animation-delay: 0.1s;
        }

        .permission-item:nth-child(even) {
            animation-delay: 0.2s;
        }

        /* إضافة صندوق الإحصائيات */
        .stats-container {
            margin-top: 20px;
        }

        .stats-box {
            background: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .stats-box:hover {
            transform: translateY(-5px);
            border-color: #667eea;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }

        .stats-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }

        .stats-label {
            font-size: 12px;
            color: #666;
        }

        .permission-category {
            margin-top: 30px;
            margin-bottom: 15px;
            color: #2c3e50;
            font-weight: 600;
            padding-right: 15px;
            position: relative;
        }

        .permission-category::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
        }

        /* تخصيص للشاشات الصغيرة */
        @media (max-width: 768px) {
            .permissions-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .card-body-custom {
                padding: 20px;
            }

            .permissions-container {
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
                        <h2 class="main-title">صلاحيات العميل</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-custom">
                                    <li class="breadcrumb-item">
                                        <a href=""> الرئيسية</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        صلاحيات العميل
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="permissionForm" action="{{ route('clients.store_permission') }}" method="POST" enctype="multipart/form-data">
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
                                <i class="fa fa-save me-2"></i> حفظ الصلاحيات
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- منطقة الصلاحيات الرئيسية -->
                <div class="col-lg-8 col-md-12">
                    <div class="custom-card">
                        <div class="card-header-custom">
                            <h5 class="mb-0"><i class="fas fa-key me-2"></i> صلاحيات العميل</h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="permissions-container">
                                <h6 class="text-muted mb-4">
                                    <i class="fas fa-users me-2"></i>
                                    اختر الصلاحيات المناسبة للعميل:
                                </h6>

                                <h5 class="permission-category">
                                    <i class="fas fa-shield-alt me-2"></i> صلاحيات الوصول الأساسية
                                </h5>
                                <!-- قسم الصلاحيات الأساسية -->
                                <div class="permissions-grid">
                                    @foreach ($ClientPermissions->where('category', 'basic')->take(4) as $index => $ClientPermission)
                                        <div class="permission-item {{ $ClientPermission->is_active ? 'checked' : '' }}"
                                            onclick="toggleCheckbox('setting_{{ $ClientPermission->id }}')">
                                            <input type="checkbox" class="custom-checkbox" id="setting_{{ $ClientPermission->id }}"
                                                name="settings[]" value="{{ $ClientPermission->id }}"
                                                {{ $ClientPermission->is_active ? 'checked' : '' }}
                                                onchange="updateItemStyle(this)">
                                            <span class="checkmark"></span>
                                            <label class="permission-label" for="setting_{{ $ClientPermission->id }}">
                                                {{ $ClientPermission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <h5 class="permission-category">
                                    <i class="fas fa-user-cog me-2"></i> صلاحيات الإدارة
                                </h5>
                                <!-- قسم صلاحيات الإدارة -->
                                <div class="permissions-grid">
                                    @foreach ($ClientPermissions->where('category', 'admin')->take(4) as $index => $ClientPermission)
                                        <div class="permission-item {{ $ClientPermission->is_active ? 'checked' : '' }}"
                                            onclick="toggleCheckbox('setting_{{ $ClientPermission->id }}')">
                                            <input type="checkbox" class="custom-checkbox" id="setting_{{ $ClientPermission->id }}"
                                                name="settings[]" value="{{ $ClientPermission->id }}"
                                                {{ $ClientPermission->is_active ? 'checked' : '' }}
                                                onchange="updateItemStyle(this)">
                                            <span class="checkmark"></span>
                                            <label class="permission-label" for="setting_{{ $ClientPermission->id }}">
                                                {{ $ClientPermission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <h5 class="permission-category">
                                    <i class="fas fa-file-alt me-2"></i> صلاحيات التقارير
                                </h5>
                                <!-- قسم صلاحيات التقارير -->
                                <div class="permissions-grid">
                                    @foreach ($ClientPermissions->where('category', 'reports')->take(4) as $index => $ClientPermission)
                                        <div class="permission-item {{ $ClientPermission->is_active ? 'checked' : '' }}"
                                            onclick="toggleCheckbox('setting_{{ $ClientPermission->id }}')">
                                            <input type="checkbox" class="custom-checkbox" id="setting_{{ $ClientPermission->id }}"
                                                name="settings[]" value="{{ $ClientPermission->id }}"
                                                {{ $ClientPermission->is_active ? 'checked' : '' }}
                                                onchange="updateItemStyle(this)">
                                            <span class="checkmark"></span>
                                            <label class="permission-label" for="setting_{{ $ClientPermission->id }}">
                                                {{ $ClientPermission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- منطقة الإحصائيات الجانبية -->
                <div class="col-lg-4 col-md-12">
                    <div class="custom-card">
                        <div class="card-header-custom">
                            <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i> لوحة إحصائيات الصلاحيات</h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="permissions-container">
                                <h6 class="text-muted mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    ملخص حالة الصلاحيات:
                                </h6>

                                <div class="row stats-container">
                                    <div class="col-6 mb-3">
                                        <div class="stats-box">
                                            <div class="stats-number">{{ count($ClientPermissions) }}</div>
                                            <div class="stats-label">إجمالي الصلاحيات</div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stats-box">
                                            <div class="stats-number" style="color: #11998e;">
                                                {{ $ClientPermissions->where('is_active', true)->count() }}
                                            </div>
                                            <div class="stats-label">صلاحيات مفعلة</div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stats-box">
                                            <div class="stats-number" style="color: #e74c3c;">
                                                {{ count($ClientPermissions) - $ClientPermissions->where('is_active', true)->count() }}
                                            </div>
                                            <div class="stats-label">صلاحيات غير مفعلة</div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stats-box">
                                            <div class="stats-number" style="color: #f39c12;">
                                                {{ round(($ClientPermissions->where('is_active', true)->count() / count($ClientPermissions)) * 100) }}%
                                            </div>
                                            <div class="stats-label">نسبة التفعيل</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- معلومات توضيحية -->
                                <div style="background-color: #eef2ff; border-radius: 10px; padding: 15px; margin-top: 20px;">
                                    <h6 style="color: #4c51bf; font-weight: 600;"><i class="fas fa-lightbulb me-2"></i> ملاحظات هامة</h6>
                                    <ul style="padding-right: 20px; margin-top: 10px; color: #4a5568;">
                                        <li style="margin-bottom: 8px;">الصلاحيات المفعلة تسمح للعميل بالوصول إلى الميزات المقابلة</li>
                                        <li style="margin-bottom: 8px;">يمكنك إلغاء تحديد الصلاحيات غير المطلوبة لتقييد الوصول</li>
                                        <li style="margin-bottom: 8px;">يتم تطبيق التغييرات فور حفظ الإعدادات</li>
                                        <li>لمزيد من المعلومات، يرجى الرجوع إلى <a href="#" style="color: #667eea; text-decoration: none;">دليل المستخدم</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- بطاقة أخرى للأنشطة الأخيرة -->
                    <div class="custom-card">
                        <div class="card-header-custom">
                            <h5 class="mb-0"><i class="fas fa-history me-2"></i> سجل التغييرات الأخيرة</h5>
                        </div>
                        <div class="card-body-custom">
                            <div style="background: #f8f9ff; border-radius: 15px; padding: 20px;">
                                <div style="border-right: 3px solid #667eea; padding-right: 15px; margin-bottom: 15px;">
                                    <div style="color: #2c3e50; font-weight: 600;">تعديل الصلاحيات</div>
                                    <div style="color: #718096; font-size: 12px;">منذ ساعتين بواسطة المدير</div>
                                </div>
                                <div style="border-right: 3px solid #e74c3c; padding-right: 15px; margin-bottom: 15px;">
                                    <div style="color: #2c3e50; font-weight: 600;">إلغاء صلاحية الحذف</div>
                                    <div style="color: #718096; font-size: 12px;">منذ يوم واحد بواسطة المشرف</div>
                                </div>
                                <div style="border-right: 3px solid #11998e; padding-right: 15px;">
                                    <div style="color: #2c3e50; font-weight: 600;">إضافة صلاحيات جديدة</div>
                                    <div style="color: #718096; font-size: 12px;">منذ 3 أيام بواسطة المدير</div>
                                </div>
                            </div>
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

        // معالجة تقديم النموذج
        document.getElementById('permissionForm').addEventListener('submit', function(e) {
            console.log('تم تقديم النموذج');

            // إظهار حالة التحميل
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> جاري الحفظ...';
            submitBtn.disabled = true;

            // إعادة تعيين الزر بعد 3 ثوانٍ (في حالة وجود خطأ)
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