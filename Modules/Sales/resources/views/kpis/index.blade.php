@extends('sales::master')

@section('title')
مؤشرات الأداء
@endsection

{{-- إضافة CSS الخاص بالصفحة --}}
@push('styles')
<style>
/* إصلاح التنسيقات المفقودة */
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

.p-50 {
    padding: 3rem !important;
}

.mt-0 {
    margin-top: 0 !important;
}

.mt-1 {
    margin-top: 0.25rem !important;
}

.mt-2 {
    margin-top: 0.5rem !important;
}

.mt-25 {
    margin-top: 1.5rem !important;
}

.mb-0 {
    margin-bottom: 0 !important;
}

.mb-2 {
    margin-bottom: 0.5rem !important;
}

.mb-25 {
    margin-bottom: 1.5rem !important;
}

.mb-50 {
    margin-bottom: 3rem !important;
}

.mb-75 {
    margin-bottom: 4.5rem !important;
}

.pb-0 {
    padding-bottom: 0 !important;
}

.pb-50 {
    padding-bottom: 3rem !important;
}

.pt-0 {
    padding-top: 0 !important;
}

.pt-50 {
    padding-top: 3rem !important;
}

.px-0 {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

.m-0 {
    margin: 0 !important;
}

.m-auto {
    margin: auto !important;
}

.mr-50 {
    margin-right: 3rem !important;
}

.ml-50 {
    margin-left: 3rem !important;
}

.text-bold-700 {
    font-weight: 700 !important;
}

.text-bold-600 {
    font-weight: 600 !important;
}

.text-bold-500 {
    font-weight: 500 !important;
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

.font-medium-3 {
    font-size: 1.4rem !important;
}

.font-medium-5 {
    font-size: 2rem !important;
}

.font-small-3 {
    font-size: 0.8rem !important;
}

.font-weight-bold {
    font-weight: 700 !important;
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
}

.activity-timeline small {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.875rem;
}

.bg-primary {
    background-color: #7367f0 !important;
}

.bg-warning {
    background-color: #ff9f43 !important;
}

.bg-danger {
    background-color: #ea5455 !important;
}

.bg-success {
    background-color: #28c76f !important;
}

.text-primary {
    color: #7367f0 !important;
}

.text-warning {
    color: #ff9f43 !important;
}

.text-danger {
    color: #ea5455 !important;
}

.text-success {
    color: #28c76f !important;
}

.text-muted {
    color: #b9c3cd !important;
}

.text-white {
    color: #fff !important;
}

/* Progress bars */
.progress {
    height: 0.5rem;
    border-radius: 1rem;
    background-color: rgba(0,0,0,0.1);
    overflow: hidden;
}

.progress-bar {
    border-radius: 1rem;
    transition: width 0.6s ease;
}

.progress-bar-primary .progress-bar {
    background-color: #7367f0;
}

.progress-bar-warning .progress-bar {
    background-color: #ff9f43;
}

.progress-bar-danger .progress-bar {
    background-color: #ea5455;
}

.progress-bar-success .progress-bar {
    background-color: #28c76f;
}

/* Card */
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

.card-content {
    padding: 0;
}

.card-title {
    margin-bottom: 0;
    font-size: 1.285rem;
    font-weight: 500;
}

.chart-dropdown .btn {
    color: #6e6b7b;
    font-size: 0.857rem;
}

.shadow {
    box-shadow: 0 4px 24px 0 rgba(34,41,47,0.1) !important;
}

/* Table */
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #6e6b7b;
}

.table thead th {
    border-bottom: 1px solid #ebe9f1;
    font-size: 0.857rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    padding: 1rem;
}

.table tbody td {
    padding: 1rem;
    border-top: 1px solid #ebe9f1;
}

.table-hover-animation tbody tr {
    transition: all 0.2s ease;
}

.table-hover-animation tbody tr:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 24px 0 rgba(34,41,47,0.1);
}

.users-list {
    display: flex;
    align-items: center;
    list-style: none;
    padding: 0;
    margin: 0;
}

.users-list .avatar {
    margin-left: -10px;
    border: 2px solid #fff;
    width: 30px;
    height: 30px;
}

.users-list .avatar:first-child {
    margin-left: 0;
}

.avatar.pull-up {
    cursor: pointer;
    transition: all 0.25s ease;
}

