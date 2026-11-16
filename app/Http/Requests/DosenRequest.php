<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $dosen = $this->route('dosen');

        return [
            'nidn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('dosen', 'nidn')->ignore($dosen?->id ?? $dosen),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'prodi' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'nohp' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.unique' => 'NIDN sudah terdaftar.',
            'nidn.max' => 'NIDN maksimal 20 karakter.',
            'nama.required' => 'Nama dosen wajib diisi.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'prodi.required' => 'Program studi wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 100 karakter.',
            'nohp.max' => 'Nomor HP maksimal 20 karakter.',
        ];
    }
}