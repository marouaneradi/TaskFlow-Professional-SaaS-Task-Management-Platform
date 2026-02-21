<x-guest-layout>
    <x-slot:title>Forgot Password</x-slot:title>

    <div class="auth-layout">
        <div class="auth-visual">
            <div class="auth-visual-content">
                <div class="auth-visual-logo">✦</div>
                <h1 class="auth-visual-title">Reset your<br>password</h1>
                <p class="auth-visual-subtitle">
                    No worries — we'll send you instructions to reset your password securely.
                </p>
            </div>
        </div>

        <div class="auth-form-section">
            <div class="auth-form-container">
                <a href="{{ route('login') }}" class="auth-logo-link">
                    <div class="auth-logo-icon">✦</div>
                    <span class="auth-logo-name">TaskFlow</span>
                </a>

                <h2 class="auth-heading">Forgot password?</h2>
                <p class="auth-subheading">
                    Enter your registered email and we'll send a reset link.
                </p>

                @if(session('status'))
                    <div class="alert alert-success">
                        <span class="alert-icon">✓</span>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" id="email" name="email" class="form-input"
                               value="{{ old('email') }}" required autofocus placeholder="you@company.com">
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;margin-bottom:16px;">
                        Send reset link
                    </button>

                    <div class="auth-footer">
                        Remember your password?
                        <a href="{{ route('login') }}" style="font-weight:600;">Back to login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
