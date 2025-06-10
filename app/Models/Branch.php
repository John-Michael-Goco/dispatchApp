<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    // Fields that can be mass assigned
    protected $fillable = [
        'name',
        'service_id',
        'address',
        'contact_number',
        'latitude',
        'longitude',
        'status',
    ];

    // Get the service this branch belongs to
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
