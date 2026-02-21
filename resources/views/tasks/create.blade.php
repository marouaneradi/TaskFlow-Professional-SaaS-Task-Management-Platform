<x-app-layout>
    <x-slot:title>New Task — {{ $team->name }}</x-slot:title>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Create Task</h1>
            <p class="page-description">{{ $team->name }}</p>
        </div>
    </div>

    <div style="max-width:680px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Task Details</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('teams.tasks.store', $team) }}" id="task-form">
                    @csrf

                    <div class="form-group">
                        <label for="title" class="form-label">Title <span>*</span></label>
                        <input type="text" id="title" name="title" class="form-input"
                               value="{{ old('title') }}" required autofocus
                               placeholder="What needs to be done?">
                        @error('title')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-textarea" rows="4"
                                  placeholder="Add more context, acceptance criteria, or notes...">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="status" class="form-label">Status <span>*</span></label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="todo" {{ old('status', 'todo') === 'todo' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ old('status') === 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="priority" class="form-label">Priority <span>*</span></label>
                            <select id="priority" name="priority" class="form-select" required>
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>🟢 Low</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>🔴 High</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="assigned_to" class="form-label">Assign to</label>
                            <select id="assigned_to" name="assigned_to" class="form-select">
                                <option value="">— Unassigned —</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('assigned_to') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="due_date" class="form-label">Due date</label>
                            <input type="date" id="due_date" name="due_date" class="form-input"
                                   value="{{ old('due_date') }}"
                                   min="{{ now()->toDateString() }}">
                            @error('due_date')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer" style="display:flex;justify-content:space-between;align-items:center;">
                <a href="{{ route('teams.tasks.index', $team) }}" class="btn btn-ghost">← Cancel</a>
                <div style="display:flex;gap:8px;">
                    <button form="task-form" type="submit" name="_redirect" value="create" class="btn btn-secondary">
                        Save & Create Another
                    </button>
                    <button form="task-form" type="submit" class="btn btn-primary">
                        Create Task →
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
