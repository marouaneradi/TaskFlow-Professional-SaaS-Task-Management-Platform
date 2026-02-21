<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function __construct(private ActivityLogService $activityLog) {}

    public function store(Request $request, Team $team)
    {
        $this->authorize('manageMembers', $team);

        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'role'  => ['required', 'in:admin,member'],
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->isMemberOf($team)) {
            return back()->withErrors(['email' => 'User is already a member of this team.']);
        }

        $team->members()->attach($user->id, [
            'role'      => $request->role,
            'joined_at' => now(),
        ]);

        $this->activityLog->memberAdded($team, $request->user(), $user, $request->role);

        return back()->with('success', "{$user->name} has been added to the team.");
    }

    public function updateRole(Request $request, Team $team, User $user)
    {
        $this->authorize('manageMembers', $team);

        $request->validate([
            'role' => ['required', 'in:admin,member'],
        ]);

        // Prevent changing owner's role
        if ($user->id === $team->owner_id) {
            return back()->withErrors(['role' => 'Cannot change the owner\'s role.']);
        }

        $team->members()->updateExistingPivot($user->id, ['role' => $request->role]);

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroy(Request $request, Team $team, User $user)
    {
        $this->authorize('manageMembers', $team);

        // Prevent removing owner
        if ($user->id === $team->owner_id) {
            return back()->withErrors(['member' => 'Cannot remove the team owner.']);
        }

        // Allow member to leave themselves
        if ($user->id !== $request->user()->id) {
            $this->authorize('manageMembers', $team);
        }

        $team->members()->detach($user->id);

        $this->activityLog->memberRemoved($team, $request->user(), $user);

        if ($user->id === $request->user()->id) {
            return redirect()->route('teams.index')->with('success', 'You have left the team.');
        }

        return back()->with('success', "{$user->name} has been removed from the team.");
    }
}
