<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\Team;
use App\Policies\TaskPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Team::class => TeamPolicy::class,
        Task::class => TaskPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
