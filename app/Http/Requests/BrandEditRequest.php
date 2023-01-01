<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('brands', 'name')->ignore($this->brand)],
            'slug' => ['required', Rule::unique('brands', 'slug')->ignore($this->brand)],
            'image' => ['image', 'max:3072'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Merk Harus Diisi',
            'name.unique' => 'Nama Merk Sudah Digunakan',
            'slug.required' => 'Slug Merk Harus Diisi',
            'slug.unique' => 'Slug Merk Sudah Digunakan',
            'image.image' => 'Logo Harus Berupa Gambar',
            'image.max' => 'Logo Harus Lebih Kecil dari 3 MB'
        ];
    }
}