<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConsCategoryEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_name' => ['required', Rule::unique('cons_categories', 'category_name')->ignore($this->category)],
            'category_slug' => ['required', Rule::unique('cons_categories', 'category_slug')->ignore($this->category)],
        ];
    }

    public function messages()
    {
        return [
            'category_name.required' => 'Nama Kategori Harus Diisi',
            'category_name.unique' => 'Nama Kategori Sudah Digunakan',
            'category_slug.required' => 'Slug Kategori Harus Diisi',
            'category_slug.unique' => 'Slug Kategori Sudah Digunakan'
        ];
    }
}