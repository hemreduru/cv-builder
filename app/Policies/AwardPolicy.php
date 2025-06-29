<?php

namespace App\Policies;

use App\Models\Award;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AwardPolicy
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
        return $user->isAbleTo('award-read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Award $award): bool
    {
        return $user->id === $award->user_id && $user->isAbleTo('award-read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAbleTo('award-create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Award $award): bool
    {
        return $user->id === $award->user_id && $user->isAbleTo('award-update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Award $award): bool
    {
        return $user->id === $award->user_id && $user->isAbleTo('award-delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Award $award): bool
    {
        return $user->id === $award->user_id && $user->isAbleTo('award-create');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Award $award): bool
    {
        return $user->id === $award->user_id && $user->isAbleTo('award-delete');
    }
}
