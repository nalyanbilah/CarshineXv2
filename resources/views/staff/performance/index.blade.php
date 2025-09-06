@extends('layouts.app')

@section('title', 'Performance')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Performance Overview</h1>
        <select onchange="window.location.href='{{ route('performance') }}?period=' + this.value"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none">
            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Last Week</option>
            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Last Month</option>
            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Last Year</option>
        </select>
    </div>
    
    <!-- Performance Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <x-stat-card 
            title="Performance Score" 
            :value="number_format($stats['avgScore'], 2)"
            subtitle="Out of 4.0 (GPA)"
            color="emerald"
            :trend="$stats['trend'] > 0 ? '+' . number_format($stats['trend'], 2) : number_format($stats['trend'], 2)"
            :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13 10V3L4 14h7v7l9-11h-7z\'></path></svg>'"
        />
        
        <x-stat-card 
            title="Tasks Completed" 
            :value="$stats['totalTasks']"
            :subtitle="'Total weight: ' . $stats['totalWeight']"
            color="blue"
            :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\'></path></svg>'"
        />
        
        <x-stat-card 
            title="Completion Rate" 
            :value="($taskStats['completed'] ?? 0) + ($taskStats['in_progress'] ?? 0) + ($taskStats['pending'] ?? 0) > 0 
                ? round(($taskStats['completed'] ?? 0) / (($taskStats['completed'] ?? 0) + ($taskStats['in_progress'] ?? 0) + ($taskStats['pending'] ?? 0)) * 100) . '%' 
                : '0%'"
            color="purple"
            :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z\'></path></svg>'"
        />
        
        <x-stat-card 
            title="Average Daily Score" 
            :value="$performances->count() > 0 ? number_format($performances->avg('performance_score'), 2) : '0.00'"
            color="orange"
            :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\'></path></svg>'"
        />
    </div>
    
    <!-- Performance Chart -->
    <x-card title="Performance Trend">
        <canvas id="performanceChart" height="300"></canvas>
    </x-card>
    
    <!-- Task Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <x-card title="Task Status Distribution">
            <canvas id="taskStatusChart" height="300"></canvas>
        </x-card>
        
        <x-card title="Performance Grade Distribution">
            <div class="space-y-4">
                @php
                    $gradeDistribution = [
                        'A (3.5-4.0)' => $performances->filter(fn($p) => $p->performance_score >= 3.5)->count(),
                        'B (2.5-3.49)' => $performances->filter(fn($p) => $p->performance_score >= 2.5 && $p->performance_score < 3.5)->count(),
                        'C (1.5-2.49)' => $performances->filter(fn($p) => $p->performance_score >= 1.5 && $p->performance_score < 2.5)->count(),
                        'D (0.5-1.49)' => $performances->filter(fn($p) => $p->performance_score >= 0.5 && $p->performance_score < 1.5)->count(),
                        'F (0-0.49)' => $performances->filter(fn($p) => $p->performance_score < 0.5)->count(),
                    ];
                    $total = array_sum($gradeDistribution);
                @endphp
                
                @foreach($gradeDistribution as $grade => $count)
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">{{ $grade }}</span>
                            <span class="text-sm text-gray-500">{{ $count }} days ({{ $total > 0 ? round($count / $total * 100) : 0 }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full 
                                @if(str_starts_with($grade, 'A')) bg-emerald-500
                                @elseif(str_starts_with($grade, 'B')) bg-blue-500
                                @elseif(str_starts_with($grade, 'C')) bg-yellow-500
                                @elseif(str_starts_with($grade, 'D')) bg-orange-500
                                @else bg-red-500
                                @endif"
                                style="width: {{ $total > 0 ? round($count / $total * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Performance Trend Chart
    const performanceData = {!! json_encode($performances->pluck('performance_score')) !!};
    const performanceDates = {!! json_encode($performances->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('M d'))) !!};

    new Chart(document.getElementById('performanceChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: performanceDates,
            datasets: [{
                label: 'Daily Performance Score',
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
            plugins: { legend: { display: true, position: 'bottom' } },
            scales: {
                y: { beginAtZero: true, max: 4, ticks: { stepSize: 0.5 } }
            }
        }
    });

    // Task Status Chart
    const taskStatusData = {!! json_encode($taskStats) !!};

    new Chart(document.getElementById('taskStatusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'In Progress', 'Pending'],
            datasets: [{
                data: [
                    taskStatusData.completed || 0,
                    taskStatusData.in_progress || 0,
                    taskStatusData.pending || 0
                ],
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(156, 163, 175)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush