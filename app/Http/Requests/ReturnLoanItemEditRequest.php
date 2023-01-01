<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnLoanItemEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'non_cons_item_id' => ['nullable'],
            'consumer_id' => ['nullable'],
            'unit_id' => ['nullable'],
            'loan_code' => ['nullable'],
            'con_loan_id' => ['nullable'],
            'con_return_id' => ['nullable'],
            'loan_date' => ['nullable'],
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