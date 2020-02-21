<?php

namespace Laravelha\Auth\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Laravelha\Auth\Models\User;

class AbilitiesService
{
    /**
     * @var Collection
     */
    private $permissions;

    /**
     * AbilitiesService constructor.
     *
     * @param  Collection  $permissions
     */
    public function __construct(Collection $permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * Define Gate Abilities
     */
    public function defineAbilities()
    {
        foreach ($this->permissions as $permission) {
            Gate::define($permission->verb . '|' . $permission->uri, function (User $user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }
    }
}
