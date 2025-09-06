@props(['title', 'value', 'subtitle' => null, 'trend' => null, 'icon' => null, 'color' => 'emerald'])

@php
    $colors = [
        'emerald' => 'from-emerald-400 to-teal-600',
        'blue' => 'from-blue-400 to-indigo-600',
        'purple' => 'from-purple-400 to-pink-600',
        'orange' => 'from-orange-400 to-red-600',
    ];
    $bgColor = $colors[$color] ?? $colors['emerald'];
@endphp

<x-card :padding="false">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">{{ $title }}</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $value }}</p>
                @if($subtitle)
                    <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            @if($icon)
                <div class="w-12 h-12 bg-gradient-to-br {{ $bgColor }} rounded-lg flex items-center justify-center text-white">
                    {!! $icon !!}
                </div>
            @endif
        </div>
        @if($trend !== null)
            <div class="mt-4 flex items-center">
                @if($trend > 0)
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    <span class="text-sm text-green-500">{{ abs($trend) }}%</span>
                @else
                    <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    <span class="text-sm text-red-500">{{ abs($trend) }}%</span>
                @endif
                <span class="text-sm text-gray-500 ml-2">vs last period</span>
            </div>
        @endif
    </div>
</x-card>