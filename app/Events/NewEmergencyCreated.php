<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewEmergencyCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $emergency;

    public function __construct($emergency)
    {
        $this->emergency = $emergency;
    }

    public function broadcastOn()
    {
        return new Channel('emergencies');
    }

    public function broadcastAs()
    {
        return 'new-emergency';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->emergency->id,
            'incident' => $this->emergency->incident,
            'latitude' => $this->emergency->latitude,
            'longitude' => $this->emergency->longitude,
            'user' => [
                'name' => $this->emergency->user->name
            ],
            'status' => $this->emergency->status,
            'created_at' => $this->emergency->created_at->format('M d, Y h:i A'),
        ];
    }
}
