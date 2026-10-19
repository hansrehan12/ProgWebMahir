@extends('layouts.app')

@section('title', 'Edit Pengawas')

@section('content')
<h3 class="mb-3">Edit Pengawas</h3>
<form action="{{ route('pengawas.update', $pengawas) }}" method="POST">
    @method('PUT')
    @include('pengawas._form')
    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
    <a href="{{ route('pengawas.index') }}" class="btn btn-outline-secondary">Batal</a>
</form>
@endsection
