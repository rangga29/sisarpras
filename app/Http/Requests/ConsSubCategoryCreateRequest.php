<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConsSubCategoryCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cons_category_id' => ['required'],
            'sub_category_name' => ['required', Rule::unique('cons_sub_categories', 'sub_category_name')],
            'sub_category_slug' => ['required', Rule::unique('cons_sub_categories', 'sub_category_slug')]
        ];
    }

    public function messages()
    {
        return [
            'sub_category_code.required' => 'Kode Sub Kategori Harus Diisi',
            'sub_category_name.required' => 'Nama Sub Kategori Harus Diisi',
            'sub_category_name.unique' => 'Nama Sub Kategori Sudah Digunakan',
            'sub_category_slug.required' => 'Slug Sub Kategori Harus Diisi',
            'sub_category_slug.unique' => 'Slug Sub Kategori Sudah Digunakan'
        ];
    }
}