@extends('layouts.app')

@section('title', 'Detail Dosen')

@section('content')
<h3 class="mb-3">Detail Dosen</h3>
<dl class="row">
    <dt class="col-sm-3">NIDN</dt><dd class="col-sm-9">{{ $dosen->nidn }}</dd>
    <dt class="col-sm-3">Nama Dosen</dt><dd class="col-sm-9">{{ $dosen->nama }}</dd>
    <dt class="col-sm-3">Program Studi</dt><dd class="col-sm-9">{{ $dosen->prodi }}</dd>
    <dt class="col-sm-3">Mata Kuliah</dt>
    <dd class="col-sm-9">
        @forelse ($dosen->mataKuliah as $mataKuliah)
            {{ $mataKuliah->kode }} - {{ $mataKuliah->nama }}<br>
        @empty
            <span class="text-muted">Belum ada mata kuliah</span>
        @endforelse
    </dd>
</dl>
<a href="{{ route('dosen.edit', $dosen) }}" class="btn btn-warning">Edit</a>
<a href="{{ route('dosen.index') }}" class="btn btn-outline-secondary">Kembali</a>
@endsection
