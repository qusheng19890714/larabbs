<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy extends Policy
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

    public function destroy(User $user, Reply $reply)
    {
        //拥有删除回复权限的用户，应当是『回复的作者』或者『回复话题的作者』：
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
