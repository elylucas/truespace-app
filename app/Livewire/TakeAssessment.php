<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Response;
use Livewire\Component;

class TakeAssessment extends Component
{
    public Assessment $assessment;
    public array $questions = [];
    public int $currentStep = 0;
    public array $answers = [];
    public bool $completed = false;

    public function mount(Assessment $assessment)
    {
        $this->assessment = $assessment;
        $this->questions = $assessment->questions()->orderBy('sort_order')->get()->toArray();

        // Load existing responses
        $existingResponses = Response::where('assessment_id', $assessment->id)
            ->where('user_id', auth()->id())
            ->pluck('value', 'question_id')
            ->toArray();

        foreach ($this->questions as $question) {
            $this->answers[$question['id']] = $existingResponses[$question['id']] ?? '';
        }
    }

    public function nextStep()
    {
        if (!$this->validateCurrentStep()) {
            return;
        }

        if ($this->currentStep < count($this->questions) - 1) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 0) {
            $this->currentStep--;
        }
    }

    public function goToStep(int $step)
    {
        if ($step >= 0 && $step < count($this->questions)) {
            $this->currentStep = $step;
        }
    }

    public function submit()
    {
        if (!$this->validateCurrentStep()) {
            return;
        }

        foreach ($this->questions as $question) {
            $value = $this->answers[$question['id']] ?? null;

            if ($value !== null && $value !== '') {
                Response::updateOrCreate(
                    [
                        'question_id' => $question['id'],
                        'user_id' => auth()->id(),
                        'assessment_id' => $this->assessment->id,
                    ],
                    ['value' => $value]
                );
            }
        }

        $this->completed = true;
    }

    protected function validateCurrentStep(): bool
    {
        $this->resetErrorBag('answer');

        $question = $this->questions[$this->currentStep];
        $answer = $this->answers[$question['id']] ?? '';

        if ($question['type'] === 'number' && $answer !== '') {
            if (!is_numeric($answer) || (int) $answer < 1 || (int) $answer > 10) {
                $this->addError('answer', 'Please enter a number between 1 and 10.');
                return false;
            }
        }

        return true;
    }

    public function getCurrentQuestionProperty()
    {
        return $this->questions[$this->currentStep] ?? null;
    }

    public function getProgressProperty(): float
    {
        if (count($this->questions) === 0) {
            return 0;
        }

        return round(($this->currentStep + 1) / count($this->questions) * 100);
    }

    public function render()
    {
        return view('livewire.take-assessment');
    }
}
