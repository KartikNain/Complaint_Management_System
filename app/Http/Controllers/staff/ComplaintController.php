<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $staffId = Auth::id();
 
        $query = Complaint::with(['user', 'assignedTo'])
            ->where('assigned_to', $staffId)
            ->latest();
 
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
 
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
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
 
        // Summary counts for dashboard cards (scoped to this staff member)
        $counts = [
            'assigned'    => Complaint::where('assigned_to', $staffId)->where('status', 'Assigned')->count(),
            'in_progress' => Complaint::where('assigned_to', $staffId)->where('status', 'In Progress')->count(),
            'resolved'    => Complaint::where('assigned_to', $staffId)->where('status', 'Resolved')->count(),
            'reopened'    => Complaint::where('assigned_to', $staffId)->where('status', 'Reopened')->count(),
        ];
 
        $data = compact('complaints', 'counts');
 
        if ($request->ajax()) {
            return view('staff.partials.complaints-index', $data);
        }
 
        return view('staff.complaints-index', $data);
    }
 
    // Show single complaint (review, status update, conversation)
    public function show(Request $request, Complaint $complaint)
    {
        // Ownership check - a staff member can only open their own assigned complaints
        if ($complaint->assigned_to !== Auth::id()) {
            abort(403, 'This complaint is not assigned to you.');
        }
 
        // Auto move Assigned -> In Progress the first time staff opens it
        if ($complaint->status === 'Assigned') {
            $complaint->update(['status' => 'In Progress']);
        }
 
        $complaint->load(['user', 'assignedTo', 'comments.user']);
 
        $data = compact('complaint');
 
        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('staff.partials.complaint-show', $data);
        }
 
        return view('staff.complaints-show', $data);
    }
 
    // Update status (In Progress / Resolved) - resolution_summary required when resolving
    public function updateStatus(Request $request, Complaint $complaint)
    {
        if ($complaint->assigned_to !== Auth::id()) {
            abort(403, 'This complaint is not assigned to you.');
        }

        $validated = $request->validate([
            'status'             => 'required|in:In Progress,Resolved',
            'resolution_summary' => 'required_if:status,Resolved|nullable|string|min:5',
        ]);

        $complaint->update([
            'status'             => $validated['status'],
            'resolution_summary' => $validated['resolution_summary'] ?? $complaint->resolution_summary,
        ]);

        NotificationService::send(
            $complaint->user_id,
            $complaint->id,
            $validated['status'] === 'Resolved' ? 'complaint_resolved' : 'status_updated',
            $validated['status'] === 'Resolved' ? 'Complaint Resolved' : 'Complaint Status Updated',
            $validated['status'] === 'Resolved'
                ? "Your complaint \"{$complaint->title}\" has been resolved. Please review the resolution."
                : "Your complaint \"{$complaint->title}\" is now In Progress."
        );

        return response()->json([
            'status'  => true,
            'message' => 'Complaint status updated successfully.',
            'new_status' => $complaint->status,
        ]);
    }
 
    // Add a comment / reply in the conversation section
    public function addComment(Request $request, Complaint $complaint)
    {
        if ($complaint->assigned_to !== Auth::id()) {
            abort(403, 'This complaint is not assigned to you.');
        }
 
        $validated = $request->validate([
            'comment'    => 'required|string|max:2000',
            'attachment' => 'nullable|file|max:5120',
        ]);
 
        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('complaint_attachments', 'public');
        }
 
        $comment = ComplaintComment::create([
            'complaint_id' => $complaint->id,
            'user_id'      => Auth::id(),
            'comment'      => $validated['comment'],
            'attachment'   => $path,
        ]);
 
        $comment->load('user');
        NotificationService::send(
            $complaint->user_id,
            $complaint->id,
            'new_comment',
            'New Reply on Your Complaint',
            "{$comment->user->name} replied on complaint \"{$complaint->title}\"."
        );
        
 
        // TODO: notify the user (submitter) that a new comment was added
 
        return response()->json([
            'status'  => true,
            'message' => 'Comment added.',
            'html'    => view('staff.partials.complaint-comment', compact('comment'))->render(),
        ]);
    }
}
