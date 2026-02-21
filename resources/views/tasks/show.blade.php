<x-app-layout>
    <x-slot:title>{{ $task->title }}</x-slot:title>

    <div class="page-header">
        <div class="page-header-left">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                <a href="{{ route('teams.tasks.index', $team) }}" style="color:var(--text-muted);font-size:13px;">
                    ← {{ $team->name }} / Tasks
                </a>
            </div>
            <h1 class="page-title" style="{{ $task->isOverdue() ? 'color:var(--danger-600);' : '' }}">
                {{ $task->title }}
            </h1>
        </div>
        <div class="page-header-actions">
            @can('update', $task)
            <a href="{{ route('teams.tasks.edit', [$team, $task]) }}" class="btn btn-secondary">Edit Task</a>
            @endcan
            @can('delete', $task)
            <form method="POST" action="{{ route('teams.tasks.destroy', [$team, $task]) }}"
                  onsubmit="return confirm('Delete this task?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
            @endcan
        </div>
    </div>

    <div class="task-detail-grid">
        {{-- Main Content --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Description --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Description</div>
                </div>
                <div class="card-body">
                    @if($task->description)
                        <p style="font-size:14px;line-height:1.7;color:var(--text-secondary);">{{ $task->description }}</p>
                    @else
                        <p style="font-size:14px;color:var(--text-muted);font-style:italic;">No description provided.</p>
                    @endif
                </div>
            </div>

            {{-- Quick Status Update --}}
            @can('update', $task)
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Update Status</div>
                </div>
                <div class="card-body" style="display:flex;gap:10px;flex-wrap:wrap;">
                    @foreach(['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done'] as $status => $label)
                    <form method="POST" action="{{ route('teams.tasks.update-status', [$team, $task]) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $status }}">
                        <button type="submit" class="btn {{ $task->status === $status ? 'btn-primary' : 'btn-secondary' }}"
                                {{ $task->status === $status ? 'disabled' : '' }}>
                            {{ $task->status === $status ? '✓ ' : '' }}{{ $label }}
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
            @endcan

            {{-- Activity Timeline --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Activity</div>
                    <span style="font-size:12px;color:var(--text-muted);">{{ $activityLogs->count() }} events</span>
                </div>
                <div class="card-body" style="padding:16px 20px;">
                    @if($activityLogs->count() > 0)
                        <div class="activity-feed">
                            @foreach($activityLogs as $activity)
                            <div class="activity-item {{ $activity->color_class }}">
                                <div class="activity-icon-wrap">{{ $activity->icon }}</div>
                                <div class="activity-body">
                                    <div class="activity-description">{{ $activity->description }}</div>
                                    <div class="activity-time">
                                        {{ $activity->user->name }} · {{ $activity->created_at->format('M j, Y g:i A') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align:center;padding:20px;color:var(--text-muted);font-size:13px;">
                            No activity yet for this task.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar Meta --}}
        <div>
            <div class="task-meta-card">
                <div class="task-meta-item">
                    <div class="task-meta-label">Status</div>
                    <div class="task-meta-value">
                        <span class="badge {{ $task->getStatusBadgeClass() }}">{{ $task->getStatusLabel() }}</span>
                    </div>
                </div>
                <div class="task-meta-item">
                    <div class="task-meta-label">Priority</div>
                    <div class="task-meta-value">
                        <span class="badge {{ $task->getPriorityBadgeClass() }}">{{ $task->getPriorityLabel() }}</span>
                    </div>
                </div>
                <div class="task-meta-item">
                    <div class="task-meta-label">Assignee</div>
                    <div class="task-meta-value">
                        @if($task->assignee)
                            <div class="task-assignee">
                                <img src="{{ $task->assignee->avatar_url }}" alt="" class="assignee-avatar">
                                {{ $task->assignee->name }}
                            </div>
                        @else
                            <span style="color:var(--text-muted);">Unassigned</span>
                        @endif
                    </div>
                </div>
                <div class="task-meta-item">
                    <div class="task-meta-label">Due Date</div>
                    <div class="task-meta-value">
                        @if($task->due_date)
                            <span style="{{ $task->isOverdue() ? 'color:var(--danger-600);font-weight:600;' : '' }}">
                                {{ $task->due_date->format('M j, Y') }}
                                <div style="font-size:12px;color:var(--text-muted);">
                                    {{ $task->due_date->diffForHumans() }}
                                </div>
                            </span>
                        @else
                            <span style="color:var(--text-muted);">No due date</span>
                        @endif
                    </div>
                </div>
                <div class="task-meta-item">
                    <div class="task-meta-label">Created by</div>
                    <div class="task-meta-value">
                        @if($task->creator)
                            <div class="task-assignee">
                                <img src="{{ $task->creator->avatar_url }}" alt="" class="assignee-avatar">
                                {{ $task->creator->name }}
                            </div>
                            <div style="font-size:12px;color:var(--text-muted);">{{ $task->created_at->format('M j, Y') }}</div>
                        @endif
                    </div>
                </div>
                @if($task->completed_at)
                <div class="task-meta-item">
                    <div class="task-meta-label">Completed</div>
                    <div class="task-meta-value" style="color:var(--success-600);">
                        {{ $task->completed_at->format('M j, Y g:i A') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
