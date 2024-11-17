<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'message' => 'nullable|string',
        ]);

        if ($project->status !== 'active') {
            return response()->json(['message' => 'Project is not accepting contributions'], 403);
        }

        $contribution = $project->contributions()->create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'message' => $request->message,
        ]);

        $project->increment('collected_amount', $request->amount);

        return response()->json(['message' => 'Contribution added', 'contribution' => $contribution], 201);
    }
}
