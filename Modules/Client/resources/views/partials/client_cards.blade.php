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
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn bg-gradient-info fa fa-ellipsis-v mr-1 mb-1 btn-sm"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end shadow-lg">
                                        @php
                                            $today = now()->toDateString();
                                            $hasActiveVisit = \App\Models\Visit::where('employee_id', auth()->id())
                                                ->where('client_id', $client->id)
                                                ->whereDate('visit_date', $today)
                                                ->whereNotNull('arrival_time')
                                                ->whereNull('departure_time')
                                                ->exists();
                                        @endphp

                                        {{-- عرض أو تسجيل زيارة --}}
                                        @if (auth()->user()->role === 'employee')
                                            @if ($hasActiveVisit)
                                                <a class="dropdown-item"
                                                    href="{{ route('clients.show', $client->id) }}">
                                                    <i class="fa fa-eye me-2 text-primary"></i>عرض
                                                </a>
                                            @else
                                                <a class="dropdown-item"
                                                    href="{{ route('clients.registerVisit', $client->id) }}">
                                                    <i class="fa fa-walking me-2 text-info"></i>تسجيل زيارة وعرض
                                                </a>
                                            @endif
                                        @else
                                            <a class="dropdown-item" href="{{ route('clients.show', $client->id) }}">
                                                <i class="fa fa-eye me-2 text-primary"></i>عرض
                                            </a>
                                        @endif

                                        {{-- تعديل --}}
                                        @if (auth()->user()->hasPermissionTo('Edit_Client'))
                                            <a class="dropdown-item" href="{{ route('clients.edit', $client->id) }}">
                                                <i class="fa fa-edit me-2 text-success"></i>تعديل
                                            </a>
                                        @endif

                                        {{-- إخفاء من الخريطة --}}
                                        <a class="dropdown-item text-warning hide-from-map-link" href="#"
                                            data-client-id="{{ $client->id }}"
                                            data-client-name="{{ $client->trade_name }}">
                                            <i class="fa fa-eye-slash me-2 text-warning"></i>إخفاء من الخريطة (24 ساعة)
                                        </a>

                                        {{-- حذف --}}
                                        @if (auth()->user()->hasPermissionTo('Delete_Client'))
                                            <a class="dropdown-item text-danger delete-client" href="#"
                                                data-id="{{ $client->id }}">
                                                <i class="fa fa-trash me-2"></i>حذف
                                            </a>
                                        @endif
                                    </div>
                                </div>
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
                                @php
                                    // تحديد لون التصنيف بناءً على تصنيف العميل
                                    $categoryClass = '';
                                    switch ($monthlyGroup) {
                                        case 'A':
                                            $categoryClass = 'category-a';
                                            break;
                                        case 'B':
                                            $categoryClass = 'category-b';
                                            break;
                                        case 'C':
                                            $categoryClass = 'category-c';
                                            break;
                                        case 'D':
                                            $categoryClass = 'category-d';
                                            break;
                                        default:
                                            $categoryClass = 'category-default';
                                    }
                                @endphp
                                <span class="category-badge {{ $categoryClass }}">{{ optional($client->categoriesClient)->name ?: 'غير مصنف' }}</span>
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

                    <!-- التصنيف الشهري مع Bar Chart الكبير -->
                    <div class="classification-section-large">
                        <div class="classification-header">
                            <h4>التصنيف الشهري {{ now()->year }}</h4>

                            <!-- دليل الألوان المحسّن -->
                            <div class="color-legend">
                                <div class="legend-item" data-bs-toggle="tooltip" data-bs-placement="top"
                                     title="<strong>A</strong><br>تحصيلات ممتازة<br>أكثر من 1000 ريال">
                                    <span class="legend-dot legend-a"></span>
                                    <span class="legend-label">A</span>
                                </div>
                                <div class="legend-item" data-bs-toggle="tooltip" data-bs-placement="top"
                                     title="<strong>B</strong><br>تحصيلات جيدة<br>500 - 1000 ريال">
                                    <span class="legend-dot legend-b"></span>
                                    <span class="legend-label">B</span>
                                </div>
                                <div class="legend-item" data-bs-toggle="tooltip" data-bs-placement="top"
                                     title="<strong>C</strong><br>تحصيلات متوسطة<br>200 - 500 ريال">
                                    <span class="legend-dot legend-c"></span>
                                    <span class="legend-label">C</span>
                                </div>
                                <div class="legend-item" data-bs-toggle="tooltip" data-bs-placement="top"
                                     title="<strong>D</strong><br>تحصيلات ضعيفة<br>0 - 200 ريال">
                                    <span class="legend-dot legend-d"></span>
                                    <span class="legend-label">D</span>
                                </div>
                            </div>
                        </div>

                        <!-- الرسم البياني الكبير -->
                        <div class="chart-container-large">
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
    @endpush


