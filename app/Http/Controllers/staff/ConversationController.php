<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $staffId = auth()->id();

        $query = Complaint::with(['user', 'comments' => function ($q) {
                $q->latest()->limit(1)->with('user');
            }])
            ->where('assigned_to', $staffId)
            ->whereHas('comments'); // only complaints with at least one message

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        // Order by the latest comment's timestamp, not the complaint's own timestamp
        $complaints = $query->get()
            ->sortByDesc(fn($c) => optional($c->comments->first())->created_at)
            ->values();

        // Manual pagination since we sorted a collection, not a query
        $page = $request->input('page', 1);
        $perPage = 10;
        $paged = new \Illuminate\Pagination\LengthAwarePaginator(
            $complaints->forPage($page, $perPage),
            $complaints->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $data = ['complaints' => $paged];

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('staff.partials.conversations-index', $data);
        }

        return view('staff.conversations-index', $data);
    }
}