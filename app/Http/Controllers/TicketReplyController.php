<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Http\Requests\StoreTicketReplyRequest;
use App\Http\Requests\UpdateTicketReplyRequest;
use App\Http\Resources\TicketReplyCollection;
use App\Http\Resources\TicketReplyResource;
use App\Models\Ticket;
use App\Models\TicketReply;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketReplyController extends Controller
{

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            function (Request $request, Closure $next) {
                $ticket = Ticket::findOrFail($request->route('ticket'));
                if ($ticket->user_id != Auth::id() && !Auth::user()->hasRole('admin'))
                    throw new UnauthorizedException;
                return $next($request);
            },
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Ticket $ticket)
    {
        //
        if (Auth::user()->cannot('view', $ticket))
            throw new UnauthorizedException;
        $data = $ticket->replies()->orderBy('id', 'desc')->paginate(10);
        return response()->json(['data' => new TicketReplyCollection($data), 'message' => 'successful']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketReplyRequest $request)
    {
        //
        $validated = $request->validated();
        $validated = array_merge($validated, ['user_id' => Auth::id()]);
        $data = TicketReply::create($validated);
        return response()->json(['data' => new TicketReplyResource($data), 'message' => 'successful']);
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketReply $ticketReply)
    {
        //
        if (Auth::user()->cannot('view', $ticketReply))
            throw new UnauthorizedException();
        return response()->json(['data' => new TicketReplyResource($ticketReply), 'message' => 'successful']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketReplyRequest $request, TicketReply $ticketReply)
    {
        //
        if (Auth::user()->cannot('update', $ticketReply))
            throw new UnauthorizedException;
        $validated = $request->validated();
        $ticketReply->update($validated);
        return response()->json(['data' => new TicketReplyResource($ticketReply), 'message' => 'successful']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketReply $ticketReply)
    {
        //
        if (Auth::user()->cannot('delete', $ticketReply))
            throw new UnauthorizedException;
        $ticketReply->delete();
        return response()->json(['message' => 'successful']);
    }
}
