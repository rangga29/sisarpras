<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConsumerCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('consumers', 'name')],
            'slug' => ['required', Rule::unique('consumers', 'slug')],
            'position_id' => ['required'],
            'unit_id' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Pengguna Harus Diisi',
            'name.unique' => 'Nama Pengguna Sudah Digunakan',
            'slug.required' => 'Slug Pengguna Harus Diisi',
            'slug.unique' => 'Slug Pengguna Sudah Digunakan',
        ];
    }
}