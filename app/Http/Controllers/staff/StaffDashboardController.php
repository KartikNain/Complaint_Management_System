<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
class StaffDashboardController extends Controller
{
    public function dashboard()
    {
        $staffId = auth()->id();

        $stats = [
            'assigned'         => Complaint::where('assigned_to', $staffId)->count(),
            'awaiting_review'  => Complaint::where('assigned_to', $staffId)->where('status', 'Assigned')->count(),
            'in_progress'      => Complaint::where('assigned_to', $staffId)->where('status', 'In Progress')->count(),
            'resolved'         => Complaint::where('assigned_to', $staffId)->where('status', 'Resolved')->count(),
        ];

        $assignedComplaints = Complaint::with('user')
            ->where('assigned_to', $staffId)
            ->latest()
            ->take(5)
            ->get();

        $data = compact('stats', 'assignedComplaints');

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('staff.partials.dashboard', $data);
        }

        return view('dashboards.staff', $data);
    }
}
