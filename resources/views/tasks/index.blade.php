<x-app-layout>
    <x-slot:title>Tasks — {{ $team->name }}</x-slot:title>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Tasks</h1>
            <p class="page-description">{{ $team->name }} · {{ $tasks->total() }} total</p>
        </div>
        <div class="page-header-actions">
            @can('create', [App\Models\Task::class, $team])
            <a href="{{ route('teams.tasks.create', $team) }}" class="btn btn-primary">+ New Task</a>
            @endcan
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('teams.tasks.index', $team) }}" id="filter-form">
        <div class="filters-bar">
            <div class="search-input-wrap">
                <svg class="search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" name="search" class="form-input search-input"
                       value="{{ $filters['search'] ?? '' }}" placeholder="Search tasks..."
                       oninput="debouncedSearch(document.getElementById('filter-form'))">
            </div>

            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="todo" {{ ($filters['status'] ?? '') === 'todo' ? 'selected' : '' }}>To Do</option>
                <option value="in_progress" {{ ($filters['status'] ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="done" {{ ($filters['status'] ?? '') === 'done' ? 'selected' : '' }}>Done</option>
            </select>

            <select name="priority" class="filter-select" onchange="this.form.submit()">
                <option value="">All Priorities</option>
                <option value="high" {{ ($filters['priority'] ?? '') === 'high' ? 'selected' : '' }}>🔴 High</option>
                <option value="medium" {{ ($filters['priority'] ?? '') === 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                <option value="low" {{ ($filters['priority'] ?? '') === 'low' ? 'selected' : '' }}>🟢 Low</option>
            </select>

            <select name="assigned_to" class="filter-select" onchange="this.form.submit()">
                <option value="">All Assignees</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ ($filters['assigned_to'] ?? '') == $member->id ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>

            <select name="sort" class="filter-select" onchange="this.form.submit()">
                <option value="">Sort: Latest</option>
                <option value="due_date" {{ ($filters['sort'] ?? '') === 'due_date' ? 'selected' : '' }}>Sort: Due Date</option>
                <option value="priority" {{ ($filters['sort'] ?? '') === 'priority' ? 'selected' : '' }}>Sort: Priority</option>
                <option value="title" {{ ($filters['sort'] ?? '') === 'title' ? 'selected' : '' }}>Sort: Title</option>
                <option value="created_at" {{ ($filters['sort'] ?? '') === 'created_at' ? 'selected' : '' }}>Sort: Created</option>
            </select>

            @if(array_filter($filters))
                <a href="{{ route('teams.tasks.index', $team) }}" class="btn btn-ghost btn-sm">✕ Clear</a>
            @endif
        </div>
    </form>

    @if($tasks->count() > 0)
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Assignee</th>
                        <th>Due Date</th>
                        <th style="width:120px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                    <tr>
                        <td>
                            <a href="{{ route('teams.tasks.show', [$team, $task]) }}"
                               class="td-title" style="{{ $task->isOverdue() ? 'color:var(--danger-600);' : '' }}">
                                {{ $task->title }}
                            </a>
                            @if($task->description)
                                <div class="td-meta">{{ Str::limit($task->description, 70) }}</div>
                            @endif
                        </td>
                        <td><span class="badge {{ $task->getStatusBadgeClass() }}">{{ $task->getStatusLabel() }}</span></td>
                        <td><span class="badge {{ $task->getPriorityBadgeClass() }}">{{ $task->getPriorityLabel() }}</span></td>
                        <td>
                            @if($task->assignee)
                                <div class="task-assignee">
                                    <img src="{{ $task->assignee->avatar_url }}" alt="" class="assignee-avatar">
                                    <span style="font-size:13px;">{{ $task->assignee->name }}</span>
                                </div>
                            @else
                                <span style="color:var(--text-muted);font-size:13px;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($task->due_date)
                                <span style="font-size:13px;{{ $task->isOverdue() ? 'color:var(--danger-600);font-weight:600;' : '' }}">
                                    {{ $task->due_date->format('M j, Y') }}
                                    @if($task->isOverdue()) <small>overdue</small> @endif
                                </span>
                            @else
                                <span style="color:var(--text-muted);font-size:13px;">—</span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:4px;justify-content:flex-end;">
                                <a href="{{ route('teams.tasks.show', [$team, $task]) }}" class="btn btn-ghost btn-sm" data-tooltip="View">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                @can('update', $task)
                                <a href="{{ route('teams.tasks.edit', [$team, $task]) }}" class="btn btn-ghost btn-sm" data-tooltip="Edit">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                @endcan
                                @can('delete', $task)
                                <form method="POST" action="{{ route('teams.tasks.destroy', [$team, $task]) }}"
                                      onsubmit="return confirm('Delete task \'{{ addslashes($task->title) }}\'?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm" data-tooltip="Delete" style="color:var(--danger-500);">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($tasks->hasPages())
                <div class="pagination-wrap">
                    <div class="pagination-info">
                        Showing {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }} of {{ $tasks->total() }} tasks
                    </div>
                    {{ $tasks->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <h2 class="empty-title">
                        {{ array_filter($filters) ? 'No tasks match your filters' : 'No tasks yet' }}
                    </h2>
                    <p class="empty-description">
                        {{ array_filter($filters) ? 'Try adjusting your search or filter criteria.' : 'Create your first task to get started.' }}
                    </p>
                    @if(array_filter($filters))
                        <a href="{{ route('teams.tasks.index', $team) }}" class="btn btn-secondary">Clear filters</a>
                    @else
                        @can('create', [App\Models\Task::class, $team])
                        <a href="{{ route('teams.tasks.create', $team) }}" class="btn btn-primary">+ Create first task</a>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
