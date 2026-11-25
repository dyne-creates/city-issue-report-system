<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeedbackRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    /**
     * Show the feedback form for a completed issue.
     */
    public function create(int $issue)
    {
        $issueRecord = DB::table('issues')
            ->where('id', $issue)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        abort_unless(
            $issueRecord->status === 'completed',
            403,
            'This issue is not yet completed.'
        );

        $alreadyRated = DB::table('feedbacks')->where('issue_id', $issue)->exists();
        abort_if($alreadyRated, 403, 'Feedback has already been submitted for this issue.');

        return view('citizen.feedbacks.create', ['issue' => $issueRecord]);
    }

    /**
     * Store the citizen's rating and comment.
     */
    public function store(StoreFeedbackRequest $request, int $issue)
    {
        try {
            DB::transaction(function () use ($request, $issue) {
                DB::table('feedbacks')->insert([
                    'issue_id'   => $issue,
                    'user_id'    => Auth::id(),
                    'rating'     => $request->validated('rating'),
                    'comment'    => $request->validated('comment'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            return redirect()
                ->route('citizen.issues.show', $issue)
                ->with('success', 'Thank you for your feedback!');
        } catch (\Illuminate\Database\QueryException) {
            return redirect()
                ->route('citizen.issues.show', $issue)
                ->with('error', 'Feedback has already been submitted for this issue.');
        }
    }
}
