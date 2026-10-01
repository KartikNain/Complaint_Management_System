<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Models\ComplaintComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;

class ComplaintController extends Controller
{
    // Show the Submit Complaint form
    public function create(Request $request)
    {
        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('employee.partials.submit-complaint');
        }

        return view('employee.submit-complaint');
    }

    // Store the complaint
    public function storeComplaint(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'department'  => 'required|string|max:100',
            'priority'    => 'required|in:Low,Medium,High',
            'description' => 'required|string|min:10',
            'attachment'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // 5MB
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('complaints', 'public');
        }

        $complaint = Complaint::create([
            'user_id'     => Auth::id(),
            'title'       => $validated['title'],
            'category'    => $validated['category'],
            'department'  => $validated['department'],
            'priority'    => $validated['priority'],
            'description' => $validated['description'],
            'attachment'  => $attachmentPath,
            'status'      => 'Pending',
        ]);

        NotificationService::sendToAdmins(
            $complaint->id,
            'complaint_submitted',
            'New Complaint Submitted',
            Auth::user()->name . " submitted a new complaint: \"{$complaint->title}\"."
        );


        return response()->json([

            'status' => true,

            'message' => 'Complaint submitted successfully.',

        ]);
    }



     // List complaints submitted by the logged-in employee
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = Complaint::with(['assignedTo'])
            ->where('user_id', $userId)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $complaints = $query->paginate(10)->withQueryString();

        $counts = [
            'total'       => Complaint::where('user_id', $userId)->count(),
            'pending'     => Complaint::where('user_id', $userId)->where('status', 'Pending')->count(),
            'in_progress' => Complaint::where('user_id', $userId)->where('status', 'In Progress')->count(),
            'resolved'    => Complaint::where('user_id', $userId)->where('status', 'Resolved')->count(),
            'closed'      => Complaint::where('user_id', $userId)->where('status', 'Closed')->count(),
        ];

        $data = compact('complaints', 'counts');

        if ($request->ajax()) {
            return view('employee.partials.complaints-index', $data);
        }

        return view('employee.complaints-index', $data);
    }

    // Show single complaint (view + conversation + accept/reopen)
    public function show(Request $request, Complaint $complaint)
    {
        // Ownership check - employee can only view their own complaint
        if ($complaint->user_id !== Auth::id()) {
            abort(403, 'This complaint does not belong to you.');
        }

        $complaint->load(['assignedTo', 'comments.user']);

        $data = compact('complaint');

        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('employee.partials.complaint-show', $data);
        }

        return view('employee.complaints-show', $data);
    }

    // Add a comment / reply in the conversation section
    public function addComment(Request $request, Complaint $complaint)
    {
        if ($complaint->user_id !== Auth::id()) {
            abort(403, 'This complaint does not belong to you.');
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

        if ($complaint->assigned_to) {
            NotificationService::send(
                $complaint->assigned_to,
                $complaint->id,
                'new_comment',
                'New Reply from Employee',
                "{$comment->user->name} replied on complaint \"{$complaint->title}\"."
            );
        } else {
            NotificationService::sendToAdmins(
                $complaint->id,
                'new_comment',
                'New Reply from Employee',
                "{$comment->user->name} replied on unassigned complaint \"{$complaint->title}\"."
            );
        }

        return response()->json([
            'status'  => true,
            'message' => 'Comment added.',
            'html'    => view('employee.partials.complaint-comment', compact('comment'))->render(),
        ]);
    }

    // Employee decision after resolution: Accept -> Closed, Reopen -> Reopened
    public function resolveDecision(Request $request, Complaint $complaint)
    {
        if ($complaint->user_id !== Auth::id()) {
            abort(403, 'This complaint does not belong to you.');
        }

        $validated = $request->validate([
            'decision' => 'required|in:accept,reopen',
        ]);

        if ($complaint->status !== 'Resolved') {
            return response()->json([
                'status'  => false,
                'message' => 'Only a Resolved complaint can be accepted or reopened.',
            ], 422);
        }

        $newStatus = $validated['decision'] === 'accept' ? 'Closed' : 'Reopened';

        $complaint->update(['status' => $newStatus]);

        if ($complaint->assigned_to) {
            NotificationService::send(
                $complaint->assigned_to,
                $complaint->id,
                $newStatus === 'Closed' ? 'complaint_closed' : 'complaint_reopened',
                $newStatus === 'Closed' ? 'Complaint Closed' : 'Complaint Reopened',
                $newStatus === 'Closed'
                    ? "The employee accepted your resolution on \"{$complaint->title}\"."
                    : "The employee reopened complaint \"{$complaint->title}\"."
            );
        }

        return response()->json([
            'status'     => true,
            'message'    => $newStatus === 'Closed' ? 'Complaint closed. Thank you!' : 'Complaint reopened for further action.',
            'new_status' => $newStatus,
        ]);
    }


}