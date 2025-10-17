<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|unique:projects,code|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'name.string' => 'Project name must be a string.',
            'name.max' => 'Project name may not be greater than 255 characters.',
            'description.string' => 'Description must be a string.',
            'code.required' => 'Project code is required.',
            'code.string' => 'Project code must be a string.',
            'code.unique' => 'Project code must be unique.',
            'code.max' => 'Project code may not be greater than 10 characters.',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json([
            'message' => 'Validation Failed',
            'errors' => $validator->errors()
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
