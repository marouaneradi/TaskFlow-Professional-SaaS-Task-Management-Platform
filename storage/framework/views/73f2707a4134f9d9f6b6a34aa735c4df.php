<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> <?php echo e($team->name); ?> <?php $__env->endSlot(); ?>

    <div class="page-header">
        <div class="page-header-left">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="team-card-avatar" style="width:48px;height:48px;font-size:18px;border-radius:12px;">
                    <?php echo e(strtoupper(substr($team->name, 0, 2))); ?>

                </div>
                <div>
                    <h1 class="page-title"><?php echo e($team->name); ?></h1>
                    <?php if($team->description): ?>
                        <p class="page-description"><?php echo e($team->description); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="page-header-actions">
            <a href="<?php echo e(route('teams.tasks.index', $team)); ?>" class="btn btn-primary">
                View Tasks
            </a>
            <?php if(in_array($currentUserRole, ['owner', 'admin'])): ?>
                <a href="<?php echo e(route('teams.edit', $team)); ?>" class="btn btn-secondary">
                    Edit Team
                </a>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:28px;">
        <div class="stat-card">
            <div class="stat-icon stat-icon--brand">📋</div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['total']); ?></div>
                <div class="stat-label">Total Tasks</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon--warning">⏳</div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['in_progress']); ?></div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon--success">✓</div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['done']); ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon--danger">⚠</div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($stats['overdue']); ?></div>
                <div class="stat-label">Overdue</div>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start;">
        
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Team Members (<?php echo e($members->count()); ?>)</div>
                </div>
                <?php if(in_array($currentUserRole, ['owner', 'admin'])): ?>
                    <button class="btn btn-primary btn-sm" onclick="openModal('add-member-modal')">
                        + Add Member
                    </button>
                <?php endif; ?>
            </div>
            <div class="card-body" style="padding:12px;">
                <div class="members-list">
                    <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="member-row">
                        <img src="<?php echo e($member->avatar_url); ?>" alt="<?php echo e($member->name); ?>" class="member-avatar">
                        <div class="member-info">
                            <div class="member-name">
                                <?php echo e($member->name); ?>

                                <?php if($member->id === auth()->id()): ?> <span style="font-size:12px;color:var(--text-muted);">(you)</span> <?php endif; ?>
                            </div>
                            <div class="member-email"><?php echo e($member->email); ?></div>
                        </div>
                        <div class="member-actions">
                            <span class="badge badge--<?php echo e($member->pivot->role); ?>"><?php echo e(ucfirst($member->pivot->role)); ?></span>

                            <?php if(in_array($currentUserRole, ['owner', 'admin']) && $member->id !== auth()->id() && $member->pivot->role !== 'owner'): ?>
                                <div class="dropdown">
                                    <button class="btn btn-ghost btn-sm" data-dropdown="member-menu-<?php echo e($member->id); ?>">⋮</button>
                                    <div class="dropdown-menu hidden" id="member-menu-<?php echo e($member->id); ?>">
                                        <form method="POST" action="<?php echo e(route('teams.members.update-role', [$team, $member])); ?>">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="role" value="<?php echo e($member->pivot->role === 'admin' ? 'member' : 'admin'); ?>">
                                            <button type="submit" class="dropdown-item">
                                                <?php echo e($member->pivot->role === 'admin' ? 'Demote to Member' : 'Promote to Admin'); ?>

                                            </button>
                                        </form>
                                        <div class="dropdown-divider"></div>
                                        <form method="POST" action="<?php echo e(route('teams.members.destroy', [$team, $member])); ?>"
                                              onsubmit="return confirm('Remove <?php echo e($member->name); ?> from this team?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="dropdown-item danger">Remove from team</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <?php if($currentUserRole !== 'owner'): ?>
                <div class="card-footer">
                    <form method="POST" action="<?php echo e(route('teams.members.destroy', [$team, auth()->user()])); ?>"
                          onsubmit="return confirm('Are you sure you want to leave this team?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm">Leave Team</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="card">
            <div class="card-header">
                <div class="card-title">Recent Activity</div>
                <a href="<?php echo e(route('teams.activity', $team)); ?>" class="btn btn-ghost btn-sm">View all</a>
            </div>
            <div class="card-body" style="padding:8px 16px;">
                <?php if($recentActivity->count() > 0): ?>
                    <div class="activity-feed">
                        <?php $__currentLoopData = $recentActivity->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="activity-item <?php echo e($activity->color_class); ?>">
                            <div class="activity-icon-wrap"><?php echo e($activity->icon); ?></div>
                            <div class="activity-body">
                                <div class="activity-description"><?php echo e($activity->description); ?></div>
                                <div class="activity-time"><?php echo e($activity->created_at->diffForHumans()); ?></div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state" style="padding:24px 16px;">
                        <div class="empty-icon">📋</div>
                        <div class="empty-description">No activity yet.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if($currentUserRole === 'owner'): ?>
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
                    <form method="POST" action="<?php echo e(route('teams.destroy', $team)); ?>"
                          onsubmit="return confirm('Type DELETE to confirm: Are you absolutely sure?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm">Delete Team</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>


<div class="modal-overlay" id="add-member-modal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Add Team Member</h3>
            <button class="modal-close" onclick="closeModal('add-member-modal')">✕</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?php echo e(route('teams.members.store', $team)); ?>">
                <?php echo csrf_field(); ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-error">
                        <span class="alert-icon">✕</span>
                        <?php echo e($errors->first()); ?>

                    </div>
                <?php endif; ?>

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

<?php if($errors->any()): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    openModal('add-member-modal');
});
</script>
<?php endif; ?>
<?php /**PATH C:\Users\radim\Downloads\taskflow-saas-platform-fixed\taskflow\resources\views/teams/show.blade.php ENDPATH**/ ?>