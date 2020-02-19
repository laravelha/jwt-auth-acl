<?php

namespace Laravelha\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class PermissionStoreRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('api.auth.permissions.store');
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'action' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}
