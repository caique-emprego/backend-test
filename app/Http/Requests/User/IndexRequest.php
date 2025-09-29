<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            /**
             * Rota 5: Validar tipo e tamanho máximo dos campos, evitar ataques de buffer overflow
             * o campo status pode ser 'ACTIVE', 'INACTIVE' OU 'BLOCK'. validar com in:
             */

            'name'   => 'sometimes',
            'email'  => 'sometimes',
            'status' => 'sometimes',
        ];
    }
}
