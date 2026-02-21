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
     <?php $__env->slot('title', null, []); ?> Teams <?php $__env->endSlot(); ?>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">My Teams</h1>
            <p class="page-description">Manage and collaborate with your teams</p>
        </div>
        <div class="page-header-actions">
            <a href="<?php echo e(route('teams.create')); ?>" class="btn btn-primary">
                + Create Team
            </a>
        </div>
    </div>

    <?php if($teams->count() > 0): ?>
        <div class="teams-grid">
            <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $userRole = auth()->user()->getRoleInTeam($team);
                $stats = $team->getTaskStats();
            ?>
            <div class="team-card">
                <div class="team-card-header">
                    <div class="team-card-avatar"><?php echo e(strtoupper(substr($team->name, 0, 2))); ?></div>
                    <div class="team-card-info">
                        <div class="team-card-name"><?php echo e($team->name); ?></div>
                        <span class="badge badge--<?php echo e($userRole); ?>"><?php echo e(ucfirst($userRole)); ?></span>
                    </div>
                </div>

                <?php if($team->description): ?>
                    <p class="team-card-desc"><?php echo e($team->description); ?></p>
                <?php endif; ?>

                <div class="team-card-stats">
                    <div class="team-stat">
                        <div class="team-stat-value"><?php echo e($team->members_count); ?></div>
                        <div class="team-stat-label">Members</div>
                    </div>
                    <div class="team-stat">
                        <div class="team-stat-value"><?php echo e($team->tasks_count); ?></div>
                        <div class="team-stat-label">Tasks</div>
                    </div>
                    <div class="team-stat">
                        <div class="team-stat-value"><?php echo e($team->getCompletionRate()); ?>%</div>
                        <div class="team-stat-label">Done</div>
                    </div>
                </div>

                <div class="team-card-footer">
                    <a href="<?php echo e(route('teams.show', $team)); ?>" class="btn btn-secondary btn-sm">
                        View Team
                    </a>
                    <div style="display:flex;gap:6px;">
                        <a href="<?php echo e(route('teams.tasks.index', $team)); ?>" class="btn btn-primary btn-sm">
                            Tasks →
                        </a>
                        <form method="POST" action="<?php echo e(route('teams.switch', $team)); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-ghost btn-sm" data-tooltip="Set as active team">
                                ⚡
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="empty-state">
                    <div class="empty-icon">👥</div>
                    <h2 class="empty-title">No teams yet</h2>
                    <p class="empty-description">
                        Create your first team and start organizing tasks with your colleagues.
                    </p>
                    <a href="<?php echo e(route('teams.create')); ?>" class="btn btn-primary">
                        + Create your first team
                    </a>
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
<?php /**PATH C:\Users\radim\Downloads\taskflow-saas-platform-fixed\taskflow\resources\views/teams/index.blade.php ENDPATH**/ ?>