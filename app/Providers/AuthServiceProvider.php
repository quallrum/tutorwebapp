<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    // protected $policies = [
    //     'App\Model' => 'App\Policies\ModelPolicy',
    //     'App\Models\Journal' => 'App\Policies\JournalPolicy',
    // ];

    protected $policies = [
        'journal'   => 'App\Policies\JournalPolicy',
        'mark'   => 'App\Policies\MarkPolicy',
        'group'     => 'App\Policies\GroupPolicy',
        'roles'     => 'App\Policies\RolesPolicy',
        'subject'   => 'App\Policies\SubjectPolicy',
    ];

    public function registerPolicies(){
        foreach ($this->policies as $model => $policy_class) {
            $methods = get_class_methods($policy_class);
            unset($methods[0]);
            foreach ($methods as $ability) {
                Gate::define($model.'.'.$ability, function($user, ...$args) use ($model, $ability){
                    $policy = new $this->policies[$model];
                    if(method_exists($policy, 'before')) $can = $policy->before($user, ...$args);
                    if(!is_null($can)) return $can;
                    return $policy->$ability($user, ...$args);
                });
            }
        }
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
