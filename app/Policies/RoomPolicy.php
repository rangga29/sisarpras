<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoomPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Room $room)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $room->unit_id;
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Room $room)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $room->unit_id;
    }

    public function delete(User $user, Room $room)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $room->unit_id;
    }
}