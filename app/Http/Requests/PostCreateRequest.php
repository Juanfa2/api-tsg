<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required',
        ];
    }

    public function messages(): array{
        return [
            'title.required' => 'El titulo es requerido',
            'title.max' => 'El titulo no puede superar los 255 caracteres',
            'body.required' => 'El contenido es requerido',
        ];
    }

}
