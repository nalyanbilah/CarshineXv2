<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskAllocationService;
use Illuminate\Http\Request;
use App\Models\Performance;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $taskAllocationService;

    public function __construct(TaskAllocationService $taskAllocationService)
    {
        $this->taskAllocationService = $taskAllocationService;
    }

    public function index()
    {
        $tasks = Task::where('assigned_to', Auth::id())
            ->with(['requiredSkill', 'assignedBy'])
            ->orderBy('due_date', 'asc')
            ->orderBy('priority', 'desc')
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        $task->update(['status' => $request->status]);

        if ($request->status === 'completed') {
            $this->updatePerformance($task);
        }

        return back()->with('success', 'Task status updated successfully');
    }

    private function updatePerformance(Task $task)
    {
        $user = $task->assignedTo;
        $today = now()->format('Y-m-d');
        
        $performance = Performance::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => $today
            ],
            [
                'tasks_completed' => 0,
                'performance_score' => 0,
                'total_weight' => 0
            ]
        );

        $performance->tasks_completed += 1;
        $performance->total_weight += $task->weight;
        
        // Calculate GPA-like score (0-4 scale)
        $baseScore = 3.0; // Base score for completing a task
        
        // Add bonuses for priority and early completion
        if ($task->priority === 'urgent') $baseScore += 0.5;
        elseif ($task->priority === 'high') $baseScore += 0.3;
        
        if ($task->due_date->isFuture()) {
            $daysEarly = now()->diffInDays($task->due_date);
            $baseScore += min($daysEarly * 0.1, 0.5); // Max 0.5 bonus
        }
        
        $baseScore = min($baseScore, 4.0); // Cap at 4.0
        
        // Update weighted average
        $currentTotal = $performance->performance_score * ($performance->total_weight - $task->weight);
        $newTotal = $currentTotal + ($baseScore * $task->weight);
        $performance->performance_score = $newTotal / $performance->total_weight;
        
        $performance->save();
        
        // Update user workload
        $user->decrement('current_workload', $task->workload_units);
    }
}