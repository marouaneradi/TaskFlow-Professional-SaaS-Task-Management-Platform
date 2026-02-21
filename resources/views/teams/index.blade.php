<x-app-layout>
    <x-slot:title>Teams</x-slot:title>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">My Teams</h1>
            <p class="page-description">Manage and collaborate with your teams</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('teams.create') }}" class="btn btn-primary">
                + Create Team
            </a>
        </div>
    </div>

    @if($teams->count() > 0)
        <div class="teams-grid">
            @foreach($teams as $team)
            @php
                $userRole = auth()->user()->getRoleInTeam($team);
                $stats = $team->getTaskStats();
            @endphp
            <div class="team-card">
                <div class="team-card-header">
                    <div class="team-card-avatar">{{ strtoupper(substr($team->name, 0, 2)) }}</div>
                    <div class="team-card-info">
                        <div class="team-card-name">{{ $team->name }}</div>
                        <span class="badge badge--{{ $userRole }}">{{ ucfirst($userRole) }}</span>
                    </div>
                </div>

                @if($team->description)
                    <p class="team-card-desc">{{ $team->description }}</p>
                @endif

                <div class="team-card-stats">
                    <div class="team-stat">
                        <div class="team-stat-value">{{ $team->members_count }}</div>
                        <div class="team-stat-label">Members</div>
                    </div>
                    <div class="team-stat">
                        <div class="team-stat-value">{{ $team->tasks_count }}</div>
                        <div class="team-stat-label">Tasks</div>
                    </div>
                    <div class="team-stat">
                        <div class="team-stat-value">{{ $team->getCompletionRate() }}%</div>
                        <div class="team-stat-label">Done</div>
                    </div>
                </div>

                <div class="team-card-footer">
                    <a href="{{ route('teams.show', $team) }}" class="btn btn-secondary btn-sm">
                        View Team
                    </a>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('teams.tasks.index', $team) }}" class="btn btn-primary btn-sm">
                            Tasks →
                        </a>
                        <form method="POST" action="{{ route('teams.switch', $team) }}">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm" data-tooltip="Set as active team">
                                ⚡
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="empty-state">
                    <div class="empty-icon">👥</div>
                    <h2 class="empty-title">No teams yet</h2>
                    <p class="empty-description">
                        Create your first team and start organizing tasks with your colleagues.
                    </p>
                    <a href="{{ route('teams.create') }}" class="btn btn-primary">
                        + Create your first team
                    </a>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
