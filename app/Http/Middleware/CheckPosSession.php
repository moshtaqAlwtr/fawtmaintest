<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PosSession;
use Symfony\Component\HttpFoundation\Response;

class CheckPosSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من وجود جلسة نشطة للمستخدم الحالي
        $activeSession = PosSession::active()->forUser(auth()->id())->first();
        
        if (!$activeSession) {
            // إذا كان الطلب Ajax
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد جلسة نشطة. يجب بدء جلسة عمل أولاً.',
                    'redirect' => route('pos.sessions.index'),
                    'error_type' => 'no_active_session'
                ], 403);
            }
            
            // إذا كان طلب عادي
            return redirect()->route('pos.sessions.index')
                ->with('warning', 'يجب بدء جلسة عمل قبل استخدام نقطة البيع');
        }
        
        // إضافة معلومات الجلسة للطلب
        $request->merge(['active_session' => $activeSession]);
        
        return $next($request);
    }
}