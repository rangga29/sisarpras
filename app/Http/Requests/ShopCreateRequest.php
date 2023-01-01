<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShopCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('shops', 'name')],
            'slug' => ['required', Rule::unique('shops', 'slug')],
            'image' => ['image', 'max:3072'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Vendor Harus Diisi',
            'name.unique' => 'Nama Vendor Sudah Digunakan',
            'slug.required' => 'Slug Vendor Harus Diisi',
            'slug.unique' => 'Slug Vendor Sudah Digunakan',
            'image.image' => 'Logo Harus Berupa Gambar',
            'image.max' => 'Logo Harus Lebih Kecil dari 3 MB'
        ];
    }
}