<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $data = $this->deepSearch(
            Message::query(),
            'messages',
            $request->all()
        )->where(function ($query) {
            $query->where('sender_id', Auth::id())
                ->orWhere('receiver_id', Auth::id());
        })->orderBy('id', 'desc')->paginate(10);
        // $data = Message::where('sender_id', Auth::id())->orWhere('receiver_id', Auth::id())->orderBy('id', 'desc')->paginate(10);
        return response()->json(['data' => new MessageCollection($data), 'message' => 'successful']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        //
        $validated = $request->validated();
        $message = $request->user()->sentMessages()->create($validated);
        return response()->json(['data' => new MessageResource($message), 'message' => 'successful']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
        if (Auth::user()->cannot('view', $message))
            throw new UnauthorizedException;
        $message->seen();
        return response()->json(['data' => new MessageResource($message), 'message' => 'successful']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
        if (Auth::user()->cannot('update', $message))
            throw new UnauthorizedException;
        $validated = $request->validated();
        $message->update($validated);
        return response()->json(['data' => new MessageResource($message), 'message' => 'successful']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
        if (Auth::user()->cannot('delete', $message))
            throw new UnauthorizedException;
        $message->delete();
        return response()->json(['message' => 'successful']);
    }
}
