<?php


namespace Modules\Client\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Log as ModelsLog;
use App\Http\Requests\AppointmentRequest;
use App\Models\Statuses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
class AppointmentController extends Controller
{
    /**
     * عرض قائمة المواعيد.
     */
  public function index(Request $request)
{
    $query = Appointment::with(['client.status_client', 'createdBy']);

    // البحث حسب العميل
    if ($request->filled('client')) {
        $query->where('client_id', $request->client);
    }

    // البحث حسب حالة الموعد
    if ($request->filled('appointment_status')) {
        $statusMap = [
            'pending' => 1,
            'completed' => 2,
            'cancelled' => 3,
            'rescheduled' => 4
        ];

        if (isset($statusMap[$request->appointment_status])) {
            $query->where('status', $statusMap[$request->appointment_status]);
        }
    }

    // البحث حسب نوع الموعد
    if ($request->filled('appointment_type')) {
        $query->where('action_type', $request->appointment_type);
    }

    // البحث حسب الموظف
    if ($request->filled('employee')) {
        $query->where('created_by', $request->employee);
    }

    // البحث حسب التاريخ من
    if ($request->filled('date_from')) {
        $query->whereDate('appointment_date', '>=', $request->date_from);
    }

    // البحث حسب التاريخ إلى
    if ($request->filled('date_to')) {
        $query->whereDate('appointment_date', '<=', $request->date_to);
    }

    // البحث حسب الأولوية
    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    $appointments = $query->latest('appointment_date')->paginate(10);

    // ✅ إذا كان الطلب AJAX، نرجع فقط الجدول
    if ($request->ajax()) {
        return view('client::appointments.partials.appointments_table', [
            'appointments' => $appointments
        ])->render();
    }

    // ✅ إذا كان طلب عادي، نرجع الصفحة كاملة
    $employees = User::where('role', 'employee')->get();
    $clients = Client::all();
    $statuses = Statuses::all();

    // Get calendar data
    $calendarAppointments = $this->getCalendarData();
    $calendarBookings = $this->formatCalendarBookings($calendarAppointments);

    return view('client::appointments.index', compact(
        'appointments',
        'statuses',
        'employees',
        'clients',
        'calendarAppointments',
        'calendarBookings'
    ));
}

// ✅ دالة تحديث الحالة
public function updateStatus($id, $status)
{
    try {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = $status;
        $appointment->save();

        $statusNames = [
            1 => 'قيد الانتظار',
            2 => 'مكتمل',
            3 => 'ملغي',
            4 => 'معاد جدولته'
        ];

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الحالة إلى: ' . $statusNames[$status]
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث الحالة بنجاح');

    } catch (\Exception $e) {
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()->with('error', 'حدث خطأ أثناء التحديث');
    }
}

