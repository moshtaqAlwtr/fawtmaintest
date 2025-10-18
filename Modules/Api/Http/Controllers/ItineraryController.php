<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Region_groub;
use App\Models\EmployeeClientVisit;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function apiItineraryFull()
{
    $itineraries = EmployeeClientVisit::with(['employee', 'client', 'client.status_client'])
        ->orderBy('year', 'desc')
        ->orderBy('week_number', 'desc')
        ->get();

    $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    $weeklyData = [];
    $totalPlannedVisits = 0;
    $totalCompletedVisits = 0;

    // ✅ نحسب أول زيارة لكل عميل مرة وحدة لتقليل الاستعلامات
    $firstVisitIds = EmployeeClientVisit::selectRaw('MIN(id) as id, client_id')
        ->groupBy('client_id')
        ->pluck('id')
        ->toArray();

    // تجميع الزيارات حسب الأسبوع
    $grouped = $itineraries->groupBy(fn($v) => $v->year . '-W' . str_pad($v->week_number, 2, '0', STR_PAD_LEFT));

    foreach ($grouped as $weekKey => $weekVisits) {
        $yearWeek = explode('-W', $weekKey);
        $year = $yearWeek[0];
        $weekNum = $yearWeek[1];

        $weekStart = new DateTime();
        $weekStart->setISODate($year, $weekNum);
        $weekEnd = clone $weekStart;
        $weekEnd->modify('+6 days');

        $employees = $weekVisits->groupBy('employee_id')->map(function ($employeeVisits, $employeeId) use ($days, &$totalPlannedVisits, &$totalCompletedVisits, $firstVisitIds) {
            $employee = $employeeVisits->first()->employee;
            $employeeStats = [
                'id' => $employee->id,
                'name' => $employee->name,
                'total_visits' => 0,
                'completed_visits' => 0,
                'incompleted_visits' => 0,
                'new_clients' => 0,
                'days' => [],
            ];

            foreach ($days as $day) {
                $dayVisits = $employeeVisits->where('day_of_week', $day);
                $dayCount = $dayVisits->count();
                $completed = $dayVisits->where('status', 'active')->count();

                // 🟢 تحديد إذا كانت الزيارة الأولى للعميل
                $dayVisitsProcessed = $dayVisits->map(function ($v) use ($firstVisitIds) {
                    $isNew = in_array($v->id, $firstVisitIds);
                    $statusText = $v->status === 'active' ? 'تمت الزيارة' : 'لم تتم الزيارة';
                    $clientStatus = $v->client?->status_client;

                    return [
                        'id' => $v->client->id ?? null,
                        'name' => $v->client->trade_name ?? 'غير معروف',
                        'code' => $v->client->code ?? null,
                        'status' => $statusText,
                        'is_new' => $isNew,
                        'client_status' => $clientStatus ? [
                            'id' => $clientStatus->id,
                            'name' => $clientStatus->name,
                            'color' => $clientStatus->color
                        ] : null,
                    ];
                })->values();

                $newClients = $dayVisitsProcessed->where('is_new', true)->count();

                $employeeStats['days'][$day] = [
                    'visit_count' => $dayCount,
                    'completed' => $completed,
                    'new_clients' => $newClients,
                    'visits' => $dayVisitsProcessed
                ];

                $employeeStats['total_visits'] += $dayCount;
                $employeeStats['completed_visits'] += $completed;
                $employeeStats['new_clients'] += $newClients;
            }

            $employeeStats['incompleted_visits'] = $employeeStats['total_visits'] - $employeeStats['completed_visits'];
            $totalPlannedVisits += $employeeStats['total_visits'];
            $totalCompletedVisits += $employeeStats['completed_visits'];

            return $employeeStats;
        });

        $weeklyData[$weekKey] = [
            'week_number' => $weekNum,
            'year' => $year,
            'from' => $weekStart->format('Y-m-d'),
            'to' => $weekEnd->format('Y-m-d'),
            'employee_count' => $employees->count(),
            'total_visits' => $employees->sum('total_visits'),
            'completed_visits' => $employees->sum('completed_visits'),
            'incompleted_visits' => $employees->sum('incompleted_visits'),
            'new_clients' => $employees->sum('new_clients'),
            'employees' => $employees->values()
        ];
    }

    $newClientsTodayCount = Client::whereDate('created_at', today())->count();

    return response()->json([
        'success' => true,
        'data' => [
            'statistics' => [
                'total_weeks' => count($weeklyData),
                'total_employees' => $itineraries->pluck('employee_id')->unique()->count(),
                'total_visits' => $totalPlannedVisits,
                'completed_visits' => $totalCompletedVisits,
                'incompleted_visits' => $totalPlannedVisits - $totalCompletedVisits,
                'new_clients_today' => $newClientsTodayCount
            ],
            'weeks' => array_values($weeklyData)
        ]
    ]);
}


    /**
     * Show the form for creating a new resource.
     */
   public function createWithClients(Request $request)
{
    $user = auth()->user();
    $employees = $user->role === 'employee'
        ? User::where('id', $user->id)->get()
        : User::where('role', 'employee')->get();

    $groups = $user->role === 'employee'
        ? $user->regionGroups()->get()
        : Region_groub::all();

    $currentYear = now()->year;
    $defaultGroup = $groups->first();

    $clients = collect();
    if ($defaultGroup) {
        $clients = Client::with([
            'visits' => fn($q) => $q->latest()->limit(1),
            'invoices' => fn($q) => $q->latest()->limit(1),
            'appointmentNotes' => fn($q) => $q->latest()->limit(1),
            'account.receipts' => fn($q) => $q->latest()->limit(1),
        ])
            ->whereHas('neighborhood', fn($q) => $q->where('region_id', $defaultGroup->id))
            ->get(['id', 'trade_name', 'code', 'city']);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'employees' => $employees->map(fn($e) => ['id' => $e->id, 'name' => $e->name]),
            'groups' => $groups->map(fn($g) => ['id' => $g->id, 'name' => $g->name]),
            'default_year' => $currentYear,
            'default_group_id' => $defaultGroup?->id,
            'clients' => $clients,
        ]
    ]);
}

  public function getClientsForGroup($id)
    {
        $clients = Client::with([
            'visits' => function ($query) {
                $query->latest()->limit(1);
            },
            'invoices' => function ($query) {
                $query->latest()->limit(1);
            },
            'appointmentNotes' => function ($query) {
                $query->latest()->limit(1);
            },
            'account.receipts' => function ($query) {
                $query->latest()->limit(1);
            },
        ])
            ->whereHas('neighborhood', function ($query) use ($id) {
                $query->where('region_id', $id); // ✅ هذا هو التعديل الصحيح
            })
            ->get(['id', 'trade_name', 'code', 'city']);

        return response()->json($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'employee_id' => 'required|exists:users,id',
        'visits' => 'required|array',
        'year' => 'required|integer|min:2020|max:2030',
        'week_number' => 'required|integer|min:1|max:53',
        'overwrite' => 'sometimes|boolean',
        'visits.*' => 'nullable|array',
        'visits.*.*' => 'nullable|integer|exists:clients,id',
    ]);

    DB::beginTransaction();

    try {
        $employeeId = $validated['employee_id'];
        $year = $validated['year'];
        $weekNumber = $validated['week_number'];
        $overwrite = $validated['overwrite'] ?? false;

        // حذف الزيارات القديمة إذا تم تفعيل overwrite
        if ($overwrite) {
            EmployeeClientVisit::where('employee_id', $employeeId)
                ->where('year', $year)
                ->where('week_number', $weekNumber)
                ->delete();
        }

        // تجهيز البيانات
        $visitData = [];
        foreach ($validated['visits'] as $day => $clientIds) {
            if (!is_array($clientIds)) continue;

            foreach (array_filter($clientIds, 'is_numeric') as $clientId) {
                $visitData[] = [
                    'employee_id' => $employeeId,
                    'client_id' => $clientId,
                    'day_of_week' => strtolower($day),
                    'year' => $year,
                    'week_number' => $weekNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (empty($visitData)) {
            throw new \Exception('لا توجد زيارات صالحة للحفظ');
        }

        EmployeeClientVisit::insert($visitData);
        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ خط السير بنجاح',
            'inserted_count' => count($visitData),
            'overwrite' => $overwrite
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'فشل الحفظ: ' . $e->getMessage(),
            'error_details' => config('app.debug') ? $e->getTraceAsString() : null
        ], 500);
    }
}


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('api::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('api::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
   public function destroyVisit($visitId)
{
    try {
        $visit = EmployeeClientVisit::findOrFail($visitId);
        $visit->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الزيارة بنجاح',
            'deleted_id' => $visitId,
        ]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'الزيارة غير موجودة',
        ], 404);
    } catch (\Exception $e) {
        Log::error('خطأ في حذف الزيارة: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء الحذف',
            'error' => config('app.debug') ? $e->getMessage() : null,
        ], 500);
    }
}

}
