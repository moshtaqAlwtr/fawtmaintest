<?php

namespace Modules\Client\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\CategoriesClient;
use App\Models\Client;
use App\Models\ClientRelation;
use App\Models\Employee;
use App\Models\Region_groub;
use App\Models\Statuses;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CatagroiyClientController extends Controller
{
    public function index()
    {
        $categories = CategoriesClient::withCount('clients')->get();

        return view('client::setting.category.index', compact('categories'));
    }
    public function create()
    {
        return view('client::setting.category.create');
    }
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories_clients,name',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // إنشاء التصنيف الجديد
            CategoriesClient::create([
                'name' => $request->name,
                'description' => $request->description,
                'active' => $request->active ?? true,
            ]);

            return redirect()->route('categoriesClient.index')->with('success', 'تم إنشاء تصنيف العميل بنجاح');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء تصنيف العميل: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * تحديث تصنيف عميل موجود
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = CategoriesClient::find($id);
        return view('client::setting.category.edit', compact('category'));
    }
    public function update(Request $request, $id)
    {
        // البحث عن التصنيف
        $category = CategoriesClient::find($id);

        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories_clients,name,' . $id,
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // تحديث بيانات التصنيف
            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'active' => $request->active ?? $category->active,
            ]);

            return redirect()->route('categoriesClient.index')->with('success', 'تم تحديث تصنيف العميل بنجاح');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء تحديث تصنيف العميل: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function destroy($id)
    {
        $category = CategoriesClient::find($id);
        $category->delete();
        return redirect()->route('categoriesClient.index')->with('success', 'تم حذف تصنيف العميل بنجاح');
    }

    // أضف هذا في ClientController.php

    // أضف هذا في ClientController.php

