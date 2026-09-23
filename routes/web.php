<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationRegistrationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\VolunteerApplicationController;
use App\Http\Controllers\VolunteerDashboardController;
use App\Http\Controllers\VolunteerNotificationController;
use App\Http\Controllers\VolunteerMessageController;
use App\Http\Controllers\VolunteerProfileController;
use App\Http\Controllers\Admin\OrganizationController as AdminOrganizationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\OrganizationController as EmployeeOrganizationController;
use App\Http\Controllers\Employee\JobController as EmployeeJobController;
use App\Http\Controllers\Employee\ApplicationController as EmployeeApplicationController;
use App\Http\Controllers\Employee\MessageController as EmployeeMessageController;
use App\Http\Controllers\Organization\OrganizationApplicationController;
use App\Http\Controllers\Organization\OrganizationDashboardController;
use App\Http\Controllers\Organization\OrganizationProfileController;
use App\Http\Controllers\Organization\OrganizationJobController;



Route::get('/', function () {
    return view('pages.home');
})->name('home');




Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');

Route::get('/jobs/{job}', [JobController::class, 'show']) ->name('jobs.show');

Route::post('/jobs/{job}/apply', [
    VolunteerApplicationController::class,
    'store'
])->name('volunteer.applications.apply')
    ->middleware(['auth', 'volunteer']);

Route::get('/organizations', [OrganizationController::class, 'index']) ->name('organizations.index');

