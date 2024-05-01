<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Http\Requests\StoreUserSupervisorRequest;
use App\Http\Requests\UpdateUserSupervisorRequest;
use App\Http\Resources\UserSupervisorCollection;
use App\Http\Resources\UserSupervisorResource;
use App\Models\UserSupervisor;
use Illuminate\Support\Facades\Auth;

class UserSupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = UserSupervisor::where('user_id', Auth::id())->orWhere('supervisor_id', Auth::id())->orderBy('id', 'desc')->paginate(10);
        return response()->json(['data' => new UserSupervisorCollection($data), 'message' => 'successful']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserSupervisorRequest $request)
    {
        //
        $validated = $request->validated();
        $validated = array_merge($validated, ['user_id' => Auth::id(), 'user_accepted' => true]);
        $data = UserSupervisor::create($validated);
        return response()->json(['data' => new UserSupervisorResource($data), 'message' => 'successful']);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserSupervisor $userSupervisor)
    {
        //
        if (Auth::user()->cannot('view', $userSupervisor))
            throw new UnauthorizedException;
        return response()->json(['data' => new UserSupervisorResource($userSupervisor), 'message' => 'successful']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserSupervisorRequest $request, UserSupervisor $userSupervisor)
    {
        //
        if (Auth::user()->cannot('update', $userSupervisor) || $userSupervisor->supervisor->id != Auth::id())
            throw new UnauthorizedException;
        $validated = $request->validated();
        $userSupervisor->update($validated);
        return response()->json(['data' => new UserSupervisorResource($userSupervisor), 'message' => 'successful']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserSupervisor $userSupervisor)
    {
        //
        if (Auth::user()->cannot('delete', $userSupervisor))
            throw new UnauthorizedException;
        $userSupervisor->delete();
        return response()->json(['message' => 'successful']);
    }
}