    /**
     * Export appointments to Excel
     */
    public function export(Request $request)
    {
        // Get all appointments with filters applied
        $query = Appointment::with(['client', 'createdBy']);

        // Apply the same filters as in index method
        if ($request->filled('client')) {
            $query->where('client_id', $request->client);
        }

        if ($request->filled('appointment_status')) {
            $statusMap = [
                'pending' => 1,
                'completed' => 2,
                'cancelled' => 3,
                'rescheduled' => 4
            ];

            if (isset($statusMap[$request->appointment_status])) {
                $query->where('status', $statusMap[$request->appointment_status]);
            }
        }

        if ($request->filled('appointment_type')) {
            $query->where('action_type', $request->appointment_type);
        }

        if ($request->filled('employee')) {
            $query->where('created_by', $request->employee);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $appointments = $query->get();

        // Create CSV content
        $csvData = [];
        $csvData[] = ['اسم العميل', 'رقم الهاتف', 'التاريخ', 'الوقت', 'الحالة', 'الموظف', 'الملاحظات'];

        foreach ($appointments as $appointment) {
            $statusText = '';
            switch ($appointment->status) {
                case 1: $statusText = 'قيد الانتظار'; break;
                case 2: $statusText = 'مكتمل'; break;
                case 3: $statusText = 'ملغي'; break;
                case 4: $statusText = 'معاد جدولته'; break;
                default: $statusText = 'غير معروف';
            }

            $csvData[] = [
                $appointment->client->trade_name ?? 'غير محدد',
                $appointment->client->phone ?? 'غير محدد',
                $appointment->appointment_date,
                $appointment->time,
                $statusText,
                $appointment->createdBy->name ?? 'غير محدد',
                $appointment->notes ?? ''
            ];
        }

        // Generate CSV file
        $filename = "appointments_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Get appointments for calendar view
     */
    protected function getCalendarData()
    {
        $appointments = Appointment::with(['client', 'createdBy'])
            ->where('appointment_date', '>=', now()->subMonths(3)) // Last 3 months
            ->get()
            ->map(function ($appointment) {
                $statusText = $this->getStatusText($appointment->status);
                $statusColor = $this->getStatusColor($appointment->status);

                return [
                    'id' => $appointment->id,
                    'title' => $appointment->client->trade_name ?? 'عميل',
                    'start' => $appointment->appointment_date . ($appointment->time ? 'T' . $appointment->time : ''),
                    'allDay' => false,
                    'backgroundColor' => $this->getStatusColorCode($appointment->status),
                    'borderColor' => $this->getStatusColorCode($appointment->status),
                    'textColor' => in_array($appointment->status, [Appointment::STATUS_PENDING, Appointment::STATUS_RESCHEDULED]) ? '#000' : '#fff',
                    'extendedProps' => [
                        'client_name' => $appointment->client->trade_name ?? 'غير معروف',
                        'client_phone' => $appointment->client->phone ?? 'غير متوفر',
                        'time' => $appointment->time ?? 'غير محدد',
                        'status' => $statusText,
                        'employee' => $appointment->createdBy->name ?? 'غير معين',
                        'notes' => $appointment->notes ?? 'لا توجد ملاحظات',
                    ]
                ];
            });

        return $appointments;
    }
    /**
     * عرض صفحة إنشاء موعد جديد.
     */
    public function create()
    {
        $clients = Client::all();
        $employees = User::where('role','employee')->get();
        return view('client::appointments.create', compact('clients', 'employees'));
    }

    /**
     * تخزين موعد جديد.
     */
   public function store(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'client_id' => 'required|exists:clients,id',
            'created_by' => 'nullable|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ],
        [
            'client_id.required' => 'يجب اختيار العميل',
            'client_id.exists' => 'العميل غير موجود',
            'created_by.exists' => 'الموظف غير موجود',
            'date.required' => 'يجب إدخال التاريخ',
            'date.date' => 'التاريخ غير صحيح',
            'date.after_or_equal' => 'يجب أن يكون التاريخ اليوم أو في المستقبل',
            'time.required' => 'يجب إدخال الوقت',
            'time.date_format' => 'صيغة الوقت غير صحيحة',
            'notes.max' => 'الملاحظات يجب أن تكون 500 حرف كحد أقصى',
        ],
    );
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $appointment = new Appointment();
    $appointment->client_id = $request->client_id;
    $appointment->appointment_date = $request->date;
    $appointment->time = $request->time;
    $appointment->duration = $request->duration;
    $appointment->notes = $request->notes;
    $appointment->created_by = auth()->id();
    $appointment->action_type = $request->action_type;

    if (!empty($request->recurrence_type)) {
        $appointment->is_recurring = true;
        $appointment->recurrence_type = $request->recurrence_type;
        $appointment->recurrence_date = $request->recurrence_date;
    }

    $appointment->save();

    // تسجيل اشعار نظام جديد
    ModelsLog::create([
        'type' => 'client_appointment',
        'type_id' => $appointment->id, // ID النشاط المرتبط
        'type_log' => 'log', // نوع النشاط
        'description' => 'تم اضافة موعد جديد'. $appointment->client->trade_name,
        'created_by' => auth()->id(), // ID المستخدم الحالي
    ]);

    // التوجيه إلى صفحة عرض العميل بدلاً من قائمة المواعيد
    return redirect()->route('clients.show', $appointment->client_id)->with('success', 'تم إضافة الموعد بنجاح');
}
    /**
     * عرض تفاصيل موعد.
     */
    public function show($id)
    {
        $appointment = Appointment::with(['client', 'employee'])->findOrFail($id);
        $client = Client::findOrFail($appointment->client_id);
        return view('client::appointments.show', compact('appointment', 'client'));
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $clients = Client::all();
        $employees = User::where('role','employee')->get();
        return view('client::appointments.edit', compact('appointment', 'clients', 'employees'));
    }

    /**
     * تحديث بيانات الموعد
     */

    /**
     * عرض صفحة تعديل موعد.
     */

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', [Appointment::STATUS_PENDING, Appointment::STATUS_COMPLETED, Appointment::STATUS_IGNORED, Appointment::STATUS_RESCHEDULED]),
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            log::error('Validation Failed', [
                'errors' => $validator->errors(),
                'input' => $request->all(),
            ]);

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Find the appointment by ID or throw an exception
        $appointment = Appointment::findOrFail($request->id ?? $request->appointment_id);

        $oldStatus = $appointment->status;
        $appointment->status = $request->status;

        // إضافة الملاحظات إذا وجدت
        if ($request->filled('notes')) {
            $appointment->notes = $request->notes;
        }

        $appointment->save();

        log::info('Appointment Updated', [
            'id' => $appointment->id,
            'old_status' => $oldStatus,
            'new_status' => $appointment->status,
        ]);

        return redirect()
            ->route('appointments.index', $appointment->id)
            ->with('success', 'تم تحديث الموعد بنجاح');

        return redirect()
            ->back()
            ->with('error', 'حدث خطأ أثناء تحديث الموعد: ' . $e->getMessage());
    }
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->back()->with('success', 'تم حذف الموعد وجميع البيانات المرتبطة به بنجاح');
    }

    /**
     * Mark appointment as ignored.
     */
    public function ignore($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'ignored';
        $appointment->save();

        return response()->json(['success' => true]);
    }

    /**
     * Mark appointment as completed.
     */
    public function complete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'completed';
        $appointment->save();

        return response()->json(['success' => true]);
    }

    /**
     * Add note to appointment.
     */
    public function addNote(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->notes = $appointment->notes ? $appointment->notes . "\n" . $request->note : $request->note;
        $appointment->save();

        return response()->json(['success' => true]);
    }

    /**
     * تحديث حالة الموعد
     */

