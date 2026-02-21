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
     <?php $__env->slot('title', null, []); ?> New Task — <?php echo e($team->name); ?> <?php $__env->endSlot(); ?>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Create Task</h1>
            <p class="page-description"><?php echo e($team->name); ?></p>
        </div>
    </div>

    <div style="max-width:680px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Task Details</div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('teams.tasks.store', $team)); ?>" id="task-form">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label for="title" class="form-label">Title <span>*</span></label>
                        <input type="text" id="title" name="title" class="form-input"
                               value="<?php echo e(old('title')); ?>" required autofocus
                               placeholder="What needs to be done?">
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="form-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-textarea" rows="4"
                                  placeholder="Add more context, acceptance criteria, or notes..."><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="form-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="status" class="form-label">Status <span>*</span></label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="todo" <?php echo e(old('status', 'todo') === 'todo' ? 'selected' : ''); ?>>To Do</option>
                                <option value="in_progress" <?php echo e(old('status') === 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                                <option value="done" <?php echo e(old('status') === 'done' ? 'selected' : ''); ?>>Done</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="priority" class="form-label">Priority <span>*</span></label>
                            <select id="priority" name="priority" class="form-select" required>
                                <option value="low" <?php echo e(old('priority') === 'low' ? 'selected' : ''); ?>>🟢 Low</option>
                                <option value="medium" <?php echo e(old('priority', 'medium') === 'medium' ? 'selected' : ''); ?>>🟡 Medium</option>
                                <option value="high" <?php echo e(old('priority') === 'high' ? 'selected' : ''); ?>>🔴 High</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="assigned_to" class="form-label">Assign to</label>
                            <select id="assigned_to" name="assigned_to" class="form-select">
                                <option value="">— Unassigned —</option>
                                <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($member->id); ?>" <?php echo e(old('assigned_to') == $member->id ? 'selected' : ''); ?>>
                                        <?php echo e($member->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['assigned_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="form-error"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="due_date" class="form-label">Due date</label>
                            <input type="date" id="due_date" name="due_date" class="form-input"
                                   value="<?php echo e(old('due_date')); ?>"
                                   min="<?php echo e(now()->toDateString()); ?>">
                            <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="form-error"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer" style="display:flex;justify-content:space-between;align-items:center;">
                <a href="<?php echo e(route('teams.tasks.index', $team)); ?>" class="btn btn-ghost">← Cancel</a>
                <div style="display:flex;gap:8px;">
                    <button form="task-form" type="submit" name="_redirect" value="create" class="btn btn-secondary">
                        Save & Create Another
                    </button>
                    <button form="task-form" type="submit" class="btn btn-primary">
                        Create Task →
                    </button>
                </div>
            </div>
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
<?php /**PATH C:\Users\radim\Downloads\taskflow-saas-platform-fixed\taskflow\resources\views/tasks/create.blade.php ENDPATH**/ ?>