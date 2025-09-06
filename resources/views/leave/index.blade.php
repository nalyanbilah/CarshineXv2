@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Leave Management</h1>
        <x-button variant="primary" onclick="document.getElementById('leaveModal').classList.remove('hidden')">
            Request Leave
        </x-button>
    </div>
    
    <!-- Leave Balance -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <x-stat-card 
            title="Annual Leave" 
            :value="($leaveBalance['annual'] - $leaveBalance['used_annual']) . ' days'"
            subtitle="Used: " . $leaveBalance['used_annual'] . " of " . $leaveBalance['annual']
            color="emerald"
        />
        
        <x-stat-card 
            title="Sick Leave" 
            :value="($leaveBalance['sick'] - $leaveBalance['used_sick']) . ' days'"
            subtitle="Used: " . $leaveBalance['used_sick'] . " of " . $leaveBalance['sick']
            color="blue"
        />
        
        <x-stat-card 
            title="Pending Requests" 
            :value="$leaves->where('status', 'pending')->count()"
            color="orange"
        />
        
        <x-stat-card 
            title="Total Days Taken" 
            :value="($leaveBalance['used_annual'] + $leaveBalance['used_sick']) . ' days'"
            subtitle="This year"
            color="purple"
        />
    </div>
    
    <!-- Leave History -->
    <x-card title="Leave History">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Type</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Duration</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Dates</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Reason</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Status</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <span class="capitalize">{{ $leave->type }}</span>
                            </td>
                            <td class="py-3 px-4">
                                {{ $leave->start_date->diffInDays($leave->end_date) + 1 }} days
                            </td>
                            <td class="py-3 px-4">
                                {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-sm text-gray-600 truncate max-w-xs">{{ $leave->reason }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($leave->status === 'approved') bg-green-100 text-green-700
                                    @elseif($leave->status === 'rejected') bg-red-100 text-red-700
                                    @elseif($leave->status === 'pending') bg-yellow-100 text-yellow-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($leave->status === 'pending')
                                    <form action="{{ route('leave.cancel', $leave) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 text-sm">
                                            Cancel
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                No leave requests found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $leaves->links() }}
        </div>
    </x-card>
</div>

<!-- Leave Request Modal -->
<div id="leaveModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
        <div class="fixed inset-0 transition-opacity" onclick="document.getElementById('leaveModal').classList.add('hidden')">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <div class="relative inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
            <form method="POST" action="{{ route('leave.store') }}">
                @csrf
                <div class="bg-white px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Leave</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                            <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <option value="">Select type</option>
                                <option value="annual">Annual Leave</option>
                                <option value="sick">Sick Leave</option>
                                <option value="personal">Personal Leave</option>
                                <option value="unpaid">Unpaid Leave</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" required 
                                       min="{{ now()->format('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="date" name="end_date" required 
                                       min="{{ now()->format('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                            <textarea name="reason" rows="3" required 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                      placeholder="Please provide a reason for your leave request..."></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <x-button type="button" variant="secondary" onclick="document.getElementById('leaveModal').classList.add('hidden')">
                        Cancel
                    </x-button>
                    <x-button type="submit" variant="primary">
                        Submit Request
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-update end date to match start date if end date is before start date
    document.querySelector('input[name="start_date"]').addEventListener('change', function() {
        const startDate = this.value;
        const endDateInput = document.querySelector('input[name="end_date"]');
        endDateInput.min = startDate;
        if (endDateInput.value && endDateInput.value < startDate) {
            endDateInput.value = startDate;
        }
    });
</script>
@endsection