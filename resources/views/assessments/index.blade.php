<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assessments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6" x-data="{ filter: 'all' }">
                    <!-- Filter Dropdown -->
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">All Assessments</h3>
                        <div>
                            <label for="status-filter" class="sr-only">Filter by status</label>
                            <select id="status-filter" x-model="filter"
                                class="rounded-md border-gray-300 shadow-sm focus:border-ts-teal focus:ring-ts-teal text-sm">
                                <option value="all">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="draft">Draft</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Assessments Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responses</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($assessments as $assessment)
                                    <tr x-show="filter === 'all' || filter === '{{ $assessment->status }}'">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('assessments.show', $assessment) }}" class="text-ts-teal hover:text-ts-blue font-medium">
                                                {{ $assessment->title }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $assessment->organization->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($assessment->status === 'active') bg-green-100 text-green-800
                                                @elseif($assessment->status === 'draft') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($assessment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $assessment->questions_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $assessment->responses_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('assessments.show', $assessment) }}" class="text-ts-teal hover:text-ts-blue mr-3">View</a>
                                            @can('manage-assessments')
                                            <a href="{{ route('assessments.edit', $assessment) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