@else
    <div class="alert alert-info text-center" role="alert">
        <i class="fa fa-info-circle me-2"></i>
        <p class="mb-0">لا يوجد عملاء </p>
    </div>
@endif

<!-- Hidden chart data for each client -->
@foreach ($clients as $client)
    @php
        $chartData = [];
        $chartLabels = [];
        $chartColors = [];
        $chartBorderColors = [];

        $clientInfo = $clientsData[$client->id] ?? null;

        if ($clientInfo && isset($clientInfo['monthly'])) {
            foreach ($clientInfo['monthly'] as $monthName => $monthData) {
                $collected = $monthData['collected'] ?? 0;
                $group = strtoupper($monthData['group'] ?? 'D');

                $totalCollected = $collected;

                $chartLabels[] = $monthName;
                $chartData[] = $totalCollected;

                // ألوان النظام الجديد
                switch ($group) {
                    case 'A': // رمادي فاتح (أكثر من 1000)
                        $chartColors[] = 'rgba(189, 189, 189, 0.7)';
                        $chartBorderColors[] = 'rgba(158, 158, 158, 1)';
                        break;
                    case 'B': // أزرق سماوي (500-1000)
                        $chartColors[] = 'rgba(33, 150, 243, 0.7)';
                        $chartBorderColors[] = 'rgba(33, 150, 243, 1)';
                        break;
                    case 'C': // أخضر (200-500)
                        $chartColors[] = 'rgba(76, 175, 80, 0.7)';
                        $chartBorderColors[] = 'rgba(76, 175, 80, 1)';
                        break;
                    case 'D': // برتقالي (0-200)
                        $chartColors[] = 'rgba(255, 152, 0, 0.7)';
                        $chartBorderColors[] = 'rgba(255, 152, 0, 1)';
                        break;
                    default:
                        $chartColors[] = 'rgba(158, 158, 158, 0.7)';
                        $chartBorderColors[] = 'rgba(158, 158, 158, 1)';
                }
            }
        }

        $chartConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => $chartLabels,
                'datasets' => [
                    [
                        'label' => 'التحصيلات الشهرية',
                        'data' => $chartData,
                        'backgroundColor' => $chartColors,
                        'borderColor' => $chartBorderColors,
                        'borderWidth' => 2,
                        'borderRadius' => 8,
                        'borderSkipped' => false,
                    ],
                ],
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => [
                        'display' => false,
                    ],
                    'tooltip' => [
                        'enabled' => true,
                        'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                        'padding' => 12,
                        'titleFont' => [
                            'size' => 14,
                            'family' => 'Cairo, sans-serif',
                        ],
                        'bodyFont' => [
                            'size' => 13,
                            'family' => 'Cairo, sans-serif',
                        ],
                        'rtl' => true,
                        'displayColors' => true,
                        'callbacks' => [],
                    ],
                    'datalabels' => [
                        'anchor' => 'end',
                        'align' => 'top',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 11,
                            'family' => 'Cairo, sans-serif',
                        ],
                        'padding' => 4,
                    ],
                ],
                'scales' => [
                    'y' => [
                        'min' => 0,
                        'max' => 1200,
                        'ticks' => [
                            'stepSize' => 100,
                            'font' => [
                                'size' => 11,
                                'family' => 'Cairo, sans-serif',
                            ],
                        ],
                        'grid' => [
                            'color' => 'rgba(0, 0, 0, 0.05)',
                            'drawBorder' => false,
                        ],
                    ],
                    'x' => [
                        'ticks' => [
                            'font' => [
                                'size' => 12,
                                'family' => 'Cairo, sans-serif',
                                'weight' => 'bold',
                            ],
                            'maxRotation' => 45,
                            'minRotation' => 45,
                        ],
                        'grid' => [
                            'display' => false,
                        ],
                    ],
                ],
            ],
        ];
    @endphp

    <div id="chartData{{ $client->id }}" style="display: none;">{!! json_encode($chartConfig) !!}</div>
@endforeach

