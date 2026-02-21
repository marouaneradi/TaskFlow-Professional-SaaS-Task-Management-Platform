<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'team_id',
        'user_id',
        'task_id',
        'action',
        'subject_type',
        'subject_id',
        'properties',
        'description',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function getIconAttribute(): string
    {
        return match($this->action) {
            'task_created'    => '✦',
            'task_updated'    => '✎',
            'task_deleted'    => '✗',
            'status_changed'  => '↻',
            'member_added'    => '⊕',
            'member_removed'  => '⊖',
            'task_assigned'   => '→',
            'task_completed'  => '✓',
            default           => '•',
        };
    }

    public function getColorClassAttribute(): string
    {
        return match($this->action) {
            'task_created'   => 'activity--create',
            'task_updated'   => 'activity--update',
            'task_deleted'   => 'activity--delete',
            'status_changed' => 'activity--status',
            'member_added'   => 'activity--member',
            'member_removed' => 'activity--delete',
            'task_assigned'  => 'activity--assign',
            'task_completed' => 'activity--done',
            default          => 'activity--default',
        };
    }
}
