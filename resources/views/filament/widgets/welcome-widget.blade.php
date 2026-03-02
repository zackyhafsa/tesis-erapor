<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ $emoji }} {{ $salam }}, {{ $userName }}!
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $tanggal }}
                </p>
                @if($namaSekolah)
                    <p class="text-sm text-primary-600 dark:text-primary-400 font-medium mt-2">
                        {{ $namaSekolah }}
                        @if($tahunPelajaran)
                            &mdash; Tahun Pelajaran {{ $tahunPelajaran }}
                            @if($semester)
                                / Semester {{ $semester }}
                            @endif
                        @endif
                    </p>
                @endif
            </div>
            <div class="shrink-0 text-right hidden md:block">
                <p class="text-xs text-gray-400 dark:text-gray-500">Sistem Penilaian Proyek dan Kinerja</p>
                <p class="text-lg font-bold text-primary-600 dark:text-primary-400">SIPEKA</p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>