@extends('layouts.app')

@section('title', 'Tambah Pengawas')

@section('content')
<h3 class="mb-3">Tambah Pengawas</h3>
<form action="{{ route('pengawas.store') }}" method="POST">
    @include('pengawas._form')
    <button class="btn btn-primary" type="submit">Simpan</button>
    <a href="{{ route('pengawas.index') }}" class="btn btn-outline-secondary">Batal</a>
</form>
@endsection
