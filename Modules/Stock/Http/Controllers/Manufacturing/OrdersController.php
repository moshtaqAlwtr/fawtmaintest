<?php

namespace Modules\Stock\Http\Controllers\Manufacturing;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManufacturOrderRequest;
use App\Models\Account;
use App\Models\Client;
use App\Models\Employee;
use App\Models\ManufacturOrders;
use App\Models\ManufacturOrdersItem;
use App\Models\Product;
use App\Models\ProductionMaterials;
use App\Models\ProductionPath;
use App\Models\Log as ModelsLog;
use App\Models\ProductionStage;
use App\Models\WorkStations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function index(){
        $orders = ManufacturOrders::select()->get();
        return view('manufacturing.orders.index', compact('orders'));
    }
    public function create(){
        $record_count = DB::table('manufactur_orders')->count();
        $serial_number = str_pad($record_count + 1, 6, '0', STR_PAD_LEFT);
        $accounts = Account::select('id', 'name')->get();
        $products = Product::select()->get();
        $employees = Employee::select('id', 'first_name','middle_name')->get();
        $clients = Client::select('id','trade_name')->get();
        $paths = ProductionPath::select('id', 'name')->get();
        $production_materials = ProductionMaterials::select()->get();
        $stages = ProductionStage::select('id', 'stage_name')->get();
        $workstations = WorkStations::select('id', 'name')->get();

        return view('Manufacturing.Orders.create', compact('serial_number', 'accounts', 'products', 'employees', 'clients', 'paths', 'production_materials', 'stages', 'workstations'));
    }

    public function store(ManufacturOrderRequest $request)
    {
        DB::beginTransaction();
        try {

            $order = ManufacturOrders::create([
                'name' => $request->name,
                'code' => $request->code,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'account_id' => $request->account_id,
                'employee_id' => $request->employee_id,
                'client_id' => $request->client_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'production_material_id' => $request->production_material_id,
                'production_path_id' => $request->production_path_id,
                'last_total_cost' => $request->last_total_cost ?? 0,
                'created_by' => auth()->id(),
            ]);

            if ($request->has('raw_product_id')) {
                foreach ($request->raw_product_id as $index => $rawProductId) {
                    ManufacturOrdersItem::create([
                        'manufactur_order_id' => $order->id,
                        'raw_product_id' => $rawProductId,
                        'raw_production_stage_id' => $request->raw_production_stage_id[$index],
                        'raw_unit_price' => $request->raw_unit_price[$index],
                        'raw_quantity' => $request->raw_quantity[$index],
                        'raw_total' => $request->raw_total[$index],

                        'expenses_account_id' => $request->expenses_account_id[$index] ?? null,
                        'expenses_cost_type' => $request->expenses_cost_type[$index] ?? null,
                        'expenses_production_stage_id' => $request->expenses_production_stage_id[$index] ?? null,
                        'expenses_price' => $request->expenses_price[$index] ?? null,
                        'expenses_description' => $request->expenses_description[$index] ?? null,
                        'expenses_total' => $request->expenses_total[$index] ?? null,

                        'workstation_id' => $request->workstation_id[$index] ?? null,
                        'operating_time' => $request->operating_time[$index] ?? null,
                        'manu_production_stage_id' => $request->manu_production_stage_id[$index] ?? null,
                        'manu_cost_type' => $request->manu_cost_type[$index] ?? null,
                        'manu_total_cost' => $request->manu_total_cost[$index] ?? null,
                        'manu_description' => $request->manu_description[$index] ?? null,
                        'manu_total' => $request->manu_total[$index] ?? null,

                        'end_life_product_id' => $request->end_life_product_id[$index] ?? null,
                        'end_life_unit_price' => $request->end_life_unit_price[$index] ?? null,
                        'end_life_production_stage_id' => $request->end_life_production_stage_id[$index] ?? null,
                        'end_life_quantity' => $request->end_life_quantity[$index] ?? null,
                        'end_life_total' => $request->end_life_total[$index] ?? null,
                    ]);
                }
            }

              // تسجيل اشعار نظام جديد
            ModelsLog::create([
                'type' => 'bom',
                'type_id' => $order->id, // ID النشاط المرتبط
                'type_log' => 'log', // نوع النشاط
                'description' => 'تم اضافة  امر تصنيع **' . $order->name . '**',
                'created_by' => auth()->id(), // ID المستخدم الحالي
            ]);

            // تأكيد حفظ البيانات
            DB::commit();

            return redirect()->route('manufacturing.orders.index')->with(['success'=>'تم حفظ البيانات بنجاح.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('BOM.index')->with(['error'=>'حدث خطأ ما.']);
        }
    }

    public function edit($id){
        $order = ManufacturOrders::find($id);
        $accounts = Account::select('id', 'name')->get();
        $products = Product::select()->get();
        $employees = Employee::select('id', 'first_name','middle_name')->get();
        $clients = Client::select('id','trade_name')->get();
        $paths = ProductionPath::select('id', 'name')->get();
        $production_materials = ProductionMaterials::select()->get();
        $stages = ProductionStage::select('id', 'stage_name')->get();
        $workstations = WorkStations::select('id', 'name')->get();
        return view('manufacturing.orders.edit', compact('order', 'accounts', 'products', 'employees', 'clients', 'paths', 'production_materials', 'stages', 'workstations'));
    }

    public function update(ManufacturOrderRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $order = ManufacturOrders::findOrFail($id);

            $order->update([
                'name' => $request->name,
                'code' => $request->code,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'account_id' => $request->account_id,
                'employee_id' => $request->employee_id,
                'client_id' => $request->client_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'production_material_id' => $request->production_material_id,
                'production_path_id' => $request->production_path_id,
                'last_total_cost' => $request->last_total_cost ?? 0,
                'updated_by' => auth()->id(),
            ]);

            $order->manufacturOrdersItem()->delete();

            if ($request->has('raw_product_id')) {
                foreach ($request->raw_product_id as $index => $rawProductId) {
                    ManufacturOrdersItem::create([
                        'manufactur_order_id' => $order->id,
                        'raw_product_id' => $rawProductId,
                        'raw_production_stage_id' => $request->raw_production_stage_id[$index],
                        'raw_unit_price' => $request->raw_unit_price[$index],
                        'raw_quantity' => $request->raw_quantity[$index],
                        'raw_total' => $request->raw_total[$index],

                        'expenses_account_id' => $request->expenses_account_id[$index] ?? null,
                        'expenses_cost_type' => $request->expenses_cost_type[$index] ?? null,
                        'expenses_production_stage_id' => $request->expenses_production_stage_id[$index] ?? null,
                        'expenses_price' => $request->expenses_price[$index] ?? null,
                        'expenses_description' => $request->expenses_description[$index] ?? null,
                        'expenses_total' => $request->expenses_total[$index] ?? null,

                        'workstation_id' => $request->workstation_id[$index] ?? null,
                        'operating_time' => $request->operating_time[$index] ?? null,
                        'manu_production_stage_id' => $request->manu_production_stage_id[$index] ?? null,
                        'manu_cost_type' => $request->manu_cost_type[$index] ?? null,
                        'manu_total_cost' => $request->manu_total_cost[$index] ?? null,
                        'manu_description' => $request->manu_description[$index] ?? null,
                        'manu_total' => $request->manu_total[$index] ?? null,

                        'end_life_product_id' => $request->end_life_product_id[$index] ?? null,
                        'end_life_unit_price' => $request->end_life_unit_price[$index] ?? null,
                        'end_life_production_stage_id' => $request->end_life_production_stage_id[$index] ?? null,
                        'end_life_quantity' => $request->end_life_quantity[$index] ?? null,
                        'end_life_total' => $request->end_life_total[$index] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('manufacturing.orders.index')->with(['success' => 'تم تحديث البيانات بنجاح.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('manufacturing.orders.index')->with(['error' => 'حدث خطأ أثناء تحديث البيانات.']);
        }
    }

    public function show($id)
    {
        $order = ManufacturOrders::findOrFail($id);
        return view('manufacturing.orders.show', compact('order'));
    }

    public function destroy($id)
    {
        $order = ManufacturOrders::findOrFail($id);
        $order->manufacturOrdersItem()->delete();
        $order->delete();
        return redirect()->route('manufacturing.orders.index')->with(['success' => 'تم حذف البيانات بنجاح.']);
    }

}
