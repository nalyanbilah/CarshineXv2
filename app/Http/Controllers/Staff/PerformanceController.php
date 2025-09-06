<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Performance;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month'); // week, month, year
        $user = Auth::user();
        
        $endDate = now();
        switch ($period) {
            case 'week':
                $startDate = now()->subWeek();
                break;
            case 'year':
                $startDate = now()->subYear();
                break;
            default: // month
                $startDate = now()->subMonth();
        }
        
        $performances = Performance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();
        
        $stats = [
            'totalTasks' => $performances->sum('tasks_completed'),
            'avgScore' => $performances->avg('performance_score') ?? 0,
            'totalWeight' => $performances->sum('total_weight'),
            'trend' => $this->calculateTrend($performances),
        ];
        
        $taskStats = Task::where('assigned_to', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        return view('performance.index', compact('performances', 'stats', 'taskStats', 'period'));
    }
    
    private function calculateTrend($performances)
    {
        if ($performances->count() < 2) return 0;
        
        $firstHalf = $performances->take($performances->count() / 2)->avg('performance_score');
        $secondHalf = $performances->skip($performances->count() / 2)->avg('performance_score');
        
        return $secondHalf - $firstHalf;
    }
}