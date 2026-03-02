<x-filament-panels::page>

    <form wire:submit.prevent="submit" class="mb-6">
        {{ $this->form }}
    </form>

    @if($stats)
        <div class="space-y-6">

            {{-- KEKUATAN & KELEMAHAN KELAS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-filament::section>
                    <x-slot name="heading"><span class="text-green-600 font-bold">👍 Kekuatan Kelas (3 Indikator Terkuat)</span></x-slot>
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="bg-green-50 dark:bg-green-900/30">
                            <tr>
                                <th class="border p-2">Indikator Kinerja</th>
                                <th class="border p-2 text-center w-24">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($terkuat as $kuat)
                                <tr>
                                    <td class="border p-2">{{ $kuat['nama'] }}</td>
                                    <td class="border p-2 text-center font-bold text-green-600">{{ $kuat['rata_rata'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="border p-2 text-center">Belum ada data nilai.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-filament::section>

                <x-filament::section>
                    <x-slot name="heading"><span class="text-red-600 font-bold">🎯 Fokus Perbaikan (3 Indikator Terlemah)</span></x-slot>
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="bg-red-50 dark:bg-red-900/30">
                            <tr>
                                <th class="border p-2">Indikator Kinerja</th>
                                <th class="border p-2 text-center w-24">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($terlemah as $lemah)
                                <tr>
                                    <td class="border p-2">{{ $lemah['nama'] }}</td>
                                    <td class="border p-2 text-center font-bold text-red-600">{{ $lemah['rata_rata'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="border p-2 text-center">Belum ada data nilai.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-filament::section>
            </div>

            {{-- ASPEK KOMPETENSI KELAS --}}
            <x-filament::section>
                <x-slot name="heading">📐 Aspek Kompetensi Kelas</x-slot>
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-indigo-50 dark:bg-indigo-900/30">
                        <tr>
                            <th class="border p-2 w-12 text-center">No</th>
                            <th class="border p-2">Aspek Penilaian</th>
                            <th class="border p-2 text-center w-32">Rata-rata (Skala 4)</th>
                            <th class="border p-2 text-center w-32">Rata-rata (Skala 100)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aspekKompetensi as $idx => $aspek)
                            <tr>
                                <td class="border p-2 text-center">{{ $idx + 1 }}</td>
                                <td class="border p-2 font-medium">{{ $aspek['nama_aspek'] }}</td>
                                <td class="border p-2 text-center font-bold text-indigo-600">{{ $aspek['rata_rata'] }}</td>
                                <td class="border p-2 text-center font-bold">{{ $aspek['rata_rata_100'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="border p-2 text-center">Belum ada data aspek.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </x-filament::section>

            {{-- DAFTAR PENGAYAAN & REMEDIAL --}}
            <x-filament::section>
                <x-slot name="heading">📝 Daftar Pengayaan & Remedial Peserta Didik (KKTP: {{ $kktp }})</x-slot>
                <div class="overflow-y-auto max-h-96">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="bg-gray-100 dark:bg-gray-800 sticky top-0">
                            <tr>
                                <th class="border p-2 w-12 text-center">No</th>
                                <th class="border p-2">NIPD</th>
                                <th class="border p-2">Nama Peserta Didik</th>
                                <th class="border p-2 text-center w-28">Nilai Akhir</th>
                                <th class="border p-2 text-center">Predikat</th>
                                <th class="border p-2 text-center w-28">Keputusan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengayaanRemedial as $idx => $siswa)
                                <tr>
                                    <td class="border p-2 text-center">{{ $idx + 1 }}</td>
                                    <td class="border p-2">{{ $siswa['nipd'] ?? '-' }}</td>
                                    <td class="border p-2 font-medium">{{ $siswa['nama'] }}</td>
                                    <td class="border p-2 text-center font-bold">{{ $siswa['nilai_akhir'] }}</td>
                                    <td class="border p-2 text-center text-xs">{{ $siswa['predikat'] }}</td>
                                    <td class="border p-2 text-center">
                                        @if($siswa['keputusan'] === 'Pengayaan')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                ✅ Pengayaan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                🔁 Remedial
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="border p-2 text-center">Belum ada data siswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pengayaanRemedial->count() > 0)
                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 text-center">
                            <span class="font-bold text-green-700 dark:text-green-300 text-lg">{{ $pengayaanRemedial->where('keputusan', 'Pengayaan')->count() }}</span>
                            <p class="text-green-600 dark:text-green-400">Siswa Pengayaan</p>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-3 text-center">
                            <span class="font-bold text-red-700 dark:text-red-300 text-lg">{{ $pengayaanRemedial->where('keputusan', 'Remedial')->count() }}</span>
                            <p class="text-red-600 dark:text-red-400">Siswa Remedial</p>
                        </div>
                    </div>
                @endif
            </x-filament::section>

        </div>
    @else
        <div class="text-center p-8 bg-white shadow rounded-lg dark:bg-gray-800">
            <p class="text-gray-500">Silakan pilih Mata Pelajaran terlebih dahulu.</p>
        </div>
    @endif

</x-filament-panels::page>

@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets