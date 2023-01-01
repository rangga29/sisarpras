<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NonConsSubCategoryEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'non_cons_category_id' => ['required'],
            'sub_category_code' => ['required'],
            'sub_category_name' => ['required', Rule::unique('non_cons_sub_categories', 'sub_category_name')->ignore($this->sub_category)],
            'sub_category_slug' => ['required', Rule::unique('non_cons_sub_categories', 'sub_category_slug')->ignore($this->sub_category)]
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