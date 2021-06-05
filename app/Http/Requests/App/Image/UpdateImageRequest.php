<?php

namespace App\Http\Requests\App\Image;

use App\Http\Requests\App\AppFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateImageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'image' => [
                'required',
                'mimes:jpg,jpeg,png,jpeg 2000,bmp',
                'file',
                'max:102400',
            ],
        ];
        return AppFormRequest::rules($rules);
    }

    public function messages()
    {
        $messages = [
            'image.required' => 'El campo :attribute es obligatorio.',
            'image.mimes' =>'El campo :attribute debe ser un archivo de tipo: :values.',
            'image.max' => 'El campo :attribute no puede ser mayor que :maxKB.',
        ];
        return AppFormRequest::messages($messages);
    }

    public function attributes()
    {
        $attributes = [
            'image' => 'imagen'
        ];
        return AppFormRequest::attributes($attributes);
    }
}
