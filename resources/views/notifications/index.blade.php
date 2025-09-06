@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Notifications</h1>
        @if($notifications->where('read_at', null)->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <x-button type="submit" variant="outline" size="sm">
                    Mark all as read
                </x-button>
            </form>
        @endif
    </div>
    
    <!-- Notifications List -->
    <x-card :padding="false">
        <div class="divide-y divide-gray-100">
            @forelse($notifications as $notification)
                <div class="p-6 hover:bg-gray-50 transition-colors {{ $notification->isRead() ? '' : 'bg-blue-50' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-800">
                                {{ $notification->title ?? 'Notification' }}
                            </h3>
                            <p class="text-gray-600 mt-1">
                                {{ $notification->message ?? '' }}
                            </p>
                            <p class="text-sm text-gray-500 mt-2">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if(!$notification->isRead())
                            <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                @csrf
                                <button type="submit" class="ml-4 text-sm text-emerald-600 hover:text-emerald-700">
                                    Mark as read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                    <p class="mt-1 text-sm text-gray-500">You're all caught up!</p>
                </div>
            @endforelse
        </div>
        
        @if($notifications->hasPages())
            <div class="p-4 border-t">
                {{ $notifications->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection