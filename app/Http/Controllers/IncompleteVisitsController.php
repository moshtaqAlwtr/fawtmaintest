<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeClientVisit;
use App\Models\notifications;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class IncompleteVisitsController extends Controller
{
    /**
     * Show the justification form for incomplete visits.
     *
     * @return \Illuminate\View\View
     */
    public function showJustificationForm()
    {
        $user = Auth::user();

        // Get incomplete visits for the employee that need justification
        // Only for dates BEFORE today
        $incompleteVisits = EmployeeClientVisit::with('client')
            ->where('employee_id', $user->id)
            ->whereDate('created_at', '<', Carbon::today())
            ->needsJustification()
            ->get();

        return view('incomplete_visits.justification', compact('incompleteVisits'));
    }

    /**
     * Process the justification form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitJustification(Request $request)
    {
        $request->validate([
            'justifications' => 'required|array',
            'justifications.*' => 'required|string|max:1000',
        ], [
            'justifications.required' => 'يجب تقديم تبرير لكل زيارة غير مكتملة',
            'justifications.*.required' => 'التبرير مطلوب لكل زيارة غير مكتملة',
            'justifications.*.max' => 'التبرير يجب ألا يتجاوز 1000 حرف',
        ]);

        $user = Auth::user();
        $justificationSubmitted = false;

        try {
            // Process each justification
            foreach ($request->justifications as $visitId => $justification) {
                $visit = EmployeeClientVisit::where('id', $visitId)
                    ->where('employee_id', $user->id)
                    ->whereDate('created_at', '<', Carbon::today())
                    ->needsJustification()
                    ->first();

                if ($visit) {
                    $visit->update([
                        'justification' => $justification,
                        'justification_date' => now(),
                        'justification_approved' => 0, // Pending approval
                    ]);

                    $justificationSubmitted = true;
                }
            }

            // Send notification to managers if justification was submitted
            if ($justificationSubmitted) {
                $managers = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['manager', 'admin']);
                })->get();

                foreach ($managers as $manager) {
                    notifications::create([
                        'user_id' => $manager->id,
                        'type' => 'visit',
                        'title' => 'تبرير زيارة جديد',
                        'message' => 'قام الموظف ' . $user->name . ' بتقديم تبرير لزيارة غير مكتملة.',
                        'read' => false,
                        'data' => [
                            'employee_id' => $user->id,
                            'employee_name' => $user->name,
                        ],
                    ]);
                }
            }

            return redirect()->route('dashboard_sales.index')->with('success', 'تم تقديم التبريرات بنجاح. سيتم مراجعتها من قبل الإدارة.');
        } catch (\Exception $e) {
            Log::error('Error submitting visit justifications: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تقديم التبريرات. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Show all justifications submitted by the employee.
     *
     * @return \Illuminate\View\View
     */
    public function showMyJustifications()
    {
        $user = Auth::user();

        // Get all justifications submitted by the employee
        $justifications = EmployeeClientVisit::with(['client', 'approvedBy'])
            ->where('employee_id', $user->id)
            ->whereNotNull('justification')
            ->where('justification', '!=', '')
            ->orderBy('justification_date', 'desc')
            ->paginate(10);

        return view('incomplete_visits.my_justifications', compact('justifications'));
    }

    /**
     * Show details of a specific justification.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showJustificationDetails($id)
    {
        $user = Auth::user();

        // Get the justification, ensuring it belongs to the employee
        $justification = EmployeeClientVisit::with(['client', 'employee', 'approvedBy'])
            ->where('id', $id)
            ->where('employee_id', $user->id)
            ->firstOrFail();

        return view('incomplete_visits.justification_details', compact('justification'));
    }

    /**
     * Show the form for editing a justification.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function editJustification($id)
    {
        $user = Auth::user();

        // Get the justification, ensuring it belongs to the employee and is still pending
        $justification = EmployeeClientVisit::with('client')
            ->where('id', $id)
            ->where('employee_id', $user->id)
            ->where('justification_approved', 0)
            ->firstOrFail();

        return view('incomplete_visits.edit_justification', compact('justification'));
    }

    /**
     * Update a justification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateJustification(Request $request, $id)
    {
        $request->validate([
            'justification' => 'required|string|max:1000',
        ], [
            'justification.required' => 'التبرير مطلوب',
            'justification.max' => 'التبرير يجب ألا يتجاوز 1000 حرف',
        ]);

        $user = Auth::user();

        try {
            // Get the justification, ensuring it belongs to the employee and is still pending
            $visit = EmployeeClientVisit::where('id', $id)
                ->where('employee_id', $user->id)
                ->where('justification_approved', 0)
                ->firstOrFail();

            $visit->update([
                'justification' => $request->justification,
                'justification_date' => now(), // Update the date to reflect the edit
            ]);

            return redirect()->route('employee.visit-justifications.show', $id)
                ->with('success', 'تم تحديث التبرير بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error updating visit justification: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث التبرير. يرجى المحاولة مرة أخرى.');
        }
    }
}

//