<?php

namespace App\Providers;

use App\Models\ConsItem;
use App\Models\Consumer;
use App\Models\LoanItem;
use App\Models\NonConsItem;
use App\Models\PickupItem;
use App\Models\PlacementItem;
use App\Models\Room;
use App\Policies\ConsItemPolicy;
use App\Policies\ConsumerPolicy;
use App\Policies\LoanItemPolicy;
use App\Policies\NonConsItemPolicy;
use App\Policies\PickupItemPolicy;
use App\Policies\PlacementItemPolicy;
use App\Policies\RoomPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ConsItem::class => ConsItemPolicy::class,
        PickupItem::class => PickupItemPolicy::class,
        NonConsItem::class => NonConsItemPolicy::class,
        LoanItem::class => LoanItemPolicy::class,
        PlacementItem::class => PlacementItemPolicy::class,
        Consumer::class => ConsumerPolicy::class,
        Room::class => RoomPolicy::class
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}