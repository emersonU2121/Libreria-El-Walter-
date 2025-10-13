<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $this->merge(['nombre' => trim((string) $this->nombre)]);
    }

    public function rules(): array
    {
        $id = $this->route('categoria')?->idcategoria; // null en store, valor en update
        return [
            'nombre' => ['required','string','max:255',"unique:categoria,nombre,{$id},idcategoria"],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique'   => 'Ya existe una categoría con ese nombre.',
        ];
    }
}
