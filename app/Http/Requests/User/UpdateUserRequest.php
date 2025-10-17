<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|string|email|max:255|unique:users,email,' . $this->route('user')->id,
            'password' => 'sometimes|nullable|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email has already been taken',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ];
    }
}
