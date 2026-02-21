<x-app-layout>
    <x-slot:title>Activity — {{ $team->name }}</x-slot:title>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Activity Feed</h1>
            <p class="page-description">{{ $team->name }} · Complete history</p>
        </div>
    </div>

    <div style="max-width:720px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Timeline</div>
                <span style="font-size:13px;color:var(--text-muted);">{{ $activities->total() }} events</span>
            </div>
            <div class="card-body" style="padding:16px 24px;">
                @if($activities->count() > 0)
                    <div class="activity-feed">
                        @foreach($activities as $activity)
                        <div class="activity-item {{ $activity->color_class }}">
                            <div class="activity-icon-wrap">{{ $activity->icon }}</div>
                            <div class="activity-body">
                                <div class="activity-description">{{ $activity->description }}</div>
                                <div class="activity-time">
                                    <strong>{{ $activity->user->name }}</strong> ·
                                    {{ $activity->created_at->format('M j, Y \a\t g:i A') }}
                                    · {{ $activity->created_at->diffForHumans() }}
                                </div>
                                @if($activity->task && $activity->task_id)
                                    <div style="margin-top:4px;">
                                        <a href="{{ route('teams.tasks.show', [$team, $activity->task]) }}"
                                           style="font-size:12px;display:inline-flex;align-items:center;gap:4px;padding:2px 8px;background:var(--bg-secondary);border-radius:var(--radius-full);">
                                            → View task
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state" style="padding:40px 24px;">
                        <div class="empty-icon">📋</div>
                        <h2 class="empty-title">No activity yet</h2>
                        <p class="empty-description">Activity will appear here when team members create or update tasks.</p>
                    </div>
                @endif
            </div>
            @if($activities->hasPages())
            <div class="card-footer">
                <div class="pagination-wrap" style="border:none;padding:0;">
                    <div class="pagination-info">
                        Showing {{ $activities->firstItem() }}–{{ $activities->lastItem() }} of {{ $activities->total() }} events
                    </div>
                    {{ $activities->links('vendor.pagination.custom') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
