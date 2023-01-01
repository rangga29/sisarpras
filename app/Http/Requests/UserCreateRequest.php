<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => ['required', 'min:5', Rule::unique('users', 'username')],
            'password' => ['required', 'min:8', 'confirmed'],
            'name' => ['required'],
            'unit_id' => ['required'],
            'role' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Username Harus Diisi',
            'username.min' => 'Username Minimal 5 Karakter',
            'username.unique' => 'Username Sudah Digunakan',
            'password.required' => 'Password Harus Diisi',
            'password.min' => 'Password Minimal 8 Karakter',
            'password.confirmed' => 'Password dan Konfirmasi Password Tidak Sama',
            'name' => 'Nama User Harus Diisi'
        ];
    }
}