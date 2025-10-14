@extends('master')

@section('title')
    تقرير دليل العملاء
@stop

@section('css')
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">

    <style>
        :root {
            --primary-color: #6a11cb;
            --secondary-color: #2575fc;
            --success-color: #4caf50;
            --warning-color: #ff9800;
            --danger-color: #ff5722;
            --info-color: #17a2b8;
            --dark-color: #343a40;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-600: #6c757d;
            --white: #ffffff;
            --border-radius: 12px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            font-family: 'Cairo', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 0;
            margin: 0;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.05"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.05"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.03"/><circle cx="20" cy="60" r="1" fill="white" opacity="0.03"/><circle cx="80" cy="40" r="1" fill="white" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }

        .breadcrumb-custom .breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            padding: 0.5rem 1rem;
            backdrop-filter: blur(10px);
        }

        .breadcrumb-custom .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-custom .breadcrumb-item a:hover {
            color: white;
        }

        .breadcrumb-custom .breadcrumb-item.active {
            color: white;
        }

        .stats-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        /* Card Modern */
        .card-modern {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            margin-bottom: 2rem;
            overflow: hidden;
            transition: var(--transition);
        }

        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .card-header-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem 2rem;
            border-bottom: none;
            position: relative;
        }

        .card-header-modern::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--warning-color), var(--success-color));
        }

        .card-body-modern {
            padding: 2rem;
        }

        /* Form Controls */
        .form-label-modern {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .form-label-modern i {
            margin-left: 0.5rem;
            color: var(--primary-color);
        }

        .form-control {
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: var(--transition);
            background: var(--white);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(106, 17, 203, 0.25);
            background: var(--white);
        }

        /* Buttons */
        .btn-modern {
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(106, 17, 203, 0.3);
        }

        .btn-outline-modern {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline-modern:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .btn-success-modern {
            background: linear-gradient(135deg, var(--success-color), #388e3c);
            color: white;
        }

        .btn-warning-modern {
            background: linear-gradient(135deg, var(--warning-color), #f57c00);
            color: white;
        }

        .btn-danger-modern {
            background: linear-gradient(135deg, var(--danger-color), #d32f2f);
            color: white;
        }

        /* Statistics Cards */
        .stats-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .stats-card .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stats-card.primary .stats-icon {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .stats-card.success .stats-icon {
            background: linear-gradient(135deg, var(--success-color), #388e3c);
        }

        .stats-card.warning .stats-icon {
            background: linear-gradient(135deg, var(--warning-color), #f57c00);
        }

        .stats-card.info .stats-icon {
            background: linear-gradient(135deg, var(--info-color), #117a8b);
        }

        .stats-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: var(--gray-600);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Map Section */
        .map-container {
            background: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            position: relative;
            height: 500px;
        }

        .map-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        #clientMap {
            height: 450px;
            width: 100%;
            display: none;
            border-radius: 0;
        }

        #mapPlaceholder {
            height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            position: relative;
            overflow: hidden;
        }

        #mapPlaceholder::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 25% 25%, rgba(106, 17, 203, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(37, 117, 252, 0.1) 0%, transparent 50%);
        }

        .placeholder-content {
            text-align: center;
            z-index: 1;
            position: relative;
        }

        .placeholder-content i {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            opacity: 0.7;
        }

        .placeholder-content h5 {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .placeholder-content p {
            color: var(--gray-600);
            margin: 0;
        }

        .map-footer {
            background: var(--gray-100);
            border-top: 1px solid var(--gray-200);
            padding: 0.75rem 1.5rem;
            text-align: center;
            font-size: 0.85rem;
            color: var(--gray-600);
        }

        /* Map Visible State */
        .map-visible #mapPlaceholder {
            display: none;
        }

        .map-visible #clientMap {
            display: block;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Table */
        .table-modern {
            background: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }

        .table-modern thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .table-modern tbody tr {
            transition: var(--transition);
            border-bottom: 1px solid var(--gray-200);
        }

        .table-modern tbody tr:hover {
            background: rgba(106, 17, 203, 0.05);
            transform: scale(1.01);
        }

        .table-modern tbody td {
            padding: 1rem;
            vertical-align: middle;
            border: none;
            font-size: 0.9rem;
        }

        .location-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            background: rgba(106, 17, 203, 0.1);
            transition: var(--transition);
        }

        .location-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(106, 17, 203, 0.3);
        }

        /* Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideInRight 0.8s ease;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(-50px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Loading Overlay */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid var(--gray-200);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Select2 Customization */
        .select2-container--bootstrap-5 .select2-selection--single {
            border: 2px solid var(--gray-200) !important;
            border-radius: var(--border-radius) !important;
            height: auto !important;
            padding: 0.5rem 1rem !important;
            min-height: 48px !important;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection--single {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 0.2rem rgba(106, 17, 203, 0.25) !important;
        }

        .select2-dropdown {
            border: 2px solid var(--primary-color) !important;
            border-radius: var(--border-radius) !important;
        }

        /* Custom Map Controls (Google Maps Style) */
        .gm-style-iw {
            background: var(--white) !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        }

        .gm-style-iw-d {
            direction: rtl !important;
            text-align: right !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.8rem;
            }

            .card-body-modern {
                padding: 1rem;
            }

            .btn-modern {
                padding: 0.6rem 1.5rem;
                font-size: 0.9rem;
            }

            .stats-card {
                margin-bottom: 1rem;
            }

            .map-container {
                height: 400px;
            }

            #clientMap, #mapPlaceholder {
                height: 350px;
            }
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .card-modern {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }

            .page-header {
                background: #333 !important;
                color: white !important;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="slide-in">
                        <i class="fas fa-address-book me-3"></i>
                        تقرير دليل العملاء
                    </h1>
                    <nav class="breadcrumb-custom">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="#"><i class="fas fa-home me-2"></i>الرئيسية</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">التقارير</a>
                            </li>
                            <li class="breadcrumb-item active">دليل العملاء</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-4 text-end">
                    <div class="stats-icon primary">
                        <i class="fas fa-address-book"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Filters Section -->
        <div class="card-modern fade-in">
            <div class="card-header-modern">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2"></i>
                    فلاتر التقرير
                </h5>
            </div>
            <div class="card-body-modern">
                <form action="{{ route('ClientReport.customerGuide') }}" method="GET" id="filterForm">
                    <div class="row g-4">
                        <!-- First Row -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-map-marker-alt"></i>
                                المنطقة
                            </label>
                            <select id="region" name="region" class="form-control select2">
                                <option value="الكل">جميع المناطق</option>
                                <!-- يتم تعبئة البيانات ديناميكياً -->
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-city"></i>
                                المدينة
                            </label>
                            <select id="city" name="city" class="form-control select2">
                                <option value="الكل">جميع المدن</option>
                                <!-- يتم تعبئة البيانات ديناميكياً -->
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-globe"></i>
                                البلد
                            </label>
                            <input type="text" id="country" name="country" class="form-control"
                                   value="{{ request('country') }}" placeholder="أدخل اسم البلد">
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-tags"></i>
                                التصنيف
                            </label>
                            <select id="classification" name="classification" class="form-control select2">
                                <option value="الكل">جميع التصنيفات</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('classification') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Second Row -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-building"></i>
                                الفرع
                            </label>
                            <select id="branch" name="branch" class="form-control select2">
                                <option value="الكل">جميع الفروع</option>
                                @foreach ($branch as $br)
                                    <option value="{{ $br->id }}"
                                        {{ request('branch') == $br->id ? 'selected' : '' }}>
                                        {{ $br->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-layer-group"></i>
                                تجميع حسب
                            </label>
                            <select id="group-by" name="group_by" class="form-control select2">
                                <option value="العميل" {{ request('group_by') == 'العميل' ? 'selected' : '' }}>العميل</option>
                                <option value="الفرع" {{ request('group_by') == 'الفرع' ? 'selected' : '' }}>الفرع</option>
                                <option value="المدينة" {{ request('group_by') == 'المدينة' ? 'selected' : '' }}>المدينة</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" id="view-details" name="view_details"
                                       class="form-check-input" {{ request('view_details') ? 'checked' : '' }}>
                                <label for="view-details" class="form-check-label">
                                    مشاهدة التفاصيل
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-lg-12">
                            <div class="d-flex gap-2 flex-wrap justify-content-center">
                                <button type="submit" class="btn-modern btn-primary-modern">
                                    <i class="fas fa-search"></i>
                                    تطبيق الفلتر
                                </button>
                                <a href="{{ route('ClientReport.customerGuide') }}" class="btn-modern btn-outline-modern">
                                    <i class="fas fa-refresh"></i>
                                    إلغاء الفلتر
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card-modern no-print fade-in">
            <div class="card-body-modern">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn-modern btn-success-modern" onclick="window.print()">
                            <i class="fas fa-print"></i>
                            طباعة
                        </button>
                        <button class="btn-modern btn-warning-modern export-excel">
                            <i class="fas fa-file-excel"></i>
                            تصدير إكسل
                        </button>
                    </div>

                    <div class="d-flex gap-2 align-items-center">
                        <span class="badge bg-primary fs-6">
                            عدد العملاء: {{ count($clients) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4 fade-in">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card primary">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-value">{{ count($clients) }}</div>
                    <div class="stats-label">إجمالي العملاء</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card success">
                    <div class="stats-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="stats-value">{{ $clients->whereNotNull('locations')->count() }}</div>
                    <div class="stats-label">عملاء بمواقع</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card warning">
                    <div class="stats-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stats-value">{{ $branch->count() }}</div>
                    <div class="stats-label">عدد الفروع</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card info">
                    <div class="stats-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="stats-value">{{ $categories->count() }}</div>
                    <div class="stats-label">عدد التصنيفات</div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="card-modern fade-in" id="mapSection">
            <div class="card-body-modern p-0">
                <div class="map-container">
                    <div class="map-header">
                        <i class="fas fa-map-marked-alt"></i>
                        خريطة مواقع العملاء
                    </div>

                    <!-- Map Placeholder -->
                    <div id="mapPlaceholder">
                        <div class="placeholder-content">
                            <i class="fas fa-map-marked-alt"></i>
                            <h5>خريطة مواقع العملاء</h5>
                            <p>اختر عميلاً من الجدول لعرض موقعه على الخريطة</p>
                        </div>
                    </div>

                    <!-- Actual Map -->
                    <div id="clientMap"></div>

                    <div class="map-footer">
                        <i class="fas fa-info-circle me-2"></i>
                        انقر على "عرض على الخريطة" في الجدول لعرض موقع العميل
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Table -->
        <div class="card-modern fade-in" id="reportContainer">
            <!-- Loading Overlay -->
            <div class="loading-overlay">
                <div class="spinner"></div>
            </div>

            <div class="card-header-modern">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    دليل العملاء - تجميع حسب {{ request('group_by', 'العميل') }}
                </h5>
                <div class="mt-2">
                    <small class="text-white-50">
                        <i class="fas fa-clock me-1"></i>
                        الوقت: {{ now()->format('H:i d/m/Y') }} |
                        <i class="fas fa-user me-1"></i>
                        صاحب الحساب: {{ auth()->user()->name }}
                    </small>
                </div>
            </div>
            <div class="card-body-modern p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>#</th>
                                <th><i class="fas fa-code me-2"></i>الكود</th>
                                <th><i class="fas fa-user me-2"></i>الاسم</th>
                                <th><i class="fas fa-map-marker-alt me-2"></i>الموقع</th>
                                <th><i class="fas fa-map me-2"></i>المنطقة</th>
                                <th><i class="fas fa-phone me-2"></i>الهاتف</th>
                                <th><i class="fas fa-mobile-alt me-2"></i>الجوال</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $index => $client)
                                <tr data-client-id="{{ $client->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $client->code }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $client->trade_name }}</div>
                                                @if($client->email)
                                                    <small class="text-muted">{{ $client->email }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($client->locations)
                                            <a href="#mapSection" class="location-link"
                                                data-lat="{{ $client->locations->latitude }}"
                                                data-lng="{{ $client->locations->longitude }}"
                                                data-name="{{ $client->trade_name }}"
                                                data-code="{{ $client->code }}">
                                                <i class="fas fa-map-marker-alt"></i>
                                                عرض على الخريطة
                                            </a>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-times-circle me-1"></i>
                                                غير متوفر
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($client->region)
                                            <span class="badge bg-info">{{ $client->region }}</span>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($client->phone)
                                            <a href="tel:{{ $client->phone }}" class="text-decoration-none">
                                                <i class="fas fa-phone text-success me-1"></i>
                                                {{ $client->phone }}
                                            </a>
                                        @else
                                            <span class="text-muted">غير متوفر</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($client->mobile)
                                            <a href="tel:{{ $client->mobile }}" class="text-decoration-none">
                                                <i class="fas fa-mobile-alt text-primary me-1"></i>
                                                {{ $client->mobile }}
                                            </a>
                                        @else
                                            <span class="text-muted">غير متوفر</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initializeGoogleMaps&libraries=places&v=weekly" async defer></script>

    <script>
        let map;
        const markers = [];
        let mapInitialized = false;
        let googleMapsLoaded = false;

        $(document).ready(function() {
            // تهيئة Select2
            initializeSelect2();

            // إضافة تأثيرات الحركة
            addAnimations();

            // تهيئة معالجات الأحداث
            initializeEventHandlers();

            // تحميل البيانات الإضافية للفلاتر
            loadFilterData();
        });

        function initializeSelect2() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                dir: 'rtl',
                language: {
                    noResults: function() { return "لا توجد نتائج"; },
                    searching: function() { return "جاري البحث..."; },
                    loadingMore: function() { return "جاري تحميل المزيد..."; }
                },
                allowClear: true,
                width: '100%',
                placeholder: 'اختر...',
                minimumResultsForSearch: 0
            });
        }

        function addAnimations() {
            // إضافة تأثيرات الحركة تدريجياً
            setTimeout(() => {
                $('.fade-in').each(function(index) {
                    const element = $(this);
                    setTimeout(() => {
                        element.css({
                            'animation-delay': (index * 0.1) + 's',
                            'animation-fill-mode': 'forwards'
                        });
                    }, index * 100);
                });
            }, 200);

            // تأثيرات hover للكروت
            $('.stats-card').hover(
                function() { $(this).css('transform', 'translateY(-8px) scale(1.02)'); },
                function() { $(this).css('transform', 'translateY(0) scale(1)'); }
            );

            // تأثيرات hover للأزرار
            $('.btn-modern').hover(
                function() {
                    if (!$(this).hasClass('active')) {
                        $(this).css('transform', 'translateY(-2px)');
                    }
                },
                function() {
                    if (!$(this).hasClass('active')) {
                        $(this).css('transform', 'translateY(0)');
                    }
                }
            );
        }

        function initializeEventHandlers() {
            // معالج النقر على روابط الموقع
            $(document).on('click', '.location-link', async function(e) {
                e.preventDefault();

                const lat = parseFloat($(this).data('lat'));
                const lng = parseFloat($(this).data('lng'));
                const clientId = $(this).closest('tr').data('client-id');
                const name = $(this).data('name');
                const code = $(this).data('code');

                if (lat && lng) {
                    await showClientLocation(lat, lng, clientId, name, code);
                }
            });

            // معالج تصدير Excel
            $('.export-excel').click(function() {
                exportToExcel();
            });

            // معالج تغيير الفلاتر
            $('#region, #city').on('change', function() {
                if ($(this).attr('id') === 'region') {
                    loadCities($(this).val());
                }
            });
        }

        function loadFilterData() {
            // تحميل بيانات المناطق والمدن
            // يمكن تنفيذ هذا عبر AJAX حسب البيانات المتوفرة
            loadRegions();
        }

        function loadRegions() {
            // محاكاة تحميل المناطق
            const regions = @json($clients->pluck('region')->unique()->filter()->values());
            const regionSelect = $('#region');

            regions.forEach(region => {
                regionSelect.append(`<option value="${region}">${region}</option>`);
            });
        }

        function loadCities(region) {
            const citySelect = $('#city');
            citySelect.empty().append('<option value="الكل">جميع المدن</option>');

            if (region && region !== 'الكل') {
                const cities = @json($clients->pluck('city')->unique()->filter()->values());
                cities.forEach(city => {
                    citySelect.append(`<option value="${city}">${city}</option>`);
                });
            }
        }

        // تهيئة خرائط جوجل
        function initializeGoogleMaps() {
            googleMapsLoaded = true;
            initMap();
        }

        function initMap() {
            if (!googleMapsLoaded) return;

            const mapElement = document.getElementById("clientMap");
            if (!mapElement) return;

            // إنشاء الخريطة بتصميم مشابه لخرائط جوجل
            map = new google.maps.Map(mapElement, {
                center: { lat: 24.7136, lng: 46.6753 }, // الرياض
                zoom: 6,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                styles: [
                    {
                        "featureType": "all",
                        "elementType": "geometry.fill",
                        "stylers": [{"weight": "2.00"}]
                    },
                    {
                        "featureType": "all",
                        "elementType": "geometry.stroke",
                        "stylers": [{"color": "#9c9c9c"}]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.text",
                        "stylers": [{"visibility": "on"}]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "all",
                        "stylers": [{"color": "#f2f2f2"}]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "geometry.fill",
                        "stylers": [{"color": "#ffffff"}]
                    },
                    {
                        "featureType": "landscape.man_made",
                        "elementType": "geometry.fill",
                        "stylers": [{"color": "#ffffff"}]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "all",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "road",
                        "elementType": "all",
                        "stylers": [{"saturation": -100}, {"lightness": 45}]
                    },
                    {
                        "featureType": "road",
                        "elementType": "geometry.fill",
                        "stylers": [{"color": "#eeeeee"}]
                    },
                    {
                        "featureType": "road",
                        "elementType": "labels.text.fill",
                        "stylers": [{"color": "#7b7b7b"}]
                    },
                    {
                        "featureType": "road",
                        "elementType": "labels.text.stroke",
                        "stylers": [{"color": "#ffffff"}]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "all",
                        "stylers": [{"visibility": "simplified"}]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "labels.icon",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "all",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [{"color": "#46bcec"}, {"visibility": "on"}]
                    },
                    {
                        "featureType": "water",
                        "elementType": "geometry.fill",
                        "stylers": [{"color": "#c8d7d4"}]
                    },
                    {
                        "featureType": "water",
                        "elementType": "labels.text.fill",
                        "stylers": [{"color": "#070707"}]
                    },
                    {
                        "featureType": "water",
                        "elementType": "labels.text.stroke",
                        "stylers": [{"color": "#ffffff"}]
                    }
                ],
                mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                    position: google.maps.ControlPosition.TOP_CENTER,
                },
                zoomControl: true,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER,
                },
                scaleControl: true,
                streetViewControl: true,
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_TOP,
                },
                fullscreenControl: true,
            });

            mapInitialized = true;

            // إضافة جميع العلامات للعملاء
            @foreach ($clients as $client)
                @if ($client->locations)
                    addClientMarker(
                        {{ $client->id }},
                        {{ $client->locations->latitude }},
                        {{ $client->locations->longitude }},
                        "{{ $client->trade_name }}",
                        "{{ $client->code }}"
                    );
                @endif
            @endforeach
        }

        async function showClientLocation(lat, lng, clientId, name, code) {
            try {
                // إظهار الخريطة
                $('.map-container').addClass('map-visible');

                // التمرير إلى قسم الخريطة
                document.getElementById('mapSection').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

                // انتظار تحميل خرائط جوجل إذا لم تكن محملة
                if (!googleMapsLoaded) {
                    await waitForGoogleMaps();
                }

                // تهيئة الخريطة إذا لم تكن مهيأة
                if (!mapInitialized) {
                    initMap();
                    await new Promise(resolve => setTimeout(resolve, 500));
                }

                // توسيط الخريطة على الموقع المحدد
                const position = { lat, lng };
                map.setCenter(position);
                map.setZoom(15);

                // العثور على العلامة أو إنشاؤها
                let markerObj = markers.find(m => m.id == clientId);
                if (!markerObj) {
                    markerObj = addClientMarker(clientId, lat, lng, name, code);
                }

                // تحريك العلامة
                markerObj.marker.setAnimation(google.maps.Animation.BOUNCE);
                setTimeout(() => {
                    markerObj.marker.setAnimation(null);
                }, 2000);

                // فتح نافذة المعلومات
                markerObj.infoWindow.open(map, markerObj.marker);

                // إظهار رسالة نجاح
                showNotification('تم عرض موقع العميل على الخريطة', 'success');

            } catch (error) {
                console.error('خطأ في عرض الموقع:', error);
                showNotification('حدث خطأ أثناء عرض الموقع على الخريطة', 'error');
            }
        }

        function addClientMarker(id, lat, lng, name, code) {
            const position = { lat, lng };

            // إنشاء علامة مخصصة
            const marker = new google.maps.Marker({
                position,
                map,
                title: name,
                icon: {
                    url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                        <svg width="32" height="42" viewBox="0 0 32 42" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#6a11cb;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#2575fc;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path d="M16 0C7.164 0 0 7.164 0 16c0 16 16 26 16 26s16-10 16-26C32 7.164 24.836 0 16 0z" fill="url(#grad)"/>
                            <circle cx="16" cy="16" r="8" fill="white"/>
                            <text x="16" y="20" text-anchor="middle" fill="#6a11cb" font-family="Arial" font-size="12" font-weight="bold">●</text>
                        </svg>
                    `),
                    scaledSize: new google.maps.Size(32, 42),
                    anchor: new google.maps.Point(16, 42)
                },
                animation: google.maps.Animation.DROP
            });

            // إنشاء نافذة المعلومات
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div class="info-window-content" style="direction: rtl; text-align: right; min-width: 250px; font-family: 'Cairo', sans-serif;">
                        <div style="border-bottom: 2px solid #6a11cb; padding-bottom: 10px; margin-bottom: 10px;">
                            <h6 style="margin: 0; color: #6a11cb; font-weight: bold; font-size: 16px;">
                                <i class="fas fa-user" style="margin-left: 8px;"></i>
                                ${name}
                            </h6>
                            ${code ? `<p style="margin: 5px 0 0 0; color: #6c757d; font-size: 14px;">
                                <i class="fas fa-code" style="margin-left: 5px;"></i>
                                الكود: <strong>${code}</strong>
                            </p>` : ''}
                        </div>

                        <div style="margin-bottom: 15px;">
                            <p style="margin: 0; color: #495057; font-size: 14px;">
                                <i class="fas fa-map-marker-alt" style="color: #28a745; margin-left: 5px;"></i>
                                خط العرض: <strong>${lat.toFixed(6)}</strong>
                            </p>
                            <p style="margin: 5px 0 0 0; color: #495057; font-size: 14px;">
                                <i class="fas fa-map-marker-alt" style="color: #28a745; margin-left: 5px;"></i>
                                خط الطول: <strong>${lng.toFixed(6)}</strong>
                            </p>
                        </div>

                        <div style="text-align: center;">
                            <a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank"
                               style="
                                   display: inline-block;
                                   background: linear-gradient(135deg, #4caf50, #388e3c);
                                   color: white;
                                   text-decoration: none;
                                   padding: 8px 16px;
                                   border-radius: 20px;
                                   font-size: 14px;
                                   font-weight: bold;
                                   transition: all 0.3s ease;
                               "
                               onmouseover="this.style.transform='scale(1.05)'"
                               onmouseout="this.style.transform='scale(1)'">
                                <i class="fas fa-external-link-alt" style="margin-left: 5px;"></i>
                                فتح في خرائط جوجل
                            </a>
                        </div>
                    </div>
                `,
                pixelOffset: new google.maps.Size(0, -10)
            });

            // إضافة معالج النقر
            marker.addListener("click", () => {
                // إغلاق جميع النوافذ المفتوحة
                markers.forEach(m => m.infoWindow.close());
                // فتح النافذة الحالية
                infoWindow.open(map, marker);
            });

            const markerObj = { id, marker, infoWindow };
            markers.push(markerObj);
            return markerObj;
        }

        function waitForGoogleMaps() {
            return new Promise((resolve) => {
                const checkInterval = setInterval(() => {
                    if (googleMapsLoaded) {
                        clearInterval(checkInterval);
                        resolve();
                    }
                }, 100);
            });
        }

        function exportToExcel() {
            showNotification('جاري تصدير الملف...', 'info');

            try {
                const table = document.querySelector('#reportContainer table');
                const wb = XLSX.utils.table_to_book(table, {
                    raw: false,
                    cellDates: true,
                    sheet: "دليل العملاء"
                });

                // تخصيص عرض الأعمدة
                const ws = wb.Sheets["دليل العملاء"];
                const range = XLSX.utils.decode_range(ws['!ref']);

                // تعيين عرض الأعمدة
                ws['!cols'] = [
                    { wch: 5 },   // #
                    { wch: 15 },  // الكود
                    { wch: 25 },  // الاسم
                    { wch: 20 },  // الموقع
                    { wch: 15 },  // المنطقة
                    { wch: 15 },  // الهاتف
                    { wch: 15 }   // الجوال
                ];

                const today = new Date();
                const fileName = `دليل_العملاء_${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}.xlsx`;

                XLSX.writeFile(wb, fileName);
                showNotification('تم تصدير الملف بنجاح!', 'success');

            } catch (error) {
                console.error('خطأ في تصدير الملف:', error);
                showNotification('حدث خطأ أثناء تصدير الملف', 'error');
            }
        }

        function showNotification(message, type) {
            const alertTypes = {
                success: { icon: 'check-circle', class: 'alert-success' },
                error: { icon: 'exclamation-triangle', class: 'alert-danger' },
                info: { icon: 'info-circle', class: 'alert-info' },
                warning: { icon: 'exclamation-triangle', class: 'alert-warning' }
            };

            const alertInfo = alertTypes[type] || alertTypes.info;

            const alertHtml = `
                <div class="alert ${alertInfo.class} alert-dismissible fade show position-fixed"
                     style="
                         top: 20px;
                         right: 20px;
                         z-index: 9999;
                         min-width: 300px;
                         border-radius: 12px;
                         box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                         border: none;
                     ">
                    <i class="fas fa-${alertInfo.icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            $('body').append(alertHtml);

            // إزالة التنبيه تلقائياً
            setTimeout(() => {
                $('.alert').alert('close');
            }, 4000);
        }

        // CSS إضافي للتحسينات
        const additionalCSS = `
            <style>
                .avatar-sm {
                    width: 35px;
                    height: 35px;
                    font-size: 14px;
                }

                .info-window-content {
                    font-family: 'Cairo', sans-serif !important;
                }

                .gm-style-iw {
                    border-radius: 12px !important;
                    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
                }

                .gm-style-iw-d {
                    overflow: hidden !important;
                }

                .gm-style-iw-t::after {
                    background: linear-gradient(45deg, #6a11cb, #2575fc) !important;
                }
            </style>
        `;

        $('head').append(additionalCSS);

        // تعيين متغير window للوصول إليه من HTML
        window.initializeGoogleMaps = initializeGoogleMaps;
    </script>
@endsection