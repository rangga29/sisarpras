<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('rooms', 'name')],
            'slug' => ['required', Rule::unique('rooms', 'slug')],
            'unit_id' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Ruangan Harus Diisi',
            'name.unique' => 'Nama Ruangan Sudah Digunakan',
            'slug.required' => 'Slug Ruangan Harus Diisi',
            'slug.unique' => 'Slug Ruangan Sudah Digunakan',
        ];
    }
}