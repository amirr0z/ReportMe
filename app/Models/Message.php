<?php

namespace App\Models;

use App\Casts\File;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'sender_id',
        'receiver_id',
        'title',
        'file',
        'seen_at',
    ];

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

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }


    public function replies(): HasMany
    {
        return $this->hasMany(MessageReply::class);
    }

    public function seen()
    {
        if (is_null($this->seen_at) && Auth::check() && Auth::id() == $this->receiver_id)
            $this->update(['seen_at' => Carbon::now()]);
    }
}
