<?php

namespace App\Models;

use App\Casts\File;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class MessageReply extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'user_id',
        'message_id',
        'file',
        'seen_at',
    ];



    // /**
    //  * The "booted" method of the model.
    //  */
    // protected static function booted(): void
    // {
    //     static::retrieved(function (MessageReply $message) {
    //         if (!isset($message->seen_at) && Auth::check() && Auth::id() != $message->user_id)
    //             $message->update(['seen_at' => Carbon::now()]);
    //     });
    // }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'file' => File::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }


    public function seen()
    {
        if (!isset($this->seen_at) && Auth::check() && Auth::id() != $this->user_id)
            $this->update(['seen_at' => Carbon::now()]);
    }
}
