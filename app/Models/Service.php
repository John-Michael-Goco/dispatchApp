<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Get the branches associated with this service.
     */
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Get the responders associated with this service.
     */
    public function responders()
    {
        return $this->hasMany(Responder::class);
    }
}
