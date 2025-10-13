<?php

use App\Http\Controllers\Client\ClientController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Http\Request;
use App\Http\Controllers\Accounts\AssetsController;
use App\Http\Controllers\Accounts\AccountsChartController;
use App\Http\Controllers\Commission\CommissionController;
use App\Http\Controllers\EmployeeTargetController;
use App\Http\Controllers\Logs\LogController;
use App\Http\Controllers\StatisticsController;
use App\Models\Client;
use App\Models\ClientRelation;
use App\Models\Invoice;
use App\Models\Offer;
use Illuminate\Support\Facades\Http;
use Modules\Client\Http\Controllers\ClientSettingController;
use Modules\Client\Http\Controllers\VisitController;
use App\Http\Controllers\TestNotificationController;

// ==================== Debug Routes ====================
// Debug route to check client relations data
Route::get('/debug-client-relations', function () {
    $client = Client::with([
        'clientRelations' => function ($query) {
            $query->with(['employee', 'location'])
                  ->orderBy('created_at', 'desc');
        },
    ])->first();

    if (!$client) {
        return response()->json(['error' => 'No clients found']);
    }

    $clientRelations = $client->clientRelations->map(function ($relation) {
        // معالجة نوع الموقع لعرضه بالعربية
        $siteTypeText = '';
        switch ($relation->site_type) {
            case 'independent_booth':
                $siteTypeText = 'بسطة مستقلة';
                break;
            case 'grocery':
                $siteTypeText = 'بقالة';
                break;
            case 'supplies':
                $siteTypeText = 'تموينات';
                break;
            case 'markets':
                $siteTypeText = 'أسواق';
                break;
            case 'station':
                $siteTypeText = 'محطة';
                break;
            default:
                $siteTypeText = $relation->site_type;
        }

        // معالجة المرفقات
        $attachmentsArray = [];
        if ($relation->attachments) {
            if (is_string($relation->attachments)) {
                $decoded = json_decode($relation->attachments, true);
                if (is_array($decoded)) {
                    $attachmentsArray = $decoded;
                } else {
                    $attachmentsArray = [$relation->attachments];
                }
            } elseif (is_array($relation->attachments)) {
                $attachmentsArray = $relation->attachments;
            }
        }

        return [
            'id' => $relation->id,
            'client_id' => $relation->client_id,
            'status' => $relation->status,
            'quotation_id' => $relation->quotation_id,
            'invoice_id' => $relation->invoice_id,
            'description' => $relation->description,
            'process' => $relation->process,
            'type' => $relation->type,
            'date' => $relation->date,
            'time' => $relation->time,
            'employee_id' => $relation->employee_id,
            'employee' => $relation->employee->name ?? 'غير محدد',
            'employee_email' => $relation->employee->email ?? null,
            'location_id' => $relation->location_id,
            'location' => $relation->location ? [
                'id' => $relation->location->id,
                'latitude' => $relation->location->latitude,
                'longitude' => $relation->location->longitude,
                'address' => $relation->location->address,
            ] : null,
            'deposit_count' => $relation->deposit_count,
            'employee_view_status' => $relation->employee_view_status,
            'site_type' => $relation->site_type,
            'site_type_text' => $siteTypeText,
            'competitor_documents' => $relation->competitor_documents,
            'additional_data' => $relation->additional_data,
            'attachments' => $relation->attachments,
            'attachments_array' => $attachmentsArray,
            'created_at' => $relation->created_at,
            'updated_at' => $relation->updated_at,
        ];
    });

    return response()->json([
        'client_id' => $client->id,
        'client_name' => $client->trade_name,
        'relations_count' => $clientRelations->count(),
        'relations' => $clientRelations
    ]);
});

// Test route for checking incomplete visits
Route::get('/test-incomplete-visits', function () {
    if (!\Illuminate\Support\Facades\Auth::check()) {
        return response()->json(['message' => 'Not authenticated']);
    }

    $user = \Illuminate\Support\Facades\Auth::user();

    if ($user->role !== 'employee') {
        return response()->json(['message' => 'Not an employee']);
    }

    $incompleteVisits = \App\Models\EmployeeClientVisit::where('employee_id', $user->id)
        ->needsJustification()
        ->get();

    return response()->json([
        'user' => $user->name,
        'incomplete_visits_count' => $incompleteVisits->count(),
        'incomplete_visits' => $incompleteVisits
    ]);
})->middleware('auth');

