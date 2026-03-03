<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Response;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalUsers = User::count();
        $totalAssessments = Assessment::count();
        $totalResponses = Response::count();

        $activeAssessments = Assessment::where('status', 'active')->count();

        $recentAssessments = Assessment::with('organization')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalUsers',
            'totalAssessments',
            'totalResponses',
            'activeAssessments',
            'recentAssessments',
        ));
    }
}
