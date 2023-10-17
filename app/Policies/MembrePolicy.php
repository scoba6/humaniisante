<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Membre;
use Illuminate\Auth\Access\HandlesAuthorization;

class MembrePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_membre');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membre  $membre
     * @return bool
     */
    public function view(User $user, Membre $membre): bool
    {
        return $user->can('view_membre');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create_membre');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membre  $membre
     * @return bool
     */
    public function update(User $user, Membre $membre): bool
    {
        return $user->can('update_membre');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membre  $membre
     * @return bool
     */
    public function delete(User $user, Membre $membre): bool
    {
        return $user->can('delete_membre');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_membre');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membre  $membre
     * @return bool
     */
    public function forceDelete(User $user, Membre $membre): bool
    {
        return $user->can('force_delete_membre');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_membre');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membre  $membre
     * @return bool
     */
    public function restore(User $user, Membre $membre): bool
    {
        return $user->can('restore_membre');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_membre');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membre  $membre
     * @return bool
     */
    public function replicate(User $user, Membre $membre): bool
    {
        return $user->can('replicate_membre');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_membre');
    }

}
