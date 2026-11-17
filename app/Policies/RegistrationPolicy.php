<?php

namespace App\Policies;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RegistrationPolicy
{
    /**
     * Determine whether the user can view the registration.
     */
    public function view(User $user, Registration $registration): Response
    {
        return $registration->user_id === $user->id
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat pendaftaran ini.');
    }

    /**
     * Determine whether the user can pay for the registration.
     */
    public function pay(User $user, Registration $registration): Response
    {
        return $registration->user_id === $user->id
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk membayar pendaftaran ini.');
    }

    /**
     * Determine whether the user can view the ticket.
     */
    public function viewTicket(User $user, Registration $registration): Response
    {
        return $registration->user_id === $user->id
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengakses tiket ini.');
    }
}