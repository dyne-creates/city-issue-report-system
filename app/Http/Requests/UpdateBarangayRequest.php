<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBarangayRequest extends FormRequest
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
        $barangay = $this->route('barangay');
        $barangayId = is_object($barangay) ? $barangay->id : $barangay;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('barangays', 'name')->ignore($barangayId),
            ],
        ];
    }
}
