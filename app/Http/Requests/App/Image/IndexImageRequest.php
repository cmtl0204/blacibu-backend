<?php

namespace App\Http\Requests\App\Image;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\App\AppFormRequest;

class IndexImageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'id' => [
                'required',
                'integer',
            ],
        ];
        return AppFormRequest::rules($rules);
    }

    public function messages()
    {
        $messages = [
            'id.required' => 'El campo :attribute es obligatorio',
            'id.integer' => 'El campo :attribute debe ser un número',
        ];
        return AppFormRequest::messages($messages);
    }

    public function attributes()
    {
        $attributes = [
            'id' => 'ID',
        ];
        return AppFormRequest::attributes($attributes);
    }
}
