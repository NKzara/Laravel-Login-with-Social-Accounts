<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Google Calendar Events') }}
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
                        <h3 class="text-lg font-semibold">Upcoming Calendar Events</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Your next 20 upcoming events from Google Calendar</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-collapse border-gray-300 table-auto dark:border-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Event Title</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Start Date/Time</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">End Date/Time</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Location</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Attendees</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($events) && count($events) > 0)
                                    @foreach($events as $event)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                                {{ $event->getSummary() ?? 'No Title' }}
                                            </td>
                                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                                @if($event->getStart())
                                                    @if($event->getStart()->getDateTime())
                                                        {{ \Carbon\Carbon::parse($event->getStart()->getDateTime())->format('M d, Y h:i A') }}
                                                    @elseif($event->getStart()->getDate())
                                                        {{ \Carbon\Carbon::parse($event->getStart()->getDate())->format('M d, Y') }} (All day)
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                                @if($event->getEnd())
                                                    @if($event->getEnd()->getDateTime())
                                                        {{ \Carbon\Carbon::parse($event->getEnd()->getDateTime())->format('M d, Y h:i A') }}
                                                    @elseif($event->getEnd()->getDate())
                                                        {{ \Carbon\Carbon::parse($event->getEnd()->getDate())->format('M d, Y') }} (All day)
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                                {{ $event->getLocation() ?? '-' }}
                                            </td>
                                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                                @if($event->getAttendees())
                                                    {{ count($event->getAttendees()) }} attendee(s)
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 border border-gray-300 dark:border-gray-600">
                                            No upcoming events found. 
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>