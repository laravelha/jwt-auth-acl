<?php

namespace Laravelha\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
{
    public function authorize()
    {
        $ability = $this->method() . '|' . $this->route()->uri;

        return $this->user()->can($ability);
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'permissions' => 'sometimes|required',
        ];
    }
}
