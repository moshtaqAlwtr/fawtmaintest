@extends('master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>إدارة المستخدمين - المديرين</h4>
                    <button type="button" class="btn btn-warning btn-sm" onclick="syncPermissions()">
                        تحديث صلاحيات المديرين
                    </button>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>عدد الصلاحيات</th>
                                <th>رقم الهاتف</th>
                                {{-- <th>الإجراءات</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge badge-success">
                                        {{ $user->getAllPermissions()->count() }}
                                    </span>
                                </td>
                                <td>{{$user->phone ?? "" }}</td>
                                {{-- <td>
                                    <button class="btn btn-info btn-sm" onclick="viewPermissions({{ $user->id }})">
                                        عرض الصلاحيات
                                    </button>
                                </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>إضافة مدير جديد</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('hr.user.store') }}">
                        @csrf
                        <div class="form-group">
                            <label>الاسم</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>رقم الهاتف</label>
                            <input type="number" name="phone" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>كلمة المرور</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        
                        <div class="alert alert-info">
                            <small>سيتم إعطاء المستخدم الجديد كافة الصلاحيات تلقائياً</small>
                        </div>
                        
                        <button type="submit" class="btn btn-success">إضافة مدير</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function syncPermissions() {
    if(confirm('هل أنت متأكد من تحديث صلاحيات جميع المديرين؟')) {
        window.location.href = "{{ route('hr.user.sync-permissions') }}";
    }
}

function viewPermissions(userId) {
    // يمكنك إضافة modal لعرض صلاحيات المستخدم
    alert('عرض صلاحيات المستخدم: ' + userId);
}
</script>
@endsection