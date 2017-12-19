<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

    public function before($user, $ability)
    {
        //如果用户拥有管理内容权限的话, 即授权通过
        if ($user->can('manage_contents')) {

            return true;
        }
    }
}
