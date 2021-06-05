<?php

namespace App\Http\Requests\App\Image;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\App\AppFormRequest;

class DownloadImageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'full_path' => [
                'required',
            ],
        ];
        return AppFormRequest::rules($rules);
    }

    public function messages()
    {
        $messages = [
            'full_path.required' => 'El campo :attribute es obligatorio',
        ];
        return AppFormRequest::messages($messages);
    }

    public function attributes()
    {
        $attributes = [
            'full_path' => 'ruta completa',
        ];
        return AppFormRequest::attributes($attributes);
    }
}
