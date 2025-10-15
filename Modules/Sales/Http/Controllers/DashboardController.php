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
use Dompdf\Dompdf;
use Dompdf\Options;

use function Ramsey\Uuid\v1;

class DashboardController extends Controller
{
    
public function index(Request $request)
{
  return view('sales::kpis.index');
}

}
