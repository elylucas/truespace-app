<div class="py-8">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div
            class="bg-white rounded-xl shadow-ts-card flex flex-col"
            style="height: 70vh;"
            x-data="{}"
        >
            {{-- Chat message list --}}
            <div
                class="flex-1 overflow-y-auto p-6 space-y-4"
                x-ref="chatContainer"
                x-init="
                    $nextTick(() => { $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight; });
                    $watch('$wire.messages', () => {
                        $nextTick(() => { $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight; });
                    });
                "
            >
                @foreach($messages as $message)
                    @if($message['role'] === 'assistant')
                        {{-- Trudy message --}}
                        <div class="flex items-start gap-3">
                            <div class="shrink-0 w-8 h-8 rounded-full bg-ts-teal flex items-center justify-center text-white text-xs font-semibold">
                                T
                            </div>
                            <div class="max-w-[80%] bg-ts-cream rounded-2xl rounded-tl-sm px-4 py-3 text-sm text-ts-text">
                                {!! nl2br(e($message['content'])) !!}
                            </div>
                        </div>
                    @else
                        {{-- User message --}}
                        <div class="flex items-start gap-3 flex-row-reverse">
                            <div class="shrink-0 w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-xs font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="max-w-[80%] bg-ts-teal text-white rounded-2xl rounded-tr-sm px-4 py-3 text-sm">
                                {!! nl2br(e($message['content'])) !!}
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- Loading indicator (shown while Livewire request is in flight) --}}
                <div wire:loading wire:target="sendMessage" class="flex items-start gap-3">
                    <div class="shrink-0 w-8 h-8 rounded-full bg-ts-teal flex items-center justify-center text-white text-xs font-semibold">
                        T
                    </div>
                    <div class="bg-ts-cream rounded-2xl rounded-tl-sm px-4 py-3">
                        <div class="flex gap-1 items-center h-5">
                            <span class="w-2 h-2 bg-ts-teal rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                            <span class="w-2 h-2 bg-ts-teal rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                            <span class="w-2 h-2 bg-ts-teal rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Input bar --}}
            <div class="border-t border-gray-100 p-4">
                <form wire:submit.prevent="sendMessage" class="flex gap-3">
                    <input
                        wire:model="input"
                        type="text"
                        placeholder="Ask Trudy about your assessment results..."
                        class="flex-1 rounded-lg border border-gray-200 px-4 py-2 text-sm text-ts-text focus:outline-none focus:ring-2 focus:ring-ts-teal focus:border-transparent"
                        wire:loading.attr="disabled" wire:target="sendMessage"
                        autocomplete="off"
                    />
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 bg-ts-teal text-white text-sm font-normal rounded-lg focus:outline-none focus:ring-2 focus:ring-ts-teal focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition"
                        wire:loading.attr="disabled" wire:target="sendMessage"
                    >
                        <span wire:loading.remove wire:target="sendMessage">Send</span>
                        <span wire:loading wire:target="sendMessage">...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
