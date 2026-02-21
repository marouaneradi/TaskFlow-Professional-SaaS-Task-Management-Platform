<x-guest-layout>
    <x-slot:title>Sign In</x-slot:title>

    <div class="auth-layout">
        {{-- Left Visual Panel --}}
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

        {{-- Right Form Panel --}}
        <div class="auth-form-section">
            <div class="auth-form-container">
                <a href="{{ route('login') }}" class="auth-logo-link">
                    <div class="auth-logo-icon">✦</div>
                    <span class="auth-logo-name">TaskFlow</span>
                </a>

                <h2 class="auth-heading">Welcome back</h2>
                <p class="auth-subheading">Sign in to your account to continue</p>

                @if(session('status'))
                    <div class="alert alert-success">
                        <span class="alert-icon">✓</span>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" id="email" name="email" class="form-input"
                               value="{{ old('email') }}" required autofocus autocomplete="email"
                               placeholder="you@company.com">
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="flex items-center justify-between">
                            <label for="password" class="form-label">Password</label>
                            <a href="{{ route('password.request') }}" style="font-size:13px;">Forgot password?</a>
                        </div>
                        <input type="password" id="password" name="password" class="form-input"
                               required autocomplete="current-password" placeholder="••••••••">
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
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
                        <a href="{{ route('register') }}" style="font-weight:600;">Create one free →</a>
                    </div>
                </form>

              
            </div>
        </div>
    </div>
</x-guest-layout>
