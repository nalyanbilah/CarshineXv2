@extends('layouts.app')

@section('title', 'Work Schedule')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Work Schedule</h1>
        <div class="flex items-center space-x-3">
            <div class="flex bg-gray-100 rounded-lg p-1">
                <a href="{{ route('schedule', ['view' => 'day']) }}" 
                   class="px-4 py-2 rounded {{ $view === 'day' ? 'bg-white shadow-sm' : '' }} text-sm font-medium transition-all">
                    Day
                </a>
                <a href="{{ route('schedule', ['view' => 'week']) }}" 
                   class="px-4 py-2 rounded {{ $view === 'week' ? 'bg-white shadow-sm' : '' }} text-sm font-medium transition-all">
                    Week
                </a>
            </div>
            
            <div class="flex items-center space-x-2">
                <button onclick="changeDate('prev')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <span class="text-sm font-medium text-gray-700">
                    {{ $view === 'week' 
                        ? $startDate->format('d M') . ' - ' . $endDate->format('d M Y')
                        : $startDate->format('d M Y') 
                    }}
                </span>
                <button onclick="changeDate('next')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Schedule Grid -->
    @if($view === 'week')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="grid grid-cols-7 border-b">
                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $index => $day)
                    <div class="p-4 text-center border-r last:border-r-0">
                        <p class="text-sm font-medium text-gray-600">{{ $day }}</p>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $startDate->copy()->addDays($index)->format('d') }}
                        </p>
                    </div>
                @endforeach
            </div>
            
            <div class="grid grid-cols-7 min-h-[400px]">
                @for($i = 0; $i < 7; $i++)
                    @php
                        $currentDate = $startDate->copy()->addDays($i);
                        $dayTasks = $tasks->filter(function($task) use ($currentDate) {
                            return $task->due_date->format('Y-m-d') === $currentDate->format('Y-m-d');
                        });
                    @endphp
                    <div class="p-2 border-r last:border-r-0">
                        @foreach($dayTasks as $task)
                            <div class="mb-2 p-2 rounded-lg border border-gray-200 hover:shadow-sm transition-shadow cursor-pointer"
                                 onclick="window.location.href='{{ route('tasks') }}'">
                                <p class="text-xs font-medium text-gray-800 truncate">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500">{{ $task->due_date->format('g:i A') }}</p>
                                <span class="inline-block px-1.5 py-0.5 text-xs rounded 
                                    @if($task->status === 'completed') bg-green-100 text-green-700
                                    @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endfor
            </div>
        </div>
    @else
        <!-- Day View -->
        <div class="space-y-4">
            @forelse($tasks as $task)
                <x-card :padding="false">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">{{ $task->title }}</h3>
                                                                <p class="text-sm text-gray-600 mt-1">{{ $task->description }}</p>
                                <div class="flex items-center space-x-4 mt-3">
                                    <span class="text-sm text-gray-500">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $task->due_date->format('g:i A') }}
                                    </span>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($task->priority === 'urgent') bg-red-100 text-red-700
                                        @elseif($task->priority === 'high') bg-orange-100 text-orange-700
                                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ ucfirst($task->priority) }} Priority
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="px-3 py-1 text-sm rounded-lg 
                                    @if($task->status === 'completed') bg-green-100 text-green-700
                                    @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </x-card>
            @empty
                <x-card>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks scheduled</h3>
                        <p class="mt-1 text-sm text-gray-500">You have no tasks scheduled for this day.</p>
                    </div>
                </x-card>
            @endforelse
        </div>
    @endif
</div>

<script>
    function changeDate(direction) {
        const currentDate = new Date('{{ $date }}');
        const view = '{{ $view }}';
        let newDate;
        
        if (view === 'week') {
            newDate = new Date(currentDate.setDate(currentDate.getDate() + (direction === 'next' ? 7 : -7)));
        } else {
            newDate = new Date(currentDate.setDate(currentDate.getDate() + (direction === 'next' ? 1 : -1)));
        }
        
        const formattedDate = newDate.toISOString().split('T')[0];
        window.location.href = `{{ route('schedule') }}?view=${view}&date=${formattedDate}`;
    }
</script>
@endsection