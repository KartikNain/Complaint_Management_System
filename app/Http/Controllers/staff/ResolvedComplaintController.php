<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ResolvedComplaintController extends Controller
{
    public function index(Request $request)
    {
        $staffId = auth()->id();

        $query = Complaint::with('user')
            ->where('assigned_to', $staffId)
            ->whereIn('status', ['Resolved', 'Closed']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->latest('updated_at')->paginate(10)->withQueryString();

        $data = compact('complaints');

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('staff.partials.resolved-index', $data);
        }

        return view('staff.resolved-index', $data);
    }
}