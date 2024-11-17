<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('creator')->where('status', 'active')->get();
        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'goal_amount' => 'required|numeric|min:0',
            'deadline' => 'required|date|after:today',
        ]);

        $project = auth()->user()->projects()->create($request->all());
        return response()->json(['message' => 'Project created', 'project' => $project], 201);
    }

    public function show(Project $project)
    {
        $project->load('creator', 'contributions.user');
        return response()->json($project);
    }
}
