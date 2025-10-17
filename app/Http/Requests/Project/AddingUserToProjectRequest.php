<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class AddingUserToProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: implement authorization
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
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:developer,manager,tester',
        ];
    }

    public function messages(): array
    {
       return [
        'user_id.required' => 'User ID is required',
        'user_id.exists' => 'User ID does not exist',
        'role.required' => 'Role is required',
        'role.in' => 'Role must be developer, manager, or tester',
       ];
    }
}
