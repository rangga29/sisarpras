<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PositionEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('positions', 'name')->ignore($this->position)],
            'slug' => ['required', Rule::unique('positions', 'slug')->ignore($this->position)],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Posisi Pengguna Harus Diisi',
            'name.unique' => 'Nama Posisi Pengguna Sudah Digunakan',
            'slug.required' => 'Slug Posisi Pengguna Harus Diisi',
            'slug.unique' => 'Slug Posisi Pengguna Sudah Digunakan'
        ];
    }
}