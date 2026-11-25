<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $role = auth()->user()?->role;

        if ($role === 'citizen') {
            return [
                'barangay_id' => ['required', 'integer', 'exists:barangays,id'],
                'category_id' => ['required', 'integer', 'exists:categories,id'],
                'title' => ['required', 'string', 'max:200'],
                'description' => ['required', 'string'],
                'specific_location' => ['nullable', 'string', 'max:255'],
                'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            ];
        }

        // Staff and Admin update workflow status and audit remarks
        return [
            'status' => ['required', 'in:reported,verified,in_progress,completed'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'resolved_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'photo.max' => 'The photo must not be larger than 5 MB.',
        ];
    }
}
