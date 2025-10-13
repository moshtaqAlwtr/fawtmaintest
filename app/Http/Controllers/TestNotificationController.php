<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\notifications;

class TestNotificationController extends Controller
{
    public function showTestPage()
    {
        // Get all employees
        $employees = User::where('role', 'employee')->get();
        
        return view('test-notification', compact('employees'));
    }
    
    public function sendTestNotification()
    {
        // Get the currently authenticated user
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        // Send a test notification to all employees
        $notificationData = [
            'title' => 'Test Notification',
            'description' => 'This is a test notification sent by ' . $user->name,
            'message' => 'This is a test notification sent by ' . $user->name,
            'type' => 'test_notification',
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'trigger_time' => now()->format('Y-m-d H:i:s')
            ]
        ];
        
        // Use our helper function to send notification
        send_notification_to_employees($user->id, $notificationData);
        
        return response()->json(['success' => 'Test notification sent successfully']);
    }
    
    public function sendNotificationToSpecificEmployee(Request $request)
    {
        // Get the currently authenticated user
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        // Validate request
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'message' => 'required|string',
        ]);
        
        // Send notification to specific employee
        $notificationData = [
            'title' => $request->title,
            'description' => $request->message,
            'message' => $request->message,
            'type' => 'specific_notification',
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'trigger_time' => now()->format('Y-m-d H:i:s')
            ]
        ];
        
        // Use our helper function to send notification to specific employee
        send_notification_to_employee($user->id, $request->employee_id, $notificationData);
        
        return response()->json(['success' => 'Notification sent to employee successfully']);
    }
}