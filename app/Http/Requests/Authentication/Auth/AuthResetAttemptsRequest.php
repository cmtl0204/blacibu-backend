<?php

namespace App\Http\Requests\Authentication\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Authentication\AuthenticationFormRequest;

class AuthResetAttemptsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'token' => [
                'string',
            ],
        ];
    }

    public function attributes()
    {
        $attributes = [
            'token' => 'token',
        ];
        return AuthenticationFormRequest::attributes($attributes);
    }
}
