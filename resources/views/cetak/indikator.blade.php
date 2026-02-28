<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Format Penilaian</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .text-center { text-align: center; }
        h2 { margin-bottom: 20px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 6px; vertical-align: top; }
        th { background-color: #d9d9d9; text-align: center; }
    </style>
</head>
<body>

    <h2 class="text-center">Data Format Penilaian (Indikator)</h2>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Jenis Penilaian</th>
                <th width="20%">Aspek Penilaian</th>
                <th width="25%">Indikator Kinerja</th>
                <th width="35%">Deskripsi Kriteria</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $item->aspect->jenis_penilaian ?? '-' }}</td>
                <td>{{ $item->aspect->nama_aspek ?? '-' }}</td>
                <td>{{ $item->nama_indikator ?? '-' }}</td>
                <td>{{ $item->deskripsi_kriteria ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data untuk ditampilkan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>