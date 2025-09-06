<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'week'); // week or day
        $date = $request->get('date', now());
        
        if ($view === 'week') {
            $startDate = Carbon::parse($date)->startOfWeek();
            $endDate = Carbon::parse($date)->endOfWeek();
        } else {
            $startDate = Carbon::parse($date)->startOfDay();
            $endDate = Carbon::parse($date)->endOfDay();
        }
        
        $tasks = Task::where('assigned_to', Auth::id())
            ->whereBetween('due_date', [$startDate, $endDate])
            ->orderBy('due_date')
            ->get();
        
        return view('schedule.index', compact('tasks', 'view', 'date', 'startDate', 'endDate'));
    }
}