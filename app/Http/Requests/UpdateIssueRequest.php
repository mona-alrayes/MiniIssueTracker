<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIssueRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|string|in:open,in_progress,completed',
            'priority' => 'sometimes|string|in:lowest,low,medium,high,highest',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
            'code' => 'sometimes|string|unique:issues,code,' . $this->issue->id . '|max:255',
            'due_window' => 'sometimes|nullable|array',
            'due_window.due_at' => 'sometimes|nullable|date',
            'due_window.remind_before' => 'sometimes|nullable|string',
            'status_change_at' => 'sometimes|nullable|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'status.in' => 'Status must be one of: open, in_progress, completed.',
            'priority.in' => 'Priority must be one of: lowest, low, medium, high, highest.',
            'assigned_to.exists' => 'The selected assignee does not exist.',
            'code.unique' => 'This issue code already exists.',
        ];
    }
}
