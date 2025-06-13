<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Responder extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'responder_code',
        'service_id',
        'longitude',
        'latitude',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'longitude' => 'decimal:7',
        'latitude' => 'decimal:7',
    ];

    /**
     * Get the user that owns the responder.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service that the responder belongs to.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Check if the responder is available.
     */
    public function isAvailable()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the responder is busy.
     */
    public function isBusy()
    {
        return $this->status === 'busy';
    }

    /**
     * Check if the responder is inactive.
     */
    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    /**
     * Check if the responder is in maintenance.
     */
    public function isInMaintenance()
    {
        return $this->status === 'maintenance';
    }
}
