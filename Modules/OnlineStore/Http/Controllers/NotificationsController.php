<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\notifications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationsController extends Controller
{
    // عرض جميع الإشعارات
public function notifications(Request $request)
{
    $user = auth()->user();

    $query = notifications::with(['user', 'receiver'])
        ->orderBy('created_at', 'desc');

    // إذا المستخدم موظف، نعرض له إشعاراته فقط
    if ($user->role === 'employee') {
        $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        });
    }

    // فلتر حسب المرسل
    if ($request->has('user_id') && $request->user_id != '') {
        $query->where('user_id', $request->user_id);
    }

    // فلتر حسب الموظف المستلم
    if ($request->has('receiver_id') && $request->receiver_id != '') {
        $query->where('receiver_id', $request->receiver_id);
    }

    // فلتر حسب التاريخ من
    if ($request->has('date_from') && $request->date_from != '') {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    // فلتر حسب التاريخ إلى
    if ($request->has('date_to') && $request->date_to != '') {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    $notifications = $query->paginate(100);
    $users = User::where('role', 'employee')->get();

    // إذا كان الطلب AJAX
    if ($request->ajax() || $request->has('ajax')) {
        // إضافة معلومات إضافية للإشعارات
        $notificationsData = $notifications->map(function($notification) use ($user) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'description' => $notification->description,
                'type' => $notification->type,
                'read' => $notification->read,
                'created_at_human' => $notification->created_at->diffForHumans(),
                'user' => $notification->user ? [
                    'id' => $notification->user->id,
                    'name' => $notification->user->name,
                ] : null,
                'receiver' => $notification->receiver ? [
                    'id' => $notification->receiver->id,
                    'name' => $notification->receiver->name,
                ] : null,
                'receiver_id' => $notification->receiver_id,
                'current_user_id' => $user->id,
            ];
        });

        return response()->json([
            'success' => true,
            'notifications' => [
                'data' => $notificationsData,
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
            'pagination' => $notifications->appends($request->except('page'))->links()->render()
        ]);
    }

    return view('notifications.index', compact('notifications', 'users'));
}
    // عرض نموذج إرسال إشعار جديد
    public function create()
    {
        $employees = User::where('role', 'employee')->get();
        return view('notifications.create', compact('employees'));
    }

    // إرسال إشعار إلى موظفين
    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'send_to' => 'required|in:all,specific',
            'employees' => 'array|nullable',
            'notification_type' => 'string|nullable'
        ]);

        $user = auth()->user();
        $sentCount = 0;

        if ($request->send_to === 'all') {
            // إرسال إلى جميع الموظفين
            $employees = User::where('role', 'employee')->get();

            foreach ($employees as $employee) {
                notifications::create([
                    'user_id' => $user->id,
                    'receiver_id' => $employee->id,
                    'title' => $request->title,
                    'description' => $request->description,

                    'type' => $request->notification_type ?? 'general',
                    'read' => 0,

                ]);
                $sentCount++;
            }
        } else {
            // إرسال إلى موظفين محددين
            if (!$request->employees || count($request->employees) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'يرجى اختيار موظف واحد على الأقل'
                ], 400);
            }

            foreach ($request->employees as $employeeId) {
                $employee = User::find($employeeId);
                if ($employee && $employee->role === 'employee') {
                    notifications::create([
                        'user_id' => $user->id,
                        'receiver_id' => $employee->id,
                        'title' => $request->title,
                        'description' => $request->description,

                        'type' => $request->notification_type ?? 'general',
                        'read' => 0,

                    ]);
                    $sentCount++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "تم إرسال الإشعار إلى $sentCount موظف بنجاح"
        ]);
    }

    // جلب الإشعارات الجديدة فقط (AJAX)
 // في NotificationsController.php - تحديث دالة getUnreadNotifications

// public function getUnreadNotifications()
// {
//     $user = auth()->user();

//     Log::info('Fetching unread notifications for user: ' . $user->id);

//     $query = notifications::with(['user', 'receiver'])
//         ->where('read', 0)
//         ->orderBy('created_at', 'desc');

//     if ($user->role === 'employee') {
//         $query->where(function ($q) use ($user) {
//             $q->where('user_id', $user->id)
//                 ->orWhere('receiver_id', $user->id);
//         });
//     }

//     $notifications = $query->get();

//     Log::info('Found ' . $notifications->count() . ' unread notifications');

//     $response = [
//         'success' => true, // ⭐ مهم جداً
//         'count' => $notifications->count(),
//         'notifications' => $notifications->map(function ($notification) use ($user) {
//             return [
//                 'id' => $notification->id,
//                 'title' => $notification->title,
//                 'description' => $notification->description,

//                 // معلومات المرسل
//                 'sender' => $notification->user ? $notification->user->name : 'النظام',
//                 'sender_id' => $notification->user_id,

//                 // معلومات المستقبل
//                 'receiver' => $notification->receiver ? $notification->receiver->name : 'الجميع',
//                 'receiver_id' => $notification->receiver_id,

