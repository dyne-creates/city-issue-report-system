<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        $issue = DB::table('issues')->where('id', $this->route('issue'))->first();

        if (! $issue) {
            return false;
        }

        // Only the citizen who filed the issue may rate it
        return $issue->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $issue = DB::table('issues')->where('id', $this->route('issue'))->first();

            if (! $issue) {
                return; // authorize() already blocks this, guarding defensively
            }

            if ($issue->status !== 'completed') {
                $validator->errors()->add(
                    'status',
                    'Feedback can only be submitted once the issue is marked completed.'
                );
            }

            $alreadyRated = DB::table('feedbacks')
                ->where('issue_id', $issue->id)
                ->exists();

            if ($alreadyRated) {
                $validator->errors()->add(
                    'issue_id',
                    'You have already submitted feedback for this issue.'
                );
            }
        });
    }
}
