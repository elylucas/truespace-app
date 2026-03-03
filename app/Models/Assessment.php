<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = ['title', 'description', 'organization_id', 'status'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('sort_order');
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function getCompletionRateAttribute(): float
    {
        $totalQuestions = $this->questions()->count();
        if ($totalQuestions === 0) {
            return 0;
        }

        $usersWithResponses = $this->responses()
            ->distinct('user_id')
            ->count('user_id');

        if ($usersWithResponses === 0) {
            return 0;
        }

        $totalExpected = $usersWithResponses * $totalQuestions;
        $totalResponses = $this->responses()->count();

        return round(($totalResponses / $totalExpected) * 100, 1);
    }
}
