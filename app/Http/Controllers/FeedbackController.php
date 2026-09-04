<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user || (! $user->hasRole('Staff Member') && ! $user->hasRole('Admin'))) {
            abort(403, 'Only staff can review feedback.');
        }

        $feedback = Feedback::latest()->get();

        return view('feedback.index', compact('feedback'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $user = Auth::user();

        if (! $user || (! $user->hasRole('Staff Member') && ! $user->hasRole('Admin'))) {
            abort(403, 'Only staff can update feedback.');
        }

        $request->validate([
            'status' => 'required|in:new,seen,processed,done',
            'notes' => 'nullable|string|max:1000',
        ]);

        $feedback->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('feedback.index')->with('success', 'Feedback updated successfully.');
    }
}
