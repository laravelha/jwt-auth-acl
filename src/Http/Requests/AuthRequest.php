<?php

namespace Laravelha\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @author Guilherme Nogueira <guilherme@connectblack.com>
 **/
class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:225',
            'password' => 'required|string|min:8',
        ];
    }
}
