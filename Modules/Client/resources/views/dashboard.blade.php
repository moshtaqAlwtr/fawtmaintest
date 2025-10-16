{{-- resources/views/client/dashboard.blade.php --}}
@extends('sales::master')

@section('title')
لوحة تحكم العملاء
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.css">
<style>
/* تنسيقات المخططات */
.chart-container {
    min-height: 350px;
    width: 100%;
    position: relative;
}

.small-chart {
    min-height: 100px;
    width: 100%;
}

/* مهم: إضافة هذه القاعدة لمنع إخفاء المخططات */
.apexcharts-canvas {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: relative !important;
}

/* أنماط لوحة التحكم */
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

/* Progress bar */
.progress {
    height: 0.5rem;
    border-radius: 1rem;
    background-color: rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-bar {
    border-radius: 1rem;
    transition: width 0.6s ease;
}

.progress-bar-primary .progress-bar,
.progress-bar-primary,
.progress.progress-bar-primary {
    background-color: #7367f0;
}

.progress-bar-warning .progress-bar,
.progress-bar-warning,
.progress.progress-bar-warning {
    background-color: #ff9f43;
}

.progress-bar-success .progress-bar,
.progress-bar-success,
.progress.progress-bar-success {
    background-color: #28c76f;
}

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

.legend-item {
    display: inline-flex;
    align-items: center;
    margin-right: 1rem;
    font-size: 0.875rem;
}

.legend-indicator {
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-left: 0.5rem;
    border-radius: 50%;
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
    <section id="client-dashboard">
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
                                <h1 class="mb-2 text-white">مرحباً بك في لوحة تحكم العملاء</h1>
                                <p class="m-auto w-75">إحصائيات شاملة عن عملائك وتحليل الأداء</p>
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
                        <div id="total-clients-chart" class="small-chart"></div>
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
                        <div id="new-clients-chart" class="small-chart"></div>
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
                                    <div id="growth-rate-chart" style="min-height: 200px;"></div>
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
                                    <p class="mb-0">إجمالي الديون</p>
                                    <div class="progress progress-bar-primary mt-1" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" style="width: 75%"></div>
                                    </div>
                                    <p class="mt-1 mb-0 text-primary">{{ number_format($totalDebt) }} ريال</p>
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
                            <div id="status-chart" style="min-height: 290px;"></div>
                            <div class="chart-info d-flex justify-content-between mt-2">
                                @foreach($clientsByStatus->take(5) as $status)
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

        <!-- الصف الثالث: مخططات العملاء الشهرية -->
        <div class="row">
            <!-- العملاء الجدد شهريًا -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">العملاء الجدد (آخر 12 شهر)</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="monthly-clients-chart" class="chart-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- الصف الرابع: مقارنة العام الحالي مع العام السابق -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">مقارنة أداء العام الحالي مع العام السابق</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="yearly-comparison-chart" class="chart-container"></div>
                            <div class="d-flex justify-content-center mt-2">
                                <div class="legend-item">
                                    <span>{{ $yearlyComparison['currentYear'] }}</span>
                                    <span class="legend-indicator" style="background-color: #7367f0;"></span>
                                </div>
                                <div class="legend-item">
                                    <span>{{ $yearlyComparison['previousYear'] }}</span>
                                    <span class="legend-indicator" style="background-color: #28c76f;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الصف الخامس: الرسوم البيانية التفصيلية -->
        <div class="row match-height">
            <!-- العملاء حسب الفئة -->
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-0">
                        <h4>التصنيفات</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="category-chart" style="min-height: 300px;" class="mb-3"></div>
                            <div class="chart-categories mt-2">
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
                            <div id="region-chart" style="min-height: 300px;"></div>
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

        <!-- الصف السادس: العملاء حسب الفرع والموظف -->
        <div class="row">
            <!-- العملاء حسب الفرع -->
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">توزيع العملاء حسب الفرع</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>الفرع</th>
                                            <th>عدد العملاء</th>
                                            <th>النسبة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($clientsByBranch as $branch)
                                        <tr>
                                            <td>{{ $branch['branch'] }}</td>
                                            <td>{{ number_format($branch['count']) }}</td>
                                            <td>
                                                <div class="progress progress-bar-primary" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{ $totalClients > 0 ? ($branch['count'] / $totalClients) * 100 : 0 }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">لا توجد بيانات</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- العملاء حسب الموظف -->
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">أفضل الموظفين (حسب عدد العملاء)</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>الموظف</th>
                                            <th>عدد العملاء</th>
                                            <th>النسبة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($clientsByEmployee as $employee)
                                        <tr>
                                            <td>{{ $employee['employee'] }}</td>
                                            <td>{{ number_format($employee['count']) }}</td>
                                            <td>
                                                <div class="progress progress-bar-success" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{ $totalClients > 0 ? ($employee['count'] / $totalClients) * 100 : 0 }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">لا توجد بيانات</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    console.log('تم تحميل الصفحة - بدء تهيئة المخططات البيانية');

    // تحقق مما إذا كانت المكتبة محملة بشكل صحيح
    if (typeof ApexCharts === 'undefined') {
        console.error('مكتبة ApexCharts غير محملة!');
        // محاولة إعادة تحميل المكتبة
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js';
        script.onload = function() {
            console.log('تم تحميل مكتبة ApexCharts بنجاح - بدء تهيئة المخططات');
            setTimeout(initCharts, 300);
        };
        document.head.appendChild(script);
        return;
    }

    // إضافة تأخير بسيط لضمان جاهزية DOM
    setTimeout(initCharts, 300);

    function initCharts() {
        try {
            console.log('بدء تهيئة المخططات البيانية...');

            // التأكد من تحميل المكتبة
            if (typeof ApexCharts === 'undefined') {
                console.error('مكتبة ApexCharts غير محملة بعد!');
                return;
            }

            // التحقق من وجود العناصر في الصفحة
            ['total-clients-chart', 'new-clients-chart', 'growth-rate-chart',
             'status-chart', 'monthly-clients-chart', 'yearly-comparison-chart',
             'category-chart', 'region-chart'].forEach(function(elementId) {
                var element = document.getElementById(elementId);
                if (!element) {
                    console.warn('عنصر المخطط غير موجود: #' + elementId);
                } else {
                    // طباعة حجم وموضع العنصر للتشخيص
                    var rect = element.getBoundingClientRect();
                    console.log('أبعاد #' + elementId + ':',
                                'العرض=' + rect.width,
                                'الارتفاع=' + rect.height,
                                'X=' + rect.x,
                                'Y=' + rect.y);

                    // تأكد من أن العنصر له أبعاد
                    if (rect.width === 0 || rect.height === 0) {
                        console.warn('#' + elementId + ' له أبعاد صفرية! تحديد أبعاد للعنصر...');
                        element.style.minHeight = '100px';
                        element.style.width = '100%';
                    }
                }
            });

            var colors = {
                primary: '#7367f0',
                warning: '#ff9f43',
                danger: '#ea5455',
                success: '#28c76f',
                info: '#0dcce1',
                light: '#e7eeef'
            };

            // مخطط إجمالي العملاء
            renderChart('#total-clients-chart', {
                chart: {
                    height: 100,
                    type: 'area',
                    toolbar: { show: false },
                    sparkline: { enabled: true },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
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
                    data: [28, 40, 36, 52, 38, 60]
                }],
                colors: [colors.primary],
                xaxis: {
                    labels: { show: false },
                    axisBorder: { show: false }
                },
                yaxis: [{
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    labels: { show: false },
                    padding: { left: 0, right: 0 }
                }],
                grid: { show: false },
                tooltip: {
                    x: { show: false }
                }
            }, 'مخطط إجمالي العملاء');

            // مخطط العملاء الجدد
            renderChart('#new-clients-chart', {
                chart: {
                    height: 100,
                    type: 'area',
                    toolbar: { show: false },
                    sparkline: { enabled: true },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
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
                    name: 'عملاء جدد',
                    data: [10, 15, 8, 15, 7, 12]
                }],
                colors: [colors.success],
                xaxis: {
                    labels: { show: false },
                    axisBorder: { show: false }
                },
                yaxis: [{
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    labels: { show: false },
                    padding: { left: 0, right: 0 }
                }],
                grid: { show: false },
                tooltip: {
                    x: { show: false }
                }
            }, 'مخطط العملاء الجدد');

            // مخطط معدل النمو
            renderChart('#growth-rate-chart', {
                chart: {
                    height: 200,
                    type: 'line',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'straight',
                    width: 3
                },
                series: [{
                    name: 'معدل النمو',
                    data: [0, 10, 5, 15, 10, 20, 15, 25, 20, 30, 25, 40]
                }],
                colors: [colors.success],
                grid: {
                    borderColor: '#e7eef0',
                    strokeDashArray: 5,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    }
                },
                xaxis: {
                    categories: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
                    labels: { show: false }
                },
                yaxis: { show: false }
            }, 'مخطط معدل النمو');

            // مخطط الحالة
            renderChart('#status-chart', {
                chart: {
                    height: 290,
                    type: 'radialBar',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
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
                colors: [{!! json_encode($clientsByStatus->pluck('color')->toArray()) !!}],
                series: {!! json_encode($clientsByStatus->pluck('count')->toArray()) !!},
                labels: {!! json_encode($clientsByStatus->pluck('status')->toArray()) !!},
                stroke: { lineCap: 'round' }
            }, 'مخطط الحالة');

            // مخطط العملاء الشهري
            renderChart('#monthly-clients-chart', {
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                series: [{
                    name: 'عملاء جدد',
                    data: {!! json_encode($newClientsMonthly['counts']) !!}
                }],
                colors: [colors.primary],
                xaxis: {
                    categories: {!! json_encode($newClientsMonthly['months']) !!},
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    crosshairs: { show: true },
                    labels: { show: true, style: { fontSize: '12px' } }
                },
                yaxis: {
                    labels: { show: true }
                },
                grid: {
                    borderColor: '#EBEBEB',
                    row: { colors: ['#f5f5f5', 'transparent'], opacity: 0.25 }
                },
                plotOptions: {
                    bar: {
                        columnWidth: '50%',
                        borderRadius: 5
                    }
                },
                tooltip: {
                    shared: true,
                    y: {
                        formatter: function(val) {
                            return val.toLocaleString();
                        }
                    }
                }
            }, 'مخطط العملاء الشهري');

            // مخطط مقارنة الأداء السنوي
            renderChart('#yearly-comparison-chart', {
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: [3, 3] },
                series: [
                    {
                        name: '{{ $yearlyComparison['currentYear'] }}',
                        data: {!! json_encode($yearlyComparison['currentYearData']) !!}
                    },
                    {
                        name: '{{ $yearlyComparison['previousYear'] }}',
                        data: {!! json_encode($yearlyComparison['previousYearData']) !!}
                    }
                ],
                colors: [colors.primary, colors.success],
                xaxis: {
                    categories: {!! json_encode($yearlyComparison['monthNames']) !!},
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: { show: true }
                },
                tooltip: {
                    x: { format: 'MM' },
                    shared: true
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.5,
                        stops: [0, 90, 100]
                    }
                }
            }, 'مخطط مقارنة الأداء السنوي');

            // مخطط الفئات
            renderChart('#category-chart', {
                chart: {
                    height: 350,
                    type: 'radialBar',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
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
            }, 'مخطط الفئات');

            // مخطط المناطق
            renderChart('#region-chart', {
                chart: {
                    height: 400,
                    type: 'radar',
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    },
                    dropShadow: {
                        enabled: true,
                        blur: 8,
                        left: 1,
                        top: 1,
                        opacity: 0.2
                    }
                },
                series: [{
                    name: 'العملاء',
                    data: {!! json_encode($clientsByRegion->pluck('total')->toArray()) !!}
                }],
                colors: [colors.info],
                fill: {
                    opacity: 0.7
                },
                xaxis: {
                    categories: {!! json_encode($clientsByRegion->pluck('region')->toArray()) !!}
                }
            }, 'مخطط المناطق');

            // سجل رسالة نجاح في وحدة التحكم للتأكد من تحميل المخططات بشكل صحيح
            console.log('تم تحميل جميع المخططات بنجاح!');

            // إضافة وظيفة تنظيف Canvas بعد حدوث أي مشكلة
            cleanBrokenCharts();

        } catch (error) {
            console.error('حدث خطأ أثناء إنشاء المخططات:', error);
        }
    }

    // دالة مساعدة لرسم المخططات
    function renderChart(selector, options, name) {
        try {
            var element = document.querySelector(selector);
            if (element) {
                console.log('تهيئة ' + name);

                // تنظيف العنصر إذا كان يحتوي على مخطط سابق
                while (element.firstChild) {
                    element.removeChild(element.firstChild);
                }

                // تأكد من أن العنصر مرئي ويملك أبعاد
                element.style.display = 'block';
                element.style.position = 'relative';
                element.style.minHeight = options.chart.height + 'px';

                // إنشاء المخطط
                var chart = new ApexCharts(element, options);
                chart.render().then(function() {
                    console.log('تم عرض ' + name + ' بنجاح');
                }).catch(function(err) {
                    console.error('فشل في عرض ' + name + ':', err);

                    // إعادة تهيئة المخطط بعد فترة قصيرة
                    setTimeout(function() {
                        console.log('محاولة إعادة تهيئة ' + name);
                        try {
                            chart.render();
                        } catch (e) {
                            console.error('فشلت محاولة إعادة تهيئة ' + name, e);
                        }
                    }, 500);
                });
            } else {
                console.warn('العنصر غير موجود: ' + selector);
            }
        } catch (error) {
            console.error('خطأ في تهيئة ' + name + ':', error);
        }
    }

    // دالة لتنظيف المخططات المعطلة
    function cleanBrokenCharts() {
        // تحقق من عناصر Canvas المعطلة
        setTimeout(function() {
            document.querySelectorAll('canvas').forEach(function(canvas) {
                if (canvas.offsetParent === null || canvas.style.display === 'none' ||
                    (canvas.width === 0 && canvas.height === 0)) {
                    console.warn('تم العثور على canvas معطل، محاولة إصلاحه');
                    if (canvas.parentNode) {
                        canvas.parentNode.style.minHeight = '100px';
                        canvas.parentNode.style.position = 'relative';
                        canvas.style.display = 'block';
                    }
                }
            });
        }, 1000);
    }

    // إضافة زر لإعادة تحميل المخططات في حالة وجود مشكلة
    function addReloadButton() {
        var button = document.createElement('button');
        button.innerHTML = 'إعادة تحميل المخططات';
        button.className = 'btn btn-primary mt-2';
        button.style.position = 'fixed';
        button.style.bottom = '20px';
        button.style.right = '20px';
        button.style.zIndex = '9999';

        button.addEventListener('click', function() {
            console.log('إعادة تهيئة جميع المخططات...');
            initCharts();
        });

        document.body.appendChild(button);
    }

    // إضافة زر إعادة التحميل للمساعدة في حالة وجود مشكلة
    setTimeout(addReloadButton, 2000);

    // إضافة مراقب DOM للتأكد من بقاء المخططات مرئية
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' || mutation.type === 'attributes') {
                cleanBrokenCharts();
            }
        });
    });

    // بدء المراقبة بعد فترة قصيرة
    setTimeout(function() {
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        });
        console.log('تم تفعيل مراقبة DOM للمخططات');
    }, 1500);
});
</script>
@endsection