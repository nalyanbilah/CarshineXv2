<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tasks</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="max-w-2xl mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">CarshineXv2 - Task List</h1>
        <ul class="space-y-2">
            @foreach($tasks as $task)
                <li class="p-3 rounded-lg shadow bg-white flex justify-between">
                    <span>{{ $task->title }}</span>
                    @if($task->completed)
                        <span class="text-green-600 font-semibold">Done ✅</span>
                    @else
                        <span class="text-red-600 font-semibold">Pending ⏳</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>
