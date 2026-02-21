<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;

class ActivityLogService
{
    public function log(
        Team $team,
        User $user,
        string $action,
        string $description,
        ?Task $task = null,
        ?array $properties = null
    ): ActivityLog {
        return ActivityLog::create([
            'team_id'     => $team->id,
            'user_id'     => $user->id,
            'task_id'     => $task?->id,
            'action'      => $action,
            'description' => $description,
            'properties'  => $properties,
        ]);
    }

    public function taskCreated(Team $team, User $user, Task $task): void
    {
        $this->log($team, $user, 'task_created', 
            "{$user->name} created task \"{$task->title}\"",
            $task,
            ['title' => $task->title, 'priority' => $task->priority]
        );
    }

    public function taskUpdated(Team $team, User $user, Task $task, array $changes): void
    {
        $this->log($team, $user, 'task_updated',
            "{$user->name} updated task \"{$task->title}\"",
            $task,
            $changes
        );
    }

    public function taskDeleted(Team $team, User $user, Task $task): void
    {
        $this->log($team, $user, 'task_deleted',
            "{$user->name} deleted task \"{$task->title}\"",
            $task
        );
    }

    public function statusChanged(Team $team, User $user, Task $task, string $old, string $new): void
    {
        $this->log($team, $user, 'status_changed',
            "{$user->name} changed status of \"{$task->title}\" from {$old} to {$new}",
            $task,
            ['old_status' => $old, 'new_status' => $new]
        );
    }

    public function taskAssigned(Team $team, User $user, Task $task, ?User $assignee): void
    {
        $assigneeName = $assignee ? $assignee->name : 'unassigned';
        $this->log($team, $user, 'task_assigned',
            "{$user->name} assigned \"{$task->title}\" to {$assigneeName}",
            $task,
            ['assigned_to' => $assignee?->name]
        );
    }

    public function memberAdded(Team $team, User $actor, User $member, string $role): void
    {
        $this->log($team, $actor, 'member_added',
            "{$actor->name} added {$member->name} as {$role}",
            null,
            ['user_id' => $member->id, 'role' => $role]
        );
    }

    public function memberRemoved(Team $team, User $actor, User $member): void
    {
        $this->log($team, $actor, 'member_removed',
            "{$actor->name} removed {$member->name} from the team",
            null,
            ['user_id' => $member->id]
        );
    }

    public function taskCompleted(Team $team, User $user, Task $task): void
    {
        $this->log($team, $user, 'task_completed',
            "{$user->name} completed task \"{$task->title}\"",
            $task
        );
    }
}
