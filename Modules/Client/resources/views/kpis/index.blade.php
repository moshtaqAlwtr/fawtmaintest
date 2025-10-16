{{-- resources/views/client/kpis/index.blade.php --}}
@extends('sales::master')

@section('title')
مؤشرات أداء العملاء
@endsection

@push('styles')
<style>
.bg-analytics {
    background: linear-gradient(118deg, rgba(115,103,240,1), rgba(130,115,255,0.7));
    position: relative;
    overflow: hidden;
}

.bg-analytics .img-left,
.bg-analytics .img-right {
    position: absolute;
    width: 150px;
    opacity: 0.1;
}

.bg-analytics .img-left {
    left: 0;
    top: 0;
}

.bg-analytics .img-right {
    right: 0;
    bottom: 0;
}

.avatar {
    width: 2.5rem;
    height: 2.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.avatar-xl {
    width: 5rem;
    height: 5rem;
}

.avatar-content {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.bg-rgba-primary {
    background-color: rgba(115, 103, 240, 0.12) !important;
}

.bg-rgba-warning {
    background-color: rgba(255, 159, 67, 0.12) !important;
}

.bg-rgba-success {
    background-color: rgba(40, 199, 111, 0.12) !important;
}

.bg-rgba-info {
    background-color: rgba(13, 204, 225, 0.12) !important;
}

.card {
    box-shadow: 0 4px 24px 0 rgba(34,41,47,0.1);
    border-radius: 0.5rem;
    margin-bottom: 2rem;
    border: none;
    background: #fff;
}

.card-header {
    background: transparent;
    border-bottom: none;
    padding: 1.5rem 1.5rem 0;
}

.card-body {
    padding: 1.5rem;
}

.card-title {
    margin-bottom: 0;
    font-size: 1.285rem;
    font-weight: 500;
}

.text-bold-700 {
    font-weight: 700 !important;
}

.text-bold-600 {
    font-weight: 600 !important;
}

.font-large-1 {
    font-size: 1.5rem !important;
}

.font-large-2 {
    font-size: 2rem !important;
}

.font-medium-2 {
    font-size: 1.2rem !important;
}

.font-medium-5 {
    font-size: 2rem !important;
}

.mt-0 { margin-top: 0 !important; }
.mt-1 { margin-top: 0.25rem !important; }
.mt-2 { margin-top: 0.5rem !important; }
.mb-0 { margin-bottom: 0 !important; }
.mb-25 { margin-bottom: 1.5rem !important; }
.mb-2 { margin-bottom: 0.5rem !important; }
.pb-0 { padding-bottom: 0 !important; }
.p-50 { padding: 3rem !important; }

.text-primary { color: #7367f0 !important; }
.text-warning { color: #ff9f43 !important; }
.text-success { color: #28c76f !important; }
.text-info { color: #0dcce1 !important; }
.text-danger { color: #ea5455 !important; }
.text-white { color: #fff !important; }
.text-muted { color: #b9c3cd !important; }

.bg-primary { background-color: #7367f0 !important; }
.bg-warning { background-color: #ff9f43 !important; }
.bg-success { background-color: #28c76f !important; }
.bg-info { background-color: #0dcce1 !important; }
.bg-danger { background-color: #ea5455 !important; }

/* Timeline */
.activity-timeline {
    list-style: none;
    padding: 0;
    position: relative;
}

.activity-timeline li {
    position: relative;
    padding-right: 60px;
    padding-bottom: 30px;
    display: flex;
    align-items: flex-start;
}

.activity-timeline li:last-child {
    padding-bottom: 0;
}

.activity-timeline li:before {
    content: '';
    position: absolute;
    right: 19px;
    top: 45px;
    bottom: -5px;
    width: 2px;
    background-color: #e8e8e8;
}

.activity-timeline li:last-child:before {
    display: none;
}

.timeline-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    right: 0;
    top: 0;
    z-index: 1;
}

.timeline-icon i {
    color: white;
}

.timeline-info {
    flex: 1;
    padding-left: 0;
}

.timeline-info p {
    margin-bottom: 0.5rem;
}

.timeline-info span {
    display: block;
    line-height: 1.5;
    font-size: 0.875rem;
}

.activity-timeline small {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #b9c3cd;
}

.chart-info {
    padding: 0.5rem 0;
}

.series-info {
    display: flex;
    align-items: center;
}

.series-info i {
    margin-left: 0.5rem;
    font-size: 1.2rem;
}

.d-flex { display: flex !important; }
.flex-column { flex-direction: column !important; }
.justify-content-between { justify-content: space-between !important; }
.align-items-center { align-items: center !important; }
.align-items-start { align-items: flex-start !important; }

.list-unstyled {
    list-style: none;
    padding-left: 0;
}

@media (max-width: 991px) {
    .activity-timeline li {
        padding-right: 50px;
    }

    .timeline-icon {
        width: 35px;
        height: 35px;
    }
}
</style>
@endpush

@section('content')
<div class="content-body">
    <section id="client-kpis">
        <!-- الصف الأول: البطاقة الترحيبية والإحصائيات الأساسية -->
        <div class="row">
            <!-- بطاقة ترحيبية -->
            <div class="col-lg-6 col-md-12">
                <div class="card bg-analytics text-white">
                    <div class="card-content">
                        <div class="card-body text-center">
                            <img src="{{ asset('app-assets/images/elements/decore-left.png') }}" class="img-left" alt="decoration">
                            <img src="{{ asset('app-assets/images/elements/decore-right.png') }}" class="img-right" alt="decoration">
                            <div class="avatar avatar-xl bg-primary shadow mt-0">
                                <div class="avatar-content">
                                    <i class="feather icon-users white font-large-1"></i>
                                </div>
                            </div>
                            <div class="text-center">
                                <h1 class="mb-2 text-white">مؤشرات أداء العملاء</h1>
                                <p class="m-auto w-75">لوحة تحكم شاملة لمتابعة أداء وإحصائيات العملاء</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- إجمالي العملاء -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-primary p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-users text-primary font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ number_format($totalClients) }}</h2>
                        <p class="mb-0">إجمالي العملاء</p>
                    </div>
                    <div class="card-content">
                        <div id="total-clients-chart"></div>
                    </div>
                </div>
            </div>

            <!-- العملاء الجدد -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-success p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-user-plus text-success font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ number_format($newClientsThisMonth) }}</h2>
                        <p class="mb-0">عملاء جدد (هذا الشهر)</p>
                    </div>
                    <div class="card-content">
                        <div id="new-clients-chart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الصف الثاني: معدل النمو والعملاء النشطين -->
        <div class="row">
            <!-- معدل النمو -->
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-6 col-12 d-flex justify-content-between flex-column">
                                    <div>
                                        <h2 class="text-bold-700 mb-25">{{ $growthRate }}%</h2>
                                        <p class="text-bold-500 mb-2">معدل نمو العملاء</p>
                                        <h5 class="font-medium-2">
                                            <span class="{{ $growthRate > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $growthRate > 0 ? '+' : '' }}{{ $growthRate }}%
                                            </span>
                                            <span>مقارنة بالشهر الماضي</span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12 text-right">
                                    <div id="growth-rate-chart"></div>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-0">
                        <div class="card-body pt-2">
                            <div class="row avg-sessions">
                                <div class="col-6">
                                    <p class="mb-0">العملاء النشطين</p>
                                    <div class="progress progress-bar-success mt-1" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar"
                                             style="width: {{ $totalClients > 0 ? ($activeClients / $totalClients) * 100 : 0 }}%"></div>
                                    </div>
                                    <p class="mt-1 mb-0 text-success">{{ number_format($activeClients) }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0">متوسط القيمة</p>
                                    <div class="progress progress-bar-primary mt-1" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" style="width: 75%"></div>
                                    </div>
                                    <p class="mt-1 mb-0 text-primary">{{ number_format($averageClientValue) }} ريال</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- العملاء حسب الحالة -->
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-0">
                        <h4 class="card-title">توزيع العملاء حسب الحالة</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body pt-0">
                            <div id="status-chart"></div>
                            <div class="chart-info d-flex justify-content-between mt-2">
                                @foreach($clientsByStatus->take(3) as $status)
                                <div class="text-center">
                                    <p class="mb-1">{{ $status['status'] }}</p>
                                    <span class="font-large-1 text-bold-700"
                                          style="color: {{ $status['color'] }}">
                                        {{ number_format($status['count']) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الصف الثالث: الرسوم البيانية التفصيلية -->
        <div class="row match-height">
            <!-- العملاء حسب الفئة -->
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-0">
                        <h4>التصنيفات</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="category-chart" class="mb-3"></div>
                            @foreach($clientsByCategory as $category)
                            <div class="chart-info d-flex justify-content-between mb-1">
                                <div class="series-info d-flex align-items-center">
                                    <i class="fa fa-circle-o text-bold-700 text-primary"></i>
                                    <span class="text-bold-600 ml-50">{{ $category['category'] }}</span>
                                </div>
                                <div class="product-result">
                                    <span>{{ number_format($category['count']) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- العملاء حسب المنطقة -->
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="card-title">التوزيع الجغرافي</h4>
                            <p class="text-muted mt-25 mb-0">أفضل 5 مناطق</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body px-0">
                            <div id="region-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- آخر الأنشطة -->
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">آخر الأنشطة</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <ul class="activity-timeline timeline-left list-unstyled">
                                @forelse($recentNotes->take(5) as $note)
                                <li>
                                    <div class="timeline-icon bg-{{
                                        $note['status'] == 'completed' ? 'success' :
                                        ($note['status'] == 'pending' ? 'warning' : 'primary')
                                    }}">
                                        <i class="feather icon-{{
                                            $note['status'] == 'completed' ? 'check' : 'clock'
                                        }} font-medium-2"></i>
                                    </div>
                                    <div class="timeline-info">
                                        <p class="font-weight-bold mb-0">{{ $note['client_name'] }}</p>
                                        <span class="font-small-3">
                                            {{ $note['process'] }} - {{ Str::limit($note['description'], 50) }}
                                        </span>
                                        <small class="text-muted">{{ $note['date'] }}</small>
                                    </div>
                                </li>
                                @empty
                                <li>
                                    <div class="timeline-info">
                                        <p class="font-weight-bold mb-0">لا توجد أنشطة حديثة</p>
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الصف الرابع: العملاء الجدد أسبوعياً -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">العملاء الجدد (آخر 7 أيام)</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="weekly-clients-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0"></script>

<script>
window.addEventListener('load', function() {
    'use strict';

    if (typeof ApexCharts === 'undefined') {
        console.error('مكتبة ApexCharts غير محملة!');
        return;
    }

    var colors = {
        primary: '#7367f0',
        warning: '#ff9f43',
        danger: '#ea5455',
        success: '#28c76f',
        info: '#0dcce1',
        light: '#e7eeef'
    };

    // مخطط إجمالي العملاء
    var totalClientsOptions = {
        chart: {
            height: 100,
            type: 'area',
            toolbar: { show: false },
            sparkline: { enabled: true }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 0.9,
                opacityFrom: 0.7,
                opacityTo: 0.5,
                stops: [0, 80, 100]
            }
        },
        series: [{
            name: 'العملاء',
            data: [{{ implode(',', array_column($newClientsWeekly, 'count')) }}]
        }],
        colors: [colors.primary],
        tooltip: { x: { show: false } }
    };
    new ApexCharts(document.querySelector("#total-clients-chart"), totalClientsOptions).render();

    // مخطط العملاء الجدد
    var newClientsOptions = {
        chart: {
            height: 100,
            type: 'area',
            toolbar: { show: false },
            sparkline: { enabled: true }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 0.9,
                opacityFrom: 0.7,
                opacityTo: 0.5,
                stops: [0, 80, 100]
            }
        },
        series: [{
            name: 'جديد',
            data: [{{ implode(',', array_column($newClientsWeekly, 'count')) }}]
        }],
        colors: [colors.success],
        tooltip: { x: { show: false } }
    };
    new ApexCharts(document.querySelector("#new-clients-chart"), newClientsOptions).render();

    // مخطط معدل النمو
    var growthOptions = {
        chart: {
            height: 200,
            type: 'bar',
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '20%',
                borderRadius: 5
            }
        },
        dataLabels: { enabled: false },
        series: [{
            name: 'العملاء',
            data: [{{ implode(',', array_column($newClientsWeekly, 'count')) }}]
        }],
        colors: [colors.primary],
        xaxis: {
            categories: {!! json_encode(array_column($newClientsWeekly, 'day')) !!},
            labels: { show: true }
        },
        yaxis: { show: false },
        grid: { show: false }
    };
    new ApexCharts(document.querySelector("#growth-rate-chart"), growthOptions).render();

    // مخطط الحالة
    var statusOptions = {
        chart: {
            height: 290,
            type: 'radialBar'
        },
        plotOptions: {
            radialBar: {
                size: 150,
                hollow: { size: '20%' },
                track: { strokeWidth: '100%', margin: 15 },
                dataLabels: {
                    name: { fontSize: '18px' },
                    value: { fontSize: '16px' },
                    total: {
                        show: true,
                        label: 'الإجمالي',
                        formatter: function() {
                            return {{ $totalClients }};
                        }
                    }
                }
            }
        },
        colors: {!! json_encode($clientsByStatus->pluck('color')->toArray()) !!},
        series: {!! json_encode($clientsByStatus->pluck('count')->toArray()) !!},
        labels: {!! json_encode($clientsByStatus->pluck('status')->toArray()) !!},
        stroke: { lineCap: 'round' }
    };
    new ApexCharts(document.querySelector("#status-chart"), statusOptions).render();

    // مخطط الفئات
    var categoryOptions = {
        chart: {
            height: 350,
            type: 'radialBar'
        },
        plotOptions: {
            radialBar: {
                hollow: { size: '30%' },
                dataLabels: {
                    name: { fontSize: '14px' },
                    value: { fontSize: '16px' }
                }
            }
        },
        colors: [colors.primary, colors.warning, colors.danger, colors.success],
        series: {!! json_encode($clientsByCategory->pluck('count')->toArray()) !!},
        labels: {!! json_encode($clientsByCategory->pluck('category')->toArray()) !!}
    };
    new ApexCharts(document.querySelector("#category-chart"), categoryOptions).render();

    // مخطط المناطق
    var regionOptions = {
        chart: {
            height: 400,
            type: 'radar',
            toolbar: { show: false }
        },
        series: [{
            name: 'العملاء',
            data: {!! json_encode($clientsByRegion->pluck('total')->toArray()) !!}
        }],
        colors: [colors.info],
        xaxis: {
            categories: {!! json_encode($clientsByRegion->pluck('region')->toArray()) !!}
        }
    };
    new ApexCharts(document.querySelector("#region-chart"), regionOptions).render();

    // مخطط العملاء الأسبوعي
    var weeklyOptions = {
        chart: {
            height: 350,
            type: 'line',
            toolbar: { show: false }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        series: [{
            name: 'عملاء جدد',
            data: [{{ implode(',', array_column($newClientsWeekly, 'count')) }}]
        }],
        colors: [colors.success],
        xaxis: {
            categories: {!! json_encode(array_column($newClientsWeekly, 'day')) !!}
        },
        markers: {
            size: 5,
            colors: [colors.success],
            strokeWidth: 0,
            hover: { size: 7 }
        }
    };
    new ApexCharts(document.querySelector("#weekly-clients-chart"), weeklyOptions).render();
});
</script>
@endpush