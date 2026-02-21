<x-guest-layout>
    <x-slot:title>Create Account</x-slot:title>

    <div class="auth-layout">
        <div class="auth-visual">
            <div class="auth-visual-content">
                <div class="auth-visual-logo">✦</div>
                <h1 class="auth-visual-title">
                    Start collaborating<br>in minutes.
                </h1>
                <p class="auth-visual-subtitle">
                    Join thousands of teams using TaskFlow to stay organized, focused, and ship their best work.
                </p>
                <ul class="auth-feature-list">
                    <li>Free to get started, no credit card required</li>
                    <li>Unlimited tasks and team members</li>
                    <li>Advanced filtering and search</li>
                    <li>Complete activity history</li>
                </ul>
            </div>
        </div>

        <div class="auth-form-section">
            <div class="auth-form-container">
                <a href="{{ route('login') }}" class="auth-logo-link">
                    <div class="auth-logo-icon">✦</div>
                    <span class="auth-logo-name">TaskFlow</span>
                </a>

                <h2 class="auth-heading">Create your account</h2>
                <p class="auth-subheading">Get started for free — no credit card required</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Full name <span>*</span></label>
                        <input type="text" id="name" name="name" class="form-input"
                               value="{{ old('name') }}" required autofocus autocomplete="name"
                               placeholder="Alex Johnson">
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email address <span>*</span></label>
                        <input type="email" id="email" name="email" class="form-input"
                               value="{{ old('email') }}" required autocomplete="username"
                               placeholder="you@company.com">
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="form-label">Password <span>*</span></label>
                            <input type="password" id="password" name="password" class="form-input"
                                   required autocomplete="new-password" placeholder="Min 8 characters">
                            @error('password')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm password <span>*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-input" required autocomplete="new-password" placeholder="Repeat password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;margin-bottom:16px;">
                        Create account →
                    </button>

                    <p style="font-size:12px;color:var(--text-muted);text-align:center;margin-bottom:16px;">
                        By creating an account, you agree to our
                        <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
                    </p>

                    <div class="auth-footer">
                        Already have an account?
                        <a href="{{ route('login') }}" style="font-weight:600;">Sign in →</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
