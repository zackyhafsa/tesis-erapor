<x-filament-panels::page>
    
    <form wire:submit.prevent="submit">
        {{ $this->form }}
    </form>

        <div class="mt-6 p-4 bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700">
            <h3 class="font-bold text-lg mb-3">Keterangan Penilaian:</h3>
            
            @if($konsep == 'Range')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mt-3">
                    <div>
                        <span class="inline-block w-20 font-bold text-green-600">91 - 100</span> 
                        : Sangat Berkembang (SB)
                    </div>
                    <div>
                        <span class="inline-block w-20 font-bold text-green-500">76 - 90</span> 
                        : Berkembang Sesuai Harapan (BSH)
                    </div>
                    <div>
                        <span class="inline-block w-20 font-bold text-yellow-600">61 - 75</span> 
                        : Mulai Berkembang (MB)
                    </div>
                    <div>
                        <span class="inline-block w-20 font-bold text-red-600">&le; 60</span> 
                        : Belum Berkembang (BB)
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mt-3">
                    <div>
                        <span class="inline-block w-16 font-bold text-green-600">&ge; {{ $kktp ?? 75 }}</span> 
                        : Tuntas / Pengayaan
                    </div>
                    <div>
                        <span class="inline-block w-16 font-bold text-red-600">&lt; {{ $kktp ?? 75 }}</span> 
                        : Belum Tuntas / Remedial
                    </div>
                </div>
                <div class="mt-2 text-xs italic text-gray-500">
                    * Patokan angka menggunakan nilai KKTP dari Mata Pelajaran yang dipilih.
                </div>
            @endif
        </div>

    <div class="space-y-8 mt-6">
        
        <x-filament::section class="mb-5">
            <x-slot name="heading">1. Rekap Penilaian Skor (Skala 1 - 4)</x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse border border-gray-300 dark:border-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-center">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-700 p-2" rowspan="2">No</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2" rowspan="2">Nama Siswa</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2" colspan="{{ count($aspects) > 0 ? count($aspects) : 1 }}">Rata-rata Skor Aspek</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2" rowspan="2">Total Skor</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2" rowspan="2">Nilai Akhir</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2" rowspan="2">Keputusan</th>
                        </tr>
                        <tr>
                            @forelse($aspects as $aspect)
                                <th class="border border-gray-300 dark:border-gray-700 p-2">{{ $aspect->nama_aspek }}</th>
                            @empty
                                <th class="border border-gray-300 dark:border-gray-700 p-2">Belum ada aspek</th>
                            @endforelse
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapData as $index => $data)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 text-center">
                            <td class="border border-gray-300 dark:border-gray-700 p-2">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 dark:border-gray-700 p-2 text-left font-bold">{{ $data['student']->nama }}</td>
                            @foreach($aspects as $aspect)
                                <td class="border border-gray-300 dark:border-gray-700 p-2">{{ $data['aspectScores'][$aspect->id]['skor'] }}</td>
                            @endforeach
                            <td class="border border-gray-300 dark:border-gray-700 p-2 font-bold">{{ $data['totalSkor'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-700 p-2 font-bold text-blue-600">{{ $data['nilaiAkhir'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-700 p-2 font-bold 
                                {{ in_array($data['ketuntasan'], ['Tuntas', 'Sangat Berkembang (SB)', 'Berkembang Sesuai Harapan (BSH)']) ? 'text-green-600' : 
                                   (in_array($data['ketuntasan'], ['Mulai Berkembang (MB)']) ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $data['ketuntasan'] }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="{{ count($aspects) + 5 }}" class="text-center p-4">Silakan pilih mapel terlebih dahulu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <x-filament::section class="mb-5">
            <x-slot name="heading">2. Rekap Penilaian Puluhan (Skala 100)</x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse border border-gray-300 dark:border-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-center">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-700 p-2" rowspan="2">No</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2" rowspan="2">Nama Siswa</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2" colspan="{{ count($aspects) > 0 ? count($aspects) : 1 }}">Nilai Aspek (Skala 100)</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2" rowspan="2">Total Skor</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2" rowspan="2">Nilai Akhir</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2" rowspan="2">Keputusan</th>
                        </tr>
                        <tr>
                            @foreach($aspects as $aspect)
                                <th class="border border-gray-300 dark:border-gray-700 p-2">{{ $aspect->nama_aspek }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapData as $index => $data)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 text-center">
                            <td class="border border-gray-300 dark:border-gray-700 p-2">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 dark:border-gray-700 p-2 text-left font-bold">{{ $data['student']->nama }}</td>
                            @foreach($aspects as $aspect)
                                <td class="border border-gray-300 dark:border-gray-700 p-2">{{ $data['aspectScores'][$aspect->id]['puluhan'] }}</td>
                            @endforeach
                            <td class="border border-gray-300 dark:border-gray-700 p-2 font-bold">{{ $data['totalSkor'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-700 p-2 font-bold text-blue-600">{{ $data['nilaiAkhir'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-700 p-2 font-bold 
                                {{ in_array($data['ketuntasan'], ['Tuntas', 'Sangat Berkembang (SB)', 'Berkembang Sesuai Harapan (BSH)']) ? 'text-green-600' : 
                                   (in_array($data['ketuntasan'], ['Mulai Berkembang (MB)']) ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $data['keputusan'] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <x-filament::section class="mb-5">
            <x-slot name="heading">
                3. Rekap Ketuntasan 
                @if($konsep == 'Tidak Range') 
                    (KKTP: {{ $kktp ?? '-' }}) 
                @else 
                    (Berdasarkan Rentang Predikat) 
                @endif
            </x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse border border-gray-300 dark:border-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-center">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-700 p-2 w-16">No</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2">Nama Siswa</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2">Nilai Akhir (0-100)</th>
                            <th class="border border-gray-300 dark:border-gray-700 p-2">
                                @if($konsep == 'Tidak Range') Keterangan Ketuntasan @else Predikat / Kategori @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapData as $index => $data)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 text-center">
                            <td class="border border-gray-300 dark:border-gray-700 p-2">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 dark:border-gray-700 p-2 text-left font-bold">{{ $data['student']->nama }}</td>
                            <td class="border border-gray-300 dark:border-gray-700 p-2 font-bold text-blue-600">{{ $data['nilaiAkhir'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-700 p-2 font-bold 
                                {{ in_array($data['ketuntasan'], ['Tuntas', 'Sangat Berkembang (SB)', 'Berkembang Sesuai Harapan (BSH)']) ? 'text-green-600 bg-green-50' : 
                                   (in_array($data['ketuntasan'], ['Mulai Berkembang (MB)']) ? 'text-yellow-600 bg-yellow-50' : 'text-red-600 bg-red-50') }}">
                                {{ $data['ketuntasan'] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>