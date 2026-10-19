@extends('layouts.app')

@section('title', 'Detail Mata Kuliah')

@section('content')
<h3 class="mb-3">Detail Mata Kuliah</h3>
<dl class="row">
    <dt class="col-sm-3">Kode</dt><dd class="col-sm-9">{{ $mataKuliah->kode }}</dd>
    <dt class="col-sm-3">Nama Mata Kuliah</dt><dd class="col-sm-9">{{ $mataKuliah->nama }}</dd>
    <dt class="col-sm-3">SKS</dt><dd class="col-sm-9">{{ $mataKuliah->sks }}</dd>
    <dt class="col-sm-3">Dosen Pengampu</dt><dd class="col-sm-9">{{ $mataKuliah->dosen?->nama ?? 'Belum ditentukan' }}</dd>
</dl>
<a href="{{ route('mata-kuliah.edit', $mataKuliah) }}" class="btn btn-warning">Edit</a>
<a href="{{ route('mata-kuliah.index') }}" class="btn btn-outline-secondary">Kembali</a>
@endsection
