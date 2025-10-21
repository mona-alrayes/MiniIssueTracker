<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIssueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:open,in_progress,completed',
            'priority' => 'required|string|in:lowest,low,medium,high,highest',
            'project_id' => 'required|exists:projects,id',
            'created_by' => 'required|exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
            'code' => 'required|string|unique:issues,code|max:255',
            'due_window' => 'nullable|array',
            'due_window.due_at' => 'nullable|date',
            'due_window.remind_before' => 'nullable|string',
            'status_change_at' => 'nullable|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Issue title is required.',
            'status.in' => 'Status must be one of: open, in_progress, completed.',
            'priority.in' => 'Priority must be one of: lowest, low, medium, high, highest.',
            'project_id.exists' => 'The selected project does not exist.',
            'created_by.exists' => 'The selected creator does not exist.',
            'assigned_to.exists' => 'The selected assignee does not exist.',
            'code.unique' => 'This issue code already exists.',
        ];
    }
}
