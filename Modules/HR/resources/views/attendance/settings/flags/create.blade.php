@extends('master')

@section('title', 'إضافة قواعد الحضور')

@section('content')
<!-- Header Section -->
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">إضافة قواعد الحضور</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                        <li class="breadcrumb-item active">إضافة</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Actions -->
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <label>الحقول التي عليها علامة <span style="color: red">*</span> الزامية</label>
            </div>
            <div>
                <a href="{{ route('Assets.index') }}" class="btn btn-outline-danger">
                    <i class="fa fa-ban"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fa fa-save"></i> حفظ
                </button>
            </div>
        </div>
    </div>
</div>

<div class="cart mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h4>قواعد الحضور</h4>
        </div>
        <div class="card-body">
            <form>
                <!-- الاسم -->
                <div class="mb-3 row">
                    <label for="name" class="col-sm-2 col-form-label">الاسم:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="name" placeholder="أدخل الاسم">
                    </div>
                </div>

                <!-- اللون -->
                <div class="mb-3 row">
                    <label for="color" class="col-sm-2 col-form-label">اللون:</label>
                    <div class="col-sm-10 d-flex align-items-center">
                        <input type="color" class="form-control form-control-color" id="color" value="#4e5381"
                            title="اختر اللون" style="max-width: 50px; margin-left: 10px;">
                        <input type="text" class="form-control" value="#4e5381" readonly>
                    </div>
                </div>

                <!-- الوصف -->
                <div class="mb-3">
                    <label for="description" class="form-label">الوصف:</label>
                    <textarea class="form-control" id="description" rows="3" placeholder="أدخل الوصف"></textarea>
                </div>

                <!-- الحاله -->
                <div class="mb-3 row">
                    <label for="status" class="col-sm-2 col-form-label">الحالة:</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="status">
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>
                </div>

<!-- الصيغة الحسابية والشرط -->
<div class="mb-3">
    <label for="formula_condition" class="form-label">الصيغة الحسابية والشرط:</label>
    <div class="row">
        <!-- الصيغة الحسابية -->
        <div class="col-6">
            <div class="input-group">
                <button class="btn btn-outline-secondary">🔍</button>
                <input type="text" class="form-control" placeholder="أدخل الصيغة الحسابية">
            </div>
        </div>

        <!-- الشرط -->
        <div class="col-6">
            <div class="input-group">
                <button class="btn btn-outline-secondary">🔍</button>
                <input type="text" class="form-control" placeholder="أدخل الشرط">
            </div>
        </div>
    </div>
</div>

        </form>
    </div>
</div>
</div>


@endsection