<?php

namespace App\Events;

use App\Http\Controllers\DataController;
use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServerUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public bool $afterCommit = true;


    public function broadcastOn(): Channel
    {
        return
            new PrivateChannel('server.updates');
    }

    public function broadcastAs(): string
    {
        return 'server.updated';
    }

    /*public function broadcastWith(): array
    {

        $date = $this->model->updated_at;

        return (new DataController())->paginate($date->subSecond());
    }*/
}
