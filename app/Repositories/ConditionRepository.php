<?php
namespace App\Repositories;
use App\User;
use App\Condition;

class ConditionRepository
{
  /**
   * @paramUser$user
   * @return Collection
   */
  public function forUser(User $user)
  {
      return Condition::where('user_id', $user->id)
      ->orderBy('created_at', 'asc')
      ->get();
  }
}
