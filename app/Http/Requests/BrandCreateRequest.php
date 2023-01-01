<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('brands', 'name')],
            'slug' => ['required', Rule::unique('brands', 'slug')],
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