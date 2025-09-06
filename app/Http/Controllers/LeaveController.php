<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $leaveBalance = [
            'annual' => 21,
            'sick' => 14,
            'used_annual' => $this->calculateUsedLeaveDays('annual'),
            'used_sick' => $this->calculateUsedLeaveDays('sick'),
        ];
        
        return view('leave.index', compact('leaves', 'leaveBalance'));
    }
    
    private function calculateUsedLeaveDays($type)
    {
        $leaves = Leave::where('user_id', Auth::id())
            ->where('type', $type)
            ->where('status', 'approved')
            ->whereYear('start_date', now()->year)
            ->get();
        
        $totalDays = 0;
        foreach ($leaves as $leave) {
            $totalDays += $leave->duration; // Uses the getDurationAttribute
        }
        
        return $totalDays;
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:annual,sick,personal,unpaid',
            'reason' => 'required|string|max:500',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        
        Leave::create($validated);
        
        return back()->with('success', 'Leave request submitted successfully');
    }
    
    public function cancel(Leave $leave)
    {
        if ($leave->user_id !== Auth::id() || $leave->status !== 'pending') {
            abort(403);
        }
        
        $leave->update(['status' => 'cancelled']);
        
        return back()->with('success', 'Leave request cancelled');
    }
}