<?php

namespace App\Policies;

use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TravelOrderPolicy
{
    use HandlesAuthorization;

    public function updateStatus(User $user, TravelOrder $order)
    {
        return $user->is_admin; // Apenas admin pode atualizar status
    }

    public function view(User $user, TravelOrder $order)
    {
        return $user->id === $order->user_id || $user->is_admin;
    }

    public function update(User $user, TravelOrder $order)
    {
        return $user->id === $order->user_id;
    }

    public function delete(User $user, TravelOrder $order)
    {
        return $user->id === $order->user_id && $order->status === 'solicitado';
    }
}
