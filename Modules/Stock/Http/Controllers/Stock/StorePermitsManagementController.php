<?php


namespace Modules\Stock\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\WarehousePermitsRequest;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Log;
use App\Models\PermissionSource;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\StoreHouse;
use App\Models\Supplier;
use App\Models\User;
use App\Models\WarehousePermits;
use App\Models\WarehousePermitsProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorePermitsManagementController extends Controller
{
    public function index(Request $request)
{
    $query = WarehousePermits::query()->orderBy('id', 'DESC');

    // فلترة حسب الفرع
    if ($request->filled('branch')) {
        $query->whereHas('user', function($q) use ($request) {
            $q->where('branch_id', $request->branch);
        });
    }

    // فلترة حسب كلمات البحث (الاسم أو الكود)
    if ($request->filled('keywords')) {
        $keywords = '%' . $request->keywords . '%';
        $query->where(function($q) use ($keywords) {
            $q->where('number', 'like', $keywords)
              ->orWhere('details', 'like', $keywords);
        });
    }

    // فلترة حسب نوع الإذن (مصدر الإذن)
    if ($request->filled('permission_type')) {
    $query->where('permission_type', $request->permission_type);
}
    // فلترة حسب الرقم المعرف
    if ($request->filled('id')) {
        $query->where('id', $request->id);
    }

    // فلترة حسب المستودع
    if ($request->filled('store_house')) {
        $query->where('store_houses_id', $request->store_house);
    }

    // فلترة حسب العميل
    if ($request->filled('client')) {
        $query->where('sub_account', $request->client);
    }

    // فلترة حسب المورد
    if ($request->filled('supplier')) {
        $query->where('sub_account', $request->supplier);
    }

    // فلترة حسب الحالة (إذا كان لديك حقل status في النموذج)
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // فلترة حسب المستخدم الذي أضاف الإذن
    if ($request->filled('created_by')) {
        $query->where('created_by', $request->created_by);
    }

    // فلترة حسب المنتج
    if ($request->filled('product')) {
        $query->whereHas('products', function($q) use ($request) {
            $q->where('product_id', $request->product);
        });
    }

    // فلترة حسب التاريخ
    if ($request->filled('from_date')) {
        $query->whereDate('permission_date', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('permission_date', '<=', $request->to_date);
    }

    $wareHousePermits = $query->paginate(30);
    $storeHouses = StoreHouse::where('status', 0)->select('id', 'name')->get();
    $branches = Branch::where('status', 0)->select('id', 'name')->get();
    $clients = Client::all();
   $permissionSources = PermissionSource::all();
    $suppliers = Supplier::all();
    $users = User::where('role','employee')->get();
    $products = Product::all();

    return view('stock::store_permits_management.index', compact('wareHousePermits','permissionSources', 'storeHouses', 'branches', 'clients', 'suppliers', 'users', 'products'));
}
    public function create()
    {
        $storeHouses = StoreHouse::where('status', 0)->select('id', 'name')->get();
        $products = Product::select()->get();

        $record_count = DB::table('warehouse_permits')->count();
        $serial_number = str_pad($record_count + 1, 6, '0', STR_PAD_LEFT);

        return view('stock::store_permits_management.create', compact('storeHouses', 'products', 'serial_number'));
    }
    public function manual_disbursement()
    {
        $storeHouses = StoreHouse::where('status', 0)->select('id', 'name')->get();
        $products = Product::select()->get();

        $record_count = DB::table('warehouse_permits')->count();

        $serial_number = str_pad($record_count + 1, 6, '0', STR_PAD_LEFT);

        return view('stock::store_permits_management.manual_disbursement', compact('storeHouses', 'products', 'serial_number'));
    }
    public function manual_conversion()
    {
        $storeHouses = StoreHouse::where('status', 0)->select('id', 'name')->get();
        $products = Product::select()->get();

        $record_count = DB::table('warehouse_permits')->count();

        $serial_number = str_pad($record_count + 1, 6, '0', STR_PAD_LEFT);

        return view('stock::store_permits_management.manual_conversion', compact('storeHouses', 'products', 'serial_number'));
    }

    public function store(WarehousePermitsRequest $request)
    {
        DB::beginTransaction();
        // try {

        $wareHousePermits = new WarehousePermits();

        if ($request->hasFile('attachments')) {
            $wareHousePermits->attachments = $this->UploadImage('assets/uploads/warehouse', $request->attachments);
        }

        if ($request->permission_type == 3) {
            $wareHousePermits->store_houses_id = $request->from_store_houses_id;
        } else {
            $wareHousePermits->store_houses_id = $request->store_houses_id;
        }

        $wareHousePermits->permission_type = $request->permission_type;
        $wareHousePermits->permission_date = $request->permission_date;
        $wareHousePermits->sub_account = $request->sub_account;
        $wareHousePermits->number = $request->number;
        $wareHousePermits->details = $request->details;
        $wareHousePermits->grand_total = $request->grand_total;
        $wareHousePermits->from_store_houses_id = $request->from_store_houses_id;
        $wareHousePermits->to_store_houses_id = $request->to_store_houses_id;
        $wareHousePermits->created_by = auth()->user()->id;

        $wareHousePermits->save();

        $products = new WarehousePermitsProducts();

        foreach ($request['quantity'] as $index => $quantity) {
            $warehousePermitProduct = $products->create([
                'quantity' => $quantity,
                'total' => $request['total'][$index],
                'unit_price' => $request['unit_price'][$index],
                'product_id' => $request['product_id'][$index],
                'stock_before' => $request['stock_before'][$index],
                'stock_after' => $request['stock_after'][$index],
                'warehouse_permits_id' => $wareHousePermits->id,
            ]);

            // التعامل مع المخزون بناءً على نوع الإذن
            if ($request->permission_type == 1) {
                // 🟢 إذن إضافة للمخزن
                ProductDetails::updateOrCreate(['store_house_id' => $request->store_houses_id, 'product_id' => $request['product_id'][$index]], ['quantity' => DB::raw("quantity + $quantity")]);

                // تحميل العلاقة product
                $store = StoreHouse::find($request->store_houses_id);

                $warehousePermitProduct->load('product');
                Log::create([
                    'type' => 'product_log',
                    'type_id' => $warehousePermitProduct->product_id, // ID النشاط المرتبط
                    'type_log' => 'log', // نوع النشاط
                    'description' => sprintf(
                        'تم إضافة كمية يدوي **%d** من المنتج **%s** إلى **%s**',
                        $quantity, // الكمية المضافة
                        $warehousePermitProduct->product->name, // اسم المنتج
                        $store->name ?? '', // اسم المخزن
                    ),
                    'created_by' => auth()->id(), // ID المستخدم الحالي // ID المستخدم الحالي
                ]);
            } elseif ($request->permission_type == 2) {
                // 🔴 إذن صرف من المخزن
                $stock = ProductDetails::where('store_house_id', $request->store_houses_id)->where('product_id', $request['product_id'][$index])->first();

                if (!$stock || $stock->quantity < $quantity) {
                    DB::rollBack();
                    return back()->with('error', 'الكمية غير متوفرة في المخزن')->withInput();
                }

                $stock->decrement('quantity', $quantity);

                // تحميل العلاقة product
                $store = StoreHouse::find($request->store_houses_id);

                $warehousePermitProduct->load('product');
                Log::create([
                    'type' => 'product_log',
                    'type_id' => $warehousePermitProduct->product_id, // ID النشاط المرتبط
                    'type_log' => 'log', // نوع النشاط
                    'description' => sprintf(
                        'تم صرف يدوي كمية **%d** من المنتج **%s** إلى **%s**',
                        $quantity, // الكمية المضافة
                        $warehousePermitProduct->product->name, // اسم المنتج
                        $store->name ?? '', // اسم المخزن
                    ),
                    'created_by' => auth()->id(), // ID المستخدم الحالي // ID المستخدم الحالي
                ]);
            } elseif ($request->permission_type == 3) {
                // 🔄 إذن تحويل من مخزن إلى مخزن آخر
                $sourceStock = ProductDetails::where('store_house_id', $request->from_store_houses_id)->where('product_id', $request['product_id'][$index])->first();

                // التحقق من وجود الكمية المطلوبة في المخزن المصدر
                if (!$sourceStock || $sourceStock->quantity < $quantity) {
                    DB::rollBack();
                    return back()->with('error', 'الكمية غير متوفرة في المخزن المصدر')->withInput();
                }

                // خصم الكمية من المخزن المصدر
                $sourceStock->decrement('quantity', $quantity);

                // إضافة الكمية إلى المخزن الهدف
                ProductDetails::updateOrCreate(['store_house_id' => $request->to_store_houses_id, 'product_id' => $request['product_id'][$index]], ['quantity' => DB::raw("quantity + $quantity")]);

                // تحميل العلاقة product
                $from_store = StoreHouse::find($request->from_store_houses_id);
                $to_store = StoreHouse::find($request->to_store_houses_id);
                $warehousePermitProduct->load('product');
                Log::create([
                    'type' => 'product_log',
                    'type_id' => $warehousePermitProduct->product_id, // ID النشاط المرتبط
                    'type_log' => 'log', // نوع النشاط
                    'description' => sprintf(
                        'تم تحويل يدوي كمية **%d** من المخزن **%s** إلى المخزن **%s** من المنتج **%s**',
                        $quantity, // الكمية المحولة
                        $from_store->name ?? '', // المخزن المصدر
                        $to_store->name ?? '', // المخزن الهدف
                        $warehousePermitProduct->product->name, // اسم المنتج
                    ),
                    'created_by' => auth()->id(), // ID المستخدم الحالي // ID المستخدم الحالي
                ]);
            }
        } #End foreach
        DB::commit();
        return redirect()
            ->route('store_permits_management.index')
            ->with(['success' => 'تم انشاء اذن المخزن بنجاح']);
        // } catch (\Exception $e) {
        DB::rollBack();
        return redirect()
            ->route('store_permits_management.index')
            ->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        // }
    }

    public function edit($id)
    {
        $permit = WarehousePermits::findOrFail($id);
        $products = Product::select()->get();
        $storeHouses = StoreHouse::where('status', 0)->select('id', 'name')->get();

        if ($permit->permission_type == 1) {
            return view('stock.store_permits_management.edit', compact('permit', 'storeHouses', 'products'));
        }

        if ($permit->permission_type == 2) {
            return view('stock.store_permits_management.manual_disbursement_edit', compact('permit', 'storeHouses', 'products'));
        }

        if ($permit->permission_type == 3) {
            return view('stock::store_permits_management.manual_conversion_edit', compact('permit', 'storeHouses', 'products'));
        }
    }

    public function update(WarehousePermitsRequest $request, $id)
    {
        $wareHousePermits = WarehousePermits::findOrFail($id);

        if ($request->hasFile('attachments')) {
            $wareHousePermits->attachments = $this->UploadImage('assets/uploads/warehouse', $request->attachments);
        }

        if ($request->permission_type == 3) {
            $wareHousePermits->store_houses_id = $request->from_store_houses_id;
        } else {
            $wareHousePermits->store_houses_id = $request->store_houses_id;
        }

        $wareHousePermits->permission_type = $request->permission_type;
        $wareHousePermits->permission_date = $request->permission_date;
        $wareHousePermits->sub_account = $request->sub_account;
        $wareHousePermits->number = $request->number;
        $wareHousePermits->details = $request->details;
        $wareHousePermits->grand_total = $request->grand_total;
        $wareHousePermits->from_store_houses_id = $request->from_store_houses_id;
        $wareHousePermits->to_store_houses_id = $request->to_store_houses_id;
        $wareHousePermits->created_by = auth()->user()->id;

        $wareHousePermits->save();

        // حذف المنتجات القديمة المرتبطة بالإذن
        WarehousePermitsProducts::where('warehouse_permits_id', $id)->delete();

        foreach ($request['quantity'] as $index => $quantity) {
            WarehousePermitsProducts::create([
                'quantity' => $quantity,
                'total' => $request['total'][$index],
                'unit_price' => $request['unit_price'][$index],
                'product_id' => $request['product_id'][$index],
                'warehouse_permits_id' => $wareHousePermits->id,
            ]);
        }

        return redirect()
            ->route('store_permits_management.index')
            ->with(['success' => 'تم تحديث إذن المخزن بنجاح']);
    }

    public function delete($id)
    {
        $wareHousePermits = WarehousePermits::findOrFail($id);
        WarehousePermitsProducts::where('warehouse_permits_id', $id)->delete();
        $wareHousePermits->delete();
        return redirect()
            ->route('store_permits_management.index')
            ->with(['error' => 'تم حذف أذن المخزن بنجاح']);
    }

    // public function getProductsByStore($storeId)
    // {
    //     $products = DB::table('product_details')
    //         ->join('products', 'product_details.product_id', '=', 'products.id')
    //         ->where('product_details.store_house_id', $storeId)
    //         ->select('products.id', 'products.name', 'products.sale_price', 'product_details.quantity')
    //         ->get();

    //     return response()->json($products);
    // }

    public function getProductStock($storeId, $productId)
    {
        $stock = DB::table('product_details')->where('store_house_id', $storeId)->where('product_id', $productId)->value('quantity'); // جلب رصيد المخزون

        return response()->json(['stock' => $stock]);
    }

    # Helper Function
    function uploadImage($folder, $image)
    {
        $fileExtension = $image->getClientOriginalExtension();
        $fileName = time() . rand(1, 99) . '.' . $fileExtension;
        $image->move($folder, $fileName);

        return $fileName;
    }
}
