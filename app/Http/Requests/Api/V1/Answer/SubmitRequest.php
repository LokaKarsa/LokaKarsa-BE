<?php

namespace App\Http\Requests\Api\V1\Answer;

use Illuminate\Foundation\Http\FormRequest;

class SubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'question_id' => ['required', 'integer', 'exists:questions,id'],
            'answer' => ['required', 'string', 'max:255'],
        ];
    }
}
