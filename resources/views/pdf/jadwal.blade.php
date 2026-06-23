<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jadwal Kuliah</title>
    <style>
        /* CSS untuk layout PDF */
        .header {
            text-align: center;
        }
        .header img {
            height: 100px;
        }
        .footer {
            text-align: right;
            margin-top: 50px;
        }
        .jadwal-table {
            width: 100%;
            border-collapse: collapse;
        }
        .jadwal-table, .jadwal-table th, .jadwal-table td {
            border: 1px solid black;
        }
        .jadwal-table th, .jadwal-table td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $logoPath }}" alt="Logo">
        <h2>Jadwal Kuliah Semester {{ ucfirst($semester) }}</h2>
        <h4>Tahun Ajaran: {{ $tahun_ajaran }}</h4>
    </div>

    <table class="jadwal-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Kuliah</th>
                <th>Dosen Pengajar</th>
                <th>Kelas</th>
                <th>SKS</th>
                <th>Ruangan</th>
                <th>Hari</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jadwal as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->matkul }}</td>
                <td>{{ $item->dosen }}</td>
                <td>{{ $item->kelas }}</td>
                <td>{{ $item->jumlah_sks }}</td>
                <td>{{ \App\Models\Ruang::formatName($item->nama_ruang) }}</td>
                <td>{{ $item->hari }}</td>
                <td>{{ $item->jam_masuk }}</td>
                <td>{{ $item->jam_keluar }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Mengetahui,</p>
        <img src="{{ $ttdPath }}" alt="Tanda Tangan" height="100px">
        <p>(Nama Tanda Tangan)</p>
    </div>
</body>
</html>
