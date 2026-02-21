<x-app-layout>
    <x-slot:title>Edit Task</x-slot:title>

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Edit Task</h1>
            <p class="page-description">{{ $team->name }}</p>
        </div>
    </div>

    <div style="max-width:680px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Task Details</div>
                <span class="badge {{ $task->getStatusBadgeClass() }}">{{ $task->getStatusLabel() }}</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('teams.tasks.update', [$team, $task]) }}" id="task-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="title" class="form-label">Title <span>*</span></label>
                        <input type="text" id="title" name="title" class="form-input"
                               value="{{ old('title', $task->title) }}" required autofocus>
                        @error('title')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-textarea" rows="4">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="status" class="form-label">Status <span>*</span></label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="todo" {{ old('status', $task->status) === 'todo' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ old('status', $task->status) === 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="priority" class="form-label">Priority <span>*</span></label>
                            <select id="priority" name="priority" class="form-select" required>
                                <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>🟢 Low</option>
                                <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                                <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>🔴 High</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="assigned_to" class="form-label">Assign to</label>
                            <select id="assigned_to" name="assigned_to" class="form-select">
                                <option value="">— Unassigned —</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('assigned_to', $task->assigned_to) == $member->id ? 'selected' : '' }}>
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
                                   value="{{ old('due_date', $task->due_date?->toDateString()) }}">
                            @error('due_date')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer" style="display:flex;justify-content:space-between;align-items:center;">
                <div style="display:flex;gap:8px;">
                    <a href="{{ route('teams.tasks.show', [$team, $task]) }}" class="btn btn-ghost">← Cancel</a>
                    @can('delete', $task)
                    <form method="POST" action="{{ route('teams.tasks.destroy', [$team, $task]) }}"
                          onsubmit="return confirm('Delete this task?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    @endcan
                </div>
                <button form="task-form" type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</x-app-layout>
