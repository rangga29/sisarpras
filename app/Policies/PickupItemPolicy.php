<?php

namespace App\Policies;

use App\Models\PickupItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PickupItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, PickupItem $pickup)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $pickup->unit_id;
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, PickupItem $pickup)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $pickup->unit_id;
    }

    public function delete(User $user, PickupItem $pickup)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $pickup->unit_id;
    }
}