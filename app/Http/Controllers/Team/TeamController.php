<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\StoreTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Models\Team;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function __construct(private ActivityLogService $activityLog) {}

    public function index(Request $request)
    {
        $teams = $request->user()->teams()->withCount('members', 'tasks')->get();
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(StoreTeamRequest $request)
    {
        DB::transaction(function () use ($request) {
            $team = Team::create([
                'name'        => $request->name,
                'description' => $request->description,
                'owner_id'    => $request->user()->id,
            ]);

            // Add owner to team_user
            $team->members()->attach($request->user()->id, [
                'role'      => 'owner',
                'joined_at' => now(),
            ]);

            session(['current_team_id' => $team->id]);

            $this->activityLog->memberAdded($team, $request->user(), $request->user(), 'owner');
        });

        return redirect()->route('dashboard')->with('success', 'Team created successfully!');
    }

    public function show(Request $request, Team $team)
    {
        $this->authorize('view', $team);
        $members = $team->members()->withPivot('role', 'joined_at')->get();
        $stats   = $team->getTaskStats();
        $recentActivity = $team->activityLogs()->with(['user', 'task'])->limit(20)->get();
        $currentUserRole = $request->user()->getRoleInTeam($team);
        return view('teams.show', compact('team', 'members', 'stats', 'recentActivity', 'currentUserRole'));
    }

    public function edit(Team $team)
    {
        $this->authorize('update', $team);
        return view('teams.edit', compact('team'));
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        $this->authorize('update', $team);
        $team->update($request->only('name', 'description'));
        return redirect()->route('teams.show', $team)->with('success', 'Team updated.');
    }

    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);
        $team->delete();
        session()->forget('current_team_id');
        return redirect()->route('teams.index')->with('success', 'Team deleted.');
    }
}
