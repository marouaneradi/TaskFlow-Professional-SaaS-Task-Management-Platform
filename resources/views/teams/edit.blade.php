<x-app-layout>
    <x-slot:title>Edit {{ $team->name }}</x-slot:title>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Edit Team</h1>
            <p class="page-description">Update team details and settings</p>
        </div>
    </div>

    <div style="max-width:580px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Team Information</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('teams.update', $team) }}" id="team-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label">Team name <span>*</span></label>
                        <input type="text" id="name" name="name" class="form-input"
                               value="{{ old('name', $team->name) }}" required autofocus>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-textarea"
                                  rows="3">{{ old('description', $team->description) }}</textarea>
                        @error('description')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="card-footer" style="display:flex;justify-content:space-between;align-items:center;">
                <a href="{{ route('teams.show', $team) }}" class="btn btn-ghost">← Cancel</a>
                <button form="team-form" type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</x-app-layout>
