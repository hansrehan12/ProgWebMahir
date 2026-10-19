@extends('layouts.app')

@section('title', 'Detail Pengawas')

@section('content')
<h3 class="mb-3">Detail Pengawas</h3>
<dl class="row">
    <dt class="col-sm-3">Nama</dt><dd class="col-sm-9">{{ $pengawas->nama }}</dd>
    <dt class="col-sm-3">Alamat</dt><dd class="col-sm-9">{{ $pengawas->alamat }}</dd>
    <dt class="col-sm-3">Nomor Telepon</dt><dd class="col-sm-9">{{ $pengawas->notelp }}</dd>
    <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $pengawas->email }}</dd>
</dl>
<a href="{{ route('pengawas.edit', $pengawas) }}" class="btn btn-warning">Edit</a>
<a href="{{ route('pengawas.index') }}" class="btn btn-outline-secondary">Kembali</a>
@endsection
