<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\GuestLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> Sign In <?php $__env->endSlot(); ?>

    <div class="auth-layout">
        
        <div class="auth-visual">
            <div class="auth-visual-content">
                <div class="auth-visual-logo">✦</div>
                <h1 class="auth-visual-title">
                    Manage tasks,<br>ship faster.
                </h1>
                <p class="auth-visual-subtitle">
                    TaskFlow brings your team together with powerful task management, real-time activity feeds, and smart prioritization.
                </p>
                <ul class="auth-feature-list">
                    <li>Multi-team workspace with role-based access</li>
                    <li>Real-time activity logs and notifications</li>
                    <li>Smart filtering and search across all tasks</li>
                    <li>Dashboard with productivity insights</li>
                    <li>Dark mode for late-night sessions</li>
                </ul>
            </div>
        </div>

        
        <div class="auth-form-section">
            <div class="auth-form-container">
                <a href="<?php echo e(route('login')); ?>" class="auth-logo-link">
                    <div class="auth-logo-icon">✦</div>
                    <span class="auth-logo-name">TaskFlow</span>
                </a>

                <h2 class="auth-heading">Welcome back</h2>
                <p class="auth-subheading">Sign in to your account to continue</p>

                <?php if(session('status')): ?>
                    <div class="alert alert-success">
                        <span class="alert-icon">✓</span>
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" id="email" name="email" class="form-input"
                               value="<?php echo e(old('email')); ?>" required autofocus autocomplete="email"
                               placeholder="you@company.com">
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

                    <div class="form-group">
                        <div class="flex items-center justify-between">
                            <label for="password" class="form-label">Password</label>
                            <a href="<?php echo e(route('password.request')); ?>" style="font-size:13px;">Forgot password?</a>
                        </div>
                        <input type="password" id="password" name="password" class="form-input"
                               required autocomplete="current-password" placeholder="••••••••">
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

                    <div class="form-group" style="flex-direction:row;align-items:center;gap:10px;">
                        <input type="checkbox" id="remember" name="remember"
                               style="width:16px;height:16px;cursor:pointer;">
                        <label for="remember" style="font-size:14px;color:var(--text-secondary);cursor:pointer;">
                            Remember me for 30 days
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;margin-bottom:16px;">
                        Sign in to TaskFlow
                    </button>

                    <div class="auth-footer">
                        Don't have an account?
                        <a href="<?php echo e(route('register')); ?>" style="font-weight:600;">Create one free →</a>
                    </div>
                </form>

              
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH C:\Users\radim\Downloads\taskflow-saas-platform-fixed\taskflow\resources\views/auth/login.blade.php ENDPATH**/ ?>