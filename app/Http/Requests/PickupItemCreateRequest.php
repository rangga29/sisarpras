<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PickupItemCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cons_item_id' => ['required'],
            'consumer_id' => ['required'],
            'unit_id' => ['nullable'],
            'pickup_code' => ['nullable'],
            'pickup_date' => ['required'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable']
        ];
    }

    public function messages()
    {
        return [
            'cons_item_id.required' => 'Nama Barang Harus Diisi',
            'consumer_id.unique' => 'Nama Pengguna Harus Diisi',
            'pickup_date.required' => 'Tanggal Ambil Harus Diisi',
            'amount.required' => 'Jumlah Ambil Harus Diisi',
            'amount.numeric' => 'Jumlah Ambil Harus Berupa Angka',
            'amount.gt' => 'Jumlah Ambil Harus Lebih Besar Dari 0',
        ];
    }
}