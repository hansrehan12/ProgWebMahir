@extends('layouts.app')

@section('title', 'Edit Dosen')

@section('content')
<h3 class="mb-3">Edit Dosen</h3>
<form action="{{ route('dosen.update', $dosen) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="nidn" class="form-label">NIDN</label>
        <input id="nidn" name="nidn" value="{{ old('nidn', $dosen->nidn) }}" class="form-control @error('nidn') is-invalid @enderror">
        @error('nidn') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Dosen</label>
        <input id="nama" name="nama" value="{{ old('nama', $dosen->nama) }}" class="form-control @error('nama') is-invalid @enderror">
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label for="prodi" class="form-label">Program Studi</label>
        <input id="prodi" name="prodi" value="{{ old('prodi', $dosen->prodi) }}" class="form-control @error('prodi') is-invalid @enderror">
        @error('prodi') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
    <a href="{{ route('dosen.index') }}" class="btn btn-outline-secondary">Batal</a>
</form>
@endsection
