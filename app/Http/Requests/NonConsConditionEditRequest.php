<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NonConsConditionEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('non_cons_conditions', 'name')->ignore($this->condition)],
            'slug' => ['required', Rule::unique('non_cons_conditions', 'slug')->ignore($this->condition)],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Kondisi Harus Diisi',
            'name.unique' => 'Nama Kondisi Sudah Digunakan',
            'slug.required' => 'Slug Kondisi Harus Diisi',
            'slug.unique' => 'Slug Kondisi Sudah Digunakan'
        ];
    }
}