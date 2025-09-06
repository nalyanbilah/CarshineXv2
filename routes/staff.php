<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\ScheduleController;
use App\Http\Controllers\Staff\TaskController;
use App\Http\Controllers\Staff\PerformanceController;
use App\Http\Controllers\Staff\LeaveController;
use App\Http\Controllers\Staff\NotificationController;
use App\Http\Controllers\Staff\ProfileController;

/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
|
| Here is where you can register staff routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group and "auth" middleware.
|
*/

Route::middleware(['auth:web'])->prefix('staff')->name('staff.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Schedule Management
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
    Route::get('/schedule/week', [ScheduleController::class, 'weekView'])->name('schedule.week');
    Route::get('/schedule/day', [ScheduleController::class, 'dayView'])->name('schedule.day');
    Route::get('/schedule/export', [ScheduleController::class, 'export'])->name('schedule.export');
    
    // Task Management
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/{task}', [TaskController::class, 'show'])->name('show');
        Route::patch('/{task}/status', [TaskController::class, 'updateStatus'])->name('update-status');
        Route::post('/{task}/start', [TaskController::class, 'start'])->name('start');
        Route::post('/{task}/complete', [TaskController::class, 'complete'])->name('complete');
        Route::post('/{task}/comment', [TaskController::class, 'addComment'])->name('add-comment');
    });
    
    // Performance Monitoring
    Route::prefix('performance')->name('performance.')->group(function () {
        Route::get('/', [PerformanceController::class, 'index'])->name('index');
        Route::get('/details', [PerformanceController::class, 'details'])->name('details');
        Route::get('/export', [PerformanceController::class, 'export'])->name('export');
        Route::get('/compare', [PerformanceController::class, 'compare'])->name('compare');
    });
    
    // Leave Management
    Route::prefix('leave')->name('leave.')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index');
        Route::get('/create', [LeaveController::class, 'create'])->name('create');
        Route::post('/', [LeaveController::class, 'store'])->name('store');
        Route::get('/{leave}', [LeaveController::class, 'show'])->name('show');
        Route::get('/{leave}/edit', [LeaveController::class, 'edit'])->name('edit');
        Route::put('/{leave}', [LeaveController::class, 'update'])->name('update');
        Route::delete('/{leave}', [LeaveController::class, 'cancel'])->name('cancel');
        Route::get('/balance/check', [LeaveController::class, 'checkBalance'])->name('balance');
        Route::get('/calendar', [LeaveController::class, 'calendar'])->name('calendar');
    });
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [NotificationController::class, 'delete'])->name('delete');
        Route::get('/preferences', [NotificationController::class, 'preferences'])->name('preferences');
        Route::post('/preferences', [NotificationController::class, 'updatePreferences'])->name('preferences.update');
    });
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::get('/skills', [ProfileController::class, 'skills'])->name('skills');
        Route::post('/skills', [ProfileController::class, 'updateSkills'])->name('skills.update');
    });
    
    // API endpoints for AJAX requests
    Route::prefix('api')->name('api.')->group(function () {
        // Dashboard widgets
        Route::get('/stats', [DashboardController::class, 'getStats'])->name('stats');
        Route::get('/upcoming-tasks', [DashboardController::class, 'getUpcomingTasks'])->name('upcoming-tasks');
        Route::get('/performance-chart', [DashboardController::class, 'getPerformanceChart'])->name('performance-chart');
        
        // Schedule
        Route::get('/schedule/events', [ScheduleController::class, 'getEvents'])->name('schedule.events');
        
        // Tasks
        Route::get('/tasks/filter', [TaskController::class, 'filter'])->name('tasks.filter');
        Route::get('/tasks/search', [TaskController::class, 'search'])->name('tasks.search');
        
        // Notifications
        Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount'])->name('notifications.count');
        Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    });
});

// Routes that don't require authentication (but still staff-specific)
Route::prefix('staff')->name('staff.')->group(function () {
    // Help/Support pages
    Route::get('/help', function () {
        return view('staff.help.index');
    })->name('help');
    
    Route::get('/help/faq', function () {
        return view('staff.help.faq');
    })->name('help.faq');
    
    Route::get('/help/contact', function () {
        return view('staff.help.contact');
    })->name('help.contact');
});