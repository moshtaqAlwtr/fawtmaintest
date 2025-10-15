<?php
namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getLogsData($request);
        }
        return view('Log.index');
    }

    private function getLogsData(Request $request)
    {
        $search = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $page = $request->input('page', 1);
        $perPage = 50;

        $query = Log::where('type_log', 'log')
            ->with(['user', 'user.branch'])
            ->when($search, function ($query) use ($search) {
                return $query->where('description', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            })
            ->when($fromDate, function ($query) use ($fromDate) {
                return $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
            })
            ->when($toDate, function ($query) use ($toDate) {
                return $query->whereDate('created_at', '<=', Carbon::parse($toDate));
            })
            ->orderBy('created_at', 'desc');

        $logs = $query->paginate($perPage, ['*'], 'page', $page);

        // تجميع البيانات حسب التاريخ
        $groupedLogs = $logs->getCollection()->filter(function ($log) {
            return !is_null($log) && !is_bool($log);
        })->groupBy(function ($log) {
            return optional($log->created_at)->format('Y-m-d');
        });

        return response()->json([
            'success' => true,
            'data' => $groupedLogs,
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
                'from' => $logs->firstItem(),
                'to' => $logs->lastItem(),
                'has_more_pages' => $logs->hasMorePages(),
                'has_previous_pages' => $logs->currentPage() > 1,
                'first_page_url' => $logs->url(1),
                'last_page_url' => $logs->url($logs->lastPage()),
                'prev_page_url' => $logs->previousPageUrl(),
                'next_page_url' => $logs->nextPageUrl(),
            ]
        ]);
    }
}