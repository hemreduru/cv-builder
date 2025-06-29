<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Volunteering;
use Illuminate\Auth\Access\Response;

class VolunteeringPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        
        return null;
    }
    
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAbleTo('volunteering-read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Volunteering $volunteering): bool
    {
        return $user->id === $volunteering->user_id && $user->isAbleTo('volunteering-read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAbleTo('volunteering-create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Volunteering $volunteering): bool
    {
        return $user->id === $volunteering->user_id && $user->isAbleTo('volunteering-update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Volunteering $volunteering): bool
    {
        return $user->id === $volunteering->user_id && $user->isAbleTo('volunteering-delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Volunteering $volunteering): bool
    {
        return $user->id === $volunteering->user_id && $user->isAbleTo('volunteering-create');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Volunteering $volunteering): bool
    {
        return $user->id === $volunteering->user_id && $user->isAbleTo('volunteering-delete');
    }
}
