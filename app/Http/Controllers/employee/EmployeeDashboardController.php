<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;

class EmployeeDashboardController extends Controller
{
    public function employee()
    {
        $userId = auth()->id();

        $stats = [
            'total'       => Complaint::where('user_id', $userId)->count(),
            'pending'     => Complaint::where('user_id', $userId)->where('status', 'Pending')->count(),
            'in_progress' => Complaint::where('user_id', $userId)->where('status', 'In Progress')->count(),
            'resolved'    => Complaint::where('user_id', $userId)->where('status', 'Resolved')->count(),
            'closed'      => Complaint::where('user_id', $userId)->where('status', 'Closed')->count(),
        ];

        $myComplaints = Complaint::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        $data = compact('stats', 'myComplaints');

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('employee.partials.dashboard', $data);
        }

        return view('dashboards.employee', $data);
    }
}
