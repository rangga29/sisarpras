<?php

namespace App\Policies;

use App\Models\PlacementItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlacementItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, PlacementItem $placement)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $placement->unit_id;
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, PlacementItem $placement)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $placement->unit_id;
    }

    public function delete(User $user, PlacementItem $placement)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $placement->unit_id;
    }
}