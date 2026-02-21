<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'avatar',
        'owner_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Team $team) {
            if (empty($team->slug)) {
                $team->slug = Str::slug($team->name) . '-' . Str::random(6);
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user')
            ->withPivot(['role', 'joined_at'])
            ->withTimestamps();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

    public function getTaskStats(): array
    {
        $tasks = $this->tasks()->withTrashed(false)->get();
        return [
            'total'       => $tasks->count(),
            'todo'        => $tasks->where('status', 'todo')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'done'        => $tasks->where('status', 'done')->count(),
            'overdue'     => $tasks->where('due_date', '<', now()->toDateString())
                                   ->whereIn('status', ['todo', 'in_progress'])->count(),
        ];
    }

    public function getCompletionRate(): int
    {
        $total = $this->tasks()->count();
        if ($total === 0) return 0;
        $done = $this->tasks()->where('status', 'done')->count();
        return (int) round(($done / $total) * 100);
    }
}
