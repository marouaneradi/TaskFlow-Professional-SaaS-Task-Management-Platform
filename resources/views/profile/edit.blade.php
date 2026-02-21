<x-app-layout>
    <x-slot:title>Profile Settings</x-slot:title>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Profile Settings</h1>
            <p class="page-description">Manage your account information and preferences</p>
        </div>
    </div>

    <div style="max-width:680px;display:flex;flex-direction:column;gap:20px;">

        {{-- Profile Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Personal Information</div>
            </div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border);">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="profile-avatar" style="width:64px;height:64px;margin:0;">
                    <div>
                        <div class="profile-name">{{ $user->name }}</div>
                        <div class="profile-email">{{ $user->email }}</div>
                        @if(!$user->email_verified_at)
                            <span class="badge badge--high" style="margin-top:6px;">Email not verified</span>
                        @else
                            <span class="badge badge--done" style="margin-top:6px;">✓ Verified</span>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PATCH')

                    <div class="form-group">
                        <label for="name" class="form-label">Full name <span>*</span></label>
                        <input type="text" id="name" name="name" class="form-input"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email address <span>*</span></label>
                        <input type="email" id="email" name="email" class="form-input"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="theme" class="form-label">Theme preference</label>
                            <select id="theme" name="theme" class="form-select">
                                <option value="light" {{ $user->theme === 'light' ? 'selected' : '' }}>☀ Light</option>
                                <option value="dark" {{ $user->theme === 'dark' ? 'selected' : '' }}>◑ Dark</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="timezone" class="form-label">Timezone</label>
                            <input type="text" id="timezone" name="timezone" class="form-input"
                                   value="{{ old('timezone', $user->timezone) }}"
                                   placeholder="UTC">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top:20px;">Save Profile</button>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Change Password</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label for="current_password" class="form-label">Current password</label>
                        <input type="password" id="current_password" name="current_password" class="form-input">
                        @error('current_password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="password" class="form-label">New password</label>
                            <input type="password" id="password" name="password" class="form-input"
                                   placeholder="Min 8 characters">
                            @error('password')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
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

        {{-- Delete Account --}}
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
                    <form method="POST" action="{{ route('profile.destroy') }}"
                          onsubmit="return confirm('This will permanently delete your account. Type your password to confirm.')">
                        @csrf @method('DELETE')
                        <div style="display:flex;gap:8px;align-items:center;">
                            <input type="password" name="password" class="form-input" placeholder="Your password" style="max-width:240px;">
                            <button type="submit" class="btn btn-danger">Delete Account</button>
                        </div>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </form>
                </div>
            </div>
        </div>

        {{-- Logout --}}
        <div style="display:flex;justify-content:flex-end;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-secondary">Sign out of all sessions</button>
            </form>
        </div>
    </div>
</x-app-layout>
