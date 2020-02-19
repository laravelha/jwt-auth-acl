<?php

namespace Laravelha\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('api.auth.permissions.update');
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
