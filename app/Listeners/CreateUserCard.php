<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Models\Card;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateUserCard
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        $userId = $event->userId;
        Card::create([
            'user_id' => $userId,
        ]);
    }
}
