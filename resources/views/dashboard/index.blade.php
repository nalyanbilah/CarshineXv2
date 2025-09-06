@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Message -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Good {{ now()->format('A') === 'AM' ? 'morning' : (now()->format('H') < 17 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 mt-1">You have {{ $pendingTasks }} pending {{ Str::plural('task', $pendingTasks) }} today.</p>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card 
            title="Today's Tasks" 
            :value="$todayTasks"
            color="emerald"
            :icon="'<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>'"
        />
        
        <x-stat-card 
            title="Completed Today" 
            :value="$completedToday"
            color="blue"
            :icon="'<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'"
        />
        
        <x-stat-card 
            title="Performance Score" 
            :value="number_format($performanceScore, 2)"
            subtitle="Out of 4.0"
            color="purple"
            :icon="'<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>'"
        />
        
        <x-stat-card 
            title="Workload" 
            :value="auth()->user()->current_workload . '%'"
            subtitle="of capacity"
            color="orange"
            :icon="'<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'"
        />
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Tasks -->
        <x-card title="Recent Tasks">
            <div class="space-y-4">
                @forelse($recentTasks as $task)
                    <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex-shrink-0">
                            @if($task->status === 'completed')
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            @elseif($task->status === 'in_progress')
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full animate-pulse"></div>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $task->title }}</p>
                            <p class="text-sm text-gray-500">Due {{ $task->due_date->diffForHumans() }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($task->priority === 'urgent') bg-red-100 text-red-700
                            @elseif($task->priority === 'high') bg-orange-100 text-orange-700
                            @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No recent tasks</p>
                @endforelse
            </div>
            <div class="mt-4 pt-4 border-t">
                <a href="{{ route('tasks') }}" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                    View all tasks →
                </a>
            </div>
        </x-card>
        
        <!-- Performance Chart -->
        <x-card title="Weekly Performance">
            <canvas id="performanceChart" height="300"></canvas>
        </x-card>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const performanceData = {!! json_encode($weeklyPerformance->pluck('performance_score')) !!};
    const performanceDates = {!! json_encode($weeklyPerformance->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('D'))) !!};
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: performanceDates,
            datasets: [{
                label: 'Performance Score',
                data: performanceData,
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 4,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection