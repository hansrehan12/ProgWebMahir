<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
</head>
<body>
    <h1>Data Mahasiswa</h1>

    <p>Nama : {{ $nama }}</p>

    @if ($status == 'Aktif')
        <p>Status : Mahasiswa Aktif</p>
    @elseif($status == 'Cuti')
        <p>Status : Mahasiswa Cuti</p>
    @else
        <p>Status : Tidak Diketahui</p>
    @endif

    <p>Jenis Kelamin : {{ $jenis_kl }}</p>

    
</body>
</html>