<script>
    function createCharts() {
        if (typeof window.clientCharts === 'undefined') {
            window.clientCharts = {};
        }

        document.querySelectorAll('canvas[id^="monthlyChart"]').forEach(canvas => {
            const clientId = canvas.id.replace('monthlyChart', '');

            if (window.clientCharts[clientId]) {
                window.clientCharts[clientId].destroy();
            }

            const chartDataElement = document.getElementById('chartData' + clientId);
            if (chartDataElement) {
                try {
                    const chartConfig = JSON.parse(chartDataElement.textContent);
                    const ctx = canvas.getContext('2d');

                    chartConfig.options.plugins.tooltip.callbacks = {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            const value = context.parsed.y;
                            return 'التحصيلات: ' + value.toLocaleString('ar-SA') + ' ريال';
                        }
                    };

                    chartConfig.options.plugins.datalabels.formatter = function(value) {
                        if (value === 0) return '';
                        if (value >= 1000) {
                            return (value / 1000).toFixed(1) + 'K';
                        }
                        return value.toLocaleString('ar-SA');
                    };

                    chartConfig.options.plugins.datalabels.color = function(context) {
                        return context.dataset.borderColor[context.dataIndex];
                    };

                    chartConfig.options.scales.y.ticks.callback = function(value) {
                        return value.toLocaleString('ar-SA');
                    };

                    window.clientCharts[clientId] = new Chart(ctx, chartConfig);
                } catch (e) {
                    console.error('Error creating chart for client ' + clientId, e);
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        createCharts();

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover',
                html: true
            });
        });
    });

    document.addEventListener('reinitializeCharts', function() {
        setTimeout(function() {
            createCharts();
        }, 150);
    });

    $(document).ajaxComplete(function() {
        setTimeout(function() {
            createCharts();
        }, 500);
    });

    document.addEventListener('clientsFiltered', function() {
        setTimeout(function() {
            createCharts();
        }, 500);
    });
</script>

<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script>
    (function() {
        function downloadWorkbook(workbook, filename) {
            const wbout = XLSX.write(workbook, {
                bookType: 'xlsx',
                type: 'array'
            });
            const blob = new Blob([wbout], {
                type: 'application/octet-stream'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
        }

        function exportClientsToExcel(clientsArray, filename = 'clients.xlsx') {
            if (!clientsArray || clientsArray.length === 0) {
                alert('لا توجد بيانات للتصدير');
                return;
            }

            const cols = ['id', 'code', 'trade_name', 'frist_name', 'phone', 'branch', 'category', 'created_at'];
            const data = [cols];
            clientsArray.forEach(c => {
                data.push(cols.map(k => c[k] ?? ''));
            });

            const ws = XLSX.utils.aoa_to_sheet(data);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Clients');

            downloadWorkbook(wb, filename);
        }

        document.getElementById('exportAllClientsBtn')?.addEventListener('click', function() {
            const clients = [];
            document.querySelectorAll('.client-card').forEach(card => {
                try {
                    const id = card.getAttribute('data-client-id');
                    const tradeName = card.querySelector('.client-title')?.textContent?.trim() || '';
                    const code = card.querySelector('.client-code-badge')?.textContent?.trim() || '';
                    const fristName = card.querySelector('.contact-item:nth-child(1) span')?.textContent?.trim() || '';
                    const phone = card.querySelector('.contact-item:nth-child(2) span')?.textContent?.trim() || '';
                    const category = card.querySelector('.contact-item:nth-child(3) span')?.textContent?.trim() || '';
                    const branch = card.querySelector('.contact-item:nth-child(4) span')?.textContent?.trim() || '';
                    const created = card.querySelector('.date-value')?.textContent?.trim() || '';

                    clients.push({
                        id,
                        code,
                        trade_name: tradeName,
                        frist_name: fristName,
                        phone,
                        branch,
                        category,
                        created_at: created
                    });
                } catch (e) {
                    console.warn('failed to gather client data', e);
                }
            });

            exportClientsToExcel(clients, 'clients_export_' + new Date().toISOString().slice(0, 10) + '.xlsx');
        });

        document.addEventListener('click', function(e) {
            const el = e.target.closest('.export-single-client');
            if (!el) return;
            e.preventDefault();
            try {
                const data = JSON.parse(el.getAttribute('data-client'));
                exportClientsToExcel([data], 'client_' + (data.id || 'export') + '.xlsx');
            } catch (err) {
                console.error('Failed to export single client', err);
                alert('حدث خطأ أثناء التصدير');
            }
        });
    })();
</script>