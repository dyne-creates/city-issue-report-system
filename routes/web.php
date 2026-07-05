<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\IssueController as AdminIssueController;
use App\Http\Controllers\Admin\BarangayController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\StatusLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;

use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\IssueController as StaffIssueController;
use App\Http\Controllers\Citizen\DashboardController as CitizenDashboardController;
use App\Http\Controllers\Citizen\IssueController as CitizenIssueController;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Home/ Landing Page Route
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            default => redirect()->route('citizen.dashboard'),
        };
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/issue-photos/{issue}', function (string $issue) {
        $query = DB::table('issues')
            ->join('categories', 'issues.category_id', '=', 'categories.id')
            ->select('issues.id', 'issues.user_id', 'issues.photo_path', 'categories.department_id')
            ->where('issues.id', $issue);

        $issueRecord = $query->first();

        abort_if(! $issueRecord || ! $issueRecord->photo_path, 404);

        $user = auth()->user();
        $canView = $user->role === 'admin'
            || ($user->role === 'citizen' && (int) $issueRecord->user_id === (int) $user->id)
            || ($user->role === 'staff' && (int) $issueRecord->department_id === (int) $user->department_id);

        abort_unless($canView, 403);
        abort_unless(Storage::disk('public')->exists($issueRecord->photo_path), 404);

        return Storage::disk('public')->response($issueRecord->photo_path);
    })->name('issue.photo');
});

require __DIR__ . '/auth.php';

// citizen routes
Route::middleware(['auth', 'citizenMiddleware'])
    ->prefix('citizen')
    ->name('citizen.') // Added a dot here for clean naming (e.g., citizen.dashboard)
    ->group(function () {

        Route::get('dashboard', [CitizenDashboardController::class, 'index'])->name('dashboard');
        Route::post('issues/search', [CitizenIssueController::class, 'search'])->name('issues.search');
        Route::get('issues/search/reset', [CitizenIssueController::class, 'resetSearch'])->name('issues.search.reset');
        Route::resource('issues', CitizenIssueController::class)->only(['index', 'create', 'store', 'show']);
    });

// staff routes
Route::middleware(['auth', 'staffMiddleware'])
    ->prefix('staff')
    ->name('staff.') // Added a dot here (e.g., staff.dashboard)
    ->group(function () {

        Route::get('dashboard', [StaffDashboardController::class, 'index'])->name('dashboard'); // Removed extra /staff/
        Route::resource('issues', StaffIssueController::class)->only(['index', 'show', 'edit', 'update']);
    });

// admin routes
Route::middleware(['auth', 'adminMiddleware'])
    ->prefix('admin')
    ->name('admin.') // Added a dot here (e.g., admin.users.index)
    ->group(function () {

        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard'); // Removed extra /admin/

        Route::resource('users', UserController::class);
        Route::resource('barangays', BarangayController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('status-logs', StatusLogController::class);
        Route::resource('issues', AdminIssueController::class);
        Route::resource('reports', ReportController::class);
    });
