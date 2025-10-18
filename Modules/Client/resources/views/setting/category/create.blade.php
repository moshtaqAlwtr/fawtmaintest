@extends('master')

@section('title')
    إضافة تصنيف عملاء جديد
@stop

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">اضافة تصنيف</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('sales.department.dashboard')}}">الرئيسيه</a></li>
                            <li class="breadcrumb-item active">عرض</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('categoriesClient.store') }}" method="POST" id="categoryForm">
        @csrf
        <!-- عرض الأخطاء -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <label>الحقول التي عليها علامة <span style="color: red">*</span> الزامية</label>
                    </div>
                    <div>
                        <a href="{{ route('categoriesClient.index') }}" class="btn btn-outline-danger">
                            <i class="fa fa-ban"></i>الغاء
                        </a>
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fa fa-save"></i>حفظ
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <!-- الحقول -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">اسم التصنيف <span style="color: red">*</span></label>
                        <input type="text" id="name" name="name" class="form-control"
                            placeholder="أدخل اسم التصنيف"
                            value="{{ old('name', $category->name ?? '') }}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="active" class="form-label">الحالة</label>
                        <select name="active" id="active" class="form-control select2">
                            <option value="1" {{ (old('active', $category->active ?? 1) == 1 ? 'selected' : '') }}>نشط</option>
                            <option value="0" {{ (old('active', $category->active ?? 1) == 0 ? 'selected' : '') }}>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="3" placeholder="أدخل وصفًا للتصنيف (اختياري)">{{ old('description', $category->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // التحقق من الصحة قبل الإرسال
            $('#categoryForm').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 100
                    }
                },
                messages: {
                    name: {
                        required: "حقل اسم التصنيف مطلوب",
                        maxlength: "يجب ألا يتجاوز اسم التصنيف 100 حرف"
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection