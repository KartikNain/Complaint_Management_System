<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $byStatus = Complaint::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->pluck('total', 'status');

        $byDepartment = Complaint::select('department', DB::raw('count(*) as total'))
            ->groupBy('department')->pluck('total', 'department');

        $byPriority = Complaint::select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')->pluck('total', 'priority');

        // Last 6 months trend
        $monthlyTrend = Complaint::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $data = compact('byStatus', 'byDepartment', 'byPriority', 'monthlyTrend');

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.partials.reports', $data);
        }

        return view('admin.reports-index', $data);
    }
}