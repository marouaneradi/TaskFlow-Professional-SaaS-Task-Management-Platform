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
     <?php $__env->slot('title', null, []); ?> Tasks — <?php echo e($team->name); ?> <?php $__env->endSlot(); ?>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Tasks</h1>
            <p class="page-description"><?php echo e($team->name); ?> · <?php echo e($tasks->total()); ?> total</p>
        </div>
        <div class="page-header-actions">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', [App\Models\Task::class, $team])): ?>
            <a href="<?php echo e(route('teams.tasks.create', $team)); ?>" class="btn btn-primary">+ New Task</a>
            <?php endif; ?>
        </div>
    </div>

    
    <form method="GET" action="<?php echo e(route('teams.tasks.index', $team)); ?>" id="filter-form">
        <div class="filters-bar">
            <div class="search-input-wrap">
                <svg class="search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" name="search" class="form-input search-input"
                       value="<?php echo e($filters['search'] ?? ''); ?>" placeholder="Search tasks..."
                       oninput="debouncedSearch(document.getElementById('filter-form'))">
            </div>

            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="todo" <?php echo e(($filters['status'] ?? '') === 'todo' ? 'selected' : ''); ?>>To Do</option>
                <option value="in_progress" <?php echo e(($filters['status'] ?? '') === 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                <option value="done" <?php echo e(($filters['status'] ?? '') === 'done' ? 'selected' : ''); ?>>Done</option>
            </select>

            <select name="priority" class="filter-select" onchange="this.form.submit()">
                <option value="">All Priorities</option>
                <option value="high" <?php echo e(($filters['priority'] ?? '') === 'high' ? 'selected' : ''); ?>>🔴 High</option>
                <option value="medium" <?php echo e(($filters['priority'] ?? '') === 'medium' ? 'selected' : ''); ?>>🟡 Medium</option>
                <option value="low" <?php echo e(($filters['priority'] ?? '') === 'low' ? 'selected' : ''); ?>>🟢 Low</option>
            </select>

            <select name="assigned_to" class="filter-select" onchange="this.form.submit()">
                <option value="">All Assignees</option>
                <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($member->id); ?>" <?php echo e(($filters['assigned_to'] ?? '') == $member->id ? 'selected' : ''); ?>>
                        <?php echo e($member->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="sort" class="filter-select" onchange="this.form.submit()">
                <option value="">Sort: Latest</option>
                <option value="due_date" <?php echo e(($filters['sort'] ?? '') === 'due_date' ? 'selected' : ''); ?>>Sort: Due Date</option>
                <option value="priority" <?php echo e(($filters['sort'] ?? '') === 'priority' ? 'selected' : ''); ?>>Sort: Priority</option>
                <option value="title" <?php echo e(($filters['sort'] ?? '') === 'title' ? 'selected' : ''); ?>>Sort: Title</option>
                <option value="created_at" <?php echo e(($filters['sort'] ?? '') === 'created_at' ? 'selected' : ''); ?>>Sort: Created</option>
            </select>

            <?php if(array_filter($filters)): ?>
                <a href="<?php echo e(route('teams.tasks.index', $team)); ?>" class="btn btn-ghost btn-sm">✕ Clear</a>
            <?php endif; ?>
        </div>
    </form>

    <?php if($tasks->count() > 0): ?>
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
                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route('teams.tasks.show', [$team, $task])); ?>"
                               class="td-title" style="<?php echo e($task->isOverdue() ? 'color:var(--danger-600);' : ''); ?>">
                                <?php echo e($task->title); ?>

                            </a>
                            <?php if($task->description): ?>
                                <div class="td-meta"><?php echo e(Str::limit($task->description, 70)); ?></div>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge <?php echo e($task->getStatusBadgeClass()); ?>"><?php echo e($task->getStatusLabel()); ?></span></td>
                        <td><span class="badge <?php echo e($task->getPriorityBadgeClass()); ?>"><?php echo e($task->getPriorityLabel()); ?></span></td>
                        <td>
                            <?php if($task->assignee): ?>
                                <div class="task-assignee">
                                    <img src="<?php echo e($task->assignee->avatar_url); ?>" alt="" class="assignee-avatar">
                                    <span style="font-size:13px;"><?php echo e($task->assignee->name); ?></span>
                                </div>
                            <?php else: ?>
                                <span style="color:var(--text-muted);font-size:13px;">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($task->due_date): ?>
                                <span style="font-size:13px;<?php echo e($task->isOverdue() ? 'color:var(--danger-600);font-weight:600;' : ''); ?>">
                                    <?php echo e($task->due_date->format('M j, Y')); ?>

                                    <?php if($task->isOverdue()): ?> <small>overdue</small> <?php endif; ?>
                                </span>
                            <?php else: ?>
                                <span style="color:var(--text-muted);font-size:13px;">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:4px;justify-content:flex-end;">
                                <a href="<?php echo e(route('teams.tasks.show', [$team, $task])); ?>" class="btn btn-ghost btn-sm" data-tooltip="View">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                                <a href="<?php echo e(route('teams.tasks.edit', [$team, $task])); ?>" class="btn btn-ghost btn-sm" data-tooltip="Edit">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $task)): ?>
                                <form method="POST" action="<?php echo e(route('teams.tasks.destroy', [$team, $task])); ?>"
                                      onsubmit="return confirm('Delete task \'<?php echo e(addslashes($task->title)); ?>\'?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-ghost btn-sm" data-tooltip="Delete" style="color:var(--danger-500);">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            
            <?php if($tasks->hasPages()): ?>
                <div class="pagination-wrap">
                    <div class="pagination-info">
                        Showing <?php echo e($tasks->firstItem()); ?>–<?php echo e($tasks->lastItem()); ?> of <?php echo e($tasks->total()); ?> tasks
                    </div>
                    <?php echo e($tasks->links('vendor.pagination.custom')); ?>

                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <h2 class="empty-title">
                        <?php echo e(array_filter($filters) ? 'No tasks match your filters' : 'No tasks yet'); ?>

                    </h2>
                    <p class="empty-description">
                        <?php echo e(array_filter($filters) ? 'Try adjusting your search or filter criteria.' : 'Create your first task to get started.'); ?>

                    </p>
                    <?php if(array_filter($filters)): ?>
                        <a href="<?php echo e(route('teams.tasks.index', $team)); ?>" class="btn btn-secondary">Clear filters</a>
                    <?php else: ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', [App\Models\Task::class, $team])): ?>
                        <a href="<?php echo e(route('teams.tasks.create', $team)); ?>" class="btn btn-primary">+ Create first task</a>
                        <?php endif; ?>
                    <?php endif; ?>
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
<?php /**PATH C:\Users\radim\Downloads\taskflow-saas-platform-fixed\taskflow\resources\views/tasks/index.blade.php ENDPATH**/ ?>