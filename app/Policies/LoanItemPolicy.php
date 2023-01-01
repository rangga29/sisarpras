<?php

namespace App\Policies;

use App\Models\LoanItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, LoanItem $loan)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $loan->unit_id;
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, LoanItem $loan)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $loan->unit_id;
    }

    public function delete(User $user, LoanItem $loan)
    {
        if ($user->unit_id === 1 && $user->role === 1) {
            return true;
        }
        return $user->unit_id === $loan->unit_id;
    }
}