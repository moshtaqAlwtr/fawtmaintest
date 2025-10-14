<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeClientVisit;
use App\Models\notifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VisitJustificationController extends Controller
{
    /**
     * Display a listing of visit justifications needing approval.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all visit justifications that need approval
        $justifications = EmployeeClientVisit::with(['employee', 'client'])
            ->whereNotNull('justification')
            ->where('justification', '!=', '')
            ->where('justification_approved', 0)
            ->orderBy('justification_date', 'desc')
            ->get();

        return view('admin.visit_justifications.index', compact('justifications'));
    }

    /**
     * Approve a visit justification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, $id)
    {
        try {
            $visit = EmployeeClientVisit::findOrFail($id);

            $visit->update([
                'justification_approved' => 1,
                'approved_by' => Auth::id(),
            ]);

            // Send notification to the employee
            notifications::create([
                'user_id' => $visit->employee_id,
                'type' => 'visit',
                'title' => 'تمت الموافقة على التبرير',
                'message' => 'تمت الموافقة على تبريرك لزيارة العميل ' . ($visit->client->trade_name ?? 'غير محدد'),
                'read' => false,
                'data' => [
                    'visit_id' => $visit->id,
                    'client_name' => $visit->client->trade_name ?? 'غير محدد',
                ],
            ]);

            return redirect()->back()->with('success', 'تمت الموافقة على التبرير بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error approving visit justification: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء الموافقة على التبرير. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Reject a visit justification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        try {
            $visit = EmployeeClientVisit::findOrFail($id);

            $visit->update([
                'justification_approved' => 2, // 0 = pending, 1 = approved, 2 = rejected
                'approved_by' => Auth::id(),
            ]);

            // Send notification to the employee
            notifications::create([
                'user_id' => $visit->employee_id,
                'type' => 'visit',
                'title' => 'تم رفض التبرير',
                'message' => 'تم رفض تبريرك لزيارة العميل ' . ($visit->client->trade_name ?? 'غير محدد') . '. يرجى تعديله.',
                'read' => false,
                'data' => [
                    'visit_id' => $visit->id,
                    'client_name' => $visit->client->trade_name ?? 'غير محدد',
                ],
            ]);

            return redirect()->back()->with('success', 'تم رفض التبرير بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error rejecting visit justification: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء رفض التبرير. يرجى المحاولة مرة أخرى.');
        }
    }
}