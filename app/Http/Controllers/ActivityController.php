<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request, Team $team)
    {
        $this->authorize('view', $team);

        $activities = $team->activityLogs()
            ->with(['user', 'task'])
            ->paginate(20);

        return view('activity.index', compact('team', 'activities'));
    }
}
