<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NonConsCategoryCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_code' => ['required', Rule::unique('non_cons_categories', 'category_code')],
            'category_name' => ['required', Rule::unique('non_cons_categories', 'category_name')],
            'category_slug' => ['required', Rule::unique('non_cons_categories', 'category_slug')]
        ];
    }

    public function messages()
    {
        return [
            'category_code.required' => 'Kode Kategori Harus Diisi',
            'category_code.unique' => 'Kode Kategori Sudah Digunakan',
            'category_name.required' => 'Nama Kategori Harus Diisi',
            'category_name.unique' => 'Nama Kategori Sudah Digunakan',
            'category_slug.required' => 'Slug Kategori Harus Diisi',
            'category_slug.unique' => 'Slug Kategori Sudah Digunakan'
        ];
    }
}