public function dashboard(Request $request)
{
    $user = auth()->user();

    // إنشاء query أساسي للعملاء
    $baseQuery = Client::query();

    // 🔹 التحقق من صلاحيات المستخدم حسب الفرع
    if ($user->branch_id) {
        $mainBranchName = Branch::where('is_main', true)->value('name');
        $currentBranchName = Branch::find($user->branch_id)->name ?? null;

        // إذا لم يكن الفرع رئيسي، فلترة العملاء حسب الفرع
        if ($currentBranchName !== $mainBranchName) {
            $baseQuery->where('branch_id', $user->branch_id);
        }
    }

    // 🔹 التحقق من الفرع المحدد من الفلتر
    $selectedBranch = null;
    if ($request->filled('branch')) {
        $selectedBranch = Branch::find($request->branch);

        // تطبيق فلتر الفرع المحدد (فقط إذا لم يكن رئيسي)
        if ($selectedBranch && !$selectedBranch->is_main) {
            $baseQuery->where('branch_id', $selectedBranch->id);
        }
    }

    // 🔹 فلترة الحالة
    if ($request->filled('status')) {
        $baseQuery->where('status_id', $request->status);
    }

    // 🔹 فلترة المنطقة
    if ($request->filled('region')) {
        $baseQuery->whereHas('neighborhood', function ($q) use ($request) {
            $q->where('region_id', $request->region);
        });
    }

    // 🔹 فلترة الفئة
    if ($request->filled('category')) {
        $baseQuery->where('category_id', $request->category);
    }

    // 🔸 الإحصائيات العامة
    $totalClients = (clone $baseQuery)->count();
    $activeClients = (clone $baseQuery)->where('status_id', 1)->count();

    $newClientsThisMonth = (clone $baseQuery)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

    // حساب العملاء الجدد الشهر الماضي مع نفس فلاتر الفرع
    $newClientsLastMonthQuery = Client::whereMonth('created_at', now()->subMonth()->month)
        ->whereYear('created_at', now()->subMonth()->year);

    // تطبيق فلتر الفرع على الشهر الماضي
    if ($user->branch_id) {
        $mainBranchName = Branch::where('is_main', true)->value('name');
        $currentBranchName = Branch::find($user->branch_id)->name ?? null;

        if ($currentBranchName !== $mainBranchName) {
            $newClientsLastMonthQuery->where('branch_id', $user->branch_id);
        }
    }

    if ($selectedBranch && !$selectedBranch->is_main) {
        $newClientsLastMonthQuery->where('branch_id', $selectedBranch->id);
    }

    $newClientsLastMonth = $newClientsLastMonthQuery->count();

    $growthRate = $newClientsLastMonth > 0
        ? round((($newClientsThisMonth - $newClientsLastMonth) / $newClientsLastMonth) * 100, 2)
        : 0;

    // 🔸 العملاء الجدد شهريًا (آخر 12 شهر)
    $newClientsMonthly = [];
    $monthlyData = [];

    for ($i = 11; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $monthYear = $date->format('Y-m');

        $count = (clone $baseQuery)
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();

        $monthlyData[] = [
            'month' => $date->locale('ar')->translatedFormat('F'),
            'year' => $date->format('Y'),
            'count' => $count,
        ];
    }

    $newClientsMonthly = [
        'months' => array_column($monthlyData, 'month'),
        'counts' => array_column($monthlyData, 'count')
    ];

    // 🔸 مقارنة الأداء الشهري للعام الحالي مقابل العام السابق
    $currentYearData = [];
    $previousYearData = [];

    for ($month = 1; $month <= 12; $month++) {
        // بيانات العام الحالي
        $currentYearCount = (clone $baseQuery)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', $month)
            ->count();

        $currentYearData[] = $currentYearCount;

        // بيانات العام السابق
        $previousYearCount = (clone $baseQuery)
            ->whereYear('created_at', now()->subYear()->year)
            ->whereMonth('created_at', $month)
            ->count();

        $previousYearData[] = $previousYearCount;
    }

    $yearlyComparison = [
        'currentYear' => now()->year,
        'previousYear' => now()->subYear()->year,
        'currentYearData' => $currentYearData,
        'previousYearData' => $previousYearData,
        'monthNames' => collect(range(1, 12))->map(function($month) {
            return \Carbon\Carbon::create(null, $month, 1)->locale('ar')->translatedFormat('F');
        })->toArray()
    ];

    // 🔸 جلب الحالات
    $statuses = Statuses::all();

    // 🔸 العملاء حسب الحالة
    $clientsByStatus = (clone $baseQuery)
        ->select('status_id', DB::raw('count(*) as total'))
        ->groupBy('status_id')
        ->get()
        ->map(function ($item) {
            $status = Statuses::find($item->status_id);
            return [
                'status' => $status->name ?? 'غير محدد',
                'count' => $item->total,
                'color' => $status->color ?? '#6c757d',
            ];
        });

    // 🔸 العملاء حسب الفئة
    $clientsByCategory = (clone $baseQuery)
        ->select('category_id', DB::raw('count(*) as total'))
        ->whereNotNull('category_id')
        ->groupBy('category_id')
        ->with('categoriesClient')
        ->get()
        ->map(function ($item) {
            return [
                'category' => $item->categoriesClient->name ?? 'غير محدد',
                'count' => $item->total,
            ];
        });

    // 🔸 العملاء حسب المنطقة
    $regionQuery = DB::table('clients')
        ->join('neighborhoods', 'clients.id', '=', 'neighborhoods.client_id')
        ->join('region_groubs', 'neighborhoods.region_id', '=', 'region_groubs.id')
        ->select('region_groubs.name as region', DB::raw('count(*) as total'))
        ->groupBy('region_groubs.id', 'region_groubs.name')
        ->orderBy('total', 'desc')
        ->limit(5);

    // تطبيق فلاتر صلاحيات المستخدم على المناطق
    if ($user->branch_id) {
        $mainBranchName = Branch::where('is_main', true)->value('name');
        $currentBranchName = Branch::find($user->branch_id)->name ?? null;

        if ($currentBranchName !== $mainBranchName) {
            $regionQuery->where('clients.branch_id', $user->branch_id);
        }
    }

    // تطبيق الفلاتر الإضافية على المناطق
    if ($request->filled('status')) {
        $regionQuery->where('clients.status_id', $request->status);
    }
    if ($request->filled('region')) {
        $regionQuery->where('neighborhoods.region_id', $request->region);
    }
    if ($selectedBranch && !$selectedBranch->is_main) {
        $regionQuery->where('clients.branch_id', $selectedBranch->id);
    }
    if ($request->filled('category')) {
        $regionQuery->where('clients.category_id', $request->category);
    }

    $clientsByRegion = $regionQuery->get();

    // 🔸 آخر الملاحظات
    $notesQuery = ClientRelation::with(['client', 'employee'])
        ->orderBy('created_at', 'desc')
        ->limit(10);

    // تطبيق فلتر الفرع على الملاحظات
    if ($user->branch_id) {
        $mainBranchName = Branch::where('is_main', true)->value('name');
        $currentBranchName = Branch::find($user->branch_id)->name ?? null;

        if ($currentBranchName !== $mainBranchName) {
            $notesQuery->whereHas('client', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }
    }

    if ($selectedBranch && !$selectedBranch->is_main) {
        $notesQuery->whereHas('client', function ($q) use ($selectedBranch) {
            $q->where('branch_id', $selectedBranch->id);
        });
    }

    $recentNotes = $notesQuery->get()->map(function ($note) {
        return [
            'id' => $note->id,
            'client_name' => $note->client->trade_name ?? 'غير محدد',
            'employee_name' => $note->employee->name ?? 'غير محدد',
            'process' => $note->process,
            'description' => $note->description,
            'date' => $note->created_at->format('Y-m-d'),
            'status' => $note->status ?? 'pending',
        ];
    });

    // 🔸 الحسابات
    $accountQuery = Account::where('balance', '>', 0)->whereNotNull('client_id');

    // تطبيق فلتر الفرع على الحسابات
    if ($user->branch_id) {
        $mainBranchName = Branch::where('is_main', true)->value('name');
        $currentBranchName = Branch::find($user->branch_id)->name ?? null;

        if ($currentBranchName !== $mainBranchName) {
            $accountQuery->whereHas('client', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }
    }

    if ($selectedBranch && !$selectedBranch->is_main) {
        $accountQuery->whereHas('client', function ($q) use ($selectedBranch) {
            $q->where('branch_id', $selectedBranch->id);
        });
    }

    $averageClientValue = $accountQuery->avg('balance') ?? 0;
    $totalDebt = (clone $accountQuery)->sum('balance') ?? 0;

    // 🔸 العملاء حسب الفرع (بدون فلترة - يظهر كل الفروع)
    $clientsByBranchQuery = Client::select('branch_id', DB::raw('count(*) as total'))
        ->groupBy('branch_id')
        ->with('branch');

    // تطبيق فلتر صلاحيات المستخدم فقط
    if ($user->branch_id) {
        $mainBranchName = Branch::where('is_main', true)->value('name');
        $currentBranchName = Branch::find($user->branch_id)->name ?? null;

        if ($currentBranchName !== $mainBranchName) {
            $clientsByBranchQuery->where('branch_id', $user->branch_id);
        }
    }

    $clientsByBranch = $clientsByBranchQuery->get()
        ->map(function ($item) {
            return [
                'branch' => $item->branch->name ?? 'غير محدد',
                'count' => $item->total,
            ];
        });

    // 🔸 العملاء حسب الموظف
    $clientsByEmployee = (clone $baseQuery)
        ->select('employee_id', DB::raw('count(*) as total'))
        ->whereNotNull('employee_id')
        ->groupBy('employee_id')
        ->with('employee')
        ->orderBy('total', 'desc')
        ->limit(10)
        ->get()
        ->map(function ($item) {
            return [
                'employee' => $item->employee->name ?? 'غير محدد',
                'count' => $item->total,
            ];
        });

    // 🔸 بيانات المساعدة للفلاتر
    $userBranch = $user->branch ?? null;

    // تحديد الفرع المستخدم للفلترة (إما الفرع المحدد من الفلتر أو فرع المستخدم)
    $activeBranch = $selectedBranch ?? $userBranch;

    if ($activeBranch && $activeBranch->is_main) {
        // ✅ إذا كان الفرع رئيسي → جلب كل المجموعات
        $regionGroups = Region_groub::all();
        // جلب كل الفروع للفرع الرئيسي
        $branches = Branch::all();
    } else {
        // ✅ إذا لم يكن رئيسي → جلب مجموعات الفرع المحدد فقط
        $regionGroups = Region_groub::where('branch_id', $activeBranch->id ?? null)->get();

        // تحديد الفروع المتاحة بناءً على صلاحيات المستخدم
        if ($userBranch && $userBranch->is_main) {
            // المستخدم في فرع رئيسي → يرى كل الفروع
            $branches = Branch::all();
        } else {
            // المستخدم في فرع فرعي → يرى فرعه فقط
            $branches = Branch::where('id', $user->branch_id)->get();
        }
    }

    $employees = Employee::all();
    $categories = CategoriesClient::all();

    return view('client::dashboard', compact(
        'totalClients',
        'activeClients',
        'newClientsThisMonth',
        'growthRate',
        'clientsByStatus',
        'clientsByCategory',
        'clientsByRegion',
        'newClientsMonthly',
        'yearlyComparison',
        'recentNotes',
        'averageClientValue',
        'totalDebt',
        'clientsByBranch',
        'clientsByEmployee',
        'statuses',
        'regionGroups',
        'branches',
        'employees',
        'categories'
    ));
}
}
