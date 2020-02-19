<?php

use Laravelha\Auth\Models\Permission;
use Laravelha\Auth\Models\Role;
use Laravelha\Auth\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = $this->getAdminRole();
        $this->createPermissions($role);

        $user = $this->getUserAdmin();
        $this->addRole($role, $user);
    }

    /**
     * @return Role
     */
    protected function getAdminRole(): Role
    {
        return Role::find(1) ? Role::find(1) : factory(Role::class)->create(['name' => 'Admin', 'description' => 'All privileges']);
    }

    /**
     * @param Role $role
     */
    protected function createPermissions(Role $role): void
    {
        foreach (RouteFacade::getRoutes() as $route) {

            if ($this->shouldIgnore($route)) {
                continue;
            }

            $permission = Permission::create([
                'name' => $route->getName(),
                'action' => ltrim($route->getActionName(), '\\'),
            ]);

            $role->permissions()->attach($permission);
        }
    }

    /**
     * @return User
     */
    protected function getUserAdmin(): User
    {
        return User::find(1) ? User::find(1) : factory(User::class)->create(['name' => 'Admin', 'email' => 'admin@laravelha.com']);
    }

    /**
     * @param Role $role
     * @param User $user
     */
    protected function addRole(Role $role, User $user): void
    {
        if (! $user->roles->contains($role)) {
            $user->roles()->attach($role);
        }
    }

    /**
     * @param Route $route
     * @return bool
     */
    private function shouldIgnore(Route $route): bool
    {
        return (
            ! in_array('auth:api', $route->gatherMiddleware()) ||
            ! $route->getName() ||
            ! Permission::where('name', $route->getName())
        );
    }
}
