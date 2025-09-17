<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Gmail Inbox') }}
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
                        <h3 class="text-lg font-semibold">Recent Emails</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Your latest 20 emails from Gmail inbox</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-collapse border-gray-300 table-auto dark:border-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Status</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">From</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Subject</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Preview</th>
                                    <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($emails) && count($emails) > 0)
                                    @foreach($emails as $email)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $email['unread'] ? 'font-semibold' : '' }}">
                                            <td class="px-4 py-2 text-center border border-gray-300 dark:border-gray-600">
                                                @if($email['unread'])
                                                    <span class="inline-block w-3 h-3 bg-blue-500 rounded-full" title="Unread"></span>
                                                @else
                                                    <span class="inline-block w-3 h-3 bg-gray-300 rounded-full" title="Read"></span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                                @php
                                                    // Extract email from "Name <email>" format
                                                    $from = $email['from'];
                                                    if (preg_match('/(.+)<(.+)>/', $from, $matches)) {
                                                        $from = trim($matches[1]);
                                                    } elseif (preg_match('/<(.+)>/', $from, $matches)) {
                                                        $from = $matches[1];
                                                    }
                                                @endphp
                                                <span title="{{ $email['from'] }}">{{ Str::limit($from, 30) }}</span>
                                            </td>
                                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                                {{ Str::limit($email['subject'] ?: '(No Subject)', 50) }}
                                            </td>
                                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                                <span class="text-gray-600 dark:text-gray-400">
                                                    {{ Str::limit($email['snippet'], 80) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600">
                                                @try
                                                    {{ \Carbon\Carbon::parse($email['date'])->format('M d, h:i A') }}
                                                @catch(\Exception $e)
                                                    {{ $email['date'] }}
                                                @endtry
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 border border-gray-300 dark:border-gray-600">
                                            No emails found. 
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