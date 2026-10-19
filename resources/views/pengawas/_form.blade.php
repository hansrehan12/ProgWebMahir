@csrf
<div class="mb-3">
    <label for="nama" class="form-label">Nama</label>
    <input id="nama" name="nama" value="{{ old('nama', $pengawas->nama ?? '') }}" class="form-control @error('nama') is-invalid @enderror">
    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="alamat" class="form-label">Alamat</label>
    <textarea id="alamat" name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $pengawas->alamat ?? '') }}</textarea>
    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="notelp" class="form-label">Nomor Telepon</label>
    <input id="notelp" name="notelp" value="{{ old('notelp', $pengawas->notelp ?? '') }}" class="form-control @error('notelp') is-invalid @enderror">
    @error('notelp') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email', $pengawas->email ?? '') }}" class="form-control @error('email') is-invalid @enderror">
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
