@extends('sales::master')

@section('title')
    اعدادات المجموعات
@stop

@section('css')
    <style>
        #map {
            height: 500px;
            width: 100%;
            margin-bottom: 20px;
        }
    </style>
    <!-- إضافة مكتبة SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
@stop

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0"> اعدادات المجموعات</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">الرئيسية</a></li>
                            <li class="breadcrumb-item active">عرض</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">

        <!-- بطاقة الإجراءات -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap justify-content-end" style="gap: 10px;">
                    <a href="{{ route('groups.group_client_create') }}"
                        class="btn btn-primary d-flex align-items-center justify-content-center"
                        style="height: 44px; padding: 0 16px; font-weight: bold; border-radius: 6px;">
                        <i class="fas fa-plus ms-2"></i>
                        أضف مجموعة جديدة
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('groups.group_client') }}" method="GET">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="name">اسم المجموعة</label>
                            <input type="text" name="name" class="form-control" value="{{ request('name') }}"
                                placeholder="ابحث باسم المجموعة">
                        </div>
                        <div class="col-md-4">
                            <label for="branch_id">الفرع</label>
                            <select name="branch_id" class="form-control select2">
                                <option value="">-- اختر الفرع --</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="directions_id">الاتجاة</label>
                            <select name="directions_id" class="form-control select2">
                                <option value="">-- اختر الاتجاه --</option>
                                @foreach ($directions as $direction)
                                    <option value="{{ $direction->id }}"
                                        {{ request('directions_id') == $direction->id ? 'selected' : '' }}>
                                        {{ $direction->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fa fa-search"></i> بحث
                        </button>
                        <a href="{{ route('groups.group_client') }}" class="btn btn-outline-warning">
                            <i class="fa fa-times"></i> إلغاء
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- جدول العملاء -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المجموعة</th>
                                <th>الاتجاه</th>
                                <th>الفرع</th>
                                <th style="width: 10%">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Regions_groub as $Region_groub)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $Region_groub->name }}</td>
                                    <td>{{ $Region_groub->direction->name ?? 'غير محدد' }}</td>
                                    <td>{{ $Region_groub->branch->name ?? '' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <div class="dropdown">
                                                <button class="btn bg-gradient-info fa fa-ellipsis-v mr-1 mb-1 btn-sm"
                                                    type="button" id="dropdownMenuButton303" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"></button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton303">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('groups.group_client_edit', $Region_groub->id) }}">
                                                            <i class="fa fa-edit me-2 text-success"></i>تعديل
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger delete-btn" href="#"
                                                            data-id="{{ $Region_groub->id }}"
                                                            data-name="{{ $Region_groub->name }}">
                                                            <i class="fa fa-trash me-2"></i>حذف
                                                        </a>
                                                    </li>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- إضافة مكتبة SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // تفعيل زر الحذف مع SweetAlert2
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();

                const groupId = $(this).data('id');
                const groupName = $(this).data('name');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: `سيتم حذف المجموعة: ${groupName}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم، قم بالحذف!',
                    cancelButtonText: 'إلغاء',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // توجيه المستخدم إلى رابط الحذف
                        window.location.href = `{{ url('/ar/group/destroy') }}/${groupId}`;
                    }
                });
            });

            // إذا كان هناك رسالة نجاح في الجلسة، عرض إشعار SweetAlert2
            @if(session('success'))
                Swal.fire({
                    title: 'تم بنجاح!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'حسناً'
                });
            @endif

            // إذا كان هناك رسالة خطأ في الجلسة، عرض إشعار SweetAlert2
            @if(session('error'))
                Swal.fire({
                    title: 'خطأ!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'حسناً'
                });
            @endif
        });
    </script>
@endsection