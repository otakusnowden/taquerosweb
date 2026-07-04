<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:120'],
            'restaurante' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'telefono' => ['nullable', 'string', 'max:40'],
            'mensaje' => ['nullable', 'string', 'max:3000'],
            // Honeypot: real users leave this empty; bots tend to fill every field.
            'website' => ['nullable', 'size:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'email' => 'correo',
            'telefono' => 'teléfono',
            'mensaje' => 'mensaje',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'website.size' => 'No fue posible enviar el formulario.',
        ];
    }
}