// public function updateStatus($id, $status)
// {
//     $appointment = Appointment::findOrFail($id);
//     $appointment->status = $status;
//     $appointment->save();

//     return redirect()->back()->with('success', 'تم تحديث حالة الموعد بنجاح.');
// }

    protected function getStatusText($status)
    {
        return Appointment::$statusArabicMap[$status] ?? 'غير معروف';
    }

    /**
     * الحصول على لون الحالة
     */
    protected function getStatusColor($status)
    {
        return match ($status) {
            Appointment::STATUS_PENDING => 'bg-warning text-dark',
            Appointment::STATUS_COMPLETED => 'bg-success text-white',
            Appointment::STATUS_IGNORED => 'bg-danger text-white',
            Appointment::STATUS_RESCHEDULED => 'bg-info text-white',
            default => 'bg-secondary text-white',
        };
    }

    /**
     * حذف موعد
     */
    public function destroyAppointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => $validator->errors(),
                ],
                400,
            );
        }

        $appointment = Appointment::findOrFail($request->appointment_id);
        $appointment->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الموعد بنجاح',
        ]);
    }

    /**
     * Get appointments for calendar view
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function calendar()
    {
        $appointments = $this->getCalendarData();
        return response()->json($appointments);
    }

    /**
     * Format calendar data for the custom calendar view
     */
    protected function formatCalendarBookings($appointments)
    {
        $bookings = [];
        
        foreach ($appointments as $appointment) {
            $date = substr($appointment['start'], 0, 10); // Extract date part
            
            if (!isset($bookings[$date])) {
                $bookings[$date] = [];
            }
            
            $bookings[$date][] = [
                'client' => $appointment['extendedProps']['client_name'] ?? 'عميل',
                'time' => $appointment['extendedProps']['time'] ?? '',
                'status' => $this->getStatusClass($appointment['extendedProps']['status'] ?? ''),
                'product' => [
                    'name' => $appointment['extendedProps']['notes'] ?? ''
                ]
            ];
        }
        
        return $bookings;
    }
    
    /**
     * Get status class for calendar view
     */
    protected function getStatusClass($status)
    {
        $statusMap = [
            'قيد الانتظار' => 'pending',
            'مكتمل' => 'completed',
            'ملغي' => 'cancelled',
            'معاد جدولته' => 'confirmed'
        ];
        
        return $statusMap[$status] ?? 'pending';
    }
    
    /**
     * Get color code for status
     */
    protected function getStatusColorCode($status)
    {
        return match ($status) {
            1 => '#ffc107',    // Yellow - Pending
            2 => '#28a745',    // Green - Completed
            3 => '#dc3545',    // Red - Cancelled
            4 => '#17a2b8',    // Cyan - Rescheduled
            default => '#6c757d', // Gray - Default
        };
    }
}