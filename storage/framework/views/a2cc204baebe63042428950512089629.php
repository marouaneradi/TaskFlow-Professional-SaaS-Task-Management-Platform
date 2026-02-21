<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-theme="<?php echo e(auth()->user()?->theme ?? 'light'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(isset($title) ? $title . ' — ' : ''); ?>TaskFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body>


<div class="sidebar-overlay" onclick="toggleSidebar()"></div>


<aside class="sidebar" id="sidebar">
    <a href="<?php echo e(route('dashboard')); ?>" class="sidebar-logo">
        <div class="sidebar-logo-icon">✦</div>
        <span class="sidebar-logo-text">TaskFlow</span>
        <span class="sidebar-logo-badge">v2</span>
    </a>

    <div class="sidebar-section">
        <ul class="sidebar-nav">
            <li>
                <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('teams.index')); ?>" class="<?php echo e(request()->routeIs('teams.*') ? 'active' : ''); ?>">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Teams
                </a>
            </li>
        </ul>

        <?php
            $teams = auth()->user()->teams()->get();
            $currentTeamId = session('current_team_id');
            $currentTeam = $teams->find($currentTeamId) ?? $teams->first();
        ?>

        <?php if($teams->count() > 0): ?>
        <div class="sidebar-section-label" style="margin-top:20px;">Your Teams</div>
        <ul class="sidebar-nav">
            <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <a href="<?php echo e(route('teams.show', $team)); ?>"
                   class="<?php echo e($currentTeam?->id === $team->id && request()->routeIs('teams.show') ? 'active' : ''); ?>"
                   style="font-size:13px;">
                    <div class="team-avatar" style="font-size:10px;width:22px;height:22px;border-radius:5px;flex-shrink:0;">
                        <?php echo e(strtoupper(substr($team->name, 0, 2))); ?>

                    </div>
                    <?php echo e(Str::limit($team->name, 22)); ?>

                </a>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>

        <?php if($currentTeam): ?>
        <div class="sidebar-section-label" style="margin-top:20px;">Current Team</div>
        <ul class="sidebar-nav">
            <li>
                <a href="<?php echo e(route('teams.tasks.index', $currentTeam)); ?>"
                   class="<?php echo e(request()->routeIs('teams.tasks.*') ? 'active' : ''); ?>">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                    Tasks
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('teams.activity', $currentTeam)); ?>"
                   class="<?php echo e(request()->routeIs('teams.activity') ? 'active' : ''); ?>">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    Activity
                </a>
            </li>
        </ul>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    
    <div class="sidebar-user">
        <img src="<?php echo e(auth()->user()->avatar_url); ?>" alt="<?php echo e(auth()->user()->name); ?>" class="sidebar-user-avatar">
        <div class="sidebar-user-info">
            <div class="sidebar-user-name"><?php echo e(auth()->user()->name); ?></div>
            <div class="sidebar-user-email"><?php echo e(auth()->user()->email); ?></div>
        </div>
        <div class="sidebar-user-actions">
            <button class="sidebar-icon-btn" onclick="toggleTheme()"
                    data-tooltip="<?php echo e(auth()->user()->theme === 'dark' ? 'Light mode' : 'Dark mode'); ?>">
                <?php echo e(auth()->user()->theme === 'dark' ? '☀' : '◑'); ?>

            </button>
            <a href="<?php echo e(route('profile.edit')); ?>" class="sidebar-icon-btn" data-tooltip="Profile">
                ⚙
            </a>
        </div>
    </div>
</aside>


<div class="main-content" id="main-content">
    
    <header class="topbar">
        <div class="topbar-left">
            <button class="mobile-menu-btn" onclick="toggleSidebar()" style="display:none;">☰</button>
            <?php if(isset($topbarLeft)): ?>
                <?php echo e($topbarLeft); ?>

            <?php else: ?>
                <div>
                    <div class="topbar-title"><?php echo e($title ?? 'Dashboard'); ?></div>
                    <?php if(isset($subtitle)): ?>
                        <div class="topbar-subtitle"><?php echo e($subtitle); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="topbar-right">
            <?php if(isset($topbarActions)): ?>
                <?php echo e($topbarActions); ?>

            <?php endif; ?>

            
            <?php if(isset($currentTeam) || isset($teams)): ?>
            <?php $switchTeams = isset($teams) ? $teams : auth()->user()->teams()->get(); ?>
            <?php if($switchTeams->count() > 1): ?>
            <div class="dropdown">
                <button class="topbar-btn" data-dropdown="team-switcher-menu">
                    Switch Team ▾
                </button>
                <div class="dropdown-menu hidden" id="team-switcher-menu">
                    <?php $__currentLoopData = $switchTeams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <form method="POST" action="<?php echo e(route('teams.switch', $t)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item">
                            <div class="team-avatar" style="font-size:10px;width:20px;height:20px;border-radius:4px;">
                                <?php echo e(strtoupper(substr($t->name, 0, 2))); ?>

                            </div>
                            <?php echo e($t->name); ?>

                        </button>
                    </form>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            
            <a href="<?php echo e(route('teams.create')); ?>" class="topbar-btn">
                + New Team
            </a>
        </div>
    </header>

    
    <div class="flash-container" id="flash-container">
        <?php if(session('success')): ?>
        <div class="flash flash--success">
            <span class="flash-icon">✓</span>
            <span class="flash-body"><?php echo e(session('success')); ?></span>
            <button class="flash-close" onclick="closeFlash(this)">✕</button>
        </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div class="flash flash--error">
            <span class="flash-icon">✕</span>
            <span class="flash-body"><?php echo e(session('error')); ?></span>
            <button class="flash-close" onclick="closeFlash(this)">✕</button>
        </div>
        <?php endif; ?>
        <?php if(session('status')): ?>
        <div class="flash flash--info">
            <span class="flash-icon">ℹ</span>
            <span class="flash-body"><?php echo e(session('status')); ?></span>
            <button class="flash-close" onclick="closeFlash(this)">✕</button>
        </div>
        <?php endif; ?>
    </div>

    
    <main class="page-content">
        <?php echo e($slot); ?>

    </main>
</div>


<form id="theme-sync-form" method="POST" action="<?php echo e(route('profile.update')); ?>" style="display:none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PATCH'); ?>
    <input type="hidden" name="name" value="<?php echo e(auth()->user()->name); ?>">
    <input type="hidden" name="email" value="<?php echo e(auth()->user()->email); ?>">
    <input type="hidden" name="theme" value="">
</form>

<script>
// Update theme sync form before submit
document.getElementById('theme-sync-form')?.addEventListener('submit', function() {
    const theme = document.documentElement.getAttribute('data-theme');
    this.querySelector('[name="theme"]').value = theme;
});
</script>

</body>
</html>
<?php /**PATH C:\Users\radim\Downloads\taskflow-saas-platform-fixed\taskflow\resources\views/layouts/app.blade.php ENDPATH**/ ?>