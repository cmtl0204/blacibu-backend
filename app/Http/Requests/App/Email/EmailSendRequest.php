<?php

namespace App\Http\Requests\App\Email;

use Illuminate\Foundation\Http\FormRequest;

class EmailSendRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'to' => ['required', 'max:50'],
            'cc' => ['max:50'],
            'from' => ['required', 'max:50'],
            'from_name' => ['required', 'max:50'],
            'subject' => ['required', 'max:100'],
            'body' => ['required']
        ];
    }

    public function messages()
    {
        return [
            //            'user.username.required' => 'username es required',
        ];
    }

    public function attributes()
    {
        return [
            //            'user.username' => 'username',
        ];
    }

}