//             'created_at' => $notification->created_at
//     ? $notification->created_at->toIso8601String()
//     : now()->toIso8601String(),
//                 'type' => $notification->type ?? 'general',

//                 // تحديد إذا كان المستخدم الحالي يمكنه الرد
//                 'can_reply' => $notification->receiver_id == $user->id && $notification->user_id != $user->id,

//                 // تحديد علاقة المستخدم بالإشعار
//                 'is_sender' => $notification->user_id == $user->id,
//                 'is_receiver' => $notification->receiver_id == $user->id,
//             ];
//         })
//     ];

//     return response()->json($response);
// }    // وضع علامة مقروء على إشعار واحد
    public function markAsReadid($id)
    {
        $user = auth()->user();

        $notification = notifications::where('id', $id)
            ->where(function ($query) use ($user) {
                if ($user->role === 'employee') {
                    $query->where('user_id', $user->id)
                        ->orWhere('receiver_id', $user->id);
                }
            })
            ->first();

        if ($notification) {
            $notification->read = 1;
            $notification->save();
        }

        return response()->json(['success' => true]);
    }

    // وضع علامة مقروء على إشعار واحد (POST method)
    public function markAsRead(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:notifications,id'
        ]);

        $user = auth()->user();

        $notification = notifications::where('id', $request->id)
            ->where(function ($query) use ($user) {
                if ($user->role === 'employee') {
                    $query->where('user_id', $user->id)
                        ->orWhere('receiver_id', $user->id);
                }
            })
            ->first();

        if ($notification) {
            $notification->read = 1;
            $notification->save();
        }

        return response()->json(['success' => true]);
    }

    // وضع علامة مقروء على جميع الإشعارات
    public function markAllAsRead()
    {
        $user = auth()->user();

        $query = notifications::where('read', 0);

        if ($user->role === 'employee') {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            });
        }

        $query->update(['read' => 1]);

        return response()->json(['success' => true]);
    }

    // الرد على الإشعار
    public function replyToNotification(Request $request, $id)
{
    $request->validate([
        'reply_message' => 'required|string|max:500'
    ]);

    $user = auth()->user();

    // السماح بالرد لأي مستخدم شارك في الإشعار (المرسل أو المستقبل)
    $originalNotification = notifications::where('id', $id)
        ->where(function ($query) use ($user) {
            $query->where('receiver_id', $user->id)
                  ->orWhere('user_id', $user->id);
        })
        ->first();

    if (!$originalNotification) {
        return response()->json([
            'success' => false,
            'message' => 'لا يمكنك الرد على هذا الإشعار'
        ], 403);
    }

    // تحديد المستقبل للرد (الشخص الآخر)
    $receiverId = $originalNotification->receiver_id == $user->id
        ? $originalNotification->user_id
        : $originalNotification->receiver_id;

    // إنشاء إشعار جديد كرد
    $replyNotification = notifications::create([
        'user_id' => $user->id,
        'receiver_id' => $receiverId,
        'title' => 'رد على: ' . $originalNotification->title,
        'description' => $request->reply_message,
        'type' => 'reply',
        'read' => 0,
    ]);

    // وضع علامة مقروء على الإشعار الأصلي
    $originalNotification->update(['read' => 1]);

    return response()->json([
        'success' => true,
        'message' => 'تم إرسال الرد بنجاح',
        'notification' => [
            'id' => $replyNotification->id,
            'title' => $replyNotification->title,
            'description' => $replyNotification->description
        ]
    ]);
}


    // عرض تفاصيل الإشعار مع السلسلة الكاملة للردود
    public function show($id)
    {
        $user = auth()->user();

        $notification = notifications::with(['user', 'receiver'])
            ->where('id', $id)
            ->where(function ($query) use ($user) {
                if ($user->role === 'employee') {
                    $query->where('user_id', $user->id)
                        ->orWhere('receiver_id', $user->id);
                }
            })
            ->first();

        // If notification not found, return 404
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'الإشعار غير موجود'
            ], 404);
        }

        // جلب جميع الردود المرتبطة
        $replies = notifications::with(['user', 'receiver'])
            ->where('type', 'reply')
            ->orderBy('created_at', 'asc')
            ->get();

        // وضع علامة مقروء
        if ($notification->receiver_id == $user->id && $notification->read == 0) {
            $notification->update(['read' => 1]);
        }

        // إذا كان AJAX request، أرجع HTML
        if (request()->ajax()) {
            $html = view('notifications.partials.notification-detail', compact('notification', 'replies'))->render();
            return response()->json([
                'success' => true,
                'notification' => [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'description' => $notification->description,
                    'user' => $notification->user,
                    'receiver' => $notification->receiver,
                    'created_at' => $notification->created_at,
                    'read' => $notification->read,
                    'type' => $notification->type,
                ],
                'html' => $html
            ]);
        }

        return view('notifications.show', compact('notification', 'replies'));
    }

    // حذف إشعار
    public function destroy($id)
    {
        $user = auth()->user();

        $notification = notifications::where('id', $id)
            ->where(function ($query) use ($user) {
                if ($user->role === 'employee') {
                    $query->where('user_id', $user->id)
                        ->orWhere('receiver_id', $user->id);
                }
            })
            ->first();

        if ($notification) {
            // حذف جميع الردود المرتبطة
            notifications::where('type', 'reply')
                ->where('data->original_notification_id', $id)
                ->delete();

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الإشعار بنجاح'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'الإشعار غير موجود'
        ], 404);
    }

    // Method to send automatic notifications to employees
    public static function sendAutomaticNotification($userId, $notificationData = [])
    {
        $user = User::find($userId);

        if (!$user) {
            return false;
        }

        // Get all employees
        $employees = User::where('role', 'employee')->get();

        // Create a notification for each employee
        foreach ($employees as $employee) {
            notifications::create([
                'user_id' => $userId,
                'receiver_id' => $employee->id,
                'title' => $notificationData['title'] ?? 'تنبيه تلقائي',
                'description' => $notificationData['description'] ?? "قام المستخدم {$user->name} بتنفيذ إجراء في الوقت " . now()->format('Y-m-d H:i:s'),

                'read' => 0,
                'type' => $notificationData['type'] ?? 'automatic_notification',

            ]);
        }

        return true;
    }

    // Method to send notification to specific employee
    public static function sendNotificationToEmployee($userId, $employeeId, $notificationData = [])
    {
        $user = User::find($userId);
        $employee = User::find($employeeId);

        if (!$user || !$employee) {
            return false;
        }

        notifications::create([
            'user_id' => $userId,
            'receiver_id' => $employeeId,
            'title' => $notificationData['title'] ?? 'تنبيه',
            'description' => $notificationData['description'] ?? "قام المستخدم {$user->name} بتنفيذ إجراء في الوقت " . now()->format('Y-m-d H:i:s'),

            'read' => 0,
            'type' => $notificationData['type'] ?? 'notification',

        ]);

        return true;
    }

    // Get unread notifications count


    // عرض الإشعارات المرسلة
    public function sentNotifications(Request $request)
    {
        $user = auth()->user();

        $query = notifications::with(['user', 'receiver'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // فلتر البحث
        if ($request->has('receiver_id') && $request->receiver_id != '') {
            $query->where('receiver_id', $request->receiver_id);
        }

        $notifications = $query->paginate(100);
        $employees = User::where('role', 'employee')->get();

        return view('notifications.sent', compact('notifications', 'employees'));
    }

    // الرد على الإشعار (عرض نموذج الرد)
    public function respondToNotification($id)
    {
        $user = auth()->user();

        $notification = notifications::with(['user', 'receiver'])
            ->where('id', $id)
            ->where('receiver_id', $user->id)
            ->first();

        if (!$notification) {
            return redirect()->route('notifications.index')->with('error', 'الإشعار غير موجود أو ليس موجهاً لك');
        }

        return view('notifications.respond', compact('notification'));
    }


// في NotificationsController.php
// استبدل دالة getUnreadNotifications بهذا الكود:

public function getUnreadNotifications()
{
    $user = auth()->user();

    Log::info('Fetching unread notifications for user: ' . $user->id);

    $query = notifications::with(['user', 'receiver'])
        ->where('read', 0)
        // ⭐ إضافة شرط 24 ساعة فقط
        ->where('created_at', '>=', now()->subHours(24))
        ->orderBy('created_at', 'desc');

    if ($user->role === 'employee') {
        $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        });
    }

    $notifications = $query->get();

    Log::info('Found ' . $notifications->count() . ' unread notifications (last 24 hours)');

    $response = [
        'success' => true,
        'count' => $notifications->count(),
        'notifications' => $notifications->map(function ($notification) use ($user) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'description' => $notification->description,

                // معلومات المرسل
                'sender' => $notification->user ? $notification->user->name : 'النظام',
                'sender_id' => $notification->user_id,

                // معلومات المستقبل
                'receiver' => $notification->receiver ? $notification->receiver->name : 'الجميع',
                'receiver_id' => $notification->receiver_id,

                'created_at' => $notification->created_at
                    ? $notification->created_at->toIso8601String()
                    : now()->toIso8601String(),
                'type' => $notification->type ?? 'general',

                // ⭐ الآن جميع المستخدمين يمكنهم الرد
                'can_reply' => true,

                // تحديد علاقة المستخدم بالإشعار
                'is_sender' => $notification->user_id == $user->id,
                'is_receiver' => $notification->receiver_id == $user->id,
            ];
        })
    ];

    return response()->json($response);
}

// ⭐ إضافة دالة جديدة لحساب عدد الإشعارات غير المقروءة خلال 24 ساعة
public function getUnreadCount()
{
    $user = auth()->user();

    $query = notifications::where('read', 0)
        ->where('created_at', '>=', now()->subHours(24)); // فقط آخر 24 ساعة

    if ($user->role === 'employee') {
        $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        });
    }

    $count = $query->count();

    return response()->json([
        'success' => true,
        'count' => $count
    ]);
}
}
