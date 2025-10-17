<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachLabelsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only the user who created (reported) the issue can attach/sync labels.
     */
    public function authorize(): bool
    {
        // Route-model binding: we assume route is projects/{project}/issues/{issue}
        $issue = $this->route('issue');

        // If route provided an id instead of model, resolve it:
        if (! $issue) {
            return false;
        }

        // If $issue is integer id, fetch model (optional safeguard)
        if (is_numeric($issue)) {
            $issue = \App\Models\Issue::find($issue);
            if (! $issue) return false;
        }

        // Allow only the reporter (creator) to attach labels
        return $this->user() && $this->user()->id === $issue->reporter_id;
    }

    /**
     * Customize the response for failed authorization (optional).
     */
    protected function failedAuthorization()
    {
        abort(response()->json([
            'message' => 'Only the issue reporter can change labels on this issue.'
        ], 403));
    }

    /**
     * Validation rules for the payload.
     */
    public function rules(): array
    {
        return [
            'label_ids'   => ['required', 'array', 'min:1'],
            'label_ids.*' => ['integer', 'distinct', 'exists:labels,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'label_ids.required' => 'At least one label id is required.',
            'label_ids.*.exists'  => 'One or more provided labels do not exist.',
        ];
    }
}
