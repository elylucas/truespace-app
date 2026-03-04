<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $assessment->title }}
            </h2>
            <div class="flex items-center space-x-3">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                    @if($assessment->status === 'active') bg-green-100 text-green-800
                    @elseif($assessment->status === 'draft') bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($assessment->status) }}
                </span>
                <a href="{{ route('assessments.edit', $assessment) }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-normal text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Assessment Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <p class="text-gray-600 mb-6">{{ $assessment->description }}</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <span>Organization: <strong>{{ $assessment->organization->name }}</strong></span>
                        <span class="mx-3">|</span>
                        <span>Created: {{ $assessment->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Questions</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalQuestions }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Respondents</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $uniqueRespondents }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Total Responses</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalResponses }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Completion Rate</div>
                        <div class="mt-1 text-2xl font-semibold text-ts-teal">{{ $completionRate }}%</div>
                    </div>
                </div>
            </div>

            <!-- Take Assessment Button -->
            @if($assessment->status === 'active')
                <div class="mb-6" x-data="{ showConfirm: false }">
                    <button @click="showConfirm = true"
                        class="inline-flex items-center px-6 py-3 bg-ts-teal border border-transparent rounded-md font-normal text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-ts-teal focus:ring-offset-2 transition ease-in-out duration-150">
                        Take Assessment
                    </button>

                    <!-- Confirmation Modal -->
                    <div x-show="showConfirm" x-cloak
                         class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="showConfirm" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showConfirm = false"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <div x-show="showConfirm" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Start Assessment
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            You are about to take "{{ $assessment->title }}". This assessment has {{ $totalQuestions }} questions.
                                            Your previous responses (if any) will be loaded.
                                        </p>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <a href="{{ route('assessments.take', $assessment) }}"
                                       class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-ts-teal text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ts-teal sm:ml-3 sm:w-auto sm:text-sm">
                                        Begin
                                    </a>
                                    <button @click="showConfirm = false" type="button"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ts-teal sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Questions List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Questions</h3>
                    <div class="space-y-4">
                        @foreach($assessment->questions as $index => $question)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-1">
                                            <span class="text-sm font-medium text-gray-500 mr-2">Q{{ $index + 1 }}.</span>
                                            <span class="px-2 py-0.5 text-xs rounded-full
                                                @if($question->type === 'text') bg-blue-100 text-blue-800
                                                @elseif($question->type === 'number') bg-purple-100 text-purple-800
                                                @else bg-orange-100 text-orange-800 @endif">
                                                {{ ucfirst($question->type) }}
                                            </span>
                                        </div>
                                        <p class="text-gray-900">{{ $question->text }}</p>
                                        @if($question->type === 'choice' && $question->options)
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @foreach($question->options as $option)
                                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $option }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if($question->type === 'number')
                                            <p class="mt-1 text-xs text-gray-400">Scale: 1-10</p>
                                        @endif
                                    </div>
                                    <div class="ml-4 text-sm text-gray-400">
                                        {{ $question->responses->count() }} responses
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
