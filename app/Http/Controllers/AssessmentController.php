<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Response;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index()
    {
        $query = Assessment::with('organization')
            ->withCount('questions', 'responses')
            ->latest();

        if (auth()->user()->role === 'member') {
            $query->where('status', 'active');
        }

        $assessments = $query->get();

        // For members, fetch how many questions they've answered per assessment
        $userAnswerCounts = [];
        if (auth()->user()->role === 'member') {
            $userAnswerCounts = Response::where('user_id', auth()->id())
                ->whereIn('assessment_id', $assessments->pluck('id'))
                ->selectRaw('assessment_id, count(*) as count')
                ->groupBy('assessment_id')
                ->pluck('count', 'assessment_id')
                ->toArray();
        }

        return view('assessments.index', compact('assessments', 'userAnswerCounts'));
    }

    public function show(Assessment $assessment)
    {
        if (auth()->user()->role === 'member' && $assessment->status !== 'active') {
            abort(404);
        }

        $assessment->load(['questions', 'organization', 'responses']);

        $totalQuestions = $assessment->questions->count();
        $uniqueRespondents = $assessment->responses->unique('user_id')->count();
        $totalResponses = $assessment->responses->count();
        $completionRate = $assessment->completion_rate;

        return view('assessments.show', compact(
            'assessment',
            'totalQuestions',
            'uniqueRespondents',
            'totalResponses',
            'completionRate',
        ));
    }

    public function edit(Assessment $assessment)
    {
        $assessment->load('questions');

        return view('assessments.edit', compact('assessment'));
    }

    public function update(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,closed',
        ]);

        $assessment->update($validated);

        return redirect()->route('assessments.show', $assessment)
            ->with('success', 'Assessment updated successfully.');
    }

    public function take(Assessment $assessment)
    {
        if ($assessment->status !== 'active') {
            return redirect()->route('assessments.show', $assessment)
                ->with('error', 'This assessment is not currently active.');
        }

        return view('assessments.take', compact('assessment'));
    }
}
