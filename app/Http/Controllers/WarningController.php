<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Http\Requests\StoreWarningRequest;
use App\Http\Requests\UpdateWarningRequest;
use App\Http\Resources\WarningCollection;
use App\Http\Resources\WarningResource;
use App\Models\Project;
use App\Models\UserProject;
use App\Models\Warning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        // $data = Auth::user()->warnings()->orderBy('id', 'desc')->paginate(10);
        $data = $this->deepSearch(
            Warning::query(),
            'warnings',
            $request->all()
        )->whereIn(
            'user_project_id',
            UserProject::query()
                ->whereIn(
                    'project_id',
                    Project::query()->where('user_id', Auth::id())->pluck('id')->toArray()
                )
                ->orWhere('user_id', Auth::id())->pluck('id')->toArray()
        )->orderBy('id', 'desc')->paginate(10);
        return response()->json(['data' => new WarningCollection($data), 'message' => 'successful']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarningRequest $request)
    {
        //
        $validated = $request->validated();
        $data = Auth::user()->warnings()->create($validated);
        return response()->json(['data' => new WarningResource($data), 'message' => 'successful']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Warning $warning)
    {
        //
        if (Auth::user()->cannot('view', $warning))
            throw new UnauthorizedException;
        return response()->json(['data' => new WarningResource($warning), 'message' => 'successful']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWarningRequest $request, Warning $warning)
    {
        //
        if (Auth::user()->cannot('update', $warning))
            throw new UnauthorizedException;
        $validated = $request->validated();
        $warning->update($validated);
        return response()->json(['data' => new WarningResource($warning), 'message' => 'successful']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warning $warning)
    {
        //
        if (Auth::user()->cannot('delete', $warning))
            throw new UnauthorizedException;
        $warning->delete();
        return response()->json(['message' => 'successful']);
    }
}
