<?php

namespace Modules\HR\Http\Controllers;

use App\Exports\EmployeesExport;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Log as ModelsLog;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Models\FunctionalLevels;
use App\Models\JobRole;
use App\Models\JopTitle;
use App\Models\Shift;
use App\Models\TypesJobs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Models\Direction;
use App\Models\EmployeeGroup;
use App\Models\Region_groub;

class UserController extends Controller
{
public function index()
{
    $users = User::where('role','manager')->get();
    $allPermissions = \Spatie\Permission\Models\Permission::all();
    
    return view('hr::user.index', compact('users', 'allPermissions'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'phone' => 'required'
    ]);

    // إنشاء المستخدم
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'manager',
        'phone' => $request->phone,
    ]);

    // التأكد من وجود دور manager في Spatie
    $managerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'manager']);
    
    // إعطاء كافة الصلاحيات للدور (مرة واحدة فقط)
    if ($managerRole->permissions->isEmpty()) {
        $allPermissions = \Spatie\Permission\Models\Permission::all();
        $managerRole->syncPermissions($allPermissions);
    }
    
    // إعطاء الدور للمستخدم الجديد
    $user->assignRole('manager');

    // تسجيل العملية في الـ Log
    ModelsLog::create([
        'type' => 'hr_log',
        'type_id' => $user->id,
        'type_log' => 'log',
        'description' => 'تم إضافة مستخدم جديد **' . $request->name . '** بدور مدير مع كافة الصلاحيات',
        'created_by' => auth()->id(),
    ]);

    return redirect()
        ->route('hr.user.index')
        ->with(['success' => 'تم إضافة المستخدم وإعطاؤه كافة الصلاحيات بنجاح']);
}

// دالة مساعدة لتحديث صلاحيات المديرين الموجودين
public function syncManagerPermissions()
{
    $managerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'manager']);
    $allPermissions = \Spatie\Permission\Models\Permission::all();
    
    // إعطاء كافة الصلاحيات للدور
    $managerRole->syncPermissions($allPermissions);
    
    // التأكد من أن جميع المستخدمين الذين role حقهم manager لديهم الدور في Spatie
    $managerUsers = User::where('role', 'manager')->get();
    foreach ($managerUsers as $user) {
        if (!$user->hasRole('manager')) {
            $user->assignRole('manager');
        }
    }

    ModelsLog::create([
        'type' => 'hr_log',
        'type_id' => 0,
        'type_log' => 'log',
        'description' => 'تم تحديث صلاحيات جميع المديرين لتشمل كافة الصلاحيات',
        'created_by' => auth()->id(),
    ]);

    return redirect()
        ->back()
        ->with(['success' => 'تم تحديث صلاحيات جميع المديرين']);
}

}
