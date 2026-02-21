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
     <?php $__env->slot('title', null, []); ?> Profile Settings <?php $__env->endSlot(); ?>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Profile Settings</h1>
            <p class="page-description">Manage your account information and preferences</p>
        </div>
    </div>

    <div style="max-width:680px;display:flex;flex-direction:column;gap:20px;">

        
        <div class="card">
            <div class="card-header">
                <div class="card-title">Personal Information</div>
            </div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border);">
                    <img src="<?php echo e($user->avatar_url); ?>" alt="<?php echo e($user->name); ?>" class="profile-avatar" style="width:64px;height:64px;margin:0;">
                    <div>
                        <div class="profile-name"><?php echo e($user->name); ?></div>
                        <div class="profile-email"><?php echo e($user->email); ?></div>
                        <?php if(!$user->email_verified_at): ?>
                            <span class="badge badge--high" style="margin-top:6px;">Email not verified</span>
                        <?php else: ?>
                            <span class="badge badge--done" style="margin-top:6px;">✓ Verified</span>
                        <?php endif; ?>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>

                    <div class="form-group">
                        <label for="name" class="form-label">Full name <span>*</span></label>
                        <input type="text" id="name" name="name" class="form-input"
                               value="<?php echo e(old('name', $user->name)); ?>" required>
                        <?php $__errorArgs = ['name'];
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
                        <label for="email" class="form-label">Email address <span>*</span></label>
                        <input type="email" id="email" name="email" class="form-input"
                               value="<?php echo e(old('email', $user->email)); ?>" required>
                        <?php $__errorArgs = ['email'];
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
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="theme" class="form-label">Theme preference</label>
                            <select id="theme" name="theme" class="form-select">
                                <option value="light" <?php echo e($user->theme === 'light' ? 'selected' : ''); ?>>☀ Light</option>
                                <option value="dark" <?php echo e($user->theme === 'dark' ? 'selected' : ''); ?>>◑ Dark</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="timezone" class="form-label">Timezone</label>
                            <input type="text" id="timezone" name="timezone" class="form-input"
                                   value="<?php echo e(old('timezone', $user->timezone)); ?>"
                                   placeholder="UTC">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top:20px;">Save Profile</button>
                </form>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <div class="card-title">Change Password</div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('profile.password')); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                    <div class="form-group">
                        <label for="current_password" class="form-label">Current password</label>
                        <input type="password" id="current_password" name="current_password" class="form-input">
                        <?php $__errorArgs = ['current_password'];
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
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="password" class="form-label">New password</label>
                            <input type="password" id="password" name="password" class="form-input"
                                   placeholder="Min 8 characters">
                            <?php $__errorArgs = ['password'];
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
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="password_confirmation" class="form-label">Confirm new password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top:20px;">Update Password</button>
                </form>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="color:var(--danger-600);">Danger Zone</div>
            </div>
            <div class="card-body">
                <div class="danger-zone">
                    <div class="danger-zone-title">Delete account</div>
                    <div class="danger-zone-description">
                        Permanently delete your account and all associated data. This action cannot be undone.
                    </div>
                    <form method="POST" action="<?php echo e(route('profile.destroy')); ?>"
                          onsubmit="return confirm('This will permanently delete your account. Type your password to confirm.')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <div style="display:flex;gap:8px;align-items:center;">
                            <input type="password" name="password" class="form-input" placeholder="Your password" style="max-width:240px;">
                            <button type="submit" class="btn btn-danger">Delete Account</button>
                        </div>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="form-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </form>
                </div>
            </div>
        </div>

        
        <div style="display:flex;justify-content:flex-end;">
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-secondary">Sign out of all sessions</button>
            </form>
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
<?php /**PATH C:\Users\radim\Downloads\taskflow-saas-platform-fixed\taskflow\resources\views/profile/edit.blade.php ENDPATH**/ ?>