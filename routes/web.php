<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\employee\EmployeeDashboardController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\Staff\ComplaintController as StaffComplaintController;

use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\ConversationController;
use App\Http\Controllers\Staff\ResolvedComplaintController;
use App\Http\Controllers\NotificationController;

Route::name('auth.')->group(function () {

    Route::get('/', function () {
        return view('auth.login');
    })->name('login.page');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register.page');

    Route::post('/registerEmployee', [AuthController::class, 'register'])->name('register.store');

    Route::post('/', [AuthController::class, 'login'])->name('login.store');

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


});


Route::middleware('auth')->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    Route::get('/employee/dashboard', [DashboardController::class, 'employee'])->name('employee.dashboard');

    Route::get('/staff/dashboard', [DashboardController::class, 'staff']) ->name('staff.dashboard');

    Route::post('/profile/update', [ProfileController::class, 'profileUpdate'])->name('profile.update');

    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

});

Route::middleware('admin')->group(function () {
    Route::post('/users/store', [AdminController::class, 'adminStoreUser'])->name('admin.users.store');
});

Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'employee'])
    ->name('dashboard');
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'storeComplaint'])->name('complaints.store');

    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{complaint}/comment', [ComplaintController::class, 'addComment'])->name('complaints.comment');
    Route::post('/complaints/{complaint}/decision', [ComplaintController::class, 'resolveDecision'])->name('complaints.decision');
});


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/complaints', [App\Http\Controllers\Admin\ComplaintController::class, 'index'])
        ->name('complaints.index');

    Route::get('/complaints/{complaint}', [App\Http\Controllers\Admin\ComplaintController::class, 'show'])
        ->name('complaints.show');

    Route::post('/complaints/{complaint}/assign', [App\Http\Controllers\Admin\ComplaintController::class, 'assign'])
        ->name('complaints.assign');

    Route::post('/complaints/{complaint}/status', [App\Http\Controllers\Admin\ComplaintController::class, 'updateStatus'])
        ->name('complaints.updateStatus');


    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');


    Route::get('/departments', [\App\Http\Controllers\Admin\DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [\App\Http\Controllers\Admin\DepartmentController::class, 'store'])->name('departments.store');
    Route::put('/departments/{department}', [\App\Http\Controllers\Admin\DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{department}', [\App\Http\Controllers\Admin\DepartmentController::class, 'destroy'])->name('departments.destroy');

    Route::get('/admin/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
    ->name('reports.index');


});
// Route::get('/admin/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
//     ->name('admin.reports.index')->middleware(['auth']);



Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'dashboard'])->name('dashboard');
 
    Route::get('/complaints', [StaffComplaintController::class, 'index'])
        ->name('complaints.index');
 
    Route::get('/complaints/{complaint}', [StaffComplaintController::class, 'show'])
        ->name('complaints.show');
 
    Route::post('/complaints/{complaint}/status', [StaffComplaintController::class, 'updateStatus'])
        ->name('complaints.updateStatus');
 
    Route::post('/complaints/{complaint}/comment', [StaffComplaintController::class, 'addComment'])
        ->name('complaints.comment');
    
    // Route::get('/conversations', [\App\Http\Controllers\Staff\ConversationController::class, 'index'])
    //     ->name('conversations.index');

    Route::get('/resolved', [\App\Http\Controllers\Staff\ResolvedComplaintController::class, 'index'])
        ->name('resolved.index')
    ;
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/resolved', [ResolvedComplaintController::class, 'index'])->name('resolved.index');
 
});

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
});