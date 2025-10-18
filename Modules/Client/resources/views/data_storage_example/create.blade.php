@extends('master')

@section('title')
    مثال على تخزين البيانات
@stop

@section('content')
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">مثال على تخزين البيانات</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item active">تخزين البيانات</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">نموذج تخزين إعداد جديد</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('data_storage_example.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="key">المفتاح (Key) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="key" name="key" 
                                       value="{{ old('key') }}" required>
                                <small class="form-text text-muted">مفتاح فريد للإعداد (مثال: email_notification)</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">الاسم (Name) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name') }}" required>
                                <small class="form-text text-muted">اسم الإعداد المعروض للمستخدم</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="vs-checkbox-con vs-checkbox-primary">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                           {{ old('is_active') ? 'checked' : '' }}>
                                    <span class="vs-checkbox">
                                        <span class="vs-checkbox--check">
                                            <i class="vs-icon feather icon-check"></i>
                                        </span>
                                    </span>
                                    <span>تفعيل الإعداد</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="feather icon-save"></i> حفظ الإعداد
                    </button>
                    
                    <a href="{{ route('clients.general') }}" class="btn btn-secondary">
                        <i class="feather icon-x"></i> إلغاء
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection