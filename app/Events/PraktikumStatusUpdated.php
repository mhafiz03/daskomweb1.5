<?php

namespace App\Events;

use App\Models\CurrentPraktikum;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;


class PraktikumStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $current_praktikum;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CurrentPraktikum $current_praktikum)
    {
        $this->current_praktikum = $current_praktikum;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('praktikum.' . $this->current_praktikum->kelas_id);
    }

    public function broadcastAs(): string
    {
        return 'PraktikumStatusUpdated';
    }
}
