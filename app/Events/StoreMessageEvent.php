<?php

declare(strict_types=1);

namespace App\Events;

use App\Http\Resources\Message\MessageResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StoreMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $message;

    /**
     * Create a new event instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('themes.'.$this->message->theme_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'store_message';

    }

    public function broadcastWith(): array
    {
        return [
            'data' => MessageResource::make($this->message)->resolve(),
        ];

    }
}
