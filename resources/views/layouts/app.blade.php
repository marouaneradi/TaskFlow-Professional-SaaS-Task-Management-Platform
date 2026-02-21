<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ auth()->user()?->theme ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}TaskFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

{{-- Sidebar Overlay for Mobile --}}
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

{{-- Sidebar --}}
<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-logo">
        <div class="sidebar-logo-icon">✦</div>
        <span class="sidebar-logo-text">TaskFlow</span>
        <span class="sidebar-logo-badge">v2</span>
    </a>

    <div class="sidebar-section">
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('teams.index') }}" class="{{ request()->routeIs('teams.*') ? 'active' : '' }}">
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

        @php
            $teams = auth()->user()->teams()->get();
            $currentTeamId = session('current_team_id');
            $currentTeam = $teams->find($currentTeamId) ?? $teams->first();
        @endphp

        @if($teams->count() > 0)
        <div class="sidebar-section-label" style="margin-top:20px;">Your Teams</div>
        <ul class="sidebar-nav">
            @foreach($teams as $team)
            <li>
                <a href="{{ route('teams.show', $team) }}"
                   class="{{ $currentTeam?->id === $team->id && request()->routeIs('teams.show') ? 'active' : '' }}"
                   style="font-size:13px;">
                    <div class="team-avatar" style="font-size:10px;width:22px;height:22px;border-radius:5px;flex-shrink:0;">
                        {{ strtoupper(substr($team->name, 0, 2)) }}
                    </div>
                    {{ Str::limit($team->name, 22) }}
                </a>
            </li>
            @endforeach
        </ul>

        @if($currentTeam)
        <div class="sidebar-section-label" style="margin-top:20px;">Current Team</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('teams.tasks.index', $currentTeam) }}"
                   class="{{ request()->routeIs('teams.tasks.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                    Tasks
                </a>
            </li>
            <li>
                <a href="{{ route('teams.activity', $currentTeam) }}"
                   class="{{ request()->routeIs('teams.activity') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    Activity
                </a>
            </li>
        </ul>
        @endif
        @endif
    </div>

    {{-- User section at bottom --}}
    <div class="sidebar-user">
        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="sidebar-user-avatar">
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
            <div class="sidebar-user-email">{{ auth()->user()->email }}</div>
        </div>
        <div class="sidebar-user-actions">
            <button class="sidebar-icon-btn" onclick="toggleTheme()"
                    data-tooltip="{{ auth()->user()->theme === 'dark' ? 'Light mode' : 'Dark mode' }}">
                {{ auth()->user()->theme === 'dark' ? '☀' : '◑' }}
            </button>
            <a href="{{ route('profile.edit') }}" class="sidebar-icon-btn" data-tooltip="Profile">
                ⚙
            </a>
        </div>
    </div>
</aside>

{{-- Main Content --}}
<div class="main-content" id="main-content">
    {{-- Topbar --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="mobile-menu-btn" onclick="toggleSidebar()" style="display:none;">☰</button>
            @isset($topbarLeft)
                {{ $topbarLeft }}
            @else
                <div>
                    <div class="topbar-title">{{ $title ?? 'Dashboard' }}</div>
                    @isset($subtitle)
                        <div class="topbar-subtitle">{{ $subtitle }}</div>
                    @endisset
                </div>
            @endisset
        </div>
        <div class="topbar-right">
            @isset($topbarActions)
                {{ $topbarActions }}
            @endisset

            {{-- Team Switcher --}}
            @if(isset($currentTeam) || isset($teams))
            @php $switchTeams = isset($teams) ? $teams : auth()->user()->teams()->get(); @endphp
            @if($switchTeams->count() > 1)
            <div class="dropdown">
                <button class="topbar-btn" data-dropdown="team-switcher-menu">
                    Switch Team ▾
                </button>
                <div class="dropdown-menu hidden" id="team-switcher-menu">
                    @foreach($switchTeams as $t)
                    <form method="POST" action="{{ route('teams.switch', $t) }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <div class="team-avatar" style="font-size:10px;width:20px;height:20px;border-radius:4px;">
                                {{ strtoupper(substr($t->name, 0, 2)) }}
                            </div>
                            {{ $t->name }}
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
            @endif
            @endif

            {{-- New Team --}}
            <a href="{{ route('teams.create') }}" class="topbar-btn">
                + New Team
            </a>
        </div>
    </header>

    {{-- Flash Messages --}}
    <div class="flash-container" id="flash-container">
        @if(session('success'))
        <div class="flash flash--success">
            <span class="flash-icon">✓</span>
            <span class="flash-body">{{ session('success') }}</span>
            <button class="flash-close" onclick="closeFlash(this)">✕</button>
        </div>
        @endif
        @if(session('error'))
        <div class="flash flash--error">
            <span class="flash-icon">✕</span>
            <span class="flash-body">{{ session('error') }}</span>
            <button class="flash-close" onclick="closeFlash(this)">✕</button>
        </div>
        @endif
        @if(session('status'))
        <div class="flash flash--info">
            <span class="flash-icon">ℹ</span>
            <span class="flash-body">{{ session('status') }}</span>
            <button class="flash-close" onclick="closeFlash(this)">✕</button>
        </div>
        @endif
    </div>

    {{-- Page Content --}}
    <main class="page-content">
        {{ $slot }}
    </main>
</div>

{{-- Theme sync form (hidden) --}}
<form id="theme-sync-form" method="POST" action="{{ route('profile.update') }}" style="display:none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
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
