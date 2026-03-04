<?php

namespace App\Livewire;

use App\Models\ChatMessage;
use App\Models\Response;
use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

class AskTrudy extends Component
{
    public array $messages = [];
    public string $input = '';

    public function mount(): void
    {
        $history = ChatMessage::where('user_id', auth()->id())
            ->orderBy('created_at')
            ->get();

        if ($history->isEmpty()) {
            $greeting = "Hi, I'm Trudy! I'm here to help you understand your assessment results. Feel free to ask me anything about your scores or what they mean.";

            ChatMessage::create([
                'user_id' => auth()->id(),
                'role'    => 'assistant',
                'content' => $greeting,
            ]);

            $this->messages = [['role' => 'assistant', 'content' => $greeting]];
        } else {
            $this->messages = $history->map(fn ($m) => [
                'role'    => $m->role,
                'content' => $m->content,
            ])->toArray();
        }
    }

    public function sendMessage(): void
    {
        $text = trim($this->input);

        if ($text === '') {
            return;
        }

        $this->input = '';

        // Persist and display user message
        ChatMessage::create([
            'user_id' => auth()->id(),
            'role'    => 'user',
            'content' => $text,
        ]);

        $this->messages[] = ['role' => 'user', 'content' => $text];

        // Build OpenAI message list: system + full history
        $openAiMessages = array_merge(
            [['role' => 'system', 'content' => $this->buildSystemPrompt()]],
            $this->messages
        );

        $response = OpenAI::chat()->create([
            'model'    => 'gpt-4o',
            'messages' => $openAiMessages,
        ]);

        $reply = $response->choices[0]->message->content;

        ChatMessage::create([
            'user_id' => auth()->id(),
            'role'    => 'assistant',
            'content' => $reply,
        ]);

        $this->messages[] = ['role' => 'assistant', 'content' => $reply];
    }

    private function buildSystemPrompt(): string
    {
        $user = auth()->user();

        $responses = Response::with(['question', 'question.assessment'])
            ->where('user_id', $user->id)
            ->get()
            ->groupBy('question.assessment_id');

        $assessmentData = '';

        if ($responses->isEmpty()) {
            $assessmentData = "This user has not completed any assessments yet.";
        } else {
            foreach ($responses as $assessmentId => $group) {
                $assessment = $group->first()->question->assessment;
                $assessmentData .= "Assessment: {$assessment->title} (status: {$assessment->status})\n";
                foreach ($group as $response) {
                    $assessmentData .= "  Q: {$response->question->text}\n";
                    $assessmentData .= "  A: {$response->value}\n";
                }
                $assessmentData .= "\n";
            }
        }

        return <<<PROMPT
You are Trudy, a friendly and insightful assessment coach for TrueSpace. Your personality is warm, encouraging, and professional.

You help users understand their own assessment results. You ONLY have access to the data provided below — do not invent scores, results, or information that is not present.

If the user asks about another person's results or data you don't have, politely decline and explain you can only discuss their own results.

For numeric scores (rated 1-10), give context about where that falls (e.g. "an 8 out of 10 is quite strong"). For Likert-scale answers (Strongly Agree / Agree / Neutral / Disagree / Strongly Disagree), explain what the response suggests.

Keep responses concise and friendly. If the user hasn't taken any assessments, encourage them to do so.

### Assessment Data for {$user->name}

{$assessmentData}
PROMPT;
    }

    public function render()
    {
        return view('livewire.ask-trudy');
    }
}
