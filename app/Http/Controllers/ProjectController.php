<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        // $data = Project::where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(10);
        $data = $this->deepSearch(
            Project::query(),
            'projects',
            $request->all()
        )->where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(10);
        return response()->json(['data' => new ProjectCollection($data), 'message' => 'successful']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        //
        $validated = $request->validated();
        $data = Auth::user()->projects()->create($validated);
        return response()->json(['data' => new ProjectResource($data), 'message' => 'successful']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
        if (Auth::user()->cannot('view', $project))
            throw new UnauthorizedException;
        return response()->json(['data' => new ProjectResource($project), 'message' => 'successful']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //
        if (Auth::user()->cannot('update', $project))
            throw new UnauthorizedException;
        $validated = $request->validated();
        $project->update($validated);
        return response()->json(['data' => new ProjectResource($project), 'message' => 'successful']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
        if (Auth::user()->cannot('delete', $project))
            throw new UnauthorizedException;
        $project->delete();
        return response()->json(['message' => 'successful']);
    }
}
