<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanItemEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'non_cons_item_id' => ['required'],
            'consumer_id' => ['required'],
            'unit_id' => ['nullable'],
            'loan_code' => ['nullable'],
            'con_loan_id' => ['nullable'],
            'con_return_id' => ['nullable'],
            'loan_date' => ['required'],
            'return_date' => ['nullable'],
            'description' => ['nullable']
        ];
    }

    public function messages()
    {
        return [
            'non_cons_item_id.required' => 'Nama Barang Harus Diisi',
            'consumer_id.unique' => 'Nama Peminjam Harus Diisi',
            'loan_date.required' => 'Tanggal Pinjam Harus Diisi',
        ];
    }
}