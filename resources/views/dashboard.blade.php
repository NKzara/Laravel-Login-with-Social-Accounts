<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}

                    @if(session('success'))
                        <div class="px-4 py-3 mt-4 text-green-700 bg-green-100 border border-green-400 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="mt-6">
                        <h3 class="mb-4 text-lg font-semibold">Google Services</h3>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <!-- Calendar Card -->
                            <div class="p-6 border border-blue-200 rounded-lg bg-blue-50 dark:bg-blue-900 dark:border-blue-700">
                                <div class="flex items-center mb-4">
                                    <svg class="w-8 h-8 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-200">Calendar</h4>
                                </div>
                                <p class="mb-4 text-blue-700 dark:text-blue-300">View your upcoming events and schedule</p>
                                <a href="{{ route('calendar.index') }}" class="inline-block px-4 py-2 text-white transition-colors bg-blue-600 rounded hover:bg-blue-700">
                                    View Calendar
                                </a>
                            </div>

                            <!-- Email Card -->
                            <div class="p-6 border border-green-200 rounded-lg bg-green-50 dark:bg-green-900 dark:border-green-700">
                                <div class="flex items-center mb-4">
                                    <svg class="w-8 h-8 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                    <h4 class="text-lg font-semibold text-green-800 dark:text-green-200">Email</h4>
                                </div>
                                <p class="mb-4 text-green-700 dark:text-green-300">Check your latest Gmail messages</p>
                                <a href="{{ route('email.index') }}" class="inline-block px-4 py-2 text-white transition-colors bg-green-600 rounded hover:bg-green-700">
                                    View Emails
                                </a>
                            </div>

                            <!-- Tasks Card -->
                            <div class="p-6 border border-purple-200 rounded-lg bg-purple-50 dark:bg-purple-900 dark:border-purple-700">
                                <div class="flex items-center mb-4">
                                    <svg class="w-8 h-8 mr-3 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <h4 class="text-lg font-semibold text-purple-800 dark:text-purple-200">Tasks</h4>
                                </div>
                                <p class="mb-4 text-purple-700 dark:text-purple-300">Manage your Google Tasks and ToDos</p>
                                <a href="{{ route('todos.index') }}" class="inline-block px-4 py-2 text-white transition-colors bg-purple-600 rounded hover:bg-purple-700">
                                    View Tasks
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(!auth()->user()->google_token)
                        <div class="p-4 mt-6 bg-yellow-100 border border-yellow-400 rounded">
                            <p class="text-yellow-700">
                                <strong>Note:</strong> To access Google services, please 
                                <a href="{{ route('google-auth') }}" class="text-blue-600 hover:underline">authenticate with Google</a> first.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>