<?php

namespace App\Policies;

use App\Models\ConsItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConsItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, ConsItem $item)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $item->unit_id;
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, ConsItem $item)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $item->unit_id;
    }

    public function delete(User $user, ConsItem $item)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $item->unit_id;
    }
}