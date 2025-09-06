<aside class="w-20 lg:w-64 bg-white shadow-lg flex flex-col transition-all duration-300">
    <div class="p-4 border-b">
        <div class="flex items-center justify-center lg:justify-start">
            <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-lg">CX</span>
            </div>
            <span class="ml-3 text-xl font-semibold text-gray-800 hidden lg:block">Carshine X</span>
        </div>
    </div>
    
    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-emerald-600' : 'text-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="ml-3 hidden lg:block">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('schedule') }}" 
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('schedule') ? 'bg-gray-100 text-emerald-600' : 'text-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="ml-3 hidden lg:block">Schedule</span>
                </a>
            </li>
            <li>
                <a href="{{ route('tasks') }}" 
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('tasks') ? 'bg-gray-100 text-emerald-600' : 'text-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span class="ml-3 hidden lg:block">Tasks</span>
                </a>
            </li>
            <li>
                <a href="{{ route('performance') }}" 
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('performance') ? 'bg-gray-100 text-emerald-600' : 'text-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="ml-3 hidden lg:block">Performance</span>
                </a>
            </li>
            <li>
                <a href="{{ route('leave') }}" 
                   class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('leave') ? 'bg-gray-100 text-emerald-600' : 'text-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    <span class="ml-3 hidden lg:block">Leave</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="p-4 border-t">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center p-3 w-full rounded-lg hover:bg-gray-100 transition-colors text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="ml-3 hidden lg:block">Logout</span>
            </button>
        </form>
    </div>
</aside>