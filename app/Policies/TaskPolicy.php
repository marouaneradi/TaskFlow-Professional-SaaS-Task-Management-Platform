<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user, Team $team): bool
    {
        return $user->isMemberOf($team);
    }

    public function view(User $user, Task $task): bool
    {
        return $user->isMemberOf($task->team);
    }

    public function create(User $user, Team $team): bool
    {
        return $user->isMemberOf($team);
    }

    public function update(User $user, Task $task): bool
    {
        $team = $task->team;

        // Admins/owners can update any task
        if ($user->isAdminOf($team)) {
            return true;
        }

        // Members can only update tasks assigned to them
        return $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        $team = $task->team;

        if ($user->isAdminOf($team)) {
            return true;
        }

        return $task->created_by === $user->id;
    }

    public function restore(User $user, Task $task): bool
    {
        return $user->isAdminOf($task->team);
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $user->isOwnerOf($task->team);
    }

    public function assign(User $user, Task $task): bool
    {
        return $user->isAdminOf($task->team);
    }
}