Route::get('/organizations/{organization}', [OrganizationController::class, 'show'])
    ->name('organizations.show');



Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'employee' => redirect()->route('employee.dashboard'),
        'organization' => redirect()->route('organization.dashboard'),
        default => redirect()->route('volunteer.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});






/*
|--------------------------------------------------------------------------
| Organization Registration
|--------------------------------------------------------------------------
*/

Route::get('/organization/register', [
    OrganizationRegistrationController::class,
    'create'
])->name('organization.register')->middleware('guest');

Route::post('/organization/register', [
    OrganizationRegistrationController::class,
    'store'
])->name('organization.register.store')->middleware('guest');


/*
|--------------------------------------------------------------------------
| Organization Status Pages
|--------------------------------------------------------------------------
*/

Route::get('/organization/pending', function () {
    return view('pages.organization.pending');
})->name('organization.pending');

Route::get('/organization/rejected', function () {
    return view('pages.organization.rejected');
})->name('organization.rejected');


/*
|--------------------------------------------------------------------------
| Organization (authenticated role area)
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:organization',
    'organization.approved',
])
    ->prefix('organization')
    ->name('organization.')
    ->group(function () {

     Route::get('/dashboard', [
            OrganizationDashboardController::class,
            'index'
        ])->name('dashboard');
           // Profile
        Route::get('/profile', [
            OrganizationProfileController::class,
            'show'
        ])->name('profile');

        Route::get('/profile/edit', [
            OrganizationProfileController::class,
            'edit'
        ])->name('profile.edit');

        Route::patch('/profile', [
            OrganizationProfileController::class,
            'update'
        ])->name('profile.update');

        // Jobs
        Route::get('/jobs', [
            OrganizationJobController::class,
            'index'
        ])->name('jobs.index');

        Route::get('/jobs/create', [
            OrganizationJobController::class,
            'create'
        ])->name('jobs.create');

        Route::post('/jobs', [
            OrganizationJobController::class,
            'store'
        ])->name('jobs.store');

        Route::get('/jobs/{job}', [
            OrganizationJobController::class,
            'show'
        ])->name('jobs.show');

        Route::get('/jobs/{job}/edit', [
            OrganizationJobController::class,
            'edit'
        ])->name('jobs.edit');

        Route::patch('/jobs/{job}', [
            OrganizationJobController::class,
            'update'
        ])->name('jobs.update');

        Route::patch('/jobs/{job}/cancel', [
            OrganizationJobController::class,
            'cancel'
        ])->name('jobs.cancel');

        Route::patch('/jobs/{job}/complete', [
            OrganizationJobController::class,
            'complete'
        ])->name('jobs.complete');

        // Applications
        Route::get('/applications', [
            OrganizationApplicationController::class,
            'index'
        ])->name('applications.index');

        Route::get('/applications/{application}/volunteer', [
            OrganizationApplicationController::class,
            'volunteerProfile'
        ])->name('applications.volunteer-profile');

        Route::get('/applications/{application}/cv', [
            OrganizationApplicationController::class,
            'downloadCv'
        ])->name('applications.cv');

        Route::patch('/applications/{application}/accept', [
            OrganizationApplicationController::class,
            'accept'
        ])->name('applications.accept');

        Route::patch('/applications/{application}/reject', [
            OrganizationApplicationController::class,
            'reject'
        ])->name('applications.reject');

    });


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [
            AdminDashboardController::class,
            'index',
        ])->name('dashboard');

        // Organizations
        Route::get('/organizations', [
            AdminOrganizationController::class,
            'index',
        ])->name('organizations.index');

        Route::get('/organizations/create', [
            AdminOrganizationController::class,
            'create',
        ])->name('organizations.create');

        Route::post('/organizations', [
            AdminOrganizationController::class,
            'store',
        ])->name('organizations.store');

        Route::get('/organizations/{organization}', [
            AdminOrganizationController::class,
            'show',
        ])->name('organizations.show');

        Route::patch('/organizations/{organization}/approve', [
            AdminOrganizationController::class,
            'approve',
        ])->name('organizations.approve');

        Route::patch('/organizations/{organization}/reject', [
            AdminOrganizationController::class,
            'reject',
        ])->name('organizations.reject');

        // Jobs
        Route::get('/jobs', [
            AdminJobController::class,
            'index',
        ])->name('jobs.index');

        Route::get('/jobs/create', [
            AdminJobController::class,
            'create',
        ])->name('jobs.create');

        Route::post('/jobs', [
            AdminJobController::class,
            'store',
        ])->name('jobs.store');

        Route::get('/jobs/{job}/edit', [
            AdminJobController::class,
            'edit',
        ])->name('jobs.edit');

        Route::patch('/jobs/{job}', [
            AdminJobController::class,
            'update',
        ])->name('jobs.update');

        Route::patch('/jobs/{job}/cancel', [
            AdminJobController::class,
            'cancel',
        ])->name('jobs.cancel');

        Route::patch('/jobs/{job}/complete', [
            AdminJobController::class,
            'complete',
        ])->name('jobs.complete');

        // Applications
        Route::get('/applications', [
            AdminApplicationController::class,
            'index',
        ])->name('applications.index');

        Route::get('/applications/{application}/cv', [
            AdminApplicationController::class,
            'downloadCv',
        ])->name('applications.cv');

        Route::patch('/applications/{application}/accept', [
            AdminApplicationController::class,
            'accept',
        ])->name('applications.accept');

        Route::patch('/applications/{application}/reject', [
            AdminApplicationController::class,
            'reject',
        ])->name('applications.reject');

        // Users
        Route::get('/users', [
            AdminUserController::class,
            'index',
        ])->name('users.index');

        Route::patch('/users/{user}/suspend', [
            AdminUserController::class,
            'suspend',
        ])->name('users.suspend');

        Route::patch('/users/{user}/activate', [
            AdminUserController::class,
            'activate',
        ])->name('users.activate');

        // Employees
        Route::get('/employees', [
            AdminEmployeeController::class,
            'index',
        ])->name('employees.index');

        Route::get('/employees/create', [
            AdminEmployeeController::class,
            'create',
        ])->name('employees.create');

        Route::post('/employees', [
            AdminEmployeeController::class,
            'store',
        ])->name('employees.store');

        // Messages
        Route::get('/messages', [
            AdminMessageController::class,
            'index',
        ])->name('messages.index');

        Route::get('/messages/{message}', [
            AdminMessageController::class,
            'show',
        ])->name('messages.show');

        Route::patch('/messages/{message}/read', [
            AdminMessageController::class,
            'markRead',
        ])->name('messages.read');

    });


    Route::middleware(['auth', 'volunteer'])
    ->prefix('volunteer')
    ->name('volunteer.')
    ->group(function () {

        Route::get('/dashboard', [
            VolunteerDashboardController::class,
            'index'
        ])->name('dashboard');

        Route::get('/profile', [
            VolunteerProfileController::class,
            'show'
        ])->name('profile');

        Route::get('/profile/edit', [
            VolunteerProfileController::class,
            'edit'
        ])->name('profile.edit');

        Route::put('/profile', [
            VolunteerProfileController::class,
            'update'
        ])->name('profile.update');

        Route::get('/applications', [
            VolunteerApplicationController::class,
            'index'
        ])->name('applications.index');

        Route::get('/applications/{application}/cv', [
            VolunteerApplicationController::class,
            'downloadCv'
        ])->name('applications.cv');

        // Notifications
        Route::get('/notifications', [
            VolunteerNotificationController::class,
            'index'
        ])->name('notifications.index');

        Route::patch('/notifications/{notification}/read', [
            VolunteerNotificationController::class,
            'read'
        ])->name('notifications.read');

        Route::patch('/notifications/read-all', [
            VolunteerNotificationController::class,
            'readAll'
        ])->name('notifications.readAll');

        Route::get('/messages', [
            VolunteerMessageController::class,
            'index'
        ])->name('messages.index');

        Route::get('/messages/{message}', [
            VolunteerMessageController::class,
            'show'
        ])->name('messages.show');
    });


/*
|--------------------------------------------------------------------------
| Employee
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {

        Route::get('/dashboard', [
            EmployeeDashboardController::class,
            'index',
        ])->name('dashboard');

        // Organizations
        Route::get('/organizations', [
            EmployeeOrganizationController::class,
            'index',
        ])->name('organizations.index');

        Route::get('/organizations/{organization}', [
            EmployeeOrganizationController::class,
            'show',
        ])->name('organizations.show');

        Route::patch('/organizations/{organization}/approve', [
            EmployeeOrganizationController::class,
            'approve',
        ])->name('organizations.approve');

        Route::patch('/organizations/{organization}/reject', [
            EmployeeOrganizationController::class,
            'reject',
        ])->name('organizations.reject');

        // Jobs (view / edit / cancel / complete — no create)
        Route::get('/jobs', [
            EmployeeJobController::class,
            'index',
        ])->name('jobs.index');

        Route::get('/jobs/{job}/edit', [
            EmployeeJobController::class,
            'edit',
        ])->name('jobs.edit');

        Route::patch('/jobs/{job}', [
            EmployeeJobController::class,
            'update',
        ])->name('jobs.update');

        Route::patch('/jobs/{job}/cancel', [
            EmployeeJobController::class,
            'cancel',
        ])->name('jobs.cancel');

        Route::patch('/jobs/{job}/complete', [
            EmployeeJobController::class,
            'complete',
        ])->name('jobs.complete');

        // Applications (view + CV only — no accept/reject)
        Route::get('/applications', [
            EmployeeApplicationController::class,
            'index',
        ])->name('applications.index');

        Route::get('/applications/{application}/cv', [
            EmployeeApplicationController::class,
            'downloadCv',
        ])->name('applications.cv');

        // Messages
        Route::get('/messages', [
            EmployeeMessageController::class,
            'index',
        ])->name('messages.index');

        Route::get('/messages/{message}', [
            EmployeeMessageController::class,
            'show',
        ])->name('messages.show');

        Route::patch('/messages/{message}/read', [
            EmployeeMessageController::class,
            'markRead',
        ])->name('messages.read');

    });
require __DIR__.'/auth.php';
