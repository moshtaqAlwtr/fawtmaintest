<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Sales\Http\Controllers\NotificationsController;
use App\Models\User;

class NotificationMiddleware
{
    /**
     * Handle an incoming request and send notifications to employees
     * when users with specific roles access certain areas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Check if user is authenticated
        if (auth()->check()) {
            $user = auth()->user();
            
            // Define roles that should trigger notifications
            $triggerRoles = ['client', 'manager'];
            
            // Define areas that should trigger notifications
            $triggerAreas = [
                'clients.personal',
                'clients.invoice_client',
                'clients.appointments_client',
                'clients.SupplyOrders_client',
                'clients.questions_client',
                'clients.profile'
            ];
            
            // Check if user role and current route should trigger a notification
            if (in_array($user->role, $triggerRoles) && 
                in_array(request()->route()->getName(), $triggerAreas)) {
                
                // Send notification to relevant employees
                $this->sendNotificationToEmployees($user);
            }
        }
        
        return $response;
    }
    
    /**
     * Send notification to employees when a user accesses a restricted area
     *
     * @param User $user
     * @return void
     */
    private function sendNotificationToEmployees(User $user)
    {
        // Prepare notification data
        $notificationData = [
            'title' => 'دخول مستخدم جديد',
            'description' => "قام المستخدم {$user->name} بالدخول إلى منطقة محظورة في الوقت " . now()->format('Y-m-d H:i:s'),
            'message' => "قام المستخدم {$user->name} بالدخول إلى منطقة محظورة في الوقت " . now()->format('Y-m-d H:i:s'),
            'type' => 'access_notification',
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'access_time' => now()->format('Y-m-d H:i:s'),
                'route' => request()->route()->getName()
            ]
        ];
        
        // Use the controller method to send notifications
        NotificationsController::sendAutomaticNotification($user->id, $notificationData);
    }
}