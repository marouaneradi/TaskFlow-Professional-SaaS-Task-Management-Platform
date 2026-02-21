<x-guest-layout>
    <x-slot:title>Reset Password</x-slot:title>

    <div class="auth-layout">
        <div class="auth-visual">
            <div class="auth-visual-content">
                <div class="auth-visual-logo">✦</div>
                <h1 class="auth-visual-title">Set a new<br>password</h1>
                <p class="auth-visual-subtitle">Choose a strong password to keep your account secure.</p>
            </div>
        </div>

        <div class="auth-form-section">
            <div class="auth-form-container">
                <a href="{{ route('login') }}" class="auth-logo-link">
                    <div class="auth-logo-icon">✦</div>
                    <span class="auth-logo-name">TaskFlow</span>
                </a>

                <h2 class="auth-heading">Reset password</h2>
                <p class="auth-subheading">Enter your new password below.</p>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ request()->route('token') }}">

                    <div class="form-group">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" id="email" name="email" class="form-input"
                               value="{{ old('email', request()->email) }}" required autofocus>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">New password</label>
                        <input type="password" id="password" name="password" class="form-input"
                               required autocomplete="new-password" placeholder="Min 8 characters">
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm new password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-input" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">
                        Reset password
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
