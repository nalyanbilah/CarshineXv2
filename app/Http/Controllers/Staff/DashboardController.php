<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Performance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $data = [
            'todayTasks' => Task::where('assigned_to', $user->id)
                ->whereDate('due_date', $today)
                ->count(),
            
            'pendingTasks' => Task::where('assigned_to', $user->id)
                ->where('status', 'pending')
                ->count(),
            
            'completedToday' => Task::where('assigned_to', $user->id)
                ->where('status', 'completed')
                ->whereDate('updated_at', $today)
                ->count(),
            
            'performanceScore' => Performance::where('user_id', $user->id)
                ->whereMonth('date', $today->month)
                ->whereYear('date', $today->year)
                ->avg('performance_score') ?? 0,
            
            'recentTasks' => Task::where('assigned_to', $user->id)
                ->with('requiredSkill')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            
            'weeklyPerformance' => Performance::where('user_id', $user->id)
                ->whereBetween('date', [
                    $today->copy()->startOfWeek(),
                    $today->copy()->endOfWeek()
                ])
                ->get(),
        ];
        
        return view('dashboard.index', $data);
    }
}