<div>
    @if($completed)
        <!-- Completion Screen -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Assessment Complete!</h3>
                <p class="text-gray-500 mb-6">Your responses have been saved successfully.</p>
                <a href="{{ route('assessments.show', $assessment) }}"
                   class="inline-flex items-center px-4 py-2 bg-ts-teal border border-transparent rounded-md font-normal text-xs text-white uppercase tracking-widest">
                    Back to Assessment
                </a>
            </div>
        </div>
    @else
        <!-- Progress Bar -->
        <div class="mb-6">
            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                <span>Question {{ $currentStep + 1 }} of {{ count($questions) }}</span>
                <span>{{ $this->progress }}% Complete</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-ts-teal h-2.5 rounded-full transition-all duration-300"
                     style="width: {{ $this->progress }}%"></div>
            </div>
        </div>

        <!-- Question Navigation Dots -->
        <div class="flex items-center justify-center space-x-2 mb-6">
            @foreach($questions as $index => $q)
                <button wire:click="goToStep({{ $index }})"
                    class="w-3 h-3 rounded-full transition-colors duration-200
                        @if($index === $currentStep) bg-ts-teal
                        @elseif(isset($answers[$q['id']]) && $answers[$q['id']] !== '') bg-ts-blue
                        @else bg-gray-300 @endif"
                    title="Question {{ $index + 1 }}">
                </button>
            @endforeach
        </div>

        <!-- Question Card -->
        @php $question = $questions[$currentStep]; @endphp
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Question Type Badge -->
                <div class="mb-2">
                    <span class="px-2 py-0.5 text-xs rounded-full
                        @if($question['type'] === 'text') bg-blue-100 text-blue-800
                        @elseif($question['type'] === 'number') bg-purple-100 text-purple-800
                        @else bg-orange-100 text-orange-800 @endif">
                        {{ ucfirst($question['type']) }}
                    </span>
                </div>

                <!-- Question Text -->
                <h3 class="text-lg font-medium text-gray-900 mb-6">{{ $question['text'] }}</h3>

                <!-- Answer Input -->
                <div class="mb-6">
                    @if($question['type'] === 'text')
                        <textarea
                            wire:model="answers.{{ $question['id'] }}"
                            rows="4"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-ts-teal focus:ring-ts-teal"
                            placeholder="Type your answer here..."></textarea>

                    @elseif($question['type'] === 'number')
                        <div>
                            <label class="block text-sm text-gray-500 mb-2">Rate from 1 (lowest) to 10 (highest)</label>
                            <div class="flex items-center space-x-2">
                                @for($i = 1; $i <= 10; $i++)
                                    <button type="button"
                                        wire:click="$set('answers.{{ $question['id'] }}', '{{ $i }}')"
                                        class="w-10 h-10 rounded-full border-2 text-sm font-medium transition-colors duration-200
                                            {{ ($answers[$question['id']] ?? '') == $i
                                                ? 'border-ts-teal bg-ts-teal text-white'
                                                : 'border-gray-300 text-gray-700 hover:border-ts-blue' }}">
                                        {{ $i }}
                                    </button>
                                @endfor
                            </div>
                        </div>

                    @elseif($question['type'] === 'choice')
                        <div class="space-y-3">
                            @foreach($question['options'] ?? [] as $option)
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer transition-colors duration-200
                                    {{ ($answers[$question['id']] ?? '') === $option
                                        ? 'border-ts-teal bg-cyan-50'
                                        : 'border-gray-200 hover:border-ts-blue' }}">
                                    <input type="radio"
                                           wire:model="answers.{{ $question['id'] }}"
                                           value="{{ $option }}"
                                           class="h-4 w-4 text-ts-teal focus:ring-ts-teal border-gray-300">
                                    <span class="ml-3 text-sm text-gray-700">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>

                @error('answer')
                    <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
                @enderror

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <button wire:click="previousStep"
                        @if($currentStep === 0) disabled @endif
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-normal text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Previous
                    </button>

                    @if($currentStep === count($questions) - 1)
                        <button wire:click="submit"
                            class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-md font-normal text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700">
                            Submit Assessment
                        </button>
                    @else
                        <button wire:click="nextStep"
                            class="inline-flex items-center px-4 py-2 bg-ts-teal border border-transparent rounded-md font-normal text-xs text-white uppercase tracking-widest">
                            Next
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
