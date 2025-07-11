<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
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
        return $user->isAbleTo('project-read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->id === $project->user_id && $user->isAbleTo('project-read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAbleTo('project-create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->user_id && $user->isAbleTo('project-update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id && $user->isAbleTo('project-delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->id === $project->user_id && $user->isAbleTo('project-create');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id && $user->isAbleTo('project-delete');
    }
    
    /**
     * Determine whether the user can toggle featured status of the project.
     */
    public function toggleFeatured(User $user, Project $project): bool
    {
        return $user->id === $project->user_id && $user->isAbleTo('project-update');
    }
}
