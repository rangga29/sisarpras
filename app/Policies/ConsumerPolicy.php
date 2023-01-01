<?php

namespace App\Policies;

use App\Models\Consumer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConsumerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Consumer $consumer)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $consumer->unit_id;
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Consumer $consumer)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $consumer->unit_id;
    }

    public function delete(User $user, Consumer $consumer)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $consumer->unit_id;
    }
}