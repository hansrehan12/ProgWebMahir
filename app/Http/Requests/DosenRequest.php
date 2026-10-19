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
        $nidnRule = Rule::unique('dosen', 'nidn');

        if ($this->route('dosen')) {
            $nidnRule->ignore($this->route('dosen'));
        }

        return [
            'nidn' => ['required', 'string', 'max:20', $nidnRule],
            'nama' => ['required', 'string', 'max:100'],
            'prodi' => ['required', 'string', 'max:100'],
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
        ];
    }
}
