@extends('master')

@section('title')
    الاعدادات الرواتب
@stop

@section('css')
    <style>
        .setting {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-direction: column;
        }
        .hover-card:hover {
            background-color: #cdd2d8;
            scale: .98;
        }
        .container {
            max-width: 1200px;
        }
    </style>
@endsection

@section('content')
    <div class="content-body">
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">اعدادات الفواتير</h2>
                <div class="breadcrumb-wrapper col-12">
                   <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسيه</a></li>
                        <li class="breadcrumb-item active">عرض</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
        <section id="statistics-card" class="container">
            <div class="row">
                <!-- إعدادات الفواتير وعروض الأسعار -->
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="card hover-card">
                        <div class="card-content">
                            <div class="card-body setting">

                        <a href="{{route('SittingInvoice.invoice')}}">
                                    <i class="fas fa-file-invoice fa-8x p-3" style="color: primary;"></i>
                                    <h5><strong>اعدادات الفواتير وعروض الاسعار</strong></h5>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تصاميم الفواتير / عروض الأسعار -->
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="card hover-card">
                        <div class="card-content">
                            <div class="card-body setting">

                                <a href="{{route('SittingInvoice.bill_designs')}}">
                                    <i class="fas fa-paint-brush fa-8x p-3" style="color: primary;"></i>
                                    <h5><strong>تصاميم الفواتير / عروض الاسعار</strong></h5>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- العروض -->
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="card hover-card">
                        <div class="card-content">
                            <div class="card-body setting">
                                <a href="{{route('offers.index')}}">
                                    <i class="fas fa-gift fa-8x p-3" style="color: primary;"></i>
                                    <h5><strong>العروض والهدايا</strong></h5>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

               
                <!-- خيارات الشحن -->
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="card hover-card">
                        <div class="card-content">
                            <div class="card-body setting">
                                <a href="{{route('shippingOptions.index')}}">
                                    <i class="fas fa-truck fa-8x p-3" style="color: primary;"></i>
                                    <h5><strong>خيارات الشحن</strong></h5>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- إعدادات الفواتير الإلكترونية -->
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="card hover-card">
                        <div class="card-content">
                            <div class="card-body setting">
                                <a href="{{route('settings.electronic_invoice')}}">
                                    <i class="fas fa-file-code fa-8x p-3" style="color: primary;"></i>
                                    <h5><strong>إعدادات الفواتير الإلكترونية</strong></h5>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- مصادر الطلب -->
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="card hover-card">
                        <div class="card-content">
                            <div class="card-body setting">
                                <a href="{{route('order_sources.index')}}">
                                    <i class="fas fa-cogs fa-8x p-3" style="color: primary;"></i>
                                    <h5><strong>مصادر الطلب</strong></h5>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection

@section('scripts')
@endsection