.avatar.pull-up:hover {
    transform: translateY(-4px);
    z-index: 10;
}

.rounded-circle {
    border-radius: 50% !important;
}

.media-object {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cursor-pointer {
    cursor: pointer;
}

/* إخفاء عناصر التحكم في الحجم */
.resize-triggers {
    display: none !important;
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

.product-result {
    font-weight: 600;
}

/* Dropdown */
.dropdown-menu {
    border: 1px solid rgba(34, 41, 47, 0.1);
    box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
}

.dropdown-item {
    padding: 0.65rem 1.28rem;
    font-size: 0.9rem;
}

.dropdown-item:hover {
    background-color: rgba(115, 103, 240, 0.12);
    color: #7367f0;
}

/* Button */
.btn-primary {
    background-color: #7367f0;
    border-color: #7367f0;
    color: #fff;
}

.btn-primary:hover {
    background-color: #5e50ee;
    border-color: #5e50ee;
}

/* Utilities */
.w-75 {
    width: 75% !important;
}

.text-center {
    text-align: center !important;
}

.text-right {
    text-align: right !important;
}

.d-flex {
    display: flex !important;
}

.flex-column {
    flex-direction: column !important;
}

.justify-content-between {
    justify-content: space-between !important;
}

.justify-content-center {
    justify-content: center !important;
}

.align-items-center {
    align-items: center !important;
}

.align-items-start {
    align-items: flex-start !important;
}

.align-middle {
    vertical-align: middle !important;
}

.list-unstyled {
    list-style: none;
    padding-left: 0;
}

/* Responsive adjustments */
@media (max-width: 991px) {
    .activity-timeline li {
        padding-right: 50px;
    }
    
    .timeline-icon {
        width: 35px;
        height: 35px;
    }
}

@media (max-width: 767px) {
    .avatar-xl {
        width: 4rem;
        height: 4rem;
    }
    
    .font-large-1 {
        font-size: 1.2rem !important;
    }
    
    .font-large-2 {
        font-size: 1.5rem !important;
    }
}

/* إصلاح عرض الجداول على الشاشات الصغيرة */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
</style>
@endpush

@section('content')
<div class="content-body">
    <!-- لوحة مؤشرات الأداء -->
    <section id="dashboard-analytics">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="card bg-analytics text-white">
                    <div class="card-content">
                        <div class="card-body text-center">
                            <img src="{{ asset('app-assets/images/elements/decore-left.png') }}" class="img-left" alt="card-img-left">
                            <img src="{{ asset('app-assets/images/elements/decore-right.png') }}" class="img-right" alt="card-img-right">
                            <div class="avatar avatar-xl bg-primary shadow mt-0">
                                <div class="avatar-content">
                                    <i class="feather icon-award white font-large-1"></i>
                                </div>
                            </div>
                            <div class="text-center">
                                <h1 class="mb-2 text-white">مرحباً بك في لوحة المبيعات</h1>
                             
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-primary p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-file-text text-primary font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ number_format($invoiceStats['current_count']) }}</h2>
                        <p class="mb-0">الفواتير</p>
                    </div>
                    <div class="card-content">
                        <div id="subscribe-gain-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-warning p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-shopping-cart text-warning font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ number_format($salesStats['current']) }}</h2>
                        <p class="mb-0">المبيعات</p>
                    </div>
                    <div class="card-content">
                        <div id="orders-received-chart"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row pb-50">
                                <div class="col-lg-6 col-12 d-flex justify-content-between flex-column order-lg-1 order-2 mt-lg-0 mt-2">
                                    <div>
                                        <h2 class="text-bold-700 mb-25">{{ number_format($salesStats['average'], 2) }}</h2>
                                        <p class="text-bold-500 mb-75">متوسط المبيعات</p>
                                        <h5 class="font-medium-2">
                                            <span class="{{ $salesStats['growth'] > 0 ? 'text-success' : 'text-danger' }}">{{ $salesStats['growth'] > 0 ? '+' : '' }}{{ $salesStats['growth'] }}% </span>
                                            <span>مقارنة بالفترة السابقة</span>
                                        </h5>
                                    </div>
                                    <a href="#" class="btn btn-primary shadow waves-effect waves-light">عرض التفاصيل <i class="feather icon-chevrons-left"></i></a>
                                </div>
                                <div class="col-lg-6 col-12 d-flex justify-content-between flex-column text-right order-lg-2 order-1">
                                    <div class="dropdown chart-dropdown">
                                        <button class="btn btn-sm border-0 dropdown-toggle p-0" type="button" id="dropdownItem5" data-toggle="dropdown">
                                            آخر 7 أيام
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#">آخر 28 يوم</a>
                                            <a class="dropdown-item" href="#">الشهر الماضي</a>
                                            <a class="dropdown-item" href="#">السنة الماضية</a>
                                        </div>
                                    </div>
                                    <div id="avg-session-chart"></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row avg-sessions pt-50">
                                <div class="col-6">
                                    <p class="mb-0">الهدف: 100000 ريال</p>
                                    <div class="progress progress-bar-primary mt-25">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="50" aria-valuemax="100" style="width:50%"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0">العملاء: 100K</p>
                                    <div class="progress progress-bar-warning mt-25">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="60" aria-valuemax="100" style="width:60%"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0">نسبة الاحتفاظ: 90%</p>
                                    <div class="progress progress-bar-danger mt-25">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="70" aria-valuemax="100" style="width:70%"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0">المدة: سنة</p>
                                    <div class="progress progress-bar-success mt-25">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="90" aria-valuemax="100" style="width:90%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-0">
                        <h4 class="card-title">المرتجعات</h4>
                        <div class="dropdown chart-dropdown">
                            <button class="btn btn-sm border-0 dropdown-toggle p-0" type="button" id="dropdownItem4" data-toggle="dropdown">
                                آخر 7 أيام
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">آخر 28 يوم</a>
                                <a class="dropdown-item" href="#">الشهر الماضي</a>
                                <a class="dropdown-item" href="#">السنة الماضية</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-sm-2 col-12 d-flex flex-column flex-wrap text-center">
                                    <h1 class="font-large-2 text-bold-700 mt-2 mb-0">{{ $returnsStats['total'] }}</h1>
                                    <small>مرتجع</small>
                                </div>
                                <div class="col-sm-10 col-12 d-flex justify-content-center">
                                    <div id="support-tracker-chart"></div>
                                </div>
                            </div>
                            <div class="chart-info d-flex justify-content-between">
                                <div class="text-center">
                                    <p class="mb-50">مرتجعات جديدة</p>
                                    <span class="font-large-1">{{ $returnsStats['new'] }}</span>
                                </div>
                                <div class="text-center">
                                    <p class="mb-50">قيد المعالجة</p>
                                    <span class="font-large-1">{{ $returnsStats['processing'] }}</span>
                                </div>
                                <div class="text-center">
                                    <p class="mb-50">وقت المعالجة</p>
                                    <span class="font-large-1">{{ $returnsStats['processing_time'] }} يوم</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row match-height">
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-0">
                        <h4>عروض الأسعار</h4>
                        <div class="dropdown chart-dropdown">
                            <button class="btn btn-sm border-0 dropdown-toggle p-0" type="button" id="dropdownItem2" data-toggle="dropdown">
                                آخر 7 أيام
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">آخر 28 يوم</a>
                                <a class="dropdown-item" href="#">الشهر الماضي</a>
                                <a class="dropdown-item" href="#">السنة الماضية</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="product-order-chart" class="mb-3"></div>
                            <div class="chart-info d-flex justify-content-between mb-1">
                                <div class="series-info d-flex align-items-center">
                                    <i class="fa fa-circle-o text-bold-700 text-primary"></i>
                                    <span class="text-bold-600 ml-50">مكتملة</span>
                                </div>
                                <div class="product-result">
                                    <span>{{ number_format($quotesStats['completed']['count']) }}</span>
                                </div>
                            </div>
                            <div class="chart-info d-flex justify-content-between mb-1">
                                <div class="series-info d-flex align-items-center">
                                    <i class="fa fa-circle-o text-bold-700 text-warning"></i>
                                    <span class="text-bold-600 ml-50">معلقة</span>
                                </div>
                                <div class="product-result">
                                    <span>{{ number_format($quotesStats['pending']['count']) }}</span>
                                </div>
                            </div>
                            <div class="chart-info d-flex justify-content-between mb-75">
                                <div class="series-info d-flex align-items-center">
                                    <i class="fa fa-circle-o text-bold-700 text-danger"></i>
                                    <span class="text-bold-600 ml-50">مرفوضة</span>
                                </div>
                                <div class="product-result">
                                    <span>{{ number_format($quotesStats['rejected']['count']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="card-title">إحصائيات المبيعات</h4>
                            <p class="text-muted mt-25 mb-0">آخر 6 أشهر</p>
                        </div>
                        <p class="mb-0"><i class="feather icon-more-vertical font-medium-3 text-muted cursor-pointer"></i></p>
                    </div>
                    <div class="card-content">
                        <div class="card-body px-0">
                            <div id="sales-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">الإشعارات الدائنة</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <ul class="activity-timeline timeline-left list-unstyled">
                                @forelse($creditNotifications as $notification)
                                <li>
                                    <div class="timeline-icon bg-{{ $notification['status_class'] }}">
                                        <i class="feather icon-{{ $notification['status_class'] == 'warning' ? 'alert-circle' : 'check' }} font-medium-2 align-middle"></i>
                                    </div>
                                    <div class="timeline-info">
                                        <p class="font-weight-bold mb-0">إشعار #{{ $notification['id'] }}</p>
                                        <span class="font-small-3">{{ $notification['status'] }} - {{ $notification['client_name'] }} - {{ number_format($notification['grand_total']) }}</span>
                                    </div>
                                    <small class="text-muted">{{ $notification['credit_date'] }}</small>
                                </li>
                                @empty
                                <li>
                                    <div class="timeline-info">
                                        <p class="font-weight-bold mb-0">لا توجد إشعارات دائنة</p>
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
      
    </section>
    <!-- نهاية لوحة مؤشرات الأداء -->
</div>
@endsection

{{-- إضافة JavaScript الخاص بالصفحة --}}
@push('scripts')
<!-- تحميل ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0"></script>

<script>
// الانتظار حتى يتم تحميل الصفحة بالكامل
window.addEventListener('load', function() {
    'use strict';
    
    console.log('تم تحميل الصفحة - بدء تهيئة الرسوم البيانية');
    
    // تحقق من وجود ApexCharts
    if (typeof ApexCharts === 'undefined') {
        console.error('مكتبة ApexCharts غير محملة!');
        return;
    }
    
    console.log('تم تحميل مكتبة ApexCharts بنجاح');

    // الألوان المستخدمة
    var colors = {
        primary: '#7367f0',
        warning: '#ff9f43',
        danger: '#ea5455',
        success: '#28c76f',
        info: '#0dcce1',
        light: '#e7eeef'
    };

    // دالة مساعدة لتهيئة الرسم البياني
    function initChart(selector, options, name) {
        var element = document.querySelector(selector);
        if (element) {
            console.log('تهيئة ' + name);
            try {
                var chart = new ApexCharts(element, options);
                chart.render();
                console.log('تم عرض ' + name + ' بنجاح');
            } catch(e) {
                console.error('خطأ في عرض ' + name + ':', e);
            }
        } else {
            console.warn('العنصر غير موجود: ' + selector);
        }
    }

    // رسم مخطط الفواتير
    var subscribeGainChartOptions = {
        chart: {
            height: 100,
            type: 'area',
            toolbar: { show: false },
            sparkline: { enabled: true }
        },
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 2.5
        },
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
            name: 'الفواتير',
            data: [28, 40, 36, 52, 38, 60, 55]
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
            padding: { left: 0, right: 0 }
        }],
        tooltip: {
            x: { show: false }
        }
    };
    initChart("#subscribe-gain-chart", subscribeGainChartOptions, 'مخطط الفواتير');

    // رسم مخطط المبيعات
    var ordersReceivedChartOptions = {
        chart: {
            height: 100,
            type: 'area',
            toolbar: { show: false },
            sparkline: { enabled: true }
        },
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 2.5
        },
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
            name: 'المبيعات',
            data: [10, 15, 8, 15, 7, 12, 8]
        }],
        colors: [colors.warning],
        xaxis: {
            labels: { show: false },
            axisBorder: { show: false }
        },
        yaxis: [{
            y: 0,
            offsetX: 0,
            offsetY: 0,
            padding: { left: 0, right: 0 }
        }],
        tooltip: {
            x: { show: false }
        }
    };
    initChart("#orders-received-chart", ordersReceivedChartOptions, 'مخطط المبيعات');

    // رسم مخطط متوسط المبيعات
    var avgSessionChartOptions = {
        chart: {
            height: 200,
            type: 'bar',
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '20%',
                borderRadius: 5,
                colors: {
                    ranges: [{
                        from: 200,
                        to: 250,
                        color: colors.primary
                    }],
                    backgroundBarColors: [colors.light, colors.light, colors.light, colors.light, colors.light, colors.light, colors.light]
                }
            }
        },
        dataLabels: { enabled: false },
        series: [{
            name: 'المبيعات',
            data: [75, 125, 225, 175, 125, 75, 25]
        }],
        xaxis: {
            labels: { show: false },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: { show: false },
        grid: {
            show: false,
            padding: { left: 0, right: 0 }
        },
        tooltip: {
            x: { show: false }
        }
    };
    initChart("#avg-session-chart", avgSessionChartOptions, 'مخطط متوسط المبيعات');

    // رسم مخطط المرتجعات
    var supportTrackerChartOptions = {
        chart: {
            height: 290,
            type: 'radialBar'
        },
        plotOptions: {
            radialBar: {
                size: 150,
                offsetY: 20,
                startAngle: -150,
                endAngle: 150,
                hollow: { size: '65%' },
                track: {
                    background: 'rgba(255,255,255,0.85)',
                    strokeWidth: '100%'
                },
                dataLabels: {
                    name: {
                        offsetY: -5,
                        fontFamily: 'Montserrat',
                        fontSize: '1rem'
                    },
                    value: {
                        offsetY: 15,
                        fontFamily: 'Montserrat',
                        fontSize: '1.714rem'
                    }
                }
            }
        },
        colors: [colors.danger],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'horizontal',
                shadeIntensity: 0.5,
                gradientToColors: [colors.primary],
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        stroke: { dashArray: 8 },
        series: [83],
        labels: ['المرتجعات المكتملة']
    };
    initChart("#support-tracker-chart", supportTrackerChartOptions, 'مخطط المرتجعات');

    // رسم مخطط عروض الأسعار
    var productOrderChartOptions = {
        chart: {
            height: 350,
            type: 'radialBar'
        },
        plotOptions: {
            radialBar: {
                size: 150,
                hollow: { size: '20%' },
                track: {
                    strokeWidth: '100%',
                    margin: 15
                },
                dataLabels: {
                    name: { fontSize: '18px' },
                    value: { fontSize: '16px' },
                    total: {
                        show: true,
                        fontSize: '18px',
                        label: 'الإجمالي',
                        formatter: function(w) {
                            return '42459';
                        }
                    }
                }
            }
        },
        colors: [colors.primary, colors.warning, colors.danger],
        series: [70, 52, 26],
        labels: ['مكتملة', 'معلقة', 'مرفوضة'],
        stroke: { lineCap: 'round' }
    };
    initChart("#product-order-chart", productOrderChartOptions, 'مخطط عروض الأسعار');

    // رسم مخطط إحصائيات المبيعات
    var salesChartOptions = {
        chart: {
            height: 400,
            type: 'radar',
            toolbar: { show: false },
            dropShadow: {
                enabled: true,
                blur: 8,
                left: 1,
                top: 1,
                opacity: 0.2
            }
        },
        series: [{
            name: 'المبيعات',
            data: [90, 50, 86, 40, 100, 20]
        }, {
            name: 'الزيارات',
            data: [70, 75, 70, 76, 20, 85]
        }],
        stroke: { width: 0 },
        colors: [colors.primary, colors.info],
        plotOptions: {
            radar: {
                polygons: {
                    strokeColors: ['#e8e8e8', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent'],
                    connectorColors: 'transparent'
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                gradientToColors: [colors.primary, colors.info],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            }
        },
        markers: { size: 0 },
        legend: {
            show: true,
            fontSize: '16px',
            position: 'top',
            horizontalAlign: 'right'
        },
        xaxis: {
            categories: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو']
        },
        yaxis: { show: false }
    };
    initChart("#sales-chart", salesChartOptions, 'مخطط إحصائيات المبيعات');

    // تفعيل Tooltips
    if (typeof $ !== 'undefined' && $.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    console.log('تم الانتهاء من تهيئة جميع الرسوم البيانية');
});
</script>
@endpush