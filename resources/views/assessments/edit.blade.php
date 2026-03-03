<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit: {{ $assessment->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Edit Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="POST" action="{{ route('assessments.update', $assessment) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title"
                                   value="{{ old('title', $assessment->title) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $assessment->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="draft" @selected(old('status', $assessment->status) === 'draft')>Draft</option>
                                <option value="active" @selected(old('status', $assessment->status) === 'active')>Active</option>
                                <option value="closed" @selected(old('status', $assessment->status) === 'closed')>Closed</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('assessments.show', $assessment) }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Questions (Read-Only) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Questions</h3>
                    <p class="text-sm text-gray-500 mb-4">Questions are displayed in their current sort order. Reordering is not yet available.</p>
                    <div class="space-y-3">
                        @foreach($assessment->questions as $index => $question)
                            <div class="flex items-center border border-gray-200 rounded-lg p-4">
                                <span class="text-sm font-mono text-gray-400 mr-4 w-8">{{ $question->sort_order }}</span>
                                <div class="flex-1">
                                    <p class="text-gray-900">{{ $question->text }}</p>
                                </div>
                                <span class="px-2 py-0.5 text-xs rounded-full ml-4
                                    @if($question->type === 'text') bg-blue-100 text-blue-800
                                    @elseif($question->type === 'number') bg-purple-100 text-purple-800
                                    @else bg-orange-100 text-orange-800 @endif">
                                    {{ ucfirst($question->type) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
