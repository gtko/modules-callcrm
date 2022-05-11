<?php

namespace Modules\CallCRM\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\BaseCore\Contracts\Entities\UserEntity;
use Modules\BaseCore\Models\Personne;
use Modules\BaseCore\Policies\PersonnePolicy;
use Modules\BaseCore\Policies\UserPolicy;
use Modules\CallCRM\Models\Appel;
use Modules\CallCRM\Policies\CallPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Appel::class => CallPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {



        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permission checks using can()
        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
    }
}
