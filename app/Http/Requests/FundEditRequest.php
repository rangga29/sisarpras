<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FundEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('funds', 'name')->ignore($this->fund)],
            'slug' => ['required', Rule::unique('funds', 'slug')->ignore($this->fund)],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Sumber Dana Harus Diisi',
            'name.unique' => 'Nama Sumber Dana Sudah Digunakan',
            'slug.required' => 'Slug Sumber Dana Harus Diisi',
            'slug.unique' => 'Slug Sumber Dana Sudah Digunakan'
        ];
    }
}