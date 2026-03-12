<x-filament-panels::page>

    {{-- Info Konsep Penilaian (Teks biasa, bukan form) --}}
    <x-filament::section>
        <x-slot name="heading">
            <span class="flex items-center gap-2">
                <x-heroicon-o-cog-6-tooth class="w-5 h-5 text-primary-500" />
                Konsep Penilaian
            </span>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500 dark:text-gray-400 font-medium">Nama Siswa</p>
                <p class="text-gray-900 dark:text-gray-100 font-semibold text-base mt-0.5">{{ $studentName ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 font-medium">Mata Pelajaran</p>
                <p class="text-gray-900 dark:text-gray-100 font-semibold text-base mt-0.5">{{ $subjectName ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 font-medium">Jenis Penilaian</p>
                <p class="text-gray-900 dark:text-gray-100 font-semibold text-base mt-0.5">{{ $jenis_penilaian ?? '-' }}
                </p>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 font-medium">Aspek Penilaian</p>
                <p class="text-gray-900 dark:text-gray-100 font-semibold text-base mt-0.5">{{ $aspectName ?? '-' }}</p>
            </div>
            @if(!empty($cpNames))
                <div class="lg:col-span-2">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Capaian Pembelajaran (CP)</p>
                    <ul class="mt-0.5 space-y-0.5">
                        @foreach($cpNames as $cp)
                            <li class="text-gray-900 dark:text-gray-100">• {{ $cp }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(!empty($tpNames))
                <div class="lg:col-span-2">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Tujuan Pembelajaran (TP)</p>
                    <ul class="mt-0.5 space-y-0.5">
                        @foreach($tpNames as $tp)
                            <li class="text-gray-900 dark:text-gray-100">• {{ $tp }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        @if(!$subjectName && !$jenis_penilaian)
            <div
                class="mt-4 flex items-center gap-3 p-3 rounded-lg bg-warning-50 dark:bg-warning-500/10 border border-warning-200 dark:border-warning-500/30">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-warning-500 shrink-0" />
                <p class="text-sm text-warning-700 dark:text-warning-400">
                    Konsep penilaian belum disetting. Silakan atur terlebih dahulu di menu <strong>Konsep
                        Penilaian</strong>.
                </p>
            </div>
        @endif
    </x-filament::section>

    {{-- Indicator Scoring Section (grouped by aspect) --}}
    @if($this->student_id && $this->hasIndicators)
        <form wire:submit.prevent="save">
            @foreach($this->aspectsWithIndicators as $aspect)
                @if($aspect->indicators->isNotEmpty())
                    <x-filament::section class="mt-4">
                        <x-slot name="heading">
                            <span class="flex items-center gap-2">
                                <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-primary-500" />
                                {{ $aspect->nama_aspek }}
                            </span>
                        </x-slot>
                        <x-slot name="description">
                            <span class="inline-flex items-center gap-1.5 text-xs">
                                <span
                                    class="px-2 py-0.5 rounded-full bg-primary-50 dark:bg-primary-500/10 text-primary-700 dark:text-primary-300 font-medium">
                                    {{ $aspect->jenis_penilaian }}
                                </span>
                                <span class="text-gray-400">&bull;</span>
                                Berikan skor 1&ndash;4 untuk setiap indikator
                            </span>
                        </x-slot>

                        <div class="space-y-4">
                            @foreach($aspect->indicators as $indicator)
                                <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
                                    <div class="mb-2">
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $loop->iteration }}. {{ $indicator->nama_indikator }}
                                        </span>
                                        @if($indicator->deskripsi_kriteria)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $indicator->deskripsi_kriteria }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap gap-3 mt-3">
                                        @foreach([1 => '1 - Perlu Bimbingan', 2 => '2 - Cukup', 3 => '3 - Baik', 4 => '4 - Sangat Baik'] as $value => $label)
                                            <label class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-all duration-150
                                                                                        {{ isset($scores[(string) $indicator->id]) && $scores[(string) $indicator->id] == $value
                                            ? 'border-primary-500 bg-primary-50 dark:bg-primary-500/10 text-primary-700 dark:text-primary-400 ring-2 ring-primary-500/30'
                                            : 'border-gray-300 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-500/50 text-gray-700 dark:text-gray-300'
                                                                                        }}">
                                                <input type="radio" name="scores_{{ $indicator->id }}" value="{{ $value }}"
                                                    wire:model="scores.{{ $indicator->id }}"
                                                    class="text-primary-600 focus:ring-primary-500" />
                                                <span class="text-sm font-medium">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-filament::section>
                @endif
            @endforeach

            {{-- Save Button --}}
            <div class="mt-6 flex justify-end">
                <x-filament::button type="submit" size="lg" icon="heroicon-o-check-circle">
                    Simpan Semua Nilai
                </x-filament::button>
            </div>
        </form>
    @elseif($this->student_id && !empty($this->aspect_ids) && !$this->hasIndicators)
        <x-filament::section>
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <x-heroicon-o-exclamation-circle class="w-10 h-10 mx-auto mb-3 text-gray-300 dark:text-gray-600" />
                <p class="font-medium">Belum ada indikator untuk aspek ini.</p>
                <p class="text-sm mt-1">Silakan tambahkan indikator terlebih dahulu di menu Data Indikator.</p>
            </div>
        </x-filament::section>
    @elseif(!$this->student_id || empty($this->aspect_ids))
        <x-filament::section>
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <x-heroicon-o-information-circle class="w-8 h-8 mx-auto mb-3 text-primary-300 dark:text-primary-600" />
                <p class="font-medium">Konsep penilaian belum lengkap.</p>
                <p class="text-sm mt-1">Pastikan konsep penilaian sudah disetting di menu <strong>Konsep Penilaian</strong>,
                    lalu klik "Input Nilai" dari menu Data Siswa.</p>
            </div>
        </x-filament::section>
    @endif

</x-filament-panels::page>