<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center">
            <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="relative">
                <input type="text" 
                       placeholder="Search anything..." 
                       class="w-64 px-4 py-2 pl-10 text-sm text-gray-700 bg-gray-100 border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            
            <!-- Notifications -->
            <x-notification-bell />
            
            <!-- User Profile -->
            @php
                $user = \Illuminate\Support\Facades\Auth::user();
            @endphp
            @if($user)
            <div class="flex items-center space-x-3">
                <div class="text-right hidden lg:block">
                    <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $user->position ?? 'Staff' }}</p>
                </div>
                <div class="relative">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                         alt="Profile" 
                         class="w-10 h-10 rounded-full object-cover">
                    <span class="absolute bottom-0 right-0 block w-3 h-3 bg-green-400 border-2 border-white rounded-full"></span>
                </div>
            </div>
            @endif
            
            <!-- Current Time -->
            <div class="text-right ml-4 hidden lg:block">
                <p class="text-xs text-gray-500">Current time</p>
                <p class="text-sm font-medium text-gray-700" id="current-time">{{ now()->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>
</header>

<script>
    // Update time every second
    setInterval(() => {
        const now = new Date();
        const options = { day: 'numeric', month: 'short', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true };
        document.getElementById('current-time').textContent = now.toLocaleString('en-GB', options).replace(',', ',');
    }, 1000);
</script>