<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

// Taskポリシーを追加
use App\Task;
use App\Policies\TaskPolicy;

// Conditionポリシーを追加
use App\Condition;
use App\Policies\ConditionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Task::class => TaskPolicy::class,
        Condition::class => ConditionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // アクセストークンの発行、アクセストークンの失効、クライアントとパーソナルアクセストークンの管理のルートを登録
        Passport::routes();


    }
}
