<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => ['required', 'min:8', 'confirmed']
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Password Harus Diisi',
            'password.min' => 'Password Minimal 8 Karakter',
            'password.confirmed' => 'Password dan Konfirmasi Password Tidak Sama'
        ];
    }
}