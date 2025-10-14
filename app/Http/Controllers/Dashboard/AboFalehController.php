<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClientRelation;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\PaymentsProcess;
use App\Models\Receipt;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Visit;
use App\Models\StoreHouse;
use App\Models\Product;
use App\Models\ProductDetails;
use Carbon\Carbon; // تأكد إنه موجود في أعلى الملف

class AboFalehController extends Controller
{
public function reportTrac(Request $request)
{
    $allUsers = User::whereIn('role', ['employee', 'manager'])->get();

    $userId = $request->input('user_id', auth()->id());
    $user = User::find($userId);

    if (!$user) {
        return view('dashboard.abo_faleh.reportTrack', [
            'user' => null,
            'all' => collect(),
            'from' => now()->subDays(7)->startOfDay(),
            'to' => now()->endOfDay(),
            'allUsers' => $allUsers,
        ]);
    }

    $fromDate = $request->input('from_date', now()->subDays(7)->format('Y-m-d'));
    $toDate = $request->input('to_date', now()->format('Y-m-d'));

    $from = Carbon::parse($fromDate)->startOfDay();
    $to = Carbon::parse($toDate)->endOfDay();

    // جلب IDs الفواتير الأصلية التي لها مرتجعات
    $invoicesWithReturns = Invoice::whereNotNull('reference_number')->pluck('reference_number')->toArray();

    // جلب الملاحظات
    $notesRaw = ClientRelation::where('employee_id', $user->id)
        ->whereBetween('created_at', [$from, $to])
        ->get();

    $notesMap = collect();
    foreach ($notesRaw as $note) {
        $key = $note->client_id . '_' . Carbon::parse($note->created_at)->format('Y-m-d');
        if (!$notesMap->has($key)) {
            $notesMap->put($key, $note);
        }
    }

    // الزيارات
    $visits = Visit::where('employee_id', $user->id)
        ->whereBetween('arrival_time', [$from, $to])
        ->with('client.group')
        ->get()
        ->map(function ($v) use ($notesMap) {
            $clientId = $v->client_id;
            $date = optional($v->arrival_time)->format('Y-m-d');
            $key = $clientId . '_' . $date;

            $note = $notesMap->get($key);
            $description_note = $note ? $note->description : '--';
            if ($note) {
                $notesMap->forget($key);
            }

            return [
                'type' => 'زيارة',
                'group' => $v->client->group->name ?? '--',
                'client' => $v->client->trade_name ?? '--',
                'arrival' => optional($v->arrival_time)->format('H:i'),
                'departure' => optional($v->departure_time)->format('H:i'),
                'date' => $date,
                'datetime' => $v->arrival_time,
                'receipt' => '--',
                'payment' => '--',
                'invoice' => '--',
                'credit_note' => '--',
                'expense' => '--',
                'warehouse_items' => [],
                'description_visit' => $v->notes ?? '--',
                'description_note' => $description_note,
            ];
        });

    $invoices = Invoice::where('created_by', $user->id)
        ->whereBetween('created_at', [$from, $to])
        ->with(['client.group', 'items.product', 'items.storeHouse'])
        ->get()
        ->map(function ($i) use ($notesMap, $invoicesWithReturns) {
            $clientId = $i->client_id;
            $date = optional($i->created_at)->format('Y-m-d');
            $key = $clientId . '_' . $date;

            $note = $notesMap->get($key);
            $description_note = $note ? $note->description : '--';
            if ($note) {
                $notesMap->forget($key);
            }

            $isReturned = $i->type == 'returned';
            $hasReturn = in_array($i->id, $invoicesWithReturns);

            // جمع بيانات المنتجات من الفاتورة
            $warehouseItems = $i->items->map(function ($item) use ($isReturned) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? $item->item ?? '--',
                    'warehouse_name' => $item->storeHouse->name ?? '--',
                    'warehouse_id' => $item->store_house_id,
                    'quantity' => $isReturned ? -$item->quantity : $item->quantity,
                ];
            })->toArray();

            return [
                'type' => $isReturned ? 'فاتورة مرتجعة' : 'فاتورة',
                'group' => optional($i->client)->group->name ?? '--',
                'client' => optional($i->client)->trade_name ?? '--',
                'arrival' => optional($i->created_at)->format('H:i'),
                'departure' => '--',
                'date' => $date,
                'datetime' => $i->created_at,
                'receipt' => '--',
                'payment' => '--',
                'invoice' => $isReturned ?
                    number_format(-$i->grand_total, 2) :
                    number_format($i->grand_total, 2),
                'credit_note' => $isReturned ? number_format($i->grand_total, 2) : '--',
                'expense' => '--',
                'warehouse_items' => $warehouseItems,
                'description_visit' => '',
                'description_note' => $description_note,
                'has_return' => $hasReturn,
            ];
        });

    // المدفوعات
    $payments = PaymentsProcess::whereBetween('payment_date', [$from, $to])
        ->where(function ($query) use ($user) {
            $query
                ->whereHas('invoice', function ($q) use ($user) {
                    $q->where('created_by', $user->id);
                })
                ->orWhere('employee_id', $user->id);
        })
        ->with('invoice.client.group')
        ->get()
        ->filter(function ($payment) use ($invoicesWithReturns) {
            $invoiceId = optional($payment->invoice)->id;
            return !in_array($invoiceId, $invoicesWithReturns);
        })
        ->map(function ($p) use ($notesMap) {
            $clientId = optional($p->invoice)->client_id;
            $date = optional($p->payment_date)->format('Y-m-d');
            $key = $clientId . '_' . $date;

            $note = $notesMap->get($key);
            $description_note = $note ? $note->description : '--';
            if ($note) {
                $notesMap->forget($key);
            }

            return [
                'type' => 'مدفوع',
                'group' => optional($p->invoice->client)->group->name ?? '--',
                'client' => optional($p->invoice->client)->trade_name ?? '--',
                'arrival' => optional($p->payment_date)->format('H:i'),
                'departure' => '--',
                'date' => $date,
                'datetime' => $p->payment_date,
                'receipt' => '--',
                'payment' => number_format($p->amount, 2),
                'invoice' => '--',
                'credit_note' => '--',
                'expense' => '--',
                'warehouse_items' => [],
                'description_visit' => '',
                'description_note' => $description_note,
            ];
        });

    // سندات القبض
    $receipts = Receipt::where('created_by', $user->id)
        ->whereBetween('created_at', [$from, $to])
        ->with('account')
        ->get()
        ->map(function ($r) use ($notesMap) {
            $clientId = optional($r->account)->id;
            $date = optional($r->created_at)->format('Y-m-d');
            $key = $clientId . '_' . $date;

            $note = $notesMap->get($key);
            $description_note = $note ? $note->description : '--';
            if ($note) {
                $notesMap->forget($key);
            }

            return [
                'type' => 'سند قبض',
                'group' => optional($r->account->group)->name ?? '--',
                'client' => optional($r->account)->name ?? '--',
                'arrival' => optional($r->created_at)->format('H:i'),
                'departure' => '--',
                'date' => $date,
                'datetime' => $r->created_at,
                'receipt' => number_format($r->amount, 2),
                'payment' => '--',
                'invoice' => '--',
                'credit_note' => '--',
                'expense' => '--',
                'warehouse_items' => [],
                'description_visit' => '',
                'description_note' => $description_note,
            ];
        });

    // المصروفات
    $expenses = Expense::where('created_by', $user->id)
        ->whereBetween('created_at', [$from, $to])
        ->get()
        ->map(function ($e) {
            return [
                'type' => 'سند صرف',
                'group' => '--',
                'client' => $e->name ?? 'مصروف',
                'arrival' => optional($e->created_at)->format('H:i'),
                'departure' => '--',
                'date' => optional($e->created_at)->format('Y-m-d'),
                'datetime' => $e->created_at,
                'receipt' => '--',
                'payment' => '--',
                'invoice' => '--',
                'credit_note' => '--',
                'expense' => number_format($e->amount, 2),
                'warehouse_items' => [],
                'description_visit' => '',
                'description_note' => $e->description ?? '--',
            ];
        });

    $all = collect()
        ->merge($visits)
        ->merge($invoices)
        ->merge($payments)
        ->merge($receipts)
        ->merge($expenses)
        ->sortBy(fn($row) => $row['datetime'])
        ->values();

    // حساب الكميات المتبقية للمستودعات (قبل وبعد)
    // الخطوة 1: جلب الرصيد الحالي لكل منتج في كل مستودع
    $currentBalances = [];

    // تحويل الـ Collection إلى Array للتعديل عليه
    $allArray = $all->toArray();

    // جمع كل المنتجات والمستودعات من البيانات
    foreach ($allArray as $row) {
        if (!empty($row['warehouse_items'])) {
            foreach ($row['warehouse_items'] as $item) {
                $productId = $item['product_id'];
                $warehouseId = $item['warehouse_id'];
                $key = $productId . '_' . $warehouseId;

                if (!isset($currentBalances[$key])) {
                    // جلب الرصيد الحالي من ProductDetails
                    $currentStock = ProductDetails::where('product_id', $productId)
                        ->where('store_house_id', $warehouseId)
                        ->value('quantity') ?? 0;

                    $currentBalances[$key] = $currentStock;
                }
            }
        }
    }

    // الخطوة 2: حساب مجموع كل العمليات لكل منتج في كل مستودع
    $totalChanges = [];
    foreach ($allArray as $row) {
        if (!empty($row['warehouse_items'])) {
            foreach ($row['warehouse_items'] as $item) {
                $productId = $item['product_id'];
                $warehouseId = $item['warehouse_id'];
                $quantity = $item['quantity'];
                $key = $productId . '_' . $warehouseId;

                if (!isset($totalChanges[$key])) {
                    $totalChanges[$key] = 0;
                }
                $totalChanges[$key] += $quantity;
            }
        }
    }

    // الخطوة 3: حساب الرصيد الأولي (قبل كل العمليات)
    $initialBalances = [];
    foreach ($currentBalances as $key => $currentBalance) {
        $totalChange = $totalChanges[$key] ?? 0;
        // الرصيد الأولي = الرصيد الحالي + مجموع التغييرات (لأن العمليات كانت تطرح)
        $initialBalances[$key] = $currentBalance + $totalChange;
    }

    // الخطوة 4: المرور على كل صف وحساب الرصيد قبل وبعد
    $runningBalances = $initialBalances;

    foreach ($allArray as $index => $row) {
        if (!empty($row['warehouse_items'])) {
            foreach ($row['warehouse_items'] as $itemIndex => $item) {
                $productId = $item['product_id'];
                $warehouseId = $item['warehouse_id'];
                $quantity = $item['quantity'];
                $key = $productId . '_' . $warehouseId;

                // الرصيد قبل العملية
                $balanceBefore = $runningBalances[$key] ?? 0;

                // تطبيق العملية (الفواتير تطرح بـ quantity موجب، المرتجعات تضيف بـ quantity سالب)
                $runningBalances[$key] = $balanceBefore - $quantity;

                // الرصيد بعد العملية
                $balanceAfter = $runningBalances[$key];

                // تعديل الـ Array مباشرة
                $allArray[$index]['warehouse_items'][$itemIndex]['balance_before'] = $balanceBefore;
                $allArray[$index]['warehouse_items'][$itemIndex]['balance_after'] = $balanceAfter;
            }
        }
    }

    // إعادة تحويل الـ Array إلى Collection
    $all = collect($allArray);

    // تجميع البيانات حسب العميل والتاريخ
    $grouped = $all->groupBy(fn($item) => $item['client'] . '_' . $item['date']);

    return view('dashboard.abo_faleh.reportTrack', compact(
        'user',
        'all',
        'grouped',
        'from',
        'to',
        'allUsers'
    ));
}
   public function index(Request $request)
{
    $users = User::withCount(['visits', 'notes'])
        ->whereIn('role', ['employee', 'manager'])
        ->get();

    // 1. استبعاد الفواتير المرجعة (نفس منطق daily_closing_entry)
    $returnedInvoiceIds = Invoice::whereNotNull('reference_number')->pluck('reference_number')->toArray();
    $excludedInvoiceIds = array_unique(array_merge($returnedInvoiceIds, Invoice::where('type', 'returned')->pluck('id')->toArray()));

    // 2. جلب جميع المدفوعات (نفس منطق daily_closing_entry)
    $payments = PaymentsProcess::with('invoice')
        ->whereHas('invoice', function ($q) use ($excludedInvoiceIds) {
            $q->where('type', 'normal')->whereNotIn('id', $excludedInvoiceIds);
        })
        ->get();

    $activeUsers = collect(); // مجموعة للموظفين النشطين فقط

    foreach ($users as $user) {
        $userId = $user->id;

        // 3. حساب مجموع المدفوعات لهذا الموظف
        $paymentsFromInvoices = $payments
            ->filter(function ($payment) use ($userId) {
                if ($payment->employee_id) {
                    return $payment->employee_id == $userId;
                }
                return optional($payment->invoice)->created_by == $userId;
            })
            ->sum('amount');

        // 4. سندات القبض
        $receiptsSum = $user->receipts()->sum('amount') ?? 0;

        // 5. إجمالي التحصيل = المدفوعات + سندات القبض
        $user->total_collection = $paymentsFromInvoices + $receiptsSum;

        // 6. النفقات (سندات الصرف)
        $user->expenses_sum = $user->expenses()->sum('amount') ?? 0;

        // 7. الفواتير غير المدفوعة (الآجلة) - مع استبعاد المرتجعات
        $unpaidInvoices = Invoice::where('created_by', $user->id)
            ->where('is_paid', false)
            ->where('type', 'normal')
            ->whereNotIn('id', $excludedInvoiceIds)
            ->get();

        $user->unpaid_invoices_count = $unpaidInvoices->count();
        $user->unpaid_invoices_sum = $unpaidInvoices->sum('grand_total');

        // ✅ شرط الإظهار: الموظف لديه أي نشاط
        $hasActivity = $user->visits_count > 0
            || $user->notes_count > 0
            || $user->total_collection > 0
            || $user->expenses_sum > 0
            || $user->unpaid_invoices_count > 0;

        if ($hasActivity) {
            $activeUsers->push($user);
        }
    }

    return view('dashboard.abo_faleh.index', [
        'employees' => $activeUsers, // إرسال الموظفين النشطين فقط
        'request' => $request,
    ]);
}
    /**
     * Display storehouse inventory report
     */
    public function storehouseReport(Request $request)
    {
        $storehouses = StoreHouse::all();
        $selectedStorehouseId = $request->input('storehouse_id');

        $productsWithQuantities = collect();

        if ($selectedStorehouseId) {
            $storehouse = StoreHouse::find($selectedStorehouseId);
            if ($storehouse) {
                // Get all products in this storehouse with their quantities
                $productsWithQuantities = $storehouse->getProductsWithQuantities();

                // Add storehouse information to each product
                $productsWithQuantities = $productsWithQuantities->map(function ($productData) use ($storehouse) {
                    return [
                        'product_id' => $productData['product_id'],
                        'product_name' => $productData['product_name'],
                        'current_quantity' => $productData['current_quantity'],
                        'sold_quantity' => $productData['sold_quantity'],
                        'remaining_quantity' => $productData['remaining_quantity'],
                        'storehouse_id' => $storehouse->id,
                        'storehouse_name' => $storehouse->name,
                    ];
                });
            }
        }

        return view('dashboard.abo_faleh.storehouseReport', compact(
            'storehouses',
            'selectedStorehouseId',
            'productsWithQuantities'
        ));
    }
}
