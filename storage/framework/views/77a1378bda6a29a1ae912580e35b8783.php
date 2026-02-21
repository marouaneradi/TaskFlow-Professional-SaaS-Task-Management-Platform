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
     <?php $__env->slot('title', null, []); ?> Activity — <?php echo e($team->name); ?> <?php $__env->endSlot(); ?>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Activity Feed</h1>
            <p class="page-description"><?php echo e($team->name); ?> · Complete history</p>
        </div>
    </div>

    <div style="max-width:720px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Timeline</div>
                <span style="font-size:13px;color:var(--text-muted);"><?php echo e($activities->total()); ?> events</span>
            </div>
            <div class="card-body" style="padding:16px 24px;">
                <?php if($activities->count() > 0): ?>
                    <div class="activity-feed">
                        <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="activity-item <?php echo e($activity->color_class); ?>">
                            <div class="activity-icon-wrap"><?php echo e($activity->icon); ?></div>
                            <div class="activity-body">
                                <div class="activity-description"><?php echo e($activity->description); ?></div>
                                <div class="activity-time">
                                    <strong><?php echo e($activity->user->name); ?></strong> ·
                                    <?php echo e($activity->created_at->format('M j, Y \a\t g:i A')); ?>

                                    · <?php echo e($activity->created_at->diffForHumans()); ?>

                                </div>
                                <?php if($activity->task && $activity->task_id): ?>
                                    <div style="margin-top:4px;">
                                        <a href="<?php echo e(route('teams.tasks.show', [$team, $activity->task])); ?>"
                                           style="font-size:12px;display:inline-flex;align-items:center;gap:4px;padding:2px 8px;background:var(--bg-secondary);border-radius:var(--radius-full);">
                                            → View task
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state" style="padding:40px 24px;">
                        <div class="empty-icon">📋</div>
                        <h2 class="empty-title">No activity yet</h2>
                        <p class="empty-description">Activity will appear here when team members create or update tasks.</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if($activities->hasPages()): ?>
            <div class="card-footer">
                <div class="pagination-wrap" style="border:none;padding:0;">
                    <div class="pagination-info">
                        Showing <?php echo e($activities->firstItem()); ?>–<?php echo e($activities->lastItem()); ?> of <?php echo e($activities->total()); ?> events
                    </div>
                    <?php echo e($activities->links('vendor.pagination.custom')); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
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
<?php /**PATH C:\Users\radim\Downloads\taskflow-saas-platform-fixed\taskflow\resources\views/activity/index.blade.php ENDPATH**/ ?>