<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total'       => Complaint::count(),
            'pending'     => Complaint::where('status', 'Pending')->count(),
            'in_progress' => Complaint::where('status', 'In Progress')->count(),
            'resolved'    => Complaint::where('status', 'Resolved')->count(),
            'closed'      => Complaint::where('status', 'Closed')->count(),
            'users'       => User::count(),
        ];

        $pendingComplaints = Complaint::with('user')
            ->where('status', 'Pending')
            ->latest()
            ->take(5)
            ->get();

        $data = compact('stats', 'pendingComplaints');

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.partials.dashboard', $data);
        }

        return view('dashboards.admin', $data);
    }
}
