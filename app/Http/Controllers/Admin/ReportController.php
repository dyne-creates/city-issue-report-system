<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    // Available issue statuses used for report filtering and status chart generation.
    protected array $statusList = ['reported', 'verified', 'in_progress', 'completed'];

    public function index(Request $request)
    {
        // Load filter dropdown options for the report page.
        $barangays   = DB::table('barangays')->orderBy('name')->get();
        $departments = DB::table('departments')->orderBy('name')->get();

        // Get the earliest year with records to dynamically generate year filters.
        $minYear = DB::table('issues')->min(DB::raw('YEAR(created_at)')) ?? now()->year;
        $years   = range((int) now()->year, (int) $minYear);

        // Build the base query using all active filters.
        // This query is cloned later so different report sections do not affect each other.
        $baseQuery = $this->buildFilteredQuery($request);

        // Retrieve filtered issue records for the data table.
        $issues = (clone $baseQuery)
            ->select(
                'issues.id',
                'issues.title',
                'issues.status',
                'issues.created_at',
                'issues.resolved_at',
                'barangays.name as barangay_name',
                'categories.name as category_name',
                'departments.name as department_name',
                'users.name as citizen_name',
                'feedbacks.rating as feedback_rating'
            )
            ->orderByDesc('issues.created_at')
            ->paginate(15)
            ->withQueryString();

        // Count issues grouped by status for the status distribution chart.
        $statusCounts = (clone $baseQuery)
            ->select('issues.status', DB::raw('COUNT(DISTINCT issues.id) as total'))
            ->groupBy('issues.status')
            ->pluck('total', 'issues.status');

        // Convert status values into readable labels for charts.
        $rawStatusLabels = $this->statusList;

        $statusLabels = array_map(function ($status) {
            return ucwords(str_replace('_', ' ', $status));
        }, $rawStatusLabels);

        // Prepare chart data while keeping status order consistent.
        $statusData = [];

        foreach ($rawStatusLabels as $status) {
            $statusData[] = (int) ($statusCounts[$status] ?? 0);
        }

        // Calculate average citizen feedback rating per department.
        // Only issues with submitted feedback ratings are included.
        $deptRatings = (clone $baseQuery)
            ->whereNotNull('feedbacks.rating')
            ->select(
                'departments.name as department_name',
                DB::raw('ROUND(AVG(feedbacks.rating), 2) as avg_rating')
            )
            ->groupBy('departments.name')
            ->orderBy('departments.name')
            ->get();

        $deptLabels = $deptRatings->pluck('department_name');
        $deptData   = $deptRatings->pluck('avg_rating');

        return view('admin.reports.index', compact(
            'barangays',
            'departments',
            'years',
            'issues',
            'statusLabels',
            'statusData',
            'deptLabels',
            'deptData'
        ));
    }

    /**
     * Generates a PDF version of the filtered report.
     * Uses the same filters applied in the report dashboard.
     */
    public function exportPdf(Request $request)
    {
        // Reuse the same filtering logic used by the dashboard.
        $baseQuery = $this->buildFilteredQuery($request);

        // Retrieve all matching records for PDF generation.
        // Pagination is not used because PDF reports should contain the complete result.
        $issues = (clone $baseQuery)
            ->select(
                'issues.id',
                'issues.title',
                'issues.status',
                'issues.created_at',
                'issues.resolved_at',
                'barangays.name as barangay_name',
                'categories.name as category_name',
                'departments.name as department_name',
                'users.name as citizen_name',
                'feedbacks.rating as feedback_rating'
            )
            ->orderByDesc('issues.created_at')
            ->get();

        // Information displayed in the generated PDF header.
        $generatedAt     = now()->format('F d, Y h:i A');
        $filtersSummary  = $this->buildFiltersSummary($request);

        // Render the PDF template into HTML.
        $html = view('admin.reports.pdf', compact('issues', 'generatedAt', 'filtersSummary'))->render();

        // Configure mPDF for landscape layout because the report contains many columns.
        $mpdf = new Mpdf([
            'mode'         => 'utf-8',
            'format'       => 'A4-L',
            'margin_top'   => 12,
            'margin_bottom' => 12,
            'margin_left'  => 10,
            'margin_right' => 10,
            'tempDir'      => storage_path('app/mpdf-tmp'),
        ]);

        $mpdf->SetTitle('City Issue Report');

        // Convert HTML content into PDF format.
        $mpdf->WriteHTML($html);

        // Display PDF directly in the browser.
        return response($mpdf->Output(
            'city-issue-report-' . now()->format('Ymd-His') . '.pdf',
            'I'
        ), 200, ['Content-Type' => 'application/pdf']);
    }

    /**
     * Creates the reusable filtered issue query.
     *
     * This method is shared by:
     * - Report table
     * - Status chart
     * - Department satisfaction chart
     * - PDF export
     * -- ito na po ung joined tables para sa reports
     */
    private function buildFilteredQuery(Request $request)
    {
        // Join required tables so reports can display complete issue information.
        $query = DB::table('issues')
            ->join('barangays', 'issues.barangay_id', '=', 'barangays.id')
            ->join('categories', 'issues.category_id', '=', 'categories.id')
            ->join('departments', 'categories.department_id', '=', 'departments.id')
            ->join('users', 'issues.user_id', '=', 'users.id')
            ->leftJoin('feedbacks', 'feedbacks.issue_id', '=', 'issues.id');

        // Filter by selected issue statuses.
        if ($request->filled('statuses')) {
            $query->whereIn('issues.status', $request->input('statuses'));
        }

        // Filter by department ownership.
        if ($request->filled('department_id')) {
            $query->where('departments.id', $request->input('department_id'));
        }

        // Filter by barangay location.
        if ($request->filled('barangay_id')) {
            $query->where('barangays.id', $request->input('barangay_id'));
        }

        // Handles either quick filters (year/month/quarter)
        // or manually selected date ranges.
        $filterMode = $request->input('filter_mode', 'custom');

        if ($filterMode === 'quick' && $request->filled('year')) {

            $year = (int) $request->input('year');

            $query->whereYear('issues.created_at', $year);

            // Quarter filter.
            if ($request->filled('quarter')) {

                $quarter    = (int) $request->input('quarter');
                $startMonth = ($quarter - 1) * 3 + 1;
                $endMonth   = $startMonth + 2;

                $query->whereMonth('issues.created_at', '>=', $startMonth)
                    ->whereMonth('issues.created_at', '<=', $endMonth);

                // Month filter.
            } elseif ($request->filled('month')) {

                $query->whereMonth('issues.created_at', (int) $request->input('month'));
            }
        } elseif ($filterMode === 'custom') {

            // Custom start date filter.
            if ($request->filled('date_from')) {
                $query->whereDate('issues.created_at', '>=', $request->input('date_from'));
            }

            // Custom end date filter.
            if ($request->filled('date_to')) {
                $query->whereDate('issues.created_at', '<=', $request->input('date_to'));
            }
        }

        return $query;
    }

    /**
     * Creates a readable summary of active filters.
     * Used inside the PDF report.
     */
    private function buildFiltersSummary(Request $request): string
    {
        $parts = [];

        // Selected statuses.
        if ($request->filled('statuses')) {
            $parts[] = 'Status: ' . implode(', ', array_map(
                fn($s) => ucwords(str_replace('_', ' ', $s)),
                $request->input('statuses')
            ));
        }

        // Selected department.
        if ($request->filled('department_id')) {
            $dept = DB::table('departments')
                ->where('id', $request->input('department_id'))
                ->value('name');

            if ($dept) {
                $parts[] = 'Department: ' . $dept;
            }
        }

        // Selected barangay.
        if ($request->filled('barangay_id')) {
            $brgy = DB::table('barangays')
                ->where('id', $request->input('barangay_id'))
                ->value('name');

            if ($brgy) {
                $parts[] = 'Barangay: ' . $brgy;
            }
        }

        // Quick date filter summary.
        if ($request->input('filter_mode') === 'quick' && $request->filled('year')) {

            $period = 'Year ' . $request->input('year');

            if ($request->filled('quarter')) {
                $period .= ', Q' . $request->input('quarter');
            }

            if ($request->filled('month')) {
                $period .= ', Month ' . $request->input('month');
            }

            $parts[] = $period;

            // Custom date range summary.
        } elseif ($request->filled('date_from') || $request->filled('date_to')) {

            $parts[] = 'Date Range: ' .
                ($request->input('date_from') ?? '...') .
                ' to ' .
                ($request->input('date_to') ?? '...');
        }

        // Default message when no filters are selected.
        return count($parts)
            ? implode(' | ', $parts)
            : 'All Records (No Filters Applied)';
    }
}
