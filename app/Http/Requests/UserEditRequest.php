<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => ['required', 'min:8', 'confirmed'],
            'name' => ['required'],
            'unit_id' => ['required'],
            'role' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Password Harus Diisi',
            'password.min' => 'Password Minimal 8 Karakter',
            'password.confirmed' => 'Password dan Konfirmasi Password Tidak Sama',
            'name' => 'Nama User Harus Diisi'
        ];
    }
}