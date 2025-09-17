<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Google Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(isset($error))
                        <div class="px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
                            {{ $error }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">Your Tasks</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">All your tasks from Google Tasks</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-collapse border-gray-300 table-auto dark:border-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Status</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Task</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Notes</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Task List</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Due Date</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Updated</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Completed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($tasks) && count($tasks) > 0)
                                    @foreach($tasks as $task)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $task['status'] === 'completed' ? 'opacity-60' : '' }}">
                                            <td class="px-4 py-2 text-center border border-gray-300 dark:border-gray-600">
                                                @if($task['status'] === 'completed')
                                                    <span class="flex items-center justify-center inline-block w-5 h-5 text-xs text-white bg-green-500 rounded">✓</span>
                                                @else
                                                    <span class="inline-block w-5 h-5 border-2 border-gray-400 rounded"></span>
                                                @endif
                                            </td>
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 {{ $task['status'] === 'completed' ? 'line-through' : '' }}">
                                                {{ $task['title'] ?: 'Untitled Task' }}
                                            </td>
                                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                                @if($task['notes'])
                                                    <span class="text-gray-600 dark:text-gray-400">
                                                        {{ Str::limit(strip_tags($task['notes']), 50) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                                <span class="inline-block px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded">
                                                    {{ $task['taskListTitle'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                                @if($task['due'])
                                                    @try
                                                        {{ \Carbon\Carbon::parse($task['due'])->format('M d, Y') }}
                                                    @catch(\Exception $e)
                                                        {{ $task['due'] }}
                                                    @endtry
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600">
                                                @if($task['updated'])
                                                    @try
                                                        {{ \Carbon\Carbon::parse($task['updated'])->format('M d, h:i A') }}
                                                    @catch(\Exception $e)
                                                        {{ $task['updated'] }}
                                                    @endtry
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600">
                                                @if($task['completed'])
                                                    @try
                                                        {{ \Carbon\Carbon::parse($task['completed'])->format('M d, h:i A') }}
                                                    @catch(\Exception $e)
                                                        {{ $task['completed'] }}
                                                    @endtry
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-500 border border-gray-300 dark:border-gray-600">
                                            No tasks found. 
                                            <a href="{{ route('google-auth') }}" class="text-blue-500 hover:underline">
                                                Re-authenticate with Google
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        <p>Data refreshed: {{ now()->format('M d, Y h:i A') }}</p>
                        @if(isset($tasks) && count($tasks) > 0)
                            <p class="mt-1">
                                Total: {{ count($tasks) }} tasks 
                                ({{ collect($tasks)->where('status', 'completed')->count() }} completed, 
                                {{ collect($tasks)->where('status', '!=', 'completed')->count() }} pending)
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>