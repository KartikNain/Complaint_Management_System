<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class ComplaintController extends Controller
{
    // List all complaints (with filters)
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'assignedTo'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $complaints = $query->paginate(10)->withQueryString();

        // Summary counts for the dashboard cards
        $counts = [
            'total'       => Complaint::count(),
            'pending'     => Complaint::where('status', 'Pending')->count(),
            'in_progress' => Complaint::where('status', 'In Progress')->count(),
            'resolved'    => Complaint::where('status', 'Resolved')->count(),
            'closed'      => Complaint::where('status', 'Closed')->count(),
        ];

        $departments = Complaint::select('department')->distinct()->pluck('department');

        $data = compact('complaints', 'counts', 'departments');

         if ($request->ajax()) {

                return view(
                    'admin.partials.complaints-index',
                    $data
                );

            }

        return view('admin.complaints-index', $data);
    }

    // Show single complaint detail (view/assign/update)
    public function show(Request $request, Complaint $complaint)
    {
        $complaint->load(['user', 'assignedTo']);

        // Staff available to assign - adjust 'role' to match your users table
        $staff = User::where('role', 'staff')->get();

        $data = compact('complaint', 'staff');

        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.partials.complaint-show', $data);
        }

        return view('admin.complaint-show', $data);
    }

    // Assign complaint to a staff member
    public function assign(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $complaint->update([
            'assigned_to' => $validated['assigned_to'],
            'status'      => 'Assigned',
        ]);

        NotificationService::send(
            $validated['assigned_to'],
            $complaint->id,
            'complaint_assigned',
            'New Complaint Assigned',
            "Complaint #{$complaint->id} \"{$complaint->title}\" has been assigned to you."
        );

        NotificationService::send(
            $complaint->user_id,
            $complaint->id,
            'status_updated',
            'Complaint Assigned',
            "Your complaint \"{$complaint->title}\" has been assigned to a staff member."
        );

        return response()->json([
            'status'  => true,
            'message' => 'Complaint assigned successfully.',
        ]);
    }

    // Update status (and resolution summary if resolving)
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Assigned,In Progress,Resolved,Closed,Reopened',
            'resolution_summary' => 'required_if:status,Resolved|nullable|string|min:5',
        ]);

        $complaint->update([
            'status'             => $validated['status'],
            'resolution_summary' => $validated['resolution_summary'] ?? $complaint->resolution_summary,
        ]);

        NotificationService::send(
            $complaint->user_id,
            $complaint->id,
            'status_updated',
            'Complaint Status Updated',
            "Your complaint \"{$complaint->title}\" is now {$validated['status']}."
        );

        return response()->json([
            'status'  => true,
            'message' => 'Complaint status updated successfully.',
        ]);
    }
}