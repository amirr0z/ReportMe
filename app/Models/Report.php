<?php

namespace App\Models;

use App\Casts\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_project_id',
        'description',
        'file',
        'score',
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


    public function userProject(): BelongsTo
    {
        return $this->belongsTo(UserProject::class);
    }

    public function user()
    {
        return $this->userProject->user();
    }

    public function project()
    {
        return $this->userProject->project;
    }
}
