<?php

namespace Laravelha\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize()
    {
        $ability = $this->method() . '|' . $this->route()->uri;

        return $this->user()->can($ability);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:225',
            'email' => 'required|string|email|max:225|unique:users',
            'email_verified_at' => 'nullable',
            'password' => 'required|string|min:8',
            'remember_token' => 'nullable|string|max:100',
            'roles' => 'sometimes|required',
        ];
    }
}
