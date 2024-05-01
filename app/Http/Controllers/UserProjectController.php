<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Http\Requests\StoreUserProjectRequest;
use App\Http\Requests\UpdateUserProjectRequest;
use App\Http\Resources\UserProjectCollection;
use App\Http\Resources\UserProjectResource;
use App\Models\UserProject;
use Illuminate\Support\Facades\Auth;

class UserProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = UserProject::where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(10);
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
