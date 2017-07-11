<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Supporter;
use App\Http\Controllers\FundingController;

class SupporterUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $supporter;
    public $calculation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Supporter $supporter)
    {
        $this->supporter = $supporter;
        $supporters = Supporter::all();
        $this->calculation = FundingController::getCurrentCalculation($supporters);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('supporter.updated');
    }
}
