<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Http\Requests\StoreMessageReplyRequest;
use App\Http\Requests\UpdateMessageReplyRequest;
use App\Http\Resources\MessageReplyCollection;
use App\Http\Resources\MessageReplyResource;
use App\Models\Message;
use App\Models\MessageReply;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageReplyController extends Controller
{

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            function (Request $request, Closure $next) {
                $message = Message::findOrFail($request->route('message'));
                if ($message->sender_id != Auth::id() && $message->receiver_id != Auth::id()) {
                    throw new UnauthorizedException;
                }
                return $next($request);
            },
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Message $message)
    {
        //
        if (Auth::user()->cannot('view', $message))
            throw new UnauthorizedException;
        // $data = $message->replies()->orderBy('id', 'desc')->paginate(10);
        $data = $this->deepSearch(
            MessageReply::query(),
            'message_replies',
            $request->all()
        )->where('message_id', $message->id)->orderBy('id', 'desc')->paginate(10);
        return response()->json(['data' => new MessageReplyCollection($data), 'message' => 'successful']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageReplyRequest $request)
    {
        $validated = $request->validated();
        $validated = array_merge($validated, ['user_id' => Auth::id()]);
        $data = MessageReply::create($validated);
        return response()->json(['data' => new MessageReplyResource($data), 'message' => 'successful']);
    }

    /**
     * Display the specified resource.
     */
    public function show(MessageReply $messageReply)
    {
        //
        if (Auth::user()->cannot('view', $messageReply))
            throw new UnauthorizedException();
        $messageReply->seen();
        return response()->json(['data' => new MessageReplyResource($messageReply), 'message' => 'successful']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageReplyRequest $request, MessageReply $messageReply)
    {
        //
        if (Auth::user()->cannot('update', $messageReply))
            throw new UnauthorizedException;
        $validated = $request->validated();
        $messageReply->update($validated);
        return response()->json(['data' => new MessageReplyResource($messageReply), 'message' => 'successful']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageReply $messageReply)
    {
        //
        if (Auth::user()->cannot('delete', $messageReply))
            throw new UnauthorizedException;
        $messageReply->delete();
        return response()->json(['message' => 'successful']);
    }
}
