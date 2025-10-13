@extends('master')

@section('title')
    تقرير أعمار ديون الأستاذ العام
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f8f9fa;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
        }

        .card-modern {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            border: none;
        }

        .card-header-modern {
            background: #f8f9fa;
            border-radius: 16px 16px 0 0;
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .card-body-modern {
            padding: 1.5rem;
        }

        .form-control {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        .btn-modern {
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary-modern:hover {
            background: linear-gradient(135deg, #5a67d8, #6b46c1);
            transform: translateY(-2px);
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .btn-success-modern:hover {
            background: linear-gradient(135deg, #218838, #1e7e74);
            transform: translateY(-2px);
        }

        .btn-danger-modern {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .btn-danger-modern:hover {
            background: linear-gradient(135deg, #c82333, #bd2130);
            transform: translateY(-2px);
        }

        .btn-warning-modern {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: white;
        }

        .btn-warning-modern:hover {
            background: linear-gradient(135deg, #e0a800, #e8590c);
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .stats-card.active {
            border: 2px solid #667eea;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
        }

        .stats-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 600;
        }

        .table-modern {
            font-size: 0.85rem;
        }

        .table-modern thead th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
            padding: 1rem 0.5rem;
            border: none;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 10;
            text-align: center;
        }

        .table-modern tbody tr {
            transition: all 0.2s ease;
        }

        .table-modern tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        .table-modern tbody td {
            vertical-align: middle;
            padding: 0.75rem 0.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 16px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
            border-radius: 0 0 16px 16px;
        }

        .badge {
            padding: 0.4rem 0.6rem;
            border-radius: 6px;
            font-size: 0.75rem;
        }

        .client-link {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            border: 1px solid transparent;
            position: relative;
        }

        .client-link:hover {
            color: #764ba2;
            text-decoration: none;
            background-color: rgba(102, 126, 234, 0.1);
            border-color: rgba(102, 126, 234, 0.3);
            transform: translateY(-1px);
        }

        .client-link:active {
            transform: translateY(0);
        }

        .client-link::after {
            content: "\f35d";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            margin-right: 0.5rem;
            font-size: 0.8em;
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .client-link:hover::after {
            opacity: 1;
            transform: translateX(2px);
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            display: inline-block;
            font-weight: 600;
            font-size: 0.8rem;
            color: white;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }

            .table-responsive {
                max-height: 400px;
            }

            .btn-modern {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }

        /* تحسينات خاصة بالطباعة */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .container-fluid {
                padding: 0 !important;
            }

            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 20px;
                padding: 10px;
                border-bottom: 2px solid #667eea;
            }

            .print-header h2 {
                color: #667eea;
                margin: 0;
                font-size: 1.5rem;
            }

            .card-modern {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
                border-radius: 0 !important;
                margin: 0 !important;
            }

            .card-header-modern {
                background: #667eea !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                border-radius: 0 !important;
                padding: 10px !important;
            }

            .card-body-modern {
                padding: 0 !important;
            }

            .table-responsive {
                max-height: none !important;
                overflow: visible !important;
            }

            .table-modern {
                font-size: 0.75rem !important;
            }

            .table-modern thead th {
                background: #667eea !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                padding: 8px 4px !important;
                position: static !important;
            }

            .table-modern tbody td {
                padding: 6px 4px !important;
                border: 1px solid #dee2e6 !important;
            }

            .table-modern tfoot tr {
                background: #28a745 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .table-modern tfoot td {
                padding: 8px 4px !important;
                border: 1px solid #fff !important;
            }

            .badge, .status-badge {
                border: 1px solid #333 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .client-link::after {
                display: none !important;
            }

            .client-link {
                color: #000 !important;
                text-decoration: underline !important;
                border: none !important;
                background: none !important;
            }

            .table-modern tbody tr {
                page-break-inside: avoid;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
            100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
        }

        .print-header {
            display: none;
        }
    </style>
@endsection

@section('content')
    <!-- عنوان خاص بالطباعة فقط -->
    <div class="print-header">
        <h2>تقرير أعمار ديون الأستاذ العام</h2>
        <p>تاريخ الطباعة: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="page-header no-print">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between">
                <h1><i class="fas fa-chart-line me-3"></i>تقرير أعمار ديون الأستاذ العام</h1>
                <div class="text-end">
                    <small class="opacity-75">آخر تحديث: {{ now()->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Filters -->
        <div class="card-modern fade-in no-print">
            <div class="card-header-modern">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>فلاتر التقرير</h5>
            </div>
            <div class="card-body-modern">
                <form id="reportForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-user me-1"></i>العميل</label>
                            <select name="customer" id="customer" class="form-control select2">
                                <option value="">جميع العملاء</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->trade_name ?? $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-building me-1"></i>الفرع</label>
                            <select name="branch" id="branch" class="form-control select2">
                                <option value="">جميع الفروع</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-tags me-1"></i>التصنيف</label>
                            <select name="customer_type" id="customer_type" class="form-control select2">
                                <option value="">جميع التصنيفات</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-map-marker-alt me-1"></i>المجموعة (المنطقة)</label>
                            <select name="group" id="group" class="form-control select2">
                                <option value="">جميع المجموعات</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-info-circle me-1"></i>الحالة</label>
                            <select name="status" id="status" class="form-control select2">
                                <option value="">جميع الحالات</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-clock me-1"></i>فلتر حسب الفترة</label>
                            <select name="aging_filter" id="aging_filter" class="form-control select2">
                                <option value="">جميع الفترات</option>
                                <option value="today">اليوم فقط</option>
                                <option value="1-30">1-30 يوم</option>
                                <option value="31-60">31-60 يوم</option>
                                <option value="61-90">61-90 يوم</option>
                                <option value="91-120">91-120 يوم</option>
                                <option value="120+">أكثر من 120 يوم</option>
                                <option value="150">121-150 يوم</option>
                                <option value="180">151-180 يوم</option>
                                <option value="210">181-210 يوم</option>
                                <option value="240">211-240 يوم</option>
                                <option value="240+">أكثر من 240 يوم</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-user-tie me-1"></i>مسؤول المبيعات</label>
                            <select name="sales_manager" id="sales_manager" class="form-control select2">
                                <option value="">جميع مسؤولي المبيعات</option>
                                @foreach ($salesManagers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-calendar me-1"></i>من تاريخ</label>
                            <input type="date" name="from_date" class="form-control" value="{{ date('Y-m-01') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-calendar me-1"></i>إلى تاريخ</label>
                            <input type="date" name="to_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="button" class="btn-modern btn-primary-modern me-2" id="filterBtn">
                                <i class="fas fa-search me-1"></i> عرض التقرير
                            </button>
                            <button type="button" class="btn-modern btn-outline-secondary" id="resetBtn">
                                <i class="fas fa-redo me-1"></i> إعادة تعيين
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Actions -->
        <div class="card-modern no-print fade-in">
            <div class="card-body-modern text-center">
                <button class="btn-modern btn-success-modern me-2" id="exportExcel">
                    <i class="fas fa-file-excel me-1"></i> تصدير Excel
                </button>

                <button class="btn-modern btn-warning-modern" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> طباعة التقرير
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="row no-print" id="totalsSection">
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="stats-card" data-filter="today">
                    <div class="stats-value" id="todayAmount">0</div>
                    <div class="stats-label">اليوم</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="stats-card" data-filter="1-30">
                    <div class="stats-value" id="days1to30">0</div>
                    <div class="stats-label">1-30 يوم</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="stats-card" data-filter="31-60">
                    <div class="stats-value" id="days31to60">0</div>
                    <div class="stats-label">31-60 يوم</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="stats-card" data-filter="61-90">
                    <div class="stats-value" id="days61to90">0</div>
                    <div class="stats-label">61-90 يوم</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="stats-card" data-filter="91-120">
                    <div class="stats-value" id="days91to120">0</div>
                    <div class="stats-label">91-120 يوم</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="stats-card" data-filter="120+">
                    <div class="stats-value" id="daysOver120">0</div>
                    <div class="stats-label">+120 يوم</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="stats-card" data-filter="150">
                    <div class="stats-value" id="days150">0</div>
                    <div class="stats-label">121-150 يوم</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="stats-card" data-filter="180">
                    <div class="stats-value" id="days180">0</div>
                    <div class="stats-label">151-180 يوم</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="stats-card" data-filter="210">
                    <div class="stats-value" id="days210">0</div>
                    <div class="stats-label">181-210 يوم</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="stats-card" data-filter="240">
                    <div class="stats-value" id="days240">0</div>
                    <div class="stats-label">211-240 يوم</div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="stats-card" data-filter="240+">
                    <div class="stats-value" id="daysOver240">0</div>
                    <div class="stats-label">+240 يوم</div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card-modern fade-in" id="reportContainer" style="position: relative;">
            <div class="loading-overlay no-print" style="display: none;">
                <div class="spinner"></div>
            </div>
            <div class="card-header-modern">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>بيانات التقرير</h5>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2" id="recordsCount">0 سجل</span>
                        <span class="badge bg-success" id="customersCount">0 عميل</span>
                    </div>
                </div>
            </div>
            <div class="card-body-modern p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th><i class="fas fa-barcode me-1"></i>كود</th>
                                <th><i class="fas fa-user me-1"></i>العميل</th>
                                <th><i class="fas fa-building me-1"></i>الفرع</th>
                                <th><i class="fas fa-map-marker-alt me-1"></i>المجموعة</th>
                                <th><i class="fas fa-home me-1"></i>الحي</th>
                                <th><i class="fas fa-info-circle me-1"></i>الحالة</th>
                                <th><i class="fas fa-calendar-day me-1"></i>اليوم</th>
                                <th>1-30</th>
                                <th>31-60</th>
                                <th>61-90</th>
                                <th>91-120</th>
                                <th>+120</th>
                                <th><i class="fas fa-calculator me-1"></i>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody id="reportTableBody">
                            <tr>
                                <td colspan="13" class="text-center p-4">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="spinner-border text-primary me-2"></div>
                                        <span>جاري تحميل البيانات...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot id="reportTableFooter" style="display: none;">
                            <tr style="background: linear-gradient(135deg, #28a745, #20c997); color: white; font-weight: bold;">
                                <td colspan="6" class="text-center">
                                    <i class="fas fa-calculator me-2"></i>الإجمالي الكلي
                                </td>
                                <td id="footerToday">0.00</td>
                                <td id="footer1to30">0.00</td>
                                <td id="footer31to60">0.00</td>
                                <td id="footer61to90">0.00</td>
                                <td id="footer91to120">0.00</td>
                                <td id="footerOver120">0.00</td>
                                <td id="footerTotal">0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mt-4 no-print">
            <div class="col-md-3">
                <div class="card-modern">
                    <div class="card-body-modern text-center">
                        <i class="fas fa-money-bill-wave text-success fa-2x mb-2"></i>
                        <h5 class="mb-1">إجمالي المتأخر</h5>
                        <h3 class="text-success" id="totalOverdueAmount">0.00</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-modern">
                    <div class="card-body-modern text-center">
                        <i class="fas fa-calendar-alt text-info fa-2x mb-2"></i>
                        <h5 class="mb-1">متوسط الأيام</h5>
                        <h3 class="text-info" id="averageDaysLate">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <!-- إضافة دعم للغة العربية -->
    <script src="https://cdn.jsdelivr.net/npm/moment/locale/ar.js"></script>
    <script>
        // تعيين اللغة العربية كلغة افتراضية
        moment.locale('ar');
    </script>

    <script>
        var currentRequest = null;

        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                dir: 'rtl',
                width: '100%',
                placeholder: 'اختر من القائمة...',
                allowClear: true,
                theme: 'classic'
            });

            $('.select2-container--classic .select2-selection--single').css({
                'border': '1px solid #dee2e6',
                'border-radius': '8px',
                'height': 'calc(2.25rem + 2px)'
            });

            loadReportData();

            $('#filterBtn').on('click', function() {
                $(this).addClass('pulse');
                loadReportData();
                setTimeout(function() {
                    $(this).removeClass('pulse');
                }.bind(this), 2000);
            });

            $('#resetBtn').on('click', function() {
                $('#reportForm')[0].reset();
                $('.select2').val(null).trigger('change');
                $('.stats-card').removeClass('active');
                loadReportData();
            });

            $('.stats-card').on('click', function() {
                var filter = $(this).data('filter');
                $('.stats-card').removeClass('active');
                $(this).addClass('active');
                $('#aging_filter').val(filter).trigger('change');
                loadReportData();
            });

            $('#aging_filter').on('change', function() {
                var filter = $(this).val();
                $('.stats-card').removeClass('active');
                if (filter) {
                    $('.stats-card[data-filter="' + filter + '"]').addClass('active');
                }
            });

            $('#exportExcel').on('click', exportToExcel);
            $('#exportPDF').on('click', exportToPDF);

            setInterval(loadReportData, 300000);
        });

        function loadReportData() {
            // إلغاء الطلب السابق إذا كان موجوداً
            if (currentRequest) {
                currentRequest.abort();
            }

            // عرض مؤشر التحميل
            $('.loading-overlay').show();
            $('#filterBtn').prop('disabled', true);

            // تعيين timeout لإيقاف التحميل إذا استغرق وقتاً طويلاً
            var loadingTimeout = setTimeout(function() {
                if (currentRequest) {
                    currentRequest.abort();
                    $('.loading-overlay').hide();
                    $('#filterBtn').prop('disabled', false);
                    showError('انتهت مهلة تحميل البيانات');
                }
            }, 30000); // 30 ثانية كحد أقصى

            currentRequest = $.ajax({
                url: '{{ route("ClientReport.debtAgingGeneralLedgerAjax") }}',
                method: 'GET',
                data: $('#reportForm').serialize(),
                headers: {
                    'Accept-Language': 'ar',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    clearTimeout(loadingTimeout);
                    if (response.success) {
                        // تحويل البيانات إلى UTF-8 قبل عرضها
                        try {
                            if (typeof response.data === 'string') {
                                response.data = JSON.parse(response.data);
                            }
                            updateDisplay(response);
                            showSuccessMessage('تم تحديث البيانات بنجاح');
                        } catch (e) {
                            console.error('Error processing data:', e);
                            showError('خطأ في معالجة البيانات');
                        }
                    } else {
                        showError('لا توجد بيانات متاحة');
                    }
                },
                error: function(xhr) {
                    clearTimeout(loadingTimeout);
                    if (xhr.statusText !== 'abort') {
                        console.error('Ajax Error:', xhr);
                        showError('حدث خطأ في تحميل البيانات: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'خطأ غير معروف'));
                    }
                },
                complete: function() {
                    $('.loading-overlay').hide();
                    $('#filterBtn').prop('disabled', false);
                    currentRequest = null;
                }
            });
        }

        function updateDisplay(data) {
            // تحويل البيانات إلى UTF-8
            try {
                data = JSON.parse(decodeURIComponent(escape(JSON.stringify(data))));
            } catch (e) {
                console.warn('UTF-8 conversion not needed');
            }

            updateStatsCards(data.totals);
            updateSummaryCards(data.summary);
            $('#recordsCount').text((data.records_count || 0) + ' سجل');
            $('#customersCount').text((data.customers_count || 0) + ' عميل');
            updateFooterTotals(data.totals);
            updateTableContent(data.data);
        }

        function updateStatsCards(totals) {
            $('#todayAmount').text(formatNumber(totals.today || 0));
            $('#days1to30').text(formatNumber(totals.days1to30 || 0));
            $('#days31to60').text(formatNumber(totals.days31to60 || 0));
            $('#days61to90').text(formatNumber(totals.days61to90 || 0));
            $('#days91to120').text(formatNumber(totals.days91to120 || 0));
            $('#daysOver120').text(formatNumber(totals.daysOver120 || 0));
            $('#days150').text(formatNumber(totals.days150 || 0));
            $('#days180').text(formatNumber(totals.days180 || 0));
            $('#days210').text(formatNumber(totals.days210 || 0));
            $('#days240').text(formatNumber(totals.days240 || 0));
            $('#daysOver240').text(formatNumber(totals.daysOver240 || 0));
        }

        function updateSummaryCards(summary) {
            $('#totalOverdueAmount').text(formatNumber(summary.total_overdue_amount || 0));
            $('#averageDaysLate').text(Math.round(summary.average_days_late || 0));
        }

        function updateFooterTotals(totals) {
            $('#footerToday').text(formatNumber(totals.today || 0));
            $('#footer1to30').text(formatNumber(totals.days1to30 || 0));
            $('#footer31to60').text(formatNumber(totals.days31to60 || 0));
            $('#footer61to90').text(formatNumber(totals.days61to90 || 0));
            $('#footer91to120').text(formatNumber(totals.days91to120 || 0));
            $('#footerOver120').text(formatNumber(totals.daysOver120 || 0));
            $('#footerTotal').text(formatNumber(totals.total_due || 0));
            $('#reportTableFooter').show();
        }

        function updateTableContent(data) {
            var html = '';

            if (data && data.length > 0) {
                // تصفية البيانات وإزالة السجلات الفارغة
                data = data.filter(function(item) {
                    return item && (item.client_name || item.client_code);
                });

                for (var i = 0; i < data.length; i++) {
                    var item = data[i];
                    // التأكد من أن جميع القيم موجودة وتحويلها إلى النص العربي المناسب
                    item.client_name = item.client_name || 'غير محدد';
                    item.branch = item.branch || 'غير محدد';
                    item.group = item.group || 'غير محدد';
                    item.neighborhood = item.neighborhood || 'غير محدد';
                    item.status = item.status || 'غير محدد';

                    // محاولة تصحيح الترميز إذا كان النص غير مقروء
                    try {
                        if (!/[\u0600-\u06FF]/.test(item.client_name)) {
                            item.client_name = decodeURIComponent(escape(item.client_name));
                        }
                        if (!/[\u0600-\u06FF]/.test(item.branch)) {
                            item.branch = decodeURIComponent(escape(item.branch));
                        }
                        if (!/[\u0600-\u06FF]/.test(item.group)) {
                            item.group = decodeURIComponent(escape(item.group));
                        }
                        if (!/[\u0600-\u06FF]/.test(item.neighborhood)) {
                            item.neighborhood = decodeURIComponent(escape(item.neighborhood));
                        }
                        if (!/[\u0600-\u06FF]/.test(item.status)) {
                            item.status = decodeURIComponent(escape(item.status));
                        }
                    } catch (e) {
                        console.warn('Error decoding text:', e);
                    }

                    var statusStyle = item.status_color ?
                        'background-color: ' + item.status_color + '; color: white;' :
                        'background-color: #6c757d; color: white;';

                    var clientUrl = item.client_id ?
                        '{{ route("clients.show", "") }}/' + item.client_id :
                        '#';

                    html += '<tr class="fade-in">';
                    html += '<td><span class="badge bg-info">' + (item.client_code || 'غير محدد') + '</span></td>';

                    if (item.client_id) {
                        html += '<td><a href="' + clientUrl + '" class="client-link" target="_blank" title="عرض تفاصيل العميل">' + (item.client_name || 'غير محدد') + '</a></td>';
                    } else {
                        html += '<td><span class="text-muted">' + (item.client_name || 'غير محدد') + '</span></td>';
                    }

                    html += '<td><span class="badge bg-secondary">' + (item.branch || 'غير محدد') + '</span></td>';
                    html += '<td>' + (item.group || 'غير محدد') + '</td>';
                    html += '<td>' + (item.neighborhood || 'غير محدد') + '</td>';
                    html += '<td><span class="status-badge" style="' + statusStyle + '">' + (item.status || 'غير محدد') + '</span></td>';
                    html += '<td class="text-center">' + formatNumber(item.today) + '</td>';
                    html += '<td class="text-center">' + formatNumber(item.days1to30) + '</td>';
                    html += '<td class="text-center">' + formatNumber(item.days31to60) + '</td>';
                    html += '<td class="text-center">' + formatNumber(item.days61to90) + '</td>';
                    html += '<td class="text-center">' + formatNumber(item.days91to120) + '</td>';
                    html += '<td class="text-center">' + formatNumber(item.daysOver120) + '</td>';
                    html += '<td class="text-center fw-bold text-primary">' + formatNumber(item.total_due) + '</td>';
                    html += '</tr>';
                }
            } else {
                html = '<tr><td colspan="13" class="text-center p-4">';
                html += '<div class="d-flex flex-column align-items-center">';
                html += '<i class="fas fa-inbox fa-3x text-muted mb-3"></i>';
                html += '<h5 class="text-muted">لا توجد بيانات</h5>';
                html += '<p class="text-muted">جرب تغيير فلاتر البحث</p>';
                html += '</div></td></tr>';
                $('#reportTableFooter').hide();
            }

            $('#reportTableBody').html(html);
        }

        function formatNumber(num) {
            return parseFloat(num || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function exportToExcel() {
            try {
                var table = document.querySelector('.table-modern');
                if (!table) {
                    showError('لا يمكن العثور على الجدول للتصدير');
                    return;
                }

                var wb = XLSX.utils.table_to_book(table, {
                    sheet: "تقرير أعمار الديون",
                    raw: false
                });

                var filename = 'debt_aging_report_' + new Date().toISOString().slice(0,10) + '.xlsx';
                XLSX.writeFile(wb, filename);

                showSuccessMessage('تم تصدير ملف Excel بنجاح');
            } catch (error) {
                console.error('Export error:', error);
                showError('حدث خطأ أثناء تصدير الملف');
            }
        }

        function exportToPDF() {
            try {
                showLoadingMessage('جاري إنشاء ملف PDF...');

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF({
                    orientation: 'landscape',
                    unit: 'mm',
                    format: 'a4'
                });

                // إضافة خط عربي من Google Fonts
                doc.addFileToVFS('NotoSansArabic.ttf', 'data:font/truetype;charset=utf-8;base64,');
                doc.addFont('NotoSansArabic.ttf', 'NotoSansArabic', 'normal');
                doc.setFont('NotoSansArabic');

                // عنوان التقرير
                doc.setFontSize(18);
                doc.setTextColor(102, 126, 234);
                doc.text('تقرير أعمار ديون الأستاذ العام', doc.internal.pageSize.width / 2, 15, { align: 'center' });

                // التاريخ
                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100);
                var currentDate = new Date().toLocaleDateString('ar-SA', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                doc.text('تاريخ الطباعة: ' + currentDate, doc.internal.pageSize.width / 2, 22, { align: 'center' });

                // إحصائيات
                var recordsCount = document.getElementById('recordsCount').textContent;
                var customersCount = document.getElementById('customersCount').textContent;
                doc.text(recordsCount + ' | ' + customersCount, doc.internal.pageSize.width / 2, 28, { align: 'center' });

                // جمع بيانات الجدول مع الحفاظ على النصوص العربية
                var tableData = [];
                var rows = document.querySelectorAll('#reportTableBody tr');

                rows.forEach(function(row) {
                    var cells = row.querySelectorAll('td');
                    if (cells.length > 1) {
                        var rowData = [];
                        cells.forEach(function(cell) {
                            // الحصول على النص بدون HTML tags وتحويله إلى UTF-8
                            var tempDiv = document.createElement('div');
                            tempDiv.innerHTML = cell.innerHTML;
                            var text = tempDiv.textContent || tempDiv.innerText || '';
                            text = decodeURIComponent(escape(text.trim()));
                            rowData.push(text);
                        });
                        tableData.push(rowData);
                    }
                });

                // إعداد رأس الجدول باللغة العربية
                var headers = [
                    ['كود', 'العميل', 'الفرع', 'المجموعة', 'الحي', 'الحالة', 'اليوم', '1-30', '31-60', '61-90', '91-120', '+120', 'الإجمالي']
                ];

                // إضافة صف الإجمالي
                var footerRow = [
                    'الإجمالي الكلي',
                    '',
                    '',
                    '',
                    '',
                    '',
                    document.getElementById('footerToday').textContent,
                    document.getElementById('footer1to30').textContent,
                    document.getElementById('footer31to60').textContent,
                    document.getElementById('footer61to90').textContent,
                    document.getElementById('footer91to120').textContent,
                    document.getElementById('footerOver120').textContent,
                    document.getElementById('footerTotal').textContent
                ];

                // إنشاء الجدول الرئيسي بدون footer
                doc.autoTable({
                    head: headers,
                    body: tableData,
                    startY: 35,
                    theme: 'grid',
                    showFoot: false,
                    styles: {
                        font: 'NotoSansArabic',
                        fontSize: 8,
                        cellPadding: 2,
                        halign: 'center',
                        valign: 'middle',
                        lineColor: [221, 221, 221],
                        lineWidth: 0.1,
                        textColor: [51, 51, 51],
                        fontStyle: 'normal'
                    },
                    headStyles: {
                        fillColor: [102, 126, 234],
                        textColor: [255, 255, 255],
                        fontStyle: 'bold',
                        fontSize: 9,
                        halign: 'center'
                    },
                    alternateRowStyles: {
                        fillColor: [248, 249, 250]
                    },
                    columnStyles: {
                        0: { cellWidth: 15 },
                        1: { cellWidth: 35 },
                        2: { cellWidth: 20 },
                        3: { cellWidth: 25 },
                        4: { cellWidth: 20 },
                        5: { cellWidth: 20 },
                        6: { cellWidth: 18 },
                        7: { cellWidth: 18 },
                        8: { cellWidth: 18 },
                        9: { cellWidth: 18 },
                        10: { cellWidth: 18 },
                        11: { cellWidth: 18 },
                        12: { cellWidth: 22 }
                    },
                    margin: { top: 35, right: 10, bottom: 20, left: 10 },
                    didDrawPage: function(data) {
                        // إضافة رقم الصفحة
                        doc.setFont('NotoSansArabic');
                        doc.setFontSize(8);
                        doc.setTextColor(100);
                        var pageCount = doc.internal.getNumberOfPages();
                        var currentPage = doc.internal.getCurrentPageInfo().pageNumber;
                        doc.text('صفحة ' + currentPage + ' من ' + pageCount,
                            doc.internal.pageSize.width / 2,
                            doc.internal.pageSize.height - 10,
                            { align: 'center' });
                    }
                });

                // إضافة صف الإجمالي في نهاية الجدول فقط
                var finalY = doc.lastAutoTable.finalY || 35;

                doc.autoTable({
                    body: [footerRow],
                    startY: finalY + 2,
                    theme: 'grid',
                    showHead: false,
                    styles: {
                        font: 'NotoSansArabic',
                        fontSize: 9,
                        cellPadding: 3,
                        halign: 'center',
                        valign: 'middle',
                        lineColor: [40, 167, 69],
                        lineWidth: 0.5,
                        textColor: [255, 255, 255],
                        fillColor: [40, 167, 69],
                        fontStyle: 'bold'
                    },
                    columnStyles: {
                        0: { cellWidth: 15 },
                        1: { cellWidth: 35 },
                        2: { cellWidth: 20 },
                        3: { cellWidth: 25 },
                        4: { cellWidth: 20 },
                        5: { cellWidth: 20 },
                        6: { cellWidth: 18 },
                        7: { cellWidth: 18 },
                        8: { cellWidth: 18 },
                        9: { cellWidth: 18 },
                        10: { cellWidth: 18 },
                        11: { cellWidth: 18 },
                        12: { cellWidth: 22 }
                    },
                    margin: { right: 10, left: 10 }
                });

                // حفظ الملف
                var filename = 'debt_aging_report_' + new Date().toISOString().slice(0,10) + '.pdf';
                doc.save(filename);

                hideLoadingMessage();
                showSuccessMessage('تم تصدير ملف PDF بنجاح');

            } catch (error) {
                console.error('PDF Export Error:', error);
                hideLoadingMessage();

                // في حالة فشل الخط العربي، استخدم helvetica مع معالجة النص
                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF({
                        orientation: 'landscape',
                        unit: 'mm',
                        format: 'a4'
                    });

                    doc.setFont('helvetica');

                    // عنوان مبسط
                    doc.setFontSize(16);
                    doc.setTextColor(102, 126, 234);
                    doc.text('Debt Aging Report', doc.internal.pageSize.width / 2, 15, { align: 'center' });

                    // جمع البيانات
                    var tableData = [];
                    var rows = document.querySelectorAll('#reportTableBody tr');

                    rows.forEach(function(row) {
                        var cells = row.querySelectorAll('td');
                        if (cells.length > 1) {
                            var rowData = [];
                            cells.forEach(function(cell) {
                                var text = cell.textContent.trim();
                                rowData.push(text);
                            });
                            tableData.push(rowData);
                        }
                    });

                    var headers = [['Code', 'Client', 'Branch', 'Group', 'Area', 'Status', 'Today', '1-30', '31-60', '61-90', '91-120', '+120', 'Total']];

                    var footerRow = [
                        'Grand Total', '', '', '', '', '',
                        document.getElementById('footerToday').textContent,
                        document.getElementById('footer1to30').textContent,
                        document.getElementById('footer31to60').textContent,
                        document.getElementById('footer61to90').textContent,
                        document.getElementById('footer91to120').textContent,
                        document.getElementById('footerOver120').textContent,
                        document.getElementById('footerTotal').textContent
                    ];

                    doc.autoTable({
                        head: headers,
                        body: tableData,
                        startY: 25,
                        showFoot: false,
                        styles: { fontSize: 8, halign: 'center' },
                        headStyles: { fillColor: [102, 126, 234] }
                    });

                    doc.autoTable({
                        body: [footerRow],
                        startY: doc.lastAutoTable.finalY + 2,
                        showHead: false,
                        styles: {
                            fontSize: 9,
                            halign: 'center',
                            fillColor: [40, 167, 69],
                            textColor: [255, 255, 255],
                            fontStyle: 'bold'
                        }
                    });

                    var filename = 'debt_aging_report_' + new Date().toISOString().slice(0,10) + '.pdf';
                    doc.save(filename);

                    showSuccessMessage('تم تصدير PDF (نسخة مبسطة)');
                } catch (fallbackError) {
                    showError('حدث خطأ أثناء تصدير PDF: ' + fallbackError.message);
                }
            }
        }

        function showError(message) {
            var toast = $('<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">' +
                '<div class="toast align-items-center text-white bg-danger border-0" role="alert">' +
                '<div class="d-flex">' +
                '<div class="toast-body"><i class="fas fa-exclamation-circle me-2"></i>' + message + '</div>' +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
                '</div></div></div>');

            $('body').append(toast);
            var toastEl = toast.find('.toast')[0];
            if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                var bsToast = new bootstrap.Toast(toastEl, { delay: 5000 });
                bsToast.show();
            } else {
                toast.find('.toast').addClass('show');
            }

            setTimeout(function() { toast.remove(); }, 6000);
        }

        function showSuccessMessage(message) {
            var toast = $('<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">' +
                '<div class="toast align-items-center text-white bg-success border-0" role="alert">' +
                '<div class="d-flex">' +
                '<div class="toast-body"><i class="fas fa-check-circle me-2"></i>' + message + '</div>' +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
                '</div></div></div>');

            $('body').append(toast);
            var toastEl = toast.find('.toast')[0];
            if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                var bsToast = new bootstrap.Toast(toastEl, { delay: 3000 });
                bsToast.show();
            } else {
                toast.find('.toast').addClass('show');
            }

            setTimeout(function() { toast.remove(); }, 4000);
        }

        function showLoadingMessage(message) {
            var loadingToast = $('<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999" id="loadingToast">' +
                '<div class="toast align-items-center text-white bg-info border-0 show" role="alert">' +
                '<div class="d-flex">' +
                '<div class="toast-body">' +
                '<div class="spinner-border spinner-border-sm me-2" role="status"></div>' + message +
                '</div></div></div></div>');

            $('body').append(loadingToast);
        }

        function hideLoadingMessage() {
            $('#loadingToast').remove();
        }
    </script>
@endsection
