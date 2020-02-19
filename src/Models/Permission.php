<?php

namespace Laravelha\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravelha\Support\Traits\RequestQueryBuildable;

class Permission extends Model
{
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
     * @inheritDoc
     */
    public static function searchable(): array
    {
        return [
            'id' => '=',
            'name' => 'like',
            'action' => 'like',
        ];
    }
}
