<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rapor - {{ $siswa->nama }}</title>
    <style>
        /* PENGATURAN KERTAS & FOOTER */
        @page {
            margin: 40px 40px 60px 40px;
        }

        /* Jarak margin dibalikan ke normal */
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }

        /* FOOTER UNTUK NOMOR HALAMAN */
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

        /* STYLE ASLI AKANG (DIPERTAHANKAN) */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .kop-surat {
            width: 100%;
            border-bottom: 3px solid black;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .kop-surat h2,
        .kop-surat h4,
        .kop-surat p {
            margin: 0;
            padding: 0;
        }

        .logo-img {
            max-width: 75px;
            height: auto;
        }

        /* Ukuran Logo */

        .table-biodata {
            width: 100%;
            margin-bottom: 15px;
            font-size: 11px;
            table-layout: fixed;
            /* KUNCI LEBAR TABEL */
        }

        .table-biodata td {
            vertical-align: top;
            padding: 2px 0;
            word-wrap: break-word;
            /* PAKSA TEKS MELIPAT KE BAWAH */
        }

        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .table-nilai th,
        .table-nilai td {
            border: 1px solid black;
            padding: 5px;
            vertical-align: top;
        }

        .table-nilai th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-top: 15px;
            margin-bottom: 5px;
            background-color: #d9d9d9;
            padding: 4px;
            border: 1px solid black;
        }

        .table-analisis {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-analisis td {
            border: 1px solid black;
            padding: 5px;
            vertical-align: top;
            width: 50%;
        }

        .tanda-tangan {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .tanda-tangan td {
            width: 50%;
            text-align: center;
        }

        .nama-ttd {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
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

    <table class="kop-surat">
        <tr>
            <td width="15%" class="text-center" style="vertical-align: middle;">
                @if(!empty($sekolah->logo_kiri))
                    <img src="{{ storage_path('app/public/' . $sekolah->logo_kiri) }}" class="logo-img" alt="Logo Kiri">
                @endif
            </td>

            <td width="70%" class="text-center" style="vertical-align: middle;">
                <h2>LAPORAN HASIL BELAJAR ({{ strtoupper($jenisPenilaian) }})</h2>
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

    <table class="table-biodata">
        <tr>
            <td width="18%">Nama Peserta Didik</td>
            <td width="2%">:</td>
            <td width="40%" class="text-bold">{{ $siswa->nama }}</td>
            <td width="15%">Kelas / Fase</td>
            <td width="2%">:</td>
            <td width="23%">{{ $sekolah->kelas ?? '-' }} / {{ $sekolah->fase ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIPD / NISN</td>
            <td>:</td>
            <td>{{ $siswa->nipd ?? '-' }}</td>
            <td>Semester</td>
            <td>:</td>
            <td>{{ $sekolah->semester ?? '-' }}</td>
        </tr>
        <tr>
            <td>Mata Pelajaran</td>
            <td>:</td>
            <td class="text-bold">{{ $mapel->nama_mapel ?? '-' }}</td>
            <td>Tahun Pelajaran</td>
            <td>:</td>
            <td>{{ $sekolah->tahun_pelajaran ?? '-' }}</td>
        </tr>

        <tr>
            <td style="vertical-align: top;">Capaian Pembelajaran</td>
            <td style="vertical-align: top;">:</td>
            <td colspan="4" style="padding-right: 15px; vertical-align: top;">
                <ul style="margin: 0; padding-left: 15px; width: 50%;">
                    @forelse($cps as $cp)
                        <li style="margin-bottom: 3px;">{{ $cp->deskripsi ?? '-' }}</li>
                    @empty
                        -
                    @endforelse
                </ul>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Tujuan Pembelajaran</td>
            <td style="vertical-align: top;">:</td>
            <td colspan="4" style="padding-right: 15px; vertical-align: top;">
                <ul style="margin: 0; padding-left: 15px; width: 50%;">
                    @forelse($tps as $tp)
                        <li style="margin-bottom: 3px;">{{ $tp->deskripsi ?? '-' }}</li>
                    @empty
                        -
                    @endforelse
                </ul>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;">KKTP</td>
            <td style="vertical-align: top;">:</td>
            <td colspan="4" class="text-bold" style="vertical-align: top;">{{ $kktp ?? '-' }}</td>
        </tr>
    </table>

    <table class="table-nilai">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="16%">Aspek</th>
                <th width="25%">Indikator Kinerja</th>
                <th width="35%">Deskripsi Kriteria</th>
                <th width="5%">Skor</th>
                <th width="15%">Catatan Guru</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nilaiSiswa as $index => $nilai)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $nilai->indicator->aspect->nama_aspek ?? '-' }}</td>
                    <td>{{ $nilai->indicator->nama_indikator ?? '-' }}</td>
                    <td>{{ $nilai->indicator->deskripsi_kriteria ?? '-' }}</td>
                    <td class="text-center text-bold">{{ $nilai->score_value ?? '' }}</td>
                    <td>{{ $nilai->catatan_guru ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data indikator dari sistem.</td>
                </tr>
            @endforelse

            <tr>
                <td colspan="4" class="text-right text-bold">Jumlah Skor</td>
                <td class="text-center text-bold">{{ $jumlahSkor }}</td>
                <td style="background-color: #f2f2f2;"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right text-bold">Nilai (Skala 100)</td>
                <td class="text-center text-bold">{{ $nilaiSkala100 }}</td>
                <td style="background-color: #f2f2f2;"></td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Analisis, Refleksi, dan Tindak Lanjut</div>
    <table class="table-analisis">
        <tr>
            <td>
                <b>Tiga Indikator Terkuat:</b><br>
                @foreach($terkuat as $idx => $kuat)
                    {{ $kuat->indicator->nama_indikator ?? '-' }} (Skor: {{ $kuat->score_value }})<br>
                @endforeach
                <br>

                <b>Tiga Indikator Terlemah:</b><br>
                @foreach($terlemah as $idx => $lemah)
                    {{ $lemah->indicator->nama_indikator ?? '-' }} (Skor: {{ $lemah->score_value }})<br>
                @endforeach
                <br>

                <b>Profil Aspek Kompetensi (Rata-rata):</b><br>
                <table style="width: 100%; font-size: 10px; margin-top: 5px;">
                    @foreach($rataPerAspek as $aspek => $rata)
                        <tr>
                            <td style="border: none; padding: 1px;">- {{ $aspek }}</td>
                            <td style="border: none; padding: 1px; text-align: right;">{{ $rata }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>

            <td>
                <b>Kategori Predikat:</b> {{ $kategori }}<br>
                <b>Keterangan Ketuntasan:</b> <span class="text-bold">{{ $ketuntasan }}</span><br><br>

                <b>Kelebihan Siswa:</b><br>
                {{ $refleksi->kelebihan_siswa ?? 'Belum ada data refleksi.' }}<br><br>

                <b>Aspek yang Perlu Ditingkatkan:</b><br>
                {{ $refleksi->aspek_ditingkatkan ?? 'Belum ada data refleksi.' }}<br><br>

                <b>Refleksi & Tindak Lanjut:</b><br>
                {{ $refleksi->tindak_lanjut ?? 'Belum ada data refleksi.' }}
            </td>
        </tr>
    </table>

    <table class="tanda-tangan">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala Sekolah
                <div class="nama-ttd">{{ $sekolah->kepala_sekolah ?? '...................................' }}</div>
                NIP. {{ $sekolah->nip_kepsek ?? '-' }}
            </td>
            <td>
                {{ $sekolah->tempat_cetak ?? '.....................' }},
                {{ $sekolah->tanggal_cetak ? \Carbon\Carbon::parse($sekolah->tanggal_cetak)->translatedFormat('d F Y') : '.....................' }}<br>
                Guru Kelas
                <div class="nama-ttd">{{ $sekolah->guru_kelas ?? '...................................' }}</div>
                NIP. {{ $sekolah->nip_guru ?? '-' }}
            </td>
        </tr>
    </table>

</body>

</html>