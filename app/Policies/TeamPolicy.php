<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Team $team): bool
    {
        return $user->isMemberOf($team);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Team $team): bool
    {
        return $user->isAdminOf($team);
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->isOwnerOf($team);
    }

    public function manageMembers(User $user, Team $team): bool
    {
        return $user->isAdminOf($team);
    }

    public function removeOwner(User $user, Team $team): bool
    {
        return $user->isOwnerOf($team);
    }
}
