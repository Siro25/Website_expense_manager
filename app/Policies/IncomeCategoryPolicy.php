<?php

namespace App\Policies;

use App\Models\IncomeCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncomeCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the income category.
     */
    public function view(User $user, IncomeCategory $incomeCategory): bool
    {
        return $user->id === $incomeCategory->user_id;
    }

    /**
     * Determine whether the user can update the income category.
     */
    public function update(User $user, IncomeCategory $incomeCategory): bool
    {
        return $user->id === $incomeCategory->user_id;
    }

    /**
     * Determine whether the user can delete the income category.
     */
    public function delete(User $user, IncomeCategory $incomeCategory): bool
    {
        return $user->id === $incomeCategory->user_id;
    }
} 