@extends('master')

@section('title')
    إعدادات العميل
@stop

@section('content')
    <style>
        .custom-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            background-color: #fff;
        }

        .card-header-custom {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
        }

        .card-body-custom {
            padding: 20px;
        }

        .btn-save {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-save:hover {
            background-color: #0056b3;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background-color: #545b62;
        }

        .btn-default {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-default:hover {
            background-color: #218838;
        }

        .required-star {
            color: red;
        }

        .alert-success-custom {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .fields-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .vs-checkbox-con {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .vs-checkbox-con:hover {
            background-color: #e9ecef;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .vs-checkbox-con input:checked+span.vs-checkbox {
            background-color: #007bff;
        }

        .vs-checkbox-con label {
            margin-bottom: 0;
            cursor: pointer;
            margin-right: 10px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .fields-grid {
                grid-template-columns: 1fr;
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
                            <a href="{{ route('clients.create_default_settings') }}" class="btn btn-default">
                                <i class="fa fa-cog me-2"></i> إنشاء الإعدادات الافتراضية
                            </a>
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

            <!-- بطاقة الإعدادات -->
            <div class="row mt-4">
                <div class="col-lg-12 col-md-12">
                    <div class="custom-card">
                        <div class="card-header-custom">
                            <h5 class="mb-0">الحقول الإضافية</h5>
                        </div>
                        <div class="card-body-custom">
                            <p class="text-muted">
                                <i class="fas fa-lightbulb me-2"></i>
                                قم بتحديد الحقول التي ترغب في تفعيلها في نموذج إضافة وتعديل العملاء
                            </p>

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

                            <div class="form-group">
                                <label for="clientType">اختر نوع العميل:</label>
                                <select class="form-control" id="clientType" name="client_type">
                                    <option value="Both"
                                        {{ old('client_type', $selectedType) == 'Both' ? 'selected' : '' }}>
                                        كلاهما (زائر + مسجل)
                                    </option>
                                    <option value="Visitor"
                                        {{ old('client_type', $selectedType) == 'Visitor' ? 'selected' : '' }}>
                                        زائر فقط
                                    </option>
                                    <option value="Registered"
                                        {{ old('client_type', $selectedType) == 'Registered' ? 'selected' : '' }}>
                                        مسجل فقط
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function updateItemStyle(checkbox) {
            const container = checkbox.closest('.vs-checkbox-con');
            if (checkbox.checked) {
                container.style.backgroundColor = '#e6f7ff';
                container.style.borderColor = '#1890ff';
            } else {
                container.style.backgroundColor = '#f8f9fa';
                container.style.borderColor = '#e9ecef';
            }
        }

        // تحديث الأنماط عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.vs-checkbox-con input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                updateItemStyle(checkbox);
            });
        });
    </script>
@endsection
