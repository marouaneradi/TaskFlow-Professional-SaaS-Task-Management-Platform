<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Handled in controller via policy
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'status'      => ['required', 'in:todo,in_progress,done'],
            'priority'    => ['required', 'in:low,medium,high'],
            'due_date'    => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Task title is required.',
            'due_date.after_or_equal' => 'Due date must be today or in the future.',
        ];
    }
}
