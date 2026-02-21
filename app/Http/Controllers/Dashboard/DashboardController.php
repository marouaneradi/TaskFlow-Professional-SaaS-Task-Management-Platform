<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $teams = $user->teams()->with(['tasks', 'members'])->get();

        // Get current team (first team or session-stored)
        $currentTeamId = session('current_team_id');
        $currentTeam = $teams->find($currentTeamId) ?? $teams->first();

        if (!$currentTeam) {
            return view('dashboard.no-team');
        }

        $stats = $currentTeam->getTaskStats();
        $completionRate = $currentTeam->getCompletionRate();

        $tasksDueSoon = $currentTeam->tasks()
            ->dueSoon(3)
            ->with(['assignee', 'creator'])
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        $overdueTasksCount = $currentTeam->tasks()->overdue()->count();

        $recentActivity = $currentTeam->activityLogs()
            ->with(['user', 'task'])
            ->limit(10)
            ->get();

        // Chart data - tasks per status
        $chartData = [
            'labels' => ['To Do', 'In Progress', 'Done'],
            'values' => [$stats['todo'], $stats['in_progress'], $stats['done']],
        ];

        // Productivity - tasks completed in last 7 days
        $completedThisWeek = $currentTeam->tasks()
            ->where('status', 'done')
            ->where('completed_at', '>=', now()->subDays(7))
            ->count();

        return view('dashboard.index', compact(
            'user',
            'teams',
            'currentTeam',
            'stats',
            'completionRate',
            'tasksDueSoon',
            'overdueTasksCount',
            'recentActivity',
            'chartData',
            'completedThisWeek'
        ));
    }

    public function switchTeam(Request $request, Team $team)
    {
        $user = $request->user();
        if (!$user->isMemberOf($team)) {
            abort(403);
        }

        session(['current_team_id' => $team->id]);
        return redirect()->route('dashboard');
    }
}
