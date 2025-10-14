<?php

if (!function_exists('send_notification_to_employees')) {
    /**
     * Send notification to all employees
     *
     * @param int $userId The user ID who triggered the notification
     * @param array $notificationData The notification data
     * @return bool
     */
    function send_notification_to_employees($userId, $notificationData = [])
    {
        return \Modules\Sales\Http\Controllers\NotificationsController::sendAutomaticNotification($userId, $notificationData);
    }
}

if (!function_exists('send_notification_to_employee')) {
    /**
     * Send notification to a specific employee
     *
     * @param int $userId The user ID who triggered the notification
     * @param int $employeeId The employee ID to send the notification to
     * @param array $notificationData The notification data
     * @return bool
     */
    function send_notification_to_employee($userId, $employeeId, $notificationData = [])
    {
        return \Modules\Sales\Http\Controllers\NotificationsController::sendNotificationToEmployee($userId, $employeeId, $notificationData);
    }
}