<?php

namespace App\Policies;

use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SocialLinkPolicy
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
        return $user->isAbleTo('social-link-read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SocialLink $socialLink): bool
    {
        return $user->id === $socialLink->user_id && $user->isAbleTo('social-link-read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAbleTo('social-link-create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SocialLink $socialLink): bool
    {
        return $user->id === $socialLink->user_id && $user->isAbleTo('social-link-update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SocialLink $socialLink): bool
    {
        return $user->id === $socialLink->user_id && $user->isAbleTo('social-link-delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SocialLink $socialLink): bool
    {
        return $user->id === $socialLink->user_id && $user->isAbleTo('social-link-create');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SocialLink $socialLink): bool
    {
        return $user->id === $socialLink->user_id && $user->isAbleTo('social-link-delete');
    }
}
