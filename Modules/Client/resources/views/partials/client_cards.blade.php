<!-- تحديث ملف: resources/views/client/partials/client_cards.blade.php -->
@if (isset($clients) && $clients->count() > 0)
    <div class="row g-4">
        @foreach ($clients as $client)
            @php
                $clientData = $clientsData[$client->id] ?? null;
                $due = $clientDueBalances[$client->id] ?? 0;
                $totalSales = $clientTotalSales[$client->id] ?? 0;
                $currentMonth = now()->format('m');
                $monthlyGroup = $clientData['monthly_groups'][$currentMonth]['group'] ?? ($clientData['group'] ?? 'D');
                $monthlyGroupClass =
                    $clientData['monthly_groups'][$currentMonth]['group_class'] ??
                    ($clientData['group_class'] ?? 'secondary');
            @endphp

            <div class="col-lg-6 client-card" data-client-id="{{ $client->id }}">
                <div class="client-card-elegant">
                    <!-- مؤشر التحميل -->
                    <div class="loading-overlay d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>

                    <!-- Header مع اسم العميل والإجراءات -->
                    <div class="card-header-elegant">
                        <div class="client-title-section">
                            <h3 class="client-title">{{ $client->trade_name }}</h3>
                            <span class="client-code-badge">{{ $client->code }}</span>
                        </div>

                        <div class="actions-section">
                            <!-- حالة العميل -->
                            @php
                                $lastNote = $client
                                    ->appointmentNotes()
                                    ->where('employee_id', auth()->id())
                                    ->where('process', 'إبلاغ المشرف')
                                    ->whereNotNull('employee_view_status')
                                    ->latest()
                                    ->first();
                                $statusToShow = $client->status_client;
                                if (
                                    auth()->user()->role === 'employee' &&
                                    $lastNote &&
                                    $lastNote->employee_id == auth()->id()
                                ) {
                                    $statusToShow = $statuses->find($lastNote->employee_view_status);
                                }
                            @endphp

                            @if ($statusToShow)
                                <div class="status-indicator"
                                    style="background: linear-gradient(135deg, {{ $statusToShow->color }}15, {{ $statusToShow->color }}25);
                                            border-left: 3px solid {{ $statusToShow->color }};">
                                    <span style="color: {{ $statusToShow->color }};">{{ $statusToShow->name }}</span>
                                </div>
                            @else
                                <div class="status-indicator status-unknown">
                                    <span>غير محدد</span>
                                </div>
                            @endif

                            <!-- Dropdown المحسّن -->
                            <div class="dropdown client-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle-btn" type="button"
                                    id="clientActionsDropdown{{ $client->id }}" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg"
                                    aria-labelledby="clientActionsDropdown{{ $client->id }}">
                                    @php
                                        $today = now()->toDateString();
                                        $hasActiveVisit = \App\Models\Visit::where('employee_id', auth()->id())
                                            ->where('client_id', $client->id)
                                            ->whereDate('visit_date', $today)
                                            ->whereNotNull('arrival_time')
                                            ->whereNull('departure_time')
                                            ->exists();
                                    @endphp

                                    @if (auth()->user()->role === 'employee')
                                        @if ($hasActiveVisit)
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('clients.show', $client->id) }}">
                                                    <i class="far fa-eye me-1"></i> عرض
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('clients.registerVisit', $client->id) }}">
                                                    <i class="fas fa-walking me-1"></i> تسجيل زيارة وعرض
                                                </a>
                                            </li>
                                        @endif
                                    @else
                                        <li>
                                            <a class="dropdown-item" href="{{ route('clients.show', $client->id) }}">
                                                <i class="far fa-eye me-1"></i> عرض
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->hasPermissionTo('Edit_Client'))
                                        <li>
                                            <a class="dropdown-item" href="{{ route('clients.edit', $client->id) }}">
                                                <i class="fas fa-edit me-1"></i> تعديل
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-warning hide-from-map-link" href="#"
                                            data-client-id="{{ $client->id }}"
                                            data-client-name="{{ $client->trade_name }}">
                                            <i class="fas fa-eye-slash me-1"></i> إخفاء من الخريطة (24 ساعة)
                                        </a>
                                    </li>
                                    @if (auth()->user()->hasPermissionTo('Delete_Client'))
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger"
                                                href="{{ route('clients.destroy', $client->id) }}">
                                                <i class="fas fa-trash-alt me-1"></i> حذف
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                        </div>
                    </div>

                    <!-- معلومات الاتصال -->
                    <div class="contact-section">
                        <div class="contact-grid">
                            <div class="contact-item">
                                <i class="fas fa-user"></i>
                                <span>{{ $client->frist_name ?: 'غير محدد' }}</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ $client->phone ?: 'غير محدد' }}</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-tags"></i>
                                <span>{{ optional($client->categoriesClient)->name ?: 'غير مصنف' }}</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-building"></i>
                                <span>{{ $client->branch->name ?: 'غير محدد' }}</span>
                            </div>
                        </div>

                        <!-- الموقع والمسافة -->
                        <div class="location-section">
                            <div class="location-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $client->locations->latitude ?? '' }},{{ $client->locations->longitude ?? '' }}"
                                    target="_blank">{{ $client->Neighborhood->Region->name ?? 'عرض الموقع' }}</a>
                            </div>

                            @php
                                $distanceInfo = $clientDistances[$client->id] ?? null;
                                $distanceClass = 'distance-default';
                                $distanceText = 'غير متاح';

                                if ($distanceInfo && isset($distanceInfo['distance'])) {
                                    if ($distanceInfo['distance'] !== null) {
                                        $distanceText = number_format($distanceInfo['distance'], 1) . ' كم';
                                        $distanceClass = $distanceInfo['within_range']
                                            ? 'distance-close'
                                            : 'distance-far';
                                    } else {
                                        $distanceText = $distanceInfo['message'] ?? 'غير متاح';
                                    }
                                }
                            @endphp

                            <div class="distance-item {{ $distanceClass }}">
                                <i class="fas fa-route"></i>
                                <span>{{ $distanceText }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- التواريخ المهمة -->
                    <div class="dates-section">
                        <div class="date-item">
                            <div class="date-label">تاريخ التسجيل</div>
                            <div class="date-value">{{ $client->created_at->format('Y/m/d') }}</div>
                        </div>
                        <div class="date-separator"></div>
                        <div class="date-item">
                            <div class="date-label">آخر فاتورة</div>
                            <div class="date-value">
                                {{ optional($client->invoices->last())->invoice_date ? \Carbon\Carbon::parse($client->invoices->last()->invoice_date)->diffForHumans() : 'لا توجد' }}
                            </div>
                        </div>

                        <div class="date-item">
                            <div class="date-label">آخر دفعة/سند قبض</div>
                            <div class="date-value">
                                @php
                                    $lastPayment = $client
                                        ->invoices()
                                        ->with('payments')
                                        ->get()
                                        ->pluck('payments')
                                        ->flatten()
                                        ->sortByDesc('created_at')
                                        ->first();

                                    $lastReceipt = $client->account
                                        ? $client->account->receipts()->latest('created_at')->first()
                                        : null;

                                    if ($lastPayment && $lastReceipt) {
                                        if ($lastPayment->payment_date >= $lastReceipt->created_at) {
                                            echo \Carbon\Carbon::parse($lastPayment->created_at)->diffForHumans() .
                                                ' (دفعة)';
                                        } else {
                                            echo \Carbon\Carbon::parse($lastReceipt->created_at)->diffForHumans() .
                                                ' (سند قبض)';
                                        }
                                    } elseif ($lastPayment) {
                                        echo \Carbon\Carbon::parse($lastPayment->created_at)->diffForHumans() .
                                            ' (دفعة)';
                                    } elseif ($lastReceipt) {
                                        echo \Carbon\Carbon::parse($lastReceipt->created_at)->diffForHumans() .
                                            ' (سند قبض)';
                                    } else {
                                        echo 'لا توجد';
                                    }
                                @endphp
                            </div>
                        </div>
                    </div>

                    <!-- الإحصائيات المالية -->
                    <div class="stats-section">
                        <div class="stat-card stat-sales">
                            <div class="stat-number">{{ number_format($totalSales ?? 0) }}</div>
                            <div class="stat-label">إجمالي المبيعات</div>
                        </div>
                        <div class="stat-card stat-collected">
                            <div class="stat-number">
                                {{ number_format($clientsData[$client->id]['total_collected'] ?? 0) }}</div>
                            <div class="stat-label">التحصيلات</div>
                        </div>
                        <div class="stat-card stat-due">
                            <div class="stat-number">{{ number_format($clientDueBalances[$client->id] ?? 0) }}</div>
                            <div class="stat-label">المبالغ الآجلة</div>
                        </div>
                    </div>

                    <!-- التصنيف الشهري مع Bar Chart -->
                    <div class="classification-section">
                        <div class="classification-header">
                            <h4>التصنيف الشهري {{ $currentYear }}</h4>
                        </div>

                        <!-- الرسم البياني فقط -->
                        <div class="chart-container">
                            <canvas id="monthlyChart{{ $client->id }}" class="monthly-chart"></canvas>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    <!-- إضافة Chart.js -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($clients as $client)
                @php
                    $chartData = [];
                    $chartLabels = [];
                    $chartColors = [];
                    $chartBorderColors = [];

                    // Debug: التأكد من وجود البيانات
                    $clientInfo = $clientsData[$client->id] ?? null;

                    if ($clientInfo && isset($clientInfo['monthly'])) {
                        foreach ($clientInfo['monthly'] as $monthName => $monthData) {
                            $collected = $monthData['collected'] ?? 0;
                            $paymentsTotal = $monthData['payments_total'] ?? 0;
                            $receiptsTotal = $monthData['receipts_total'] ?? 0;
                            $group = strtoupper($monthData['group'] ?? 'D');

                            // حساب إجمالي التحصيلات (المدفوعات + سندات القبض)
                            $totalCollected = $collected;

                            $chartLabels[] = $monthName;
                            $chartData[] = $totalCollected;

                            // تحديد لون البار حسب التصنيف
                            switch($group) {
                                case 'A':
                                    $chartColors[] = 'rgba(33, 150, 243, 0.7)';
                                    $chartBorderColors[] = 'rgba(33, 150, 243, 1)';
                                    break;
                                case 'B':
                                    $chartColors[] = 'rgba(76, 175, 80, 0.7)';
                                    $chartBorderColors[] = 'rgba(76, 175, 80, 1)';
                                    break;
                                case 'C':
                                    $chartColors[] = 'rgba(255, 152, 0, 0.7)';
                                    $chartBorderColors[] = 'rgba(255, 152, 0, 1)';
                                    break;
                                case 'D':
                                    $chartColors[] = 'rgba(244, 67, 54, 0.7)';
                                    $chartBorderColors[] = 'rgba(244, 67, 54, 1)';
                                    break;
                                default:
                                    $chartColors[] = 'rgba(158, 158, 158, 0.7)';
                                    $chartBorderColors[] = 'rgba(158, 158, 158, 1)';
                            }
                        }
                    }
                @endphp

                const ctx{{ $client->id }} = document.getElementById('monthlyChart{{ $client->id }}');
                if (ctx{{ $client->id }}) {
                    const chartData{{ $client->id }} = {!! json_encode($chartData) !!};
                    const chartLabels{{ $client->id }} = {!! json_encode($chartLabels) !!};
                    const chartColors{{ $client->id }} = {!! json_encode($chartColors) !!};
                    const chartBorderColors{{ $client->id }} = {!! json_encode($chartBorderColors) !!};

                    console.log('Client {{ $client->id }} - Labels:', chartLabels{{ $client->id }});
                    console.log('Client {{ $client->id }} - Data:', chartData{{ $client->id }});
                    console.log('Client {{ $client->id }} - Colors:', chartColors{{ $client->id }});

                    new Chart(ctx{{ $client->id }}, {
                        type: 'bar',
                        data: {
                            labels: chartLabels{{ $client->id }},
                            datasets: [{
                                label: 'التحصيلات الشهرية',
                                data: chartData{{ $client->id }},
                                backgroundColor: chartColors{{ $client->id }},
                                borderColor: chartBorderColors{{ $client->id }},
                                borderWidth: 2,
                                borderRadius: 6,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    titleFont: {
                                        size: 13,
                                        family: 'Cairo, sans-serif'
                                    },
                                    bodyFont: {
                                        size: 12,
                                        family: 'Cairo, sans-serif'
                                    },
                                    callbacks: {
                                        title: function(context) {
                                            return context[0].label;
                                        },
                                        label: function(context) {
                                            const value = context.parsed.y;
                                            return 'التحصيلات: ' + value.toLocaleString('ar-SA') + ' ريال';
                                        }
                                    },
                                    rtl: true,
                                    displayColors: true
                                },
                                datalabels: {
                                    anchor: 'end',
                                    align: 'top',
                                    formatter: function(value) {
                                        if (value === 0) return '';
                                        if (value >= 1000) {
                                            return (value / 1000).toFixed(1) + 'K';
                                        }
                                        return value.toLocaleString('ar-SA');
                                    },
                                    font: {
                                        weight: 'bold',
                                        size: 9,
                                        family: 'Cairo, sans-serif'
                                    },
                                    color: function(context) {
                                        return context.dataset.borderColor[context.dataIndex];
                                    },
                                    padding: 2
                                }
                            },
                            scales: {
                                y: {
                                    min: 50,
                                    max: 500,
                                    ticks: {
                                        stepSize: 100,
                                        font: {
                                            size: 10,
                                            family: 'Cairo, sans-serif'
                                        },
                                        callback: function(value) {
                                            return value.toLocaleString('ar-SA');
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)',
                                        drawBorder: false
                                    }
                                },
                                x: {
                                    ticks: {
                                        font: {
                                            size: 12,
                                            family: 'Cairo, sans-serif',
                                            weight: 'bold'
                                        },
                                        maxRotation: 45,
                                        minRotation: 45
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            @endforeach
        });
    </script>
    @endpush

    <style>
        .client-card-elegant {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            overflow: visible;
            position: relative;
        }

        .client-card-elegant:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        /* إصلاح Dropdown */
        .client-dropdown {
            position: relative;
        }

        .dropdown-toggle-btn {
            font-size: 11px;
            padding: 5px 10px;
        }

        .client-dropdown .dropdown-menu {
            position: absolute !important;
            top: 100% !important;
            left: auto !important;
            right: 0 !important;
            z-index: 9999 !important;
            margin-top: 5px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
            min-width: 200px;
        }

        .client-dropdown .dropdown-menu.show {
            display: block !important;
        }

        .client-card {
            position: relative;
            z-index: 1;
        }

        .client-card:has(.dropdown-menu.show) {
            z-index: 1000;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            backdrop-filter: blur(2px);
        }

        /* Header Section */
        .card-header-elegant {
            padding: 24px;
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border-bottom: 1px solid #f0f0f0;
        }

        .client-title-section {
            margin-bottom: 16px;
        }

        .client-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .client-code-badge {
            background: #e8f4f8;
            color: #2c5aa0;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .actions-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-indicator {
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-unknown {
            background: #f5f5f5;
            border-left: 3px solid #9e9e9e;
            color: #757575;
        }

        /* Contact Section */
        .contact-section {
            padding: 20px 24px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #495057;
        }

        .contact-item i {
            width: 16px;
            color: #6c757d;
            font-size: 12px;
        }

        .location-section {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            padding-top: 16px;
            border-top: 1px solid #f0f0f0;
        }

        .location-item a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .distance-item {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .distance-close {
            background: #d4edda;
            color: #155724;
        }

        .distance-far {
            background: #f8d7da;
            color: #721c24;
        }

        .distance-default {
            background: #e2e3e5;
            color: #383d41;
        }

        /* Dates Section */
        .dates-section {
            padding: 16px 24px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
        }

        .date-item {
            flex: 1;
            text-align: center;
        }

        .date-label {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .date-value {
            font-size: 13px;
            color: #212529;
            font-weight: 700;
        }

        .date-separator {
            width: 1px;
            height: 30px;
            background: #dee2e6;
            margin: 0 16px;
        }

        /* Stats Section */
        .stats-section {
            padding: 20px 24px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .stat-card {
            text-align: center;
            padding: 16px 8px;
            border-radius: 10px;
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-sales {
            background: linear-gradient(135deg, #667eea20, #764ba220);
        }

        .stat-collected {
            background: linear-gradient(135deg, #11998e20, #38ef7d20);
        }

        .stat-due {
            background: linear-gradient(135deg, #fc466b20, #3f5efb20);
        }

        .stat-number {
            font-size: 16px;
            font-weight: 800;
            color: #212529;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 11px;
            color: #6c757d;
            font-weight: 600;
        }

        /* Classification Section with Chart */
        .classification-section {
            padding: 20px 24px;
            background: #fafafa;
        }

        .classification-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .classification-header h4 {
            font-size: 14px;
            color: #495057;
            font-weight: 700;
            margin: 0;
        }

        .chart-container {
            height: 220px;
            position: relative;
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .monthly-chart {
            width: 100% !important;
            height: 100% !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .stats-section {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .stat-card {
                display: flex;
                align-items: center;
                text-align: right;
                gap: 12px;
            }

            .location-section {
                grid-template-columns: 1fr;
                gap: 8px;
            }
        }
    </style>
@else
    <div class="alert alert-info text-center" role="alert">
        <i class="fa fa-info-circle me-2"></i>
        <p class="mb-0">لا يوجد عملاء </p>
    </div>
@endif
