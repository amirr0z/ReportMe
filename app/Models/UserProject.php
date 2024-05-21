<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProject extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_supervisor_id',
        'project_id'
    ];

    public function userSupervisor(): BelongsTo
    {
        return $this->belongsTo(UserSupervisor::class);
    }

    public function user()
    {
        return $this->userSupervisor->user;
    }

    public function supervisor()
    {
        return $this->userSupervisor->supervisor;
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
