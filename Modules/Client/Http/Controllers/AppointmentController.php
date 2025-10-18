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

    // Get calendar data - جلب المواعيد من جدول appointments
    $calendarAppointmentsQuery = Appointment::with(['client', 'createdBy'])
        ->whereBetween('appointment_date', [
            now()->subMonths(6)->startOfMonth()->format('Y-m-d'),
            now()->addMonths(6)->endOfMonth()->format('Y-m-d')
        ])
        ->orderBy('appointment_date')
        ->orderBy('time')
        ->get();

    // تجميع المواعيد حسب التاريخ بتنسيق YYYY-MM-DD
    $calendarBookings = [];

    foreach ($calendarAppointmentsQuery as $appointment) {
        // تنسيق التاريخ بصيغة YYYY-MM-DD
        $dateKey = date('Y-m-d', strtotime($appointment->appointment_date));

        // إنشاء مصفوفة للتاريخ إذا لم تكن موجودة
        if (!isset($calendarBookings[$dateKey])) {
            $calendarBookings[$dateKey] = [];
        }

        // إضافة بيانات الموعد
        $calendarBookings[$dateKey][] = [
            'id' => $appointment->id,
            'date' => $dateKey,
            'time' => $appointment->time ?? 'غير محدد',
            'client' => $appointment->client ? $appointment->client->trade_name : 'عميل غير محدد',
            'phone' => $appointment->client ? $appointment->client->phone : 'غير متوفر',
            'employee' => $appointment->createdBy ? $appointment->createdBy->name : 'غير محدد',
            'notes' => $appointment->notes ?? '',
            'status' => $this->getStatusClass($appointment->status),
            'status_text' => $this->getStatusText($appointment->status),
            'status_code' => $appointment->status
        ];
    }

    // جلب بيانات FullCalendar
    $fullCalendarEvents = $this->getCalendarData();

    // Debug: Log the data
    Log::info('📅 Calendar Data Debug:', [
        'total_appointments' => $calendarAppointmentsQuery->count(),
        'calendar_bookings_dates' => count($calendarBookings),
        'full_calendar_events' => count($fullCalendarEvents),
        'sample_event' => $fullCalendarEvents[0] ?? null
    ]);

    return view('client::appointments.index', compact(
        'appointments',
        'statuses',
        'employees',
        'clients',
        'calendarBookings',
        'fullCalendarEvents'
    ));
}
/**
 * Get status class for calendar view
 */
protected function getStatusClass($status)
{
    $statusMap = [
        1 => 'pending',      // قيد الانتظار
        2 => 'completed',    // مكتمل
        3 => 'cancelled',    // ملغي
        4 => 'confirmed'     // معاد جدولته
    ];

    return $statusMap[$status] ?? 'pending';
}

protected function getStatusText($status)
{
    $statusMap = [
        1 => 'قيد الانتظار',
        2 => 'مكتمل',
        3 => 'ملغي',
        4 => 'معاد جدلته'
    ];

    return $statusMap[$status] ?? 'غير معروف';
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
            4 => 'معاد جدلته'
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
                case 4: $statusText = 'معاد جدلته'; break;
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
        ->whereBetween('appointment_date', [
            now()->subMonths(1)->startOfMonth()->format('Y-m-d'),
            now()->addMonths(6)->endOfMonth()->format('Y-m-d')
        ])
        ->orderBy('appointment_date')
        ->orderBy('time')
        ->get()
        ->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'title' => ($appointment->client->trade_name ?? 'عميل') . ' - ' . $appointment->time,
                'start' => $appointment->appointment_date,
                'extendedProps' => [
                    'client_name' => $appointment->client->trade_name ?? 'عميل',
                    'client_phone' => $appointment->client->phone ?? 'غير متوفر',
                    'time' => $appointment->time,
                    'status_code' => $appointment->status,
                    'status_text' => $this->getStatusText($appointment->status),
                    'notes' => $appointment->notes ?? 'لا توجد ملاحظات',
                    'employee' => $appointment->createdBy->name ?? 'غير محدد'
                ],
                'className' => 'status-' . $appointment->status,
                'backgroundColor' => $this->getStatusColorCode($appointment->status),
                'borderColor' => $this->getStatusColorCode($appointment->status)
            ];
        })->toArray();

    return $appointments;



}
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
    $appointment->created_by = auth()->user()->id;
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
        'created_by' => auth()->user()->id, // ID المستخدم الحالي
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



/**
 * Get status class for calendar view
 */

    protected function getStatusColor($status)
    {
        $colorMap = [
            1 => 'bg-warning text-dark',    // Pending
            2 => 'bg-success text-white',   // Completed
            3 => 'bg-danger text-white',    // Ignored/Cancelled
            4 => 'bg-info text-white',      // Rescheduled
        ];

        return $colorMap[$status] ?? 'bg-secondary text-white'; // Default
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
        // استخراج التاريخ من حقل البداية بتنسيق YYYY-MM-DD
        $date = substr($appointment['start'], 0, 10);

        if (!isset($bookings[$date])) {
            $bookings[$date] = [];
        }

        // تحديد نوع الحالة
        $statusClass = $this->getStatusClass($appointment['extendedProps']['status_code'] ?? 'pending');

        // إضافة تفاصيل الموعد إلى التاريخ
        $bookings[$date][] = [
            'id' => $appointment['id'],
            'time' => $appointment['extendedProps']['time'],
            'client' => $appointment['extendedProps']['client_name'],
            'phone' => $appointment['extendedProps']['client_phone'],
            'employee' => $appointment['extendedProps']['employee'],
            'notes' => $appointment['extendedProps']['notes'],
            'status' => $statusClass,
            'status_text' => $appointment['extendedProps']['status_text'],
        ];
    }

    return $bookings;
}

    /**
     * Get status class for calendar view
     */

    /**
     * Get color code for status
     */
    protected function getStatusColorCode($status)
    {
        $colorMap = [
            1 => '#ffc107',    // Yellow - Pending
            2 => '#28a745',    // Green - Completed
            3 => '#dc3545',    // Red - Cancelled
            4 => '#17a2b8',    // Cyan - Rescheduled
        ];

        return $colorMap[$status] ?? '#6c757d'; // Gray - Default
    }

    /**
     * Test calendar data
     */
    public function testCalendar()
    {
        // Create some test data
        $testEvents = [
            [
                'id' => 1,
                'title' => 'موعد تجريبي',
                'start' => date('Y-m-d') . 'T10:00:00',
                'extendedProps' => [
                    'client_name' => 'عميل تجريبي',
                    'client_phone' => '123456789',
                    'status_code' => 1,
                    'status_text' => 'قيد الانتظار',
                    'status_id' => 1,
                    'notes' => 'ملاحظات تجريبية',
                    'employee' => 'موظف تجريبي',
                    'time' => '10:00'
                ]
            ]
        ];

        return response()->json($testEvents);
    }
}
