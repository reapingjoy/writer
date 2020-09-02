<?php

namespace App\Policies;

use App\Draft;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DraftPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Draft  $draft
     * @return mixed
     */
    public function view(User $user, Draft $draft)
    {
        return $user->id === $draft->user_id
            ? Response::allow()
            : Response::deny('You do not own this draft.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Draft  $draft
     * @return mixed
     */
    public function update(User $user, Draft $draft)
    {
        return $user->id === $draft->user_id
            ? Response::allow()
            : Response::deny('You do not own this draft.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Draft  $draft
     * @return mixed
     */
    public function delete(User $user, Draft $draft)
    {
        return $user->id === $draft->user_id
            ? Response::allow()
            : Response::deny('You do not own this draft.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Draft  $draft
     * @return mixed
     */
    public function restore(User $user, Draft $draft)
    {
        return $user->id === $draft->user_id
            ? Response::allow()
            : Response::deny('You do not own this draft.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Draft  $draft
     * @return mixed
     */
    public function forceDelete(User $user, Draft $draft)
    {
        return $user->id === $draft->user_id
            ? Response::allow()
            : Response::deny('You do not own this draft.');
    }
}
