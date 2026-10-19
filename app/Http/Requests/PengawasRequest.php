<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PengawasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $emailRule = Rule::unique('pengawas', 'email');

        if ($this->route('pengawas')) {
            $emailRule->ignore($this->route('pengawas'));
        }

        return [
            'nama' => ['required', 'string', 'max:100'],
            'alamat' => ['required', 'string'],
            'notelp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:100', $emailRule],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama pengawas wajib diisi.',
            'nama.string' => 'Nama pengawas harus berupa teks.',
            'nama.max' => 'Nama pengawas maksimal 100 karakter.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'notelp.required' => 'Nomor telepon wajib diisi.',
            'notelp.string' => 'Nomor telepon harus berupa teks.',
            'notelp.max' => 'Nomor telepon maksimal 20 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 100 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
        ];
    }
}
