<x-app-layout>
    <x-slot:title>Create Team</x-slot:title>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Create a new team</h1>
            <p class="page-description">Set up a workspace for your team</p>
        </div>
    </div>

    <div style="max-width:580px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Team Details</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('teams.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Team name <span>*</span></label>
                        <input type="text" id="name" name="name" class="form-input"
                               value="{{ old('name') }}" required autofocus
                               placeholder="e.g. Product Team, Marketing, Engineering">
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-textarea" rows="3"
                                  placeholder="What does this team work on?">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="card-footer" style="display:flex;justify-content:space-between;align-items:center;">
                <a href="{{ route('teams.index') }}" class="btn btn-ghost">
                    ← Cancel
                </a>
                <button form="create-team-form" type="submit" class="btn btn-primary"
                        onclick="this.closest('.card').querySelector('form').submit()">
                    Create Team →
                </button>
            </div>
        </div>

        {{-- Info block --}}
        <div style="margin-top:16px;padding:16px;background:var(--brand-50);border:1px solid var(--brand-100);border-radius:var(--radius);font-size:13px;color:var(--text-secondary);">
            <strong style="color:var(--brand-700);">You'll be the team owner.</strong>
            After creating the team, you can invite members and assign roles (Admin or Member).
        </div>
    </div>
</x-app-layout>
