<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Http\Resources\ReportCollection;
use App\Http\Resources\ReportResource;
use App\Models\Project;
use App\Models\Report;
use App\Models\UserProject;
use App\Models\UserSupervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        // $data = Auth::user()->reports()->orderBy('id', 'desc')->paginate(10);
        $data = $this->deepSearch(
            Report::query(),
            'reports',
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

        return response()->json(['data' => new ReportCollection($data), 'message' => 'successful']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        //
        $validated = $request->validated();
        $data = Auth::user()->reports()->create($validated);
        return response()->json(['data' => new ReportResource($data), 'message' => 'successful']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
        if (Auth::user()->cannot('view', $report))
            throw new UnauthorizedException;
        return response()->json(['data' => new ReportResource($report), 'message' => 'successful']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        //
        if (Auth::user()->cannot('update', $report))
            throw new UnauthorizedException;
        $validated = $request->validated();
        $report->update($validated);
        return response()->json(['data' => new ReportResource($report), 'message' => 'successful']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
        if (Auth::user()->cannot('delete', $report))
            throw new UnauthorizedException;
        $report->delete();
        return response()->json(['message' => 'successful']);
    }
}
