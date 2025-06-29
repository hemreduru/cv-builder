<?php

namespace App\Policies;

use App\Models\Reference;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReferencePolicy
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
        return $user->isAbleTo('reference-read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reference $reference): bool
    {
        return $user->id === $reference->user_id && $user->isAbleTo('reference-read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAbleTo('reference-create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reference $reference): bool
    {
        return $user->id === $reference->user_id && $user->isAbleTo('reference-update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reference $reference): bool
    {
        return $user->id === $reference->user_id && $user->isAbleTo('reference-delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reference $reference): bool
    {
        return $user->id === $reference->user_id && $user->isAbleTo('reference-create');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reference $reference): bool
    {
        return $user->id === $reference->user_id && $user->isAbleTo('reference-delete');
    }
}
