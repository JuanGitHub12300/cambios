<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompra extends FormRequest
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
            'fecha_compra' => 'required|date',
            'total_compra' => 'required|numeric',
            'metodo_pago' => 'required|string|max:30',
            'proveedor_id' => 'required|exists:proveedores,proveedor_id',
            'numero_factura' => 'required|string|max:255', // Añade esta línea si es obligatorio
        ];
    }
}
