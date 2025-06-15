<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Emergency extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'incident',
        'latitude',
        'longitude',
        'user_id',
        'status',
    ];

    /**
     * Get the user that reported the emergency.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
