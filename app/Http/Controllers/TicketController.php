<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Auth::user()->tickets()->orderBy('id', 'desc')->paginate(10);
        return response()->json(['data' => new TicketCollection($data), 'message' => 'successful']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        //
        $validated = $request->validated();
        $data = Auth::user()->tickets()->create($validated);
        return response()->json(['data' => new TicketResource($data), 'message' => 'successful']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
        if (Auth::user()->cannot('view', $ticket))
            throw new UnauthorizedException();
        return response()->json(['data' => new TicketResource($ticket), 'message' => 'successful']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
        if (Auth::user()->cannot('update', $ticket))
            throw new UnauthorizedException;
        $validated = $request->validated();
        $ticket->update($validated);
        return response()->json(['data' => new TicketResource($ticket), 'message' => 'successful']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
        if (Auth::user()->cannot('delete', $ticket))
            throw new UnauthorizedException;
        $ticket->delete();
        return response()->json(['message' => 'successful']);
    }
}
