<?php


namespace App\Providers;

use App\Listeners\CreateUserCard;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\UserLoggedIn;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserLoggedIn::class => [
            CreateUserCard::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
