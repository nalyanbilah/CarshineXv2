<div class="relative">
    @php
        $user = \Illuminate\Support\Facades\Auth::user();
        $unreadCount = $user ? $user->notifications()->where('read_at', null)->count() : 0;
    @endphp
    
    <button onclick="toggleNotifications()" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 block h-2 w-2 bg-red-500 rounded-full ring-2 ring-white"></span>
        @endif
    </button>
    
    <!-- Notification Dropdown -->
    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-100 z-50">
        <div class="p-4 border-b">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Notifications</h3>
                @if($unreadCount > 0)
                    <span class="text-xs text-gray-500">{{ $unreadCount }} new</span>
                @endif
            </div>
        </div>
        
        <div class="max-h-96 overflow-y-auto">
            @if($user)
                @forelse($user->notifications()->latest()->take(5)->get() as $notification)
                    <div class="p-4 hover:bg-gray-50 border-b last:border-b-0 {{ $notification->read_at ? '' : 'bg-blue-50' }}">
                        <p class="font-medium text-sm text-gray-800">{{ $notification->title ?? 'New Notification' }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $notification->message ?? '' }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        No notifications
                    </div>
                @endforelse
            @else
                <div class="p-8 text-center text-gray-500">
                    No notifications
                </div>
            @endif
        </div>
        
        <div class="p-3 border-t">
            <a href="{{ route('notifications') }}" class="text-center block text-sm text-emerald-600 hover:text-emerald-700">
                View all notifications
            </a>
        </div>
    </div>
</div>

<script>
    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('hidden');
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        const button = event.target.closest('button');
        
        if (!dropdown.contains(event.target) && !button) {
            dropdown.classList.add('hidden');
        }
    });
</script>