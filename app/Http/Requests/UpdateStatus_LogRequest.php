<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStatus_LogRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'issue_id' => ['required', 'integer', 'exists:issues,id'],
            'changed_by' => ['required', 'integer', 'exists:users,id'],
            'old_status' => ['nullable', 'in:reported,verified,in_progress,completed'],
            'new_status' => ['required', 'in:reported,verified,in_progress,completed'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
