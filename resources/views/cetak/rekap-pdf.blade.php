<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Nilai PDF</title>
    <style>
        @page {
            margin: 30px;
        }

        footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            font-size: 9px;
            font-style: italic;
        }

        .page-number:after {
            content: counter(page);
        }

        .total-pages:after {
            content: counter(pages);
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .kop {
            width: 100%;
            border-bottom: 3px solid black;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .kop td,
        footer table td {
            border: none !important;
        }

        .logo-img {
            max-width: 75px;
            height: auto;
        }

        .kop h2,
        .kop h3,
        .kop p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background-color: #e5e7eb;
        }

        .text-left {
            text-align: left;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-green {
            color: #16a34a;
        }

        .text-red {
            color: #dc2626;
        }

        .text-yellow {
            color: #d97706;
        }

        .text-blue {
            color: #2563eb;
        }

        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
            text-align: left;
            background-color: #d1d5db;
            padding: 5px;
            border: 1px solid #000;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <footer>
        <table style="width: 100%; border-top: 1px solid black; padding-top: 5px;">
            <tr>
                <td style="text-align: left; width: 60%;">
                    {{ $sekolah->nama_sekolah ?? '-' }} | Semester {{ $sekolah->semester ?? '-' }} | TA.
                    {{ $sekolah->tahun_pelajaran ?? '-' }}
                </td>
                <td style="text-align: right; width: 40%;">
                    <span class="page-number"></span>
                </td>
            </tr>
        </table>
    </footer>

    <table class="kop no-border">
        <tr>
            <td width="15%" class="text-center" style="vertical-align: middle;">
                @if(!empty($sekolah->logo_kiri))
                    <img src="{{ storage_path('app/public/' . $sekolah->logo_kiri) }}" class="logo-img" alt="Logo Kiri">
                @endif
            </td>

            <td width="70%" class="text-center" style="vertical-align: middle;">
                <h2>REKAPITULASI PENILAIAN {{ strtoupper($jenis_penilaian) }}</h2>
                <h4>{{ strtoupper($sekolah->nama_sekolah ?? 'NAMA SEKOLAH BELUM DIISI') }}</h4>
                <p>NPSN: {{ $sekolah->npsn ?? '-' }} | NSS: {{ $sekolah->nss ?? '-' }}</p>
            </td>

            <td width="15%" class="text-center" style="vertical-align: middle;">
                @if(!empty($sekolah->logo_kanan))
                    <img src="{{ storage_path('app/public/' . $sekolah->logo_kanan) }}" class="logo-img" alt="Logo Kanan">
                @endif
            </td>
        </tr>
    </table>

    <div class="section-title">1. Rekap Penilaian Skor (Skala 1 - 4)</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2" width="3%">No</th>
                <th rowspan="2" width="20%">Nama Siswa</th>
                <th colspan="{{ count($aspects) > 0 ? count($aspects) : 1 }}">Rata-rata Skor Aspek</th>
                <th rowspan="2" width="7%">Total Skor</th>
                <th rowspan="2" width="7%">Nilai Akhir</th>
                <th rowspan="2" width="10%">Keputusan</th>
            </tr>
            <tr>
                @foreach($aspects as $aspect) <th>{{ $aspect->nama_aspek }}</th> @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rekapData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left font-bold">{{ $data['student']->nama }}</td>
                    @foreach($aspects as $aspect) <td>{{ $data['aspectScores'][$aspect->id]['skor'] }}</td> @endforeach
                    <td class="font-bold">{{ $data['totalSkor'] }}</td>
                    <td class="font-bold text-blue">{{ $data['nilaiAkhir'] }}</td>
                    <td class="font-bold {{ $data['keputusan'] == 'Pengayaan' ? 'text-green' : 'text-red' }}">
                        {{ $data['keputusan'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">2. Rekap Penilaian Puluhan (Skala 100)</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2" width="3%">No</th>
                <th rowspan="2" width="20%">Nama Siswa</th>
                <th colspan="{{ count($aspects) > 0 ? count($aspects) : 1 }}">Nilai Aspek (Skala 100)</th>
                <th rowspan="2" width="7%">Total Skor</th>
                <th rowspan="2" width="7%">Nilai Akhir</th>
                <th rowspan="2" width="10%">Keputusan</th>
            </tr>
            <tr>
                @foreach($aspects as $aspect) <th>{{ $aspect->nama_aspek }}</th> @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rekapData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left font-bold">{{ $data['student']->nama }}</td>
                    @foreach($aspects as $aspect) <td>{{ $data['aspectScores'][$aspect->id]['puluhan'] }}</td> @endforeach
                    <td class="font-bold">{{ $data['totalSkor'] }}</td>
                    <td class="font-bold text-blue">{{ $data['nilaiAkhir'] }}</td>
                    <td class="font-bold {{ $data['keputusan'] == 'Pengayaan' ? 'text-green' : 'text-red' }}">
                        {{ $data['keputusan'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">3. Rekap Ketuntasan @if($konsep == 'Tidak Range') (Berdasarkan KKTP) @else (Berdasarkan
    Rentang Predikat) @endif</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Nama Siswa</th>
                <th width="15%">Nilai Akhir (0-100)</th>
                <th width="35%">Keterangan Ketuntasan / Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left font-bold">{{ $data['student']->nama }}</td>
                    <td class="font-bold text-blue">{{ $data['nilaiAkhir'] }}</td>
                    <td
                        class="font-bold 
                                                {{ in_array($data['ketuntasan'], ['Tuntas', 'Sangat Berkembang (SB)', 'Berkembang Sesuai Harapan (BSH)']) ? 'text-green' : (in_array($data['ketuntasan'], ['Mulai Berkembang (MB)']) ? 'text-yellow' : 'text-red') }}">
                        {{ $data['ketuntasan'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>