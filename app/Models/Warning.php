<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warning extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_project_id',
        'description',
        'file',
        'score',
    ];

    public function userProject(): BelongsTo
    {
        return $this->belongsTo(UserProject::class);
    }

    public function user()
    {
        return $this->userProject->user;
    }

    public function project()
    {
        return $this->userProject->project;
    }
}
