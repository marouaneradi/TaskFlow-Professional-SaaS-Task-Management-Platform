<x-guest-layout>
    <x-slot:title>Verify Email</x-slot:title>

    <div class="auth-layout">
        <div class="auth-visual">
            <div class="auth-visual-content">
                <div class="auth-visual-logo">✦</div>
                <h1 class="auth-visual-title">Check your<br>inbox</h1>
                <p class="auth-visual-subtitle">One more step to unlock full access to TaskFlow.</p>
            </div>
        </div>

        <div class="auth-form-section">
            <div class="auth-form-container">
                <a href="{{ route('login') }}" class="auth-logo-link">
                    <div class="auth-logo-icon">✦</div>
                    <span class="auth-logo-name">TaskFlow</span>
                </a>

                <h2 class="auth-heading">Verify your email</h2>
                <p class="auth-subheading">
                    We've sent a verification link to <strong>{{ auth()->user()->email }}</strong>.
                    Click the link in the email to activate your account.
                </p>

                @if(session('status') === 'verification-link-sent')
                    <div class="alert alert-success" style="margin-bottom:20px;">
                        <span class="alert-icon">✓</span>
                        A fresh verification link has been sent to your email.
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom:16px;">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">
                        Resend verification email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary" style="width:100%;">
                        Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
