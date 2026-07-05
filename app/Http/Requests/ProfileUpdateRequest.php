<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:150',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'contact_number' => [
<<<<<<< HEAD
                'nullable',
                'digits: 11',
            ],
            'barangay_id' => [
                'nullable',
=======
                'required',
                'string',
                'max:11',
            ],
            'barangay_id' => [
                'required',
>>>>>>> d97a5ac3780dcf270d7a5b2cc879f28b8a482c15
                'exists:barangays,id',
            ],
        ];
    }
}
