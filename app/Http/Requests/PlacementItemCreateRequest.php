<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlacementItemCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'non_cons_item_id' => ['required'],
            'room_id' => ['required'],
            'unit_id' => ['nullable'],
            'placement_code' => ['nullable'],
            'con_placement_id' => ['nullable'],
            'con_return_id' => ['nullable'],
            'placement_date' => ['required'],
            'return_date' => ['nullable'],
            'description' => ['nullable']
        ];
    }

    public function messages()
    {
        return [
            'non_cons_item_id.required' => 'Nama Barang Harus Diisi',
            'room_id.unique' => 'Nama Ruangan Harus Diisi',
            'placement_date.required' => 'Tanggal Penempatan Harus Diisi',
        ];
    }
}