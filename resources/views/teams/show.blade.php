<x-app-layout>
    <x-slot:title>{{ $team->name }}</x-slot:title>

    <div class="page-header">
        <div class="page-header-left">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="team-card-avatar" style="width:48px;height:48px;font-size:18px;border-radius:12px;">
                    {{ strtoupper(substr($team->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="page-title">{{ $team->name }}</h1>
                    @if($team->description)
                        <p class="page-description">{{ $team->description }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('teams.tasks.index', $team) }}" class="btn btn-primary">
                View Tasks
            </a>
            @if(in_array($currentUserRole, ['owner', 'admin']))
                <a href="{{ route('teams.edit', $team) }}" class="btn btn-secondary">
                    Edit Team
                </a>
            @endif
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:28px;">
        <div class="stat-card">
            <div class="stat-icon stat-icon--brand">📋</div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Tasks</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon--warning">⏳</div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['in_progress'] }}</div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon--success">✓</div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['done'] }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon--danger">⚠</div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['overdue'] }}</div>
                <div class="stat-label">Overdue</div>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start;">
        {{-- Members Section --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Team Members ({{ $members->count() }})</div>
                </div>
                @if(in_array($currentUserRole, ['owner', 'admin']))
                    <button class="btn btn-primary btn-sm" onclick="openModal('add-member-modal')">
                        + Add Member
                    </button>
                @endif
            </div>
            <div class="card-body" style="padding:12px;">
                <div class="members-list">
                    @foreach($members as $member)
                    <div class="member-row">
                        <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="member-avatar">
                        <div class="member-info">
                            <div class="member-name">
                                {{ $member->name }}
                                @if($member->id === auth()->id()) <span style="font-size:12px;color:var(--text-muted);">(you)</span> @endif
                            </div>
                            <div class="member-email">{{ $member->email }}</div>
                        </div>
                        <div class="member-actions">
                            <span class="badge badge--{{ $member->pivot->role }}">{{ ucfirst($member->pivot->role) }}</span>

                            @if(in_array($currentUserRole, ['owner', 'admin']) && $member->id !== auth()->id() && $member->pivot->role !== 'owner')
                                <div class="dropdown">
                                    <button class="btn btn-ghost btn-sm" data-dropdown="member-menu-{{ $member->id }}">⋮</button>
                                    <div class="dropdown-menu hidden" id="member-menu-{{ $member->id }}">
                                        <form method="POST" action="{{ route('teams.members.update-role', [$team, $member]) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="role" value="{{ $member->pivot->role === 'admin' ? 'member' : 'admin' }}">
                                            <button type="submit" class="dropdown-item">
                                                {{ $member->pivot->role === 'admin' ? 'Demote to Member' : 'Promote to Admin' }}
                                            </button>
                                        </form>
                                        <div class="dropdown-divider"></div>
                                        <form method="POST" action="{{ route('teams.members.destroy', [$team, $member]) }}"
                                              onsubmit="return confirm('Remove {{ $member->name }} from this team?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item danger">Remove from team</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Leave/Delete Team --}}
            @if($currentUserRole !== 'owner')
                <div class="card-footer">
                    <form method="POST" action="{{ route('teams.members.destroy', [$team, auth()->user()]) }}"
                          onsubmit="return confirm('Are you sure you want to leave this team?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Leave Team</button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Activity --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Recent Activity</div>
                <a href="{{ route('teams.activity', $team) }}" class="btn btn-ghost btn-sm">View all</a>
            </div>
            <div class="card-body" style="padding:8px 16px;">
                @if($recentActivity->count() > 0)
                    <div class="activity-feed">
                        @foreach($recentActivity->take(8) as $activity)
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
                    <div class="empty-state" style="padding:24px 16px;">
                        <div class="empty-icon">📋</div>
                        <div class="empty-description">No activity yet.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($currentUserRole === 'owner')
    <div style="margin-top:28px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="color:var(--danger-600);">Danger Zone</div>
            </div>
            <div class="card-body">
                <div class="danger-zone">
                    <div class="danger-zone-title">Delete this team</div>
                    <div class="danger-zone-description">
                        Once deleted, all tasks and activity for this team will be permanently removed. This action cannot be undone.
                    </div>
                    <form method="POST" action="{{ route('teams.destroy', $team) }}"
                          onsubmit="return confirm('Type DELETE to confirm: Are you absolutely sure?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete Team</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>

{{-- Add Member Modal --}}
<div class="modal-overlay" id="add-member-modal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Add Team Member</h3>
            <button class="modal-close" onclick="closeModal('add-member-modal')">✕</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('teams.members.store', $team) }}">
                @csrf

                @if($errors->any())
                    <div class="alert alert-error">
                        <span class="alert-icon">✕</span>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="form-group">
                    <label for="member-email" class="form-label">Email address <span>*</span></label>
                    <input type="email" id="member-email" name="email" class="form-input"
                           placeholder="colleague@company.com" required>
                    <span class="form-hint">The user must already have a TaskFlow account.</span>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label for="member-role" class="form-label">Role <span>*</span></label>
                    <select id="member-role" name="role" class="form-select" required>
                        <option value="member">Member — manage assigned tasks</option>
                        <option value="admin">Admin — manage tasks & members</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('add-member-modal')">Cancel</button>
            <button type="button" class="btn btn-primary"
                onclick="this.closest('.modal').querySelector('form').submit()">
                Add Member
            </button>
        </div>
    </div>
</div>

@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function() {
    openModal('add-member-modal');
});
</script>
@endif