// ==================== Test & Reports Routes ====================
Route::get('/test-send', [ClientSettingController::class, 'test'])->name('clients.test_send');
Route::get('/test/send', [ClientSettingController::class, 'test'])->name('clients.test_send');

Route::get('/send-daily-report', [VisitController::class, 'sendDailyReport']);
Route::get('/send-weekly-report', [VisitController::class, 'sendWeeklyReport']);
Route::get('/send-monthly-report', [VisitController::class, 'sendMonthlyReport']);

// ==================== Client Data Route ====================
Route::get('/client-data/{clientId}', function ($clientId) {
    $client = Client::with(['latestStatus'])->findOrFail($clientId);

    $invoices = Invoice::where('client_id', $clientId)
        ->with(['items', 'payments_process'])
        ->orderBy('created_at', 'desc')
        ->get();

    $notes = ClientRelation::with(['employee', 'location'])
        ->where('client_id', $clientId)
        ->latest()
        ->get();

    return response()->json([
        'client' => $client,
        'invoices' => $invoices,
        'notes' => $notes,
    ]);
})->name('client.data');

// ==================== Incomplete Visits Justification Routes ====================
// Employee routes for incomplete visits justification
Route::middleware(['auth', 'check.incomplete.visits'])->group(function () {
    Route::get('/incomplete-visits-justification', [\App\Http\Controllers\IncompleteVisitsController::class, 'showJustificationForm'])
        ->name('incomplete.visits.justification');

    Route::post('/incomplete-visits-justification', [\App\Http\Controllers\IncompleteVisitsController::class, 'submitJustification'])
        ->name('incomplete.visits.submit');

    // Employee viewing their own justifications
    Route::get('/my-visit-justifications', [\App\Http\Controllers\IncompleteVisitsController::class, 'showMyJustifications'])
        ->name('employee.visit-justifications.index');

    Route::get('/my-visit-justifications/{id}', [\App\Http\Controllers\IncompleteVisitsController::class, 'showJustificationDetails'])
        ->name('employee.visit-justifications.show');

    Route::get('/my-visit-justifications/{id}/edit', [\App\Http\Controllers\IncompleteVisitsController::class, 'editJustification'])
        ->name('employee.visit-justifications.edit');

    Route::put('/my-visit-justifications/{id}', [\App\Http\Controllers\IncompleteVisitsController::class, 'updateJustification'])
        ->name('employee.visit-justifications.update');
});

// Admin routes for managing visit justifications
Route::middleware(['auth', 'role:admin|manager'])->group(function () {
    Route::get('/admin/visit-justifications', [\App\Http\Controllers\Admin\VisitJustificationController::class, 'index'])
        ->name('admin.visit-justifications.index');

    Route::post('/admin/visit-justifications/{id}/approve', [\App\Http\Controllers\Admin\VisitJustificationController::class, 'approve'])
        ->name('admin.visit-justifications.approve');

    Route::post('/admin/visit-justifications/{id}/reject', [\App\Http\Controllers\Admin\VisitJustificationController::class, 'reject'])
        ->name('admin.visit-justifications.reject');
});

// ==================== Auth Routes ====================
require __DIR__ . '/auth.php';

// ==================== Text Editor Route ====================
Route::get('/text/editor', function () {
    return view('text_editor');
});

