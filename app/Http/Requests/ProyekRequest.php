<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProyekRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'proyek_id' => 'string',
            'nama_proyek' => 'required|string',
            'barang_id' => 'required|numeric',
            'jumlah' => 'required|numeric|gt:0'
        ];
    }
}
