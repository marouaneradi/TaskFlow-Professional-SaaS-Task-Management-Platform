<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'team_id',
        'created_by',
        'assigned_to',
        'status',
        'priority',
        'due_date',
        'completed_at',
    ];

    protected $casts = [
        'due_date'     => 'date',
        'completed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::updating(function (Task $task) {
            if ($task->isDirty('status') && $task->status === 'done' && !$task->completed_at) {
                $task->completed_at = now();
            } elseif ($task->isDirty('status') && $task->status !== 'done') {
                $task->completed_at = null;
            }
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

    // Scopes
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        });

        $query->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v));
        $query->when($filters['priority'] ?? null, fn($q, $v) => $q->where('priority', $v));
        $query->when($filters['assigned_to'] ?? null, fn($q, $v) => $q->where('assigned_to', $v));

        $query->when($filters['due_from'] ?? null, fn($q, $v) => $q->where('due_date', '>=', $v));
        $query->when($filters['due_to'] ?? null, fn($q, $v) => $q->where('due_date', '<=', $v));

        $query->when($filters['sort'] ?? null, function ($q, $sort) use ($filters) {
            $direction = $filters['direction'] ?? 'asc';
            $allowedSorts = ['title', 'status', 'priority', 'due_date', 'created_at'];
            if (in_array($sort, $allowedSorts)) {
                $q->orderBy($sort, $direction);
            }
        }, fn($q) => $q->latest());

        return $query;
    }

    public function scopeDueSoon(Builder $query, int $days = 3): Builder
    {
        return $query->whereIn('status', ['todo', 'in_progress'])
                     ->whereNotNull('due_date')
                     ->where('due_date', '>=', now()->toDateString())
                     ->where('due_date', '<=', now()->addDays($days)->toDateString());
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereIn('status', ['todo', 'in_progress'])
                     ->whereNotNull('due_date')
                     ->where('due_date', '<', now()->toDateString());
    }

    public function isOverdue(): bool
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               !in_array($this->status, ['done']);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'todo'        => 'badge--todo',
            'in_progress' => 'badge--progress',
            'done'        => 'badge--done',
            default       => '',
        };
    }

    public function getPriorityBadgeClass(): string
    {
        return match($this->priority) {
            'low'    => 'badge--low',
            'medium' => 'badge--medium',
            'high'   => 'badge--high',
            default  => '',
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'todo'        => 'To Do',
            'in_progress' => 'In Progress',
            'done'        => 'Done',
            default       => $this->status,
        };
    }

    public function getPriorityLabel(): string
    {
        return ucfirst($this->priority);
    }
}
