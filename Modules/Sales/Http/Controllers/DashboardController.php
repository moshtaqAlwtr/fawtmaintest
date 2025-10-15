<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\StoreCreditNotificationRequest;
use App\Models\Account;
use App\Models\Client;
use App\Models\CreditNotification;
use App\Models\Employee;
use App\Models\InvoiceItem;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\TaxSitting;
use App\Models\AccountSetting;
use App\Models\TaxInvoice;
use App\Models\StoreHouse;
use App\Models\ProductDetails;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\PaymentsProcess;
use Carbon\Carbon;
use App\Models\PermissionSource;
use App\Models\WarehousePermitsProducts;
use App\Models\WarehousePermits;
use App\Models\DefaultWarehouses;
use Illuminate\Http\Request;
use App\Mail\CreditNotificationLinkMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Dompdf\Dompdf;
use Dompdf\Options;

use function Ramsey\Uuid\v1;

class DashboardController extends Controller
{
    
public function index(Request $request)
{
    // تحديد الفترة الزمنية (افتراضياً آخر 7 أيام)
    $period = $request->input('period', 'week');
    $today = Carbon::today();
    
    switch ($period) {
        case 'month':
            $startDate = Carbon::today()->subDays(30);
            $previousStartDate = Carbon::today()->subDays(60);
            $previousEndDate = Carbon::today()->subDays(31);
            break;
        case 'year':
            $startDate = Carbon::today()->subDays(365);
            $previousStartDate = Carbon::today()->subDays(730);
            $previousEndDate = Carbon::today()->subDays(366);
            break;
        case 'week':
        default:
            $startDate = Carbon::today()->subDays(7);
            $previousStartDate = Carbon::today()->subDays(14);
            $previousEndDate = Carbon::today()->subDays(8);
            break;
    }

    // إحصائيات المبيعات
    $salesStats = $this->getSalesStatistics($startDate, $today, $previousStartDate, $previousEndDate);
    
    // إحصائيات الفواتير
    $invoiceStats = $this->getInvoiceStatistics($startDate, $today, $previousStartDate, $previousEndDate);
    
    // إحصائيات المرتجعات
    $returnsStats = $this->getReturnsStatistics($startDate, $today);
    
    // إحصائيات عروض الأسعار
    $quotesStats = $this->getQuotesStatistics($startDate, $today);
    
    // إحصائيات الإشعارات الدائنة
    $creditNotifications = $this->getCreditNotifications($startDate, $today);
    
    // إحصائيات المدفوعات
    $paymentsStats = $this->getPaymentsStatistics($startDate, $today);
    
    // إحصائيات الطلبات المرسلة (آخر 5 طلبات)
    $recentOrders = $this->getRecentOrders();

    return View::make('sales::kpis.index', compact(
        'salesStats',
        'invoiceStats',
        'returnsStats',
        'quotesStats',
        'creditNotifications',
        'paymentsStats',
        'recentOrders',
        'period'
    ));
}

/**
 * الحصول على إحصائيات المبيعات
 */
private function getSalesStatistics($startDate, $endDate, $previousStartDate, $previousEndDate)
{
    // المبيعات الحالية (الفواتير العادية فقط، ليست المرتجعات)
    $currentSales = Invoice::where('type', '!=', 'returned')
                        ->whereBetween('invoice_date', [$startDate, $endDate])
                        ->sum('grand_total');
    
    // المبيعات السابقة للمقارنة
    $previousSales = Invoice::where('type', '!=', 'returned')
                         ->whereBetween('invoice_date', [$previousStartDate, $previousEndDate])
                         ->sum('grand_total');
    
    // حساب نسبة النمو
    $growthRate = 0;
    if ($previousSales > 0) {
        $growthRate = (($currentSales - $previousSales) / $previousSales) * 100;
    }
    
    // البيانات اليومية للرسم البياني
    $dailySales = Invoice::where('type', '!=', 'returned')
                     ->whereBetween('invoice_date', [$startDate, $endDate])
                     ->selectRaw('DATE(invoice_date) as date, SUM(grand_total) as total')
                     ->groupBy('date')
                     ->orderBy('date')
                     ->get()
                     ->pluck('total', 'date')
                     ->toArray();
    
    return [
        'current' => $currentSales,
        'previous' => $previousSales,
        'growth' => round($growthRate, 2),
        'daily_sales' => $dailySales,
        'average' => $currentSales > 0 ? $currentSales / $startDate->diffInDays($endDate) : 0,
    ];
}

/**
 * الحصول على إحصائيات الفواتير
 */
private function getInvoiceStatistics($startDate, $endDate, $previousStartDate, $previousEndDate)
{
    // عدد الفواتير الحالية
    $currentCount = Invoice::where('type', '!=', 'returned')
                      ->whereBetween('invoice_date', [$startDate, $endDate])
                      ->count();
    
    // عدد الفواتير السابقة للمقارنة
    $previousCount = Invoice::where('type', '!=', 'returned')
                       ->whereBetween('invoice_date', [$previousStartDate, $previousEndDate])
                       ->count();
    
    // حساب نسبة النمو
    $growthRate = 0;
    if ($previousCount > 0) {
        $growthRate = (($currentCount - $previousCount) / $previousCount) * 100;
    }
    
    return [
        'current_count' => $currentCount,
        'previous_count' => $previousCount,
        'growth' => round($growthRate, 2),
    ];
}

/**
 * الحصول على إحصائيات المرتجعات
 */
private function getReturnsStatistics($startDate, $endDate)
{
    // إجمالي المرتجعات
    $totalReturns = Invoice::where('type', 'returned')
                       ->whereBetween('invoice_date', [$startDate, $endDate])
                       ->count();
    
    // المرتجعات الجديدة (آخر يومين)
    $newReturns = Invoice::where('type', 'returned')
                     ->whereBetween('invoice_date', [Carbon::now()->subDays(2), Carbon::now()])
                     ->count();
    
    // المرتجعات قيد المعالجة (المرتجعات التي لم تكتمل معالجتها)
    $processingReturns = Invoice::where('type', 'returned')
                           ->where('payment_status', '!=', 1) // افتراض أن حالة الدفع 1 تعني مكتمل
                           ->whereBetween('invoice_date', [$startDate, $endDate])
                           ->count();
    
    // حساب متوسط وقت المعالجة (افتراضي يوم واحد، يمكن حسابه بشكل أكثر دقة حسب بيانات النظام)
    $processingTime = 1; // يوم
    
    // نسبة المرتجعات المكتملة
    $completionPercentage = 0;
    if ($totalReturns > 0) {
        $completedReturns = $totalReturns - $processingReturns;
        $completionPercentage = ($completedReturns / $totalReturns) * 100;
    }
    
    return [
        'total' => $totalReturns,
        'new' => $newReturns,
        'processing' => $processingReturns,
        'processing_time' => $processingTime,
        'completion_percentage' => round($completionPercentage, 0),
    ];
}

/**
 * الحصول على إحصائيات عروض الأسعار
 */
private function getQuotesStatistics($startDate, $endDate)
{
    // عروض الأسعار المكتملة
    $completedQuotes = Quote::where('status', Quote::STATUS_APPROVED)
                        ->whereBetween('quote_date', [$startDate, $endDate])
                        ->count();
    
    // عروض الأسعار المعلقة
    $pendingQuotes = Quote::where('status', Quote::STATUS_PENDING)
                      ->whereBetween('quote_date', [$startDate, $endDate])
                      ->count();
    
    // عروض الأسعار المرفوضة
    $rejectedQuotes = Quote::where('status', Quote::STATUS_REJECTED)
                       ->whereBetween('quote_date', [$startDate, $endDate])
                       ->count();
    
    // إجمالي عروض الأسعار
    $totalQuotes = $completedQuotes + $pendingQuotes + $rejectedQuotes;
    
    // إجمالي قيمة عروض الأسعار حسب الحالة
    $completedAmount = Quote::where('status', Quote::STATUS_APPROVED)
                        ->whereBetween('quote_date', [$startDate, $endDate])
                        ->sum('grand_total');
    
    $pendingAmount = Quote::where('status', Quote::STATUS_PENDING)
                      ->whereBetween('quote_date', [$startDate, $endDate])
                      ->sum('grand_total');
    
    $rejectedAmount = Quote::where('status', Quote::STATUS_REJECTED)
                       ->whereBetween('quote_date', [$startDate, $endDate])
                       ->sum('grand_total');
    
    return [
        'completed' => [
            'count' => $completedQuotes,
            'amount' => $completedAmount,
        ],
        'pending' => [
            'count' => $pendingQuotes,
            'amount' => $pendingAmount,
        ],
        'rejected' => [
            'count' => $rejectedQuotes,
            'amount' => $rejectedAmount,
        ],
        'total' => $totalQuotes,
        'total_amount' => $completedAmount + $pendingAmount + $rejectedAmount,
    ];
}

/**
 * الحصول على الإشعارات الدائنة الأخيرة
 */
private function getCreditNotifications($startDate, $endDate)
{
    // الحصول على آخر 5 إشعارات دائنة
    return CreditNotification::with('client')
                         ->whereBetween('credit_date', [$startDate, $endDate])
                         ->orderBy('id', 'desc')
                         ->take(5)
                         ->get()
                         ->map(function ($notification) {
                             $status = '';
                             $statusClass = '';
                             
                             switch ($notification->status) {
                                 case 1: // Draft
                                     $status = 'مسودة';
                                     $statusClass = 'primary';
                                     break;
                                 case 2: // Pending
                                     $status = 'معلق';
                                     $statusClass = 'warning';
                                     break;
                                 case 3: // Approved
                                     $status = 'تمت الموافقة';
                                     $statusClass = 'success';
                                     break;
                                 case 4: // Converted to Invoice
                                     $status = 'تم التحويل إلى فاتورة';
                                     $statusClass = 'info';
                                     break;
                                 case 5: // Cancelled
                                     $status = 'ملغي';
                                     $statusClass = 'danger';
                                     break;
                             }
                             
                             return [
                                 'id' => $notification->id,
                                 'client_name' => $notification->client ? $notification->client->name : 'غير محدد',
                                 'credit_date' => $notification->credit_date->diffForHumans(),
                                 'grand_total' => $notification->grand_total,
                                 'status' => $status,
                                 'status_class' => $statusClass,
                             ];
                         });
}

/**
 * الحصول على إحصائيات المدفوعات
 */
private function getPaymentsStatistics($startDate, $endDate)
{
    // إجمالي المدفوعات
    $totalPayments = PaymentsProcess::whereBetween('payment_date', [$startDate, $endDate])
                         ->where('type', 'client payments')
                         ->sum('amount');
    
    // المدفوعات حسب طريقة الدفع
    $paymentsByMethod = PaymentsProcess::whereBetween('payment_date', [$startDate, $endDate])
                           ->where('type', 'client payments')
                           ->select('payment_method', DB::raw('SUM(amount) as total'))
                           ->groupBy('payment_method')
                           ->get();
    
    return [
        'total' => $totalPayments,
        'by_method' => $paymentsByMethod,
    ];
}

/**
 * الحصول على آخر الطلبات
 */
private function getRecentOrders()
{
    // الحصول على آخر 5 فواتير (يمكن تعديل هذا حسب هيكلية البيانات الحقيقية)
    return Invoice::with(['client', 'createdByUser'])
               ->where('type', '!=', 'returned')
               ->orderBy('id', 'desc')
               ->take(4)
               ->get()
               ->map(function ($invoice) {
                   $statusClass = '';
                   $statusText = '';
                   
                   // تحديد حالة الفاتورة
                   switch ($invoice->payment_status) {
                       case 1: // مدفوع بالكامل
                           $statusClass = 'success';
                           $statusText = 'قيد التوصيل';
                           break;
                       case 2: // مدفوع جزئياً
                           $statusClass = 'warning';
                           $statusText = 'معلق';
                           break;
                       case 3: // غير مدفوع
                       default:
                           $statusClass = 'danger';
                           $statusText = 'ملغي';
                           break;
                   }
                   
                   return [
                       'id' => $invoice->id,
                       'number' => '#' . $invoice->id,
                       'status' => $statusText,
                       'status_class' => $statusClass,
                       'responsible' => $invoice->createdByUser ? [$invoice->createdByUser] : [],
                       'location' => $invoice->client ? $invoice->client->city . '، ' . $invoice->client->country : 'غير محدد',
                       'distance' => rand(100, 250) . ' كم', // قيمة عشوائية للعرض
                       'progress' => $invoice->payment_status == 1 ? 80 : ($invoice->payment_status == 2 ? 60 : 95),
                       'start_date' => $invoice->invoice_date ? $invoice->invoice_date->format('H:i d/m/Y') : '',
                       'delivery_date' => $invoice->invoice_date ? $invoice->invoice_date->addDays(2)->format('d/m/Y') : '',
                   ];
               });
}

}