// ==================== Localized Routes ====================
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'check.branch'],
    ],
    function () {
        // Client Access Routes
        Route::middleware(['auth', 'client.access'])->group(function () {
            Route::get('/personal', [ClientSettingController::class, 'personal'])->name('clients.personal');
            Route::get('/invoice/client', [ClientSettingController::class, 'invoice_client'])->name('clients.invoice_client');
            Route::get('/appointments/client', [ClientSettingController::class, 'appointments_client'])->name('clients.appointments_client');
            Route::get('/SupplyOrders/client', [ClientSettingController::class, 'SupplyOrders_client'])->name('clients.SupplyOrders_client');
            Route::get('/questions/client', [ClientSettingController::class, 'questions_client'])->name('clients.questions_client');
            Route::get('/edit/profile', [ClientSettingController::class, 'profile'])->name('clients.profile');

            // Employee Targets
            Route::get('/employee-targets', [EmployeeTargetController::class, 'index'])->name('employee_targets.index');
            Route::post('/employee-targets', [EmployeeTargetController::class, 'storeOrUpdate'])->name('employee_targets.store');
            Route::get('/general-target', [EmployeeTargetController::class, 'showGeneralTarget'])->name('target.show');
            Route::post('/general-target', [EmployeeTargetController::class, 'updateGeneralTarget'])->name('target.update');
            Route::get('/client-target', [EmployeeTargetController::class, 'client_target'])->name('target.client');

            // التحصيل اليومي
            Route::get('/daily_closing_entry', [EmployeeTargetController::class, 'daily_closing_entry'])->name('daily_closing_entry');

            // احصائيات الزيارات
            Route::get('/visitTarget', [EmployeeTargetController::class, 'visitTarget'])->name('visitTarget');
            Route::post('/visitTarget', [EmployeeTargetController::class, 'updatevisitTarget'])->name('target.visitTarget');

            // احصائيات الفروع
            Route::get('/statistics_branch', [StatisticsController::class, 'StatisticsGroup'])->name('statistics.group');

            // احصائيات المجموعات
            Route::get('/statistics_group', [StatisticsController::class, 'Group'])->name('statistics.groupall');

            // احصائيات الاحياء
            Route::get('/statistics_neighborhood', [StatisticsController::class, 'neighborhood'])->name('statistics.neighborhood');
        });

        // Sales & Accounts Routes
        Route::prefix('sales')
            ->middleware(['auth', 'check.branch'])
            ->group(function () {
                Route::prefix('account')
                    ->middleware(['auth'])
                    ->group(function () {
                        Route::resource('Assets', AssetsController::class);
                        Route::get('Assets/{id}/pdf', [AssetsController::class, 'generatePdf'])->name('Assets.generatePdf');
                        Route::get('Assets/{id}/sell', [AssetsController::class, 'showSellForm'])->name('Assets.showSell');
                        Route::post('Assets/{id}/sell', [AssetsController::class, 'sell'])->name('Assets.sell');
                        Route::get('/chart/details/{accountId}', [AccountsChartController::class, 'getAccountDetails'])->name('account.details');
                        Route::post('/set-error', function (Illuminate\Http\Request $request) {
                            session()->flash('error', $request->message);
                            return response()->json(['success' => true]);
                        });
                    });
            });

        // Accounts Routes
        Route::prefix('accounts')
            ->middleware(['auth'])
            ->group(function () {
                Route::get('/tree', [AccountsChartController::class, 'getTree'])->name('accounts.tree');
                Route::get('/showDetails/{id}', [AccountsChartController::class, 'showDetails'])->name('account.showDetails');
                Route::get('/chart/details/{accountId}', [AccountsChartController::class, 'getAccountDetails'])->name('accounts.details');
                Route::get('/{id}/children', [AccountsChartController::class, 'getChildren'])->name('accounts.children');
            });

        // Visits Routes
        Route::prefix('visits')->group(function () {
            Route::post('/visits', [VisitController::class, 'storeEmployeeLocation'])->name('visits.storeEmployeeLocation');
            Route::get('/visits/today', [VisitController::class, 'getTodayVisits'])
                ->middleware('auth')
                ->name('visits.today');

            Route::get('/traffic-analysis', [VisitController::class, 'tracktaff'])->name('traffic.analysis');
            Route::post('/get-weeks-data', [VisitController::class, 'getWeeksData'])->name('get.weeks.data');
            Route::post('/get-traffic-data', [VisitController::class, 'getTrafficData'])->name('get.traffic.data');

            Route::post('/visits/location-enhanced', [VisitController::class, 'storeLocationEnhanced'])->name('visits.storeLocationEnhanced');

            Route::get('/tracktaff', [VisitController::class, 'tracktaff'])->name('visits.tracktaff');

            // الانصراف التلقائي
            Route::get('/process-auto-departures', [VisitController::class, 'checkAndProcessAutoDepartures'])->name('visits.processAutoDepartures');
            Route::get('/send-daily-report', [VisitController::class, 'sendDailyReport']);

            // الانصراف اليدوي
            Route::post('/manual-departure/{visitId}', [VisitController::class, 'manualDeparture'])->name('visits.manualDeparture');

            // Routes للتحسين
            Route::post('/clear-visits-data', [VisitController::class, 'clearVisitsData'])->name('visits.clearData');
            Route::post('/clear-cache', function() {
                cache()->forget('traffic_analytics_' . date('Y-m-d-H'));
                return response()->json(['success' => true]);
            })->name('visits.clearCache');
        });

        // Logs Routes
        Route::prefix('logs')
            ->middleware(['auth'])
            ->group(function () {
                Route::get('/index', [LogController::class, 'index'])->name('logs.index');
            });
    }
);