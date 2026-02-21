<x-app-layout>
    <x-slot:title>Dashboard</x-slot:title>

    {{-- Pass variables for team switcher in layout --}}
    @php $teams = auth()->user()->teams()->get(); @endphp

    {{-- Stat Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon--brand">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--brand-600)" stroke-width="2">
                    <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Tasks</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--warning">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--warning-600)" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['in_progress'] }}</div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--success">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--success-600)" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['done'] }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--danger">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--danger-500)" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $overdueTasksCount }}</div>
                <div class="stat-label">Overdue</div>
                @if($overdueTasksCount > 0)
                    <div class="stat-change stat-change--down">Needs attention</div>
                @endif
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--info">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--info-500)" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $currentTeam->members()->count() }}</div>
                <div class="stat-label">Team Members</div>
            </div>
        </div>
    </div>

    {{-- Main Dashboard Grid --}}
    <div class="dashboard-grid">
        {{-- Left Column --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Task Status Chart --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">Task Overview</div>
                        <div class="card-subtitle">{{ $currentTeam->name }} — Status breakdown</div>
                    </div>
                    <div class="progress-ring" style="width:54px;height:54px;">
                        <svg width="54" height="54" viewBox="0 0 54 54">
                            <circle cx="27" cy="27" r="22" fill="none" stroke="var(--border)" stroke-width="4"/>
                            <circle class="progress-ring-circle"
                                    cx="27" cy="27" r="22" fill="none"
                                    stroke="var(--brand-500)" stroke-width="4"
                                    stroke-linecap="round"
                                    style="stroke-dasharray: {{ 2 * M_PI * 22 }}; stroke-dashoffset: {{ 2 * M_PI * 22 }}; transition: stroke-dashoffset 0.8s ease; transform-origin: center; transform: rotate(-90deg);"
                                    data-progress="{{ $completionRate }}">
                            </circle>
                        </svg>
                        <div class="progress-ring-value" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;">
                            {{ $completionRate }}%
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Bar chart --}}
                    @php
                        $maxVal = max($stats['todo'], $stats['in_progress'], $stats['done'], 1);
                    @endphp
                    <div class="chart-container">
                        <div class="chart-bars">
                            <div class="chart-bar-group">
                                <div class="chart-bar-wrapper">
                                    <div class="chart-bar chart-bar--todo"
                                         data-height="{{ ($stats['todo'] / $maxVal) * 100 }}"
                                         data-value="{{ $stats['todo'] }}"
                                         style="height:0%;transition:height 0.8s ease 0.1s;"></div>
                                </div>
                                <div class="chart-label">To Do<br><strong>{{ $stats['todo'] }}</strong></div>
                            </div>
                            <div class="chart-bar-group">
                                <div class="chart-bar-wrapper">
                                    <div class="chart-bar chart-bar--progress"
                                         data-height="{{ ($stats['in_progress'] / $maxVal) * 100 }}"
                                         data-value="{{ $stats['in_progress'] }}"
                                         style="height:0%;transition:height 0.8s ease 0.2s;"></div>
                                </div>
                                <div class="chart-label">In Progress<br><strong>{{ $stats['in_progress'] }}</strong></div>
                            </div>
                            <div class="chart-bar-group">
                                <div class="chart-bar-wrapper">
                                    <div class="chart-bar chart-bar--done"
                                         data-height="{{ ($stats['done'] / $maxVal) * 100 }}"
                                         data-value="{{ $stats['done'] }}"
                                         style="height:0%;transition:height 0.8s ease 0.3s;"></div>
                                </div>
                                <div class="chart-label">Done<br><strong>{{ $stats['done'] }}</strong></div>
                            </div>
                        </div>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item"><div class="legend-dot legend-dot--todo"></div>To Do</div>
                        <div class="legend-item"><div class="legend-dot legend-dot--progress"></div>In Progress</div>
                        <div class="legend-item"><div class="legend-dot legend-dot--done"></div>Done</div>
                    </div>
                </div>
            </div>

            {{-- Productivity --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">Productivity</div>
                        <div class="card-subtitle">Last 7 days</div>
                    </div>
                    <span class="badge badge--done">{{ $completedThisWeek }} completed</span>
                </div>
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <div style="flex:1;">
                            <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--text-muted);margin-bottom:6px;">
                                <span>Completion rate</span>
                                <span>{{ $completionRate }}%</span>
                            </div>
                            <div style="background:var(--bg-tertiary);border-radius:9999px;height:8px;overflow:hidden;">
                                <div style="background:linear-gradient(to right, var(--brand-500), var(--success-500));height:100%;border-radius:9999px;width:{{ $completionRate }}%;transition:width 1s ease;"></div>
                            </div>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:20px;">
                        <div style="text-align:center;padding:12px;background:var(--bg-secondary);border-radius:var(--radius);">
                            <div style="font-size:22px;font-weight:700;color:var(--brand-600);">{{ $completedThisWeek }}</div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">This Week</div>
                        </div>
                        <div style="text-align:center;padding:12px;background:var(--bg-secondary);border-radius:var(--radius);">
                            <div style="font-size:22px;font-weight:700;color:var(--success-600);">{{ $stats['done'] }}</div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">Total Done</div>
                        </div>
                        <div style="text-align:center;padding:12px;background:var(--bg-secondary);border-radius:var(--radius);">
                            <div style="font-size:22px;font-weight:700;color:var(--warning-600);">{{ $stats['todo'] }}</div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">Pending</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('teams.tasks.index', $currentTeam) }}" class="btn btn-primary btn-sm">
                        View All Tasks →
                    </a>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Due Soon --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">Due Soon</div>
                        <div class="card-subtitle">Next 3 days</div>
                    </div>
                    @if($overdueTasksCount > 0)
                        <span class="badge badge--high">{{ $overdueTasksCount }} overdue</span>
                    @endif
                </div>
                <div class="card-body" style="padding:12px;">
                    @if($tasksDueSoon->count() > 0)
                        <div class="due-soon-list">
                            @foreach($tasksDueSoon as $task)
                            <a href="{{ route('teams.tasks.show', [$currentTeam, $task]) }}" class="due-soon-item">
                                <div class="due-soon-icon due-soon-icon--{{ $task->priority }}"></div>
                                <div class="due-soon-info">
                                    <div class="due-soon-title">{{ $task->title }}</div>
                                    <div class="due-soon-date">
                                        Due {{ $task->due_date->format('M j') }}
                                        ({{ $task->due_date->diffForHumans() }})
                                    </div>
                                </div>
                                <span class="badge badge--{{ $task->status === 'in_progress' ? 'progress' : 'todo' }}" style="font-size:11px;">
                                    {{ $task->getStatusLabel() }}
                                </span>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state" style="padding:30px 16px;">
                            <div class="empty-icon">🎉</div>
                            <div class="empty-title">All caught up!</div>
                            <div class="empty-description">No tasks due in the next 3 days.</div>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('teams.tasks.create', $currentTeam) }}" class="btn btn-primary btn-sm">
                        + Create Task
                    </a>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">Recent Activity</div>
                        <div class="card-subtitle">{{ $currentTeam->name }}</div>
                    </div>
                    <a href="{{ route('teams.activity', $currentTeam) }}" class="btn btn-secondary btn-sm">
                        View all
                    </a>
                </div>
                <div class="card-body" style="padding:16px 20px;">
                    @if($recentActivity->count() > 0)
                        <div class="activity-feed">
                            @foreach($recentActivity->take(6) as $activity)
                            <div class="activity-item {{ $activity->color_class }}">
                                <div class="activity-icon-wrap">{{ $activity->icon }}</div>
                                <div class="activity-body">
                                    <div class="activity-description">{{ $activity->description }}</div>
                                    <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state" style="padding:20px 16px;">
                            <div class="empty-icon">📋</div>
                            <div class="empty-description">No activity yet. Create your first task!</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
