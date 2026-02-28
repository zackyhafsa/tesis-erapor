<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table>
    <tr>
        <th colspan="{{ count($aspects) + 5 }}" style="font-size: 16px; font-weight: bold; text-align: center;">REKAPITULASI PENILAIAN {{ strtoupper($jenis_penilaian) }}</th>
    </tr>
    <tr>
        <th colspan="{{ count($aspects) + 5 }}" style="font-size: 14px; font-weight: bold; text-align: center;">{{ strtoupper($sekolah->nama_sekolah ?? 'NAMA SEKOLAH') }}</th>
    </tr>
    <tr>
        <th colspan="{{ count($aspects) + 5 }}" style="text-align: center;">Mata Pelajaran: {{ $mapel->nama_mapel ?? '-' }} | KKTP: {{ $kktp ?? '-' }} | Kelas/Fase: {{ $sekolah->kelas ?? '-' }} / {{ $sekolah->fase ?? '-' }}</th>
    </tr>
    <tr></tr> </table>

<h4>1. Rekap Penilaian Skor (Skala 1 - 4)</h4>
<table border="1">
    <thead>
        <tr>
            <th rowspan="2" style="background-color: #d1d5db; text-align: center;">No</th>
            <th rowspan="2" style="background-color: #d1d5db; text-align: center;">Nama Siswa</th>
            <th colspan="{{ count($aspects) > 0 ? count($aspects) : 1 }}" style="background-color: #d1d5db; text-align: center;">Rata-rata Skor Aspek</th>
            <th rowspan="2" style="background-color: #d1d5db; text-align: center;">Total Skor</th>
            <th rowspan="2" style="background-color: #d1d5db; text-align: center;">Nilai Akhir</th>
            <th rowspan="2" style="background-color: #d1d5db; text-align: center;">Keputusan</th>
        </tr>
        <tr>
            @foreach($aspects as $aspect) <th style="background-color: #d1d5db; text-align: center;">{{ $aspect->nama_aspek }}</th> @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($rekapData as $index => $data)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td><b>{{ $data['student']->nama }}</b></td>
            @foreach($aspects as $aspect) <td style="text-align: center;">{{ $data['aspectScores'][$aspect->id]['skor'] }}</td> @endforeach
            <td style="text-align: center;"><b>{{ $data['totalSkor'] }}</b></td>
            <td style="text-align: center; color: blue;"><b>{{ $data['nilaiAkhir'] }}</b></td>
            <td style="text-align: center; font-weight: bold; color: {{ $data['keputusan'] == 'Pengayaan' ? 'green' : 'red' }}">{{ $data['keputusan'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table><tr></tr><tr></tr></table>

<h4>2. Rekap Penilaian Puluhan (Skala 100)</h4>
<table border="1">
    <thead>
        <tr>
            <th rowspan="2" style="background-color: #d1d5db; text-align: center;">No</th>
            <th rowspan="2" style="background-color: #d1d5db; text-align: center;">Nama Siswa</th>
            <th colspan="{{ count($aspects) > 0 ? count($aspects) : 1 }}" style="background-color: #d1d5db; text-align: center;">Nilai Aspek (Skala 100)</th>
            <th rowspan="2" style="background-color: #d1d5db; text-align: center;">Total Skor</th>
            <th rowspan="2" style="background-color: #d1d5db; text-align: center;">Nilai Akhir</th>
            <th rowspan="2" style="background-color: #d1d5db; text-align: center;">Keputusan</th>
        </tr>
        <tr>
            @foreach($aspects as $aspect) <th style="background-color: #d1d5db; text-align: center;">{{ $aspect->nama_aspek }}</th> @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($rekapData as $index => $data)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td><b>{{ $data['student']->nama }}</b></td>
            @foreach($aspects as $aspect) <td style="text-align: center;">{{ $data['aspectScores'][$aspect->id]['puluhan'] }}</td> @endforeach
            <td style="text-align: center;"><b>{{ $data['totalSkor'] }}</b></td>
            <td style="text-align: center; color: blue;"><b>{{ $data['nilaiAkhir'] }}</b></td>
            <td style="text-align: center; font-weight: bold; color: {{ $data['keputusan'] == 'Pengayaan' ? 'green' : 'red' }}">{{ $data['keputusan'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>