<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table>
    <tr>
        <th colspan="6" style="font-size: 16px; font-weight: bold; text-align: center;">DATA INDIKATOR PENILAIAN</th>
    </tr>
    <tr>
        <th colspan="6" style="font-size: 14px; font-weight: bold; text-align: center;">{{ strtoupper($sekolah->nama_sekolah ?? 'NAMA SEKOLAH') }}</th>
    </tr>
    <tr>
        <th colspan="6" style="text-align: center;">Semester: {{ $sekolah->semester ?? '-' }} | Tahun Pelajaran: {{ $sekolah->tahun_pelajaran ?? '-' }}</th>
    </tr>
    <tr></tr>
</table>

<table border="1">
    <thead>
        <tr>
            <th style="background-color: #d1d5db; text-align: center;">No</th>
            <th style="background-color: #d1d5db; text-align: center;">Kelas</th>
            <th style="background-color: #d1d5db; text-align: center;">Jenis Penilaian</th>
            <th style="background-color: #d1d5db; text-align: center;">Aspek Penilaian</th>
            <th style="background-color: #d1d5db; text-align: center;">Indikator</th>
            <th style="background-color: #d1d5db; text-align: center;">Deskripsi Kriteria</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $item)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td style="text-align: center;">{{ $item->kelas ?? '-' }}</td>
            <td style="text-align: center;">{{ $item->aspect->jenis_penilaian ?? '-' }}</td>
            <td>{{ $item->aspect->nama_aspek ?? '-' }}</td>
            <td>{{ $item->nama_indikator ?? '-' }}</td>
            <td>{{ $item->deskripsi_kriteria ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center;">Tidak ada data untuk ditampilkan.</td>
        </tr>
        @endforelse
    </tbody>
</table>
