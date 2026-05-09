<?php

namespace App\Listeners;

use App\Models\Order;
use Illuminate\Auth\Events\Registered;

class LinkGuestOrdersAfterRegistration
{
    public function handle(Registered $event): void
    {
        Order::whereNull('user_id')
            ->where('email', $event->user->email)
            ->update(['user_id' => $event->user->id]);
    }
}
