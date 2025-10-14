@extends('rentalmanagement::master')

@section('title')
    لوحة التحكم
@stop

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>



@section('content')



    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-between align-items-center mb-1">
                <div class="mr-1">
                    <p><span>{{ \Carbon\Carbon::now()->translatedFormat('l، d F Y') }}</span></p>
                    <h4 class="content-header-title float-left mb-0"> أهلاً <strong
                            style="color: #2C2C2C">{{ auth()->user()->name }} ، </strong> مرحباً بعودتك!</h4>
                </div>
                <div class="ml-auto bg-rgba-success">
                    <a href="{{ route('dashboard_sales.index') }}" class="text-success"><i class="ficon feather icon-globe"></i> <span>الذهاب إلى
                            الموقع</span></a>
                </div>
            </div>
        </div>
    </div>


@endsection

