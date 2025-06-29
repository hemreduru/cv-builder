<?php

namespace App\Policies;

use App\Models\Hobby;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HobbyPolicy
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
        return $user->isAbleTo('hobby-read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Hobby $hobby): bool
    {
        return $user->id === $hobby->user_id && $user->isAbleTo('hobby-read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAbleTo('hobby-create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Hobby $hobby): bool
    {
        return $user->id === $hobby->user_id && $user->isAbleTo('hobby-update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Hobby $hobby): bool
    {
        return $user->id === $hobby->user_id && $user->isAbleTo('hobby-delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Hobby $hobby): bool
    {
        return $user->id === $hobby->user_id && $user->isAbleTo('hobby-create');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Hobby $hobby): bool
    {
        return $user->id === $hobby->user_id && $user->isAbleTo('hobby-delete');
    }
}
