<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MataKuliahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $kodeRule = Rule::unique('mata_kuliah', 'kode');

        if ($this->route('mata_kuliah')) {
            $kodeRule->ignore($this->route('mata_kuliah'));
        }

        return [
            'kode' => ['required', 'string', 'max:10', $kodeRule],
            'nama' => ['required', 'string', 'max:100'],
            'sks' => ['required', 'integer', 'min:1', 'max:6'],
            'dosen_id' => ['nullable', 'exists:dosen,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode mata kuliah wajib diisi.',
            'kode.unique' => 'Kode mata kuliah sudah terdaftar.',
            'kode.max' => 'Kode mata kuliah maksimal 10 karakter.',
            'nama.required' => 'Nama mata kuliah wajib diisi.',
            'nama.max' => 'Nama mata kuliah maksimal 100 karakter.',
            'sks.required' => 'Jumlah SKS wajib diisi.',
            'sks.integer' => 'Jumlah SKS harus berupa angka.',
            'sks.min' => 'Jumlah SKS minimal 1.',
            'sks.max' => 'Jumlah SKS maksimal 6.',
            'dosen_id.exists' => 'Dosen pengampu tidak valid.',
        ];
    }
}
