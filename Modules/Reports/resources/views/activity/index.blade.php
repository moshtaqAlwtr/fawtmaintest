@extends('master')

@section('title')
سجل نشاطات الحساب
@stop

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">سجل نشاطات الحساب</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">الرئيسيه</a>
                        </li>
                        <li class="breadcrumb-item active">عرض
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="bg-white p-3 rounded shadow-sm d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <button class="btn btn-outline-secondary"><i class="fas fa-th"></i></button>
                        <button class="btn btn-outline-secondary"><i class="fas fa-list"></i></button>
                    </div>
                    <div class="d-flex">
                        <input type="text" class="form-control me-2" placeholder="الكلمة المفتاحية">

                        <div class="dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dateRangeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        الفترة من / إلى
    </button>
    <ul class="dropdown-menu" aria-labelledby="dateRangeDropdown">
        <li><a class="dropdown-item" href="#">الأسبوع الماضي</a></li>
        <li><a class="dropdown-item" href="#">الشهر الأخير</a></li>
        <li><a class="dropdown-item" href="#">من أول الشهر حتى اليوم</a></li>
        <li><a class="dropdown-item" href="#">السنة الماضية</a></li>
        <li><a class="dropdown-item" href="#">من أول السنة حتى اليوم</a></li>
        <li class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item dropdown-toggle" href="#">الفترة من / إلى</a>

                <li><a class="dropdown-item" href="#">تاريخ محدد</a></li>
                <li><a class="dropdown-item" href="#">كل التواريخ قبل</a></li>
                <li><a class="dropdown-item" href="#">كل التواريخ بعد</a></li>

        </li>
    </ul>
</div>


                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="usersDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                كل المستخدمين
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="usersDropdown">
                                <li><a class="dropdown-item" href="#">المستخدم 1</a></li>
                                <li><a class="dropdown-item" href="#">المستخدم 2</a></li>
                                <li><a class="dropdown-item" href="#">المستخدم 3</a></li>
                            </ul>
                        </div>

                        <div class="dropdown ms-2">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="actionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                كل الإجراءات
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="actionsDropdown">
                                <li><a class="dropdown-item" href="#">إنشاء فاتورة</a></li>
                                <li><a class="dropdown-item" href="#">تحديث فاتورة</a></li>
                                <li><a class="dropdown-item" href="#">إرسال فاتورة</a></li>
                                <li><a class="dropdown-item" href="#">طباعة الفاتورة</a></li>
                                <li><a class="dropdown-item" href="#">حذف الفاتورة</a></li>
                                <li><a class="dropdown-item" href="#">Preview Invoice</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <span class="bg-white py-2 px-4 rounded-pill shadow-sm d-inline-block">اليوم</span>
                </div>

                <ul class="list-unstyled">
                    <li class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="bg-white p-3 rounded shadow-sm w-100">
                                <small class="text-muted"><i class="far fa-clock"></i> 13:19:58</small>
                                <p class="mb-1">
                                    <strong>محمد المنصوب مدير</strong> قام بإضافة إذن صرف مخزن <span class="text-muted">#10134</span> لفاتورة <span class="text-muted">#08842</span>
                                </p>
                                <small class="text-muted">Main Branch - مدير</small>
                            </div>
                        </div>
                    </li>

                    <li class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="bg-white p-3 rounded shadow-sm w-100">
                                <small class="text-muted"><i class="far fa-clock"></i> 13:19:58</small>
                                <p class="mb-1">
                                    أنشأ <strong>محمد المنصوب مدير</strong> فاتورة جديدة <span class="text-danger">غير مدفوعة</span> ورقمها <span class="text-muted">#08842</span>
                                    للعميل <strong>مؤسسة حنان سلطان العمري للتجارة حي طويق</strong> <span class="text-muted">(270#)</span> بإجمالي <strong>216.00</strong> والمبلغ مستحق الدفع.
                                </p>
                                <small class="text-muted">Main Branch - مدير</small>
                            </div>
                        </div>
                    </li>

                    <li class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <div class="bg-white p-3 rounded shadow-sm w-100">
                                <small class="text-muted"><i class="far fa-clock"></i> 13:19:28</small>
                                <p class="mb-1">
                                    حدث النظام الفاتورة <span class="text-muted">#08011</span> بإجمالي <strong>216.00</strong> والمبلغ المستحق أصبح: <strong>0.00</strong>
                                </p>
                                <small class="text-muted">Main Branch - النظام</small>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>



@endsection
