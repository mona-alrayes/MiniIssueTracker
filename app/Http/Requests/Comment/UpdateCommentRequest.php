<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Comment $comment */
        $comment = $this->route('comment'); // type hint helps VSCode

        return Auth::check() && Auth::id() === $comment->user_id;
    }

    public function rules(): array
    {
        return [
            'body' => 'required|string|max:2000',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Tell VSCode this is a Request method
        /** @var \Illuminate\Http\Request $this */
        $this->merge([
            'body' => trim($this->body),
        ]);
    }


}
