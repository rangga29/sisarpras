<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnPlacementItemEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'non_cons_item_id' => ['nullable'],
            'room_id' => ['nullable'],
            'unit_id' => ['nullable'],
            'placement_code' => ['nullable'],
            'con_placement_id' => ['nullable'],
            'con_return_id' => ['nullable'],
            'placement_date' => ['nullable'],
            'return_date' => ['required'],
            'description' => ['nullable']
        ];
    }

    public function messages()
    {
        return [
            'return_date.required' => 'Tanggal Pengembalian Harus Diisi',
        ];
    }
}