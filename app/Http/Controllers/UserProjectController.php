<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Http\Requests\StoreUserProjectRequest;
use App\Http\Requests\UpdateUserProjectRequest;
use App\Http\Resources\UserProjectCollection;
use App\Http\Resources\UserProjectResource;
use App\Models\Project;
use App\Models\UserProject;
use App\Models\UserSupervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $data = $this->deepSearch(
            UserProject::query(),
            'user_projects',
            $request->all()
        )->whereIn('user_supervisor_id', UserSupervisor::where('user_id', Auth::id())->orWhere('supervisor_id', Auth::id())->pluck('id')->toArray())->orderBy('id', 'desc')->paginate(10);
        return response()->json(['data' => new UserProjectCollection($data), 'message' => 'successful']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserProjectRequest $request)
    {
        //
        $validated = $request->validated();
        $data = UserProject::create($validated);
        return response()->json(['data' => new UserProjectResource($data), 'message' => 'successful']);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserProject $userProject)
    {
        //
        if (Auth::user()->cannot('view', $userProject))
            throw new UnauthorizedException;
        return response()->json(['data' => new UserProjectResource($userProject), 'message' => 'successful']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserProjectRequest $request, UserProject $userProject)
    {
        //
        if (Auth::user()->cannot('update', $userProject))
            throw new UnauthorizedException;
        $validated = $request->validated();
        $userProject->update($validated);
        return response()->json(['data' => new UserProjectResource($userProject), 'message' => 'successful']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserProject $userProject)
    {
        //
        if (Auth::user()->cannot('delete', $userProject))
            throw new UnauthorizedException;
        $userProject->delete();
        return response()->json(['message' => 'successful']);
    }
}
