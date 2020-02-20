<?php

namespace Laravelha\Auth\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravelha\Support\Traits\RequestQueryBuildable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use RequestQueryBuildable;

    protected $guarded = ['id'];

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param  Role $role
     * @return bool
     */
    public function hasRole(Role $role): bool
    {
        return $this->roles->contains($role);
    }

    /**
     * @param  Permission $permission
     * @return bool
     */
    public function hasPermission(Permission $permission): bool
    {
        return $this->roles->each(function (Role $role) use ($permission) {
            return $role->hasPermission($permission);
        })->isNotEmpty();
    }

    /**
     * @inheritDoc
     */
    public static function searchable(): array
    {
        return [
            'id' => '=',
            'name' => 'like',
            'email' => 'like',
            'password' => 'like',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * @param array $attributes
     * @return Builder|Model
     */
    public static function create(array $attributes = [])
    {
        $permissions = $attributes['roles'] ?? null;
        if ($permissions) {
            unset($attributes['roles']);
        }

        $model = static::query()->create($attributes);

        if ($permissions) {
            $model->roles()->attach($permissions);
        }

        return $model;
    }

    /**
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        $permissions = $attributes['roles'] ?? null;
        if ($permissions) {
            unset($attributes['roles']);
        }

        $updated = parent::update($attributes, $options);

        if ($updated && $permissions) {
            $this->roles()->sync($permissions);
        }

        return $updated;
    }
}
