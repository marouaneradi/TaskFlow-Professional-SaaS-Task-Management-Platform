<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Team;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private ActivityLogService $activityLog) {}

    public function index(Request $request, Team $team)
    {
        $this->authorize('viewAny', [Task::class, $team]);

        $filters = $request->only([
            'search', 'status', 'priority', 'assigned_to', 
            'due_from', 'due_to', 'sort', 'direction',
        ]);

        $tasks = $team->tasks()
            ->filter($filters)
            ->with(['assignee', 'creator'])
            ->paginate(15)
            ->withQueryString();

        $members  = $team->members()->get();
        $userRole = $request->user()->getRoleInTeam($team);

        return view('tasks.index', compact('team', 'tasks', 'filters', 'members', 'userRole'));
    }

    public function create(Request $request, Team $team)
    {
        $this->authorize('create', [Task::class, $team]);
        $members = $team->members()->get();
        return view('tasks.create', compact('team', 'members'));
    }

    public function store(StoreTaskRequest $request, Team $team)
    {
        $this->authorize('create', [Task::class, $team]);

        // Validate assigned_to is a team member
        if ($request->assigned_to) {
            $isMember = $team->members()->where('users.id', $request->assigned_to)->exists();
            if (!$isMember) {
                return back()->withErrors(['assigned_to' => 'Selected user is not a team member.']);
            }
        }

        $task = $team->tasks()->create([
            'title'       => $request->title,
            'description' => $request->description,
            'created_by'  => $request->user()->id,
            'assigned_to' => $request->assigned_to,
            'status'      => $request->status,
            'priority'    => $request->priority,
            'due_date'    => $request->due_date,
        ]);

        $this->activityLog->taskCreated($team, $request->user(), $task);

        if ($request->assigned_to && $request->assigned_to != $request->user()->id) {
            $this->activityLog->taskAssigned($team, $request->user(), $task, $task->assignee);
        }

        return redirect()->route('teams.tasks.index', $team)->with('success', 'Task created successfully!');
    }

    public function show(Request $request, Team $team, Task $task)
    {
        $this->authorize('view', $task);
        $activityLogs = $task->activityLogs()->with('user')->limit(20)->get();
        $members = $team->members()->get();
        $userRole = $request->user()->getRoleInTeam($team);
        return view('tasks.show', compact('team', 'task', 'activityLogs', 'members', 'userRole'));
    }

    public function edit(Request $request, Team $team, Task $task)
    {
        $this->authorize('update', $task);
        $members = $team->members()->get();
        return view('tasks.edit', compact('team', 'task', 'members'));
    }

    public function update(UpdateTaskRequest $request, Team $team, Task $task)
    {
        $this->authorize('update', $task);

        $oldStatus = $task->status;
        $oldAssigned = $task->assigned_to;

        $changes = [];
        $fields = ['title', 'description', 'status', 'priority', 'due_date', 'assigned_to'];
        foreach ($fields as $field) {
            if ($request->has($field) && $task->$field != $request->$field) {
                $changes[$field] = ['old' => $task->$field, 'new' => $request->$field];
            }
        }

        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'status'      => $request->status,
            'priority'    => $request->priority,
            'due_date'    => $request->due_date,
        ]);

        if ($oldStatus !== $task->status) {
            $this->activityLog->statusChanged($team, $request->user(), $task, $oldStatus, $task->status);
            if ($task->status === 'done') {
                $this->activityLog->taskCompleted($team, $request->user(), $task);
            }
        }

        if ($oldAssigned !== $task->assigned_to) {
            $task->load('assignee');
            $this->activityLog->taskAssigned($team, $request->user(), $task, $task->assignee);
        }

        if (!empty($changes)) {
            $this->activityLog->taskUpdated($team, $request->user(), $task, $changes);
        }

        return redirect()->route('teams.tasks.show', [$team, $task])->with('success', 'Task updated successfully!');
    }

    public function destroy(Request $request, Team $team, Task $task)
    {
        $this->authorize('delete', $task);
        $taskTitle = $task->title;
        $task->delete();
        $this->activityLog->taskDeleted($team, $request->user(), $task);

        return redirect()->route('teams.tasks.index', $team)->with('success', "Task \"{$taskTitle}\" deleted.");
    }

    public function updateStatus(Request $request, Team $team, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate(['status' => ['required', 'in:todo,in_progress,done']]);

        $oldStatus = $task->status;
        $task->update(['status' => $request->status]);

        $this->activityLog->statusChanged($team, $request->user(), $task, $oldStatus, $task->status);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $task->status]);
        }

        return back()->with('success', 'Status updated.');
    }
}
