<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Issue;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('issues')
            ->join('users', 'issues.user_id', '=', 'users.id')
            ->join('barangays', 'issues.barangay_id', '=', 'barangays.id')
            ->join('categories', 'issues.category_id', '=', 'categories.id')
            ->join('departments', 'categories.department_id', '=', 'departments.id')
            ->select(
                'issues.id',
                'issues.title',
                'issues.status',
                'issues.specific_location',
                'issues.created_at',
                'users.name AS citizen_name',
                'barangays.name AS barangay_name',
                'categories.name AS category_name',
                'departments.name AS department_name'
            );

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('issues.title', 'like', '%' . $request->search . '%')
                    ->orWhere('issues.description', 'like', '%' . $request->search . '%')
                    ->orWhere('users.name', 'like', '%' . $request->search . '%')
                    ->orWhere('barangays.name', 'like', '%' . $request->search . '%')
                    ->orWhere('categories.name', 'like', '%' . $request->search . '%')
                    ->orWhere('departments.name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('issues.status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('issues.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('issues.created_at', '<=', $request->date_to);
        }

        $resultCount = (clone $query)->count();
        $issues = $query->orderByDesc('issues.created_at')->paginate(10)->withQueryString();

        return view('admin.issues.index', compact('issues', 'resultCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.issues.index')
            ->with('error', 'Admins do not create citizen issues. Citizens submit issues from their own account.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssueRequest $request)
    {
        return redirect()->route('admin.issues.index')
            ->with('error', 'Admins do not create citizen issues. Citizens submit issues from their own account.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        return redirect()->route('admin.issues.edit');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $issue = $this->findIssueWithDetails($id);

        abort_if(! $issue, 404);

        $statusLogs = $this->statusLogsForIssue($id);
        $statuses = ['reported', 'verified', 'in_progress', 'completed'];

        return view('admin.issues.edit', compact('issue', 'statusLogs', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIssueRequest $request, string $id)
    {
        $validated = $request->validated();
        $issue = DB::table('issues')->find($id);

        abort_if(! $issue, 404);

        DB::transaction(function () use ($id, $issue, $validated) {
            $newStatus = $validated['status'];

            // Admins may update workflow status, but not the citizen's submitted issue details.
            DB::table('issues')
                ->where('id', $id)
                ->update([
                    'status' => $newStatus,
                    'resolved_at' => $newStatus === 'completed'
                        ? ($validated['resolved_at'] ?? now())
                        : null,
                    'updated_at' => now(),
                ]);

            if ($issue->status !== $newStatus || ! empty($validated['remarks'])) {
                DB::table('status_logs')->insert([
                    'issue_id' => $id,
                    'changed_by' => Auth::id(),
                    'old_status' => $issue->status,
                    'new_status' => $newStatus,
                    'remarks' => $validated['remarks'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return redirect()->route('admin.issues.index')
            ->with('success', 'Issue status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        if ($issue->status !== 'reported') {
            return redirect()
                ->route('admin.issues.index')
                ->with('error', 'Only issues with the "Reported" status can be deleted.');
        }

        try {
            $issue->delete();

            return redirect()
                ->route('admin.issues.index')
                ->with('success', 'Issue deleted successfully.');
        } catch (QueryException) {
            return redirect()
                ->route('admin.issues.index')
                ->with('error', 'Issue cannot be deleted at this time.');
        }
    }

    private function findIssueWithDetails(string $id): ?object
    {
        return DB::table('issues')
            ->join('users', 'issues.user_id', '=', 'users.id')
            ->join('barangays', 'issues.barangay_id', '=', 'barangays.id')
            ->join('categories', 'issues.category_id', '=', 'categories.id')
            ->join('departments', 'categories.department_id', '=', 'departments.id')
            ->select(
                'issues.*',
                'users.name AS citizen_name',
                'barangays.name AS barangay_name',
                'categories.name AS category_name',
                'departments.name AS department_name'
            )
            ->where('issues.id', $id)
            ->first();
    }

    private function statusLogsForIssue(string $issueId)
    {
        return DB::table('status_logs')
            ->join('users', 'status_logs.changed_by', '=', 'users.id')
            ->select('status_logs.*', 'users.name AS changed_by_name')
            ->where('status_logs.issue_id', $issueId)
            ->orderBy('status_logs.created_at')
            ->get();
    }
}
