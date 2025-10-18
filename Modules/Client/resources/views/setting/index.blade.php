@extends('sales::master')

@section('title')
    اعدادات العميل
@stop

@section('css')
<style>
    .equal-height-card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .equal-height-card .card-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .equal-height-card .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .hover-card {
        transition: transform 0.3s;
        height: 100%;
    }

    .hover-card:hover {
        transform: translateY(-5px);
    }

    .card-body.setting {
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .card-body.setting a {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        text-decoration: none;
        color: inherit;
    }

    .card-body.setting h5 {
        margin-top: 10px;
        width: 100%;
    }
</style>
@endsection

@section('content')
<div class="content-body">
    <section id="statistics-card" class="container">
        <div class="row">

            <!-- عام -->
            <div class="col-lg-4 col-sm-6 col-12 mb-4">
                <div class="card hover-card equal-height-card">
                    <div class="card-content">
                        <div class="card-body setting">
                            <a href="{{ route('clients.general') }}">
                                <i class="fas fa-user fa-6x p-3 text-primary"></i>
                                <h5><strong>عام</strong></h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تصنيف العملاء -->
            <div class="col-lg-4 col-sm-6 col-12 mb-4">
                <div class="card hover-card equal-height-card">
                    <div class="card-content">
                        <div class="card-body setting">
                            <a href="{{ route('categoriesClient.index') }}">
                                <i class="fas fa-tags fa-6x p-3 text-primary"></i>
                                <h5><strong>تصنيف العملاء</strong></h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- صلاحيات العميل -->
            <div class="col-lg-4 col-sm-6 col-12 mb-4">
                <div class="card hover-card equal-height-card">
                    <div class="card-content">
                        <div class="card-body setting">
                            <a href="{{ route('clients.permission') }}">
                                <i class="fas fa-user-lock fa-6x p-3 text-primary"></i>
                                <h5><strong>صلاحيات العميل</strong></h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- حالات متابعة العميل -->
            <div class="col-lg-4 col-sm-6 col-12 mb-4">
                <div class="card hover-card equal-height-card">
                    <div class="card-content">
                        <div class="card-body setting">
                            <a href="{{ route('SupplyOrders.edit_status') }}">
                                <i class="fas fa-clipboard-list fa-6x p-3 text-primary"></i>
                                <h5><strong>حالات متابعة العميل</strong></h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- اعدادات المجموعات -->
            <div class="col-lg-4 col-sm-6 col-12 mb-4">
                <div class="card hover-card equal-height-card">
                    <div class="card-content">
                        <div class="card-body setting">
                            <a href="">
                                <i class="fas fa-users-cog fa-6x p-3 text-primary"></i>
                                <h5><strong>اعدادات المجموعات</strong></h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- اعدادات الاحصائيات -->
            <div class="col-lg-4 col-sm-6 col-12 mb-4">
                <div class="card hover-card equal-height-card">
                    <div class="card-content">
                        <div class="card-body setting">
                            <a href="">
                                <i class="fas fa-chart-bar fa-6x p-3 text-primary"></i>
                                <h5><strong>اعدادات الاحصائيات</strong></h5>
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
