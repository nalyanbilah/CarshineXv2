<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;

class TaskAllocationService
{
    public function allocateTask(Task $task)
    {
        // Find suitable employees
        $suitableEmployees = User::where('status', 'active')
            ->when($task->required_skill_id, function ($query) use ($task) {
                return $query->whereHas('skills', function ($q) use ($task) {
                    $q->where('skills.id', $task->required_skill_id)
                      ->where('proficiency_level', '>=', 3); // Minimum proficiency
                });
            })
            ->get();
        
        // Filter by availability and workload
        $availableEmployees = $suitableEmployees->filter(function ($employee) use ($task) {
            return $employee->isAvailable() && 
                   $employee->getAvailableCapacity() >= $task->workload_units;
        });
        
        if ($availableEmployees->isEmpty()) {
            return null;
        }
        
        // Select the employee with the lowest current workload
        $selectedEmployee = $availableEmployees->sortBy('current_workload')->first();
        
        // Assign task
        $task->assigned_to = $selectedEmployee->id;
        $task->save();
        
        // Update employee workload
        $selectedEmployee->increment('current_workload', $task->workload_units);
        
        // Create notification
        $this->createNotification($selectedEmployee, $task);
        
        return $selectedEmployee;
    }
    
    private function createNotification($user, $task)
    {
        $user->notifications()->create([
            'type' => 'task_assigned',
            'title' => 'New Task Assigned',
            'message' => "You have been assigned a new task: {$task->title}",
            'data' => ['task_id' => $task->id],
        ]);
    }
}