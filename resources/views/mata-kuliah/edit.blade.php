@extends('layouts.app')

@section('title', 'Edit Mata Kuliah')

@section('content')
<h3 class="mb-3">Edit Mata Kuliah</h3>
<form action="{{ route('mata-kuliah.update', $mataKuliah) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="kode" class="form-label">Kode</label>
        <input id="kode" name="kode" value="{{ old('kode', $mataKuliah->kode) }}" class="form-control @error('kode') is-invalid @enderror">
        @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Mata Kuliah</label>
        <input id="nama" name="nama" value="{{ old('nama', $mataKuliah->nama) }}" class="form-control @error('nama') is-invalid @enderror">
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label for="sks" class="form-label">SKS</label>
        <input id="sks" name="sks" type="number" min="1" max="6" value="{{ old('sks', $mataKuliah->sks) }}" class="form-control @error('sks') is-invalid @enderror">
        @error('sks') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label for="dosen_id" class="form-label">Dosen Pengampu</label>
        <select id="dosen_id" name="dosen_id" class="form-select @error('dosen_id') is-invalid @enderror">
            <option value="">Belum ditentukan</option>
            @foreach ($dosens as $dosen)
                <option value="{{ $dosen->id }}" @selected((string) old('dosen_id', $mataKuliah->dosen_id) === (string) $dosen->id)>{{ $dosen->nama }}</option>
            @endforeach
        </select>
        @error('dosen_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
    <a href="{{ route('mata-kuliah.index') }}" class="btn btn-outline-secondary">Batal</a>
</form>
@endsection
