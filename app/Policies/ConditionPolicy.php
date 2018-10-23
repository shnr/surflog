<?php

namespace App\Policies;

use App\User;
use App\Condition;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConditionPolicy
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


    /**
     * ユーザにより削除可能か決める
     *
     * @param  \App\User  $user
     * @param  \App\Condition  $condition
     * @return bool
     */
    public function destroy(User $user, Condition $condition)
    {
        return $user->id === $condition->user_id;
    }


    public function update(User $user, Condition $condition)
    {
        return $user->id === $condition->user_id;
    }


}
