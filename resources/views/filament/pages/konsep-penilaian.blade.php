<x-filament-panels::page>

    <form wire:submit.prevent="save">
        {{ $this->form }}

        <div class="mt-6 flex justify-end">
            <x-filament::button type="submit" size="lg" icon="heroicon-o-check-circle">
                Simpan Konsep Penilaian
            </x-filament::button>
        </div>
    </form>

    {{-- Info box --}}
    <x-filament::section class="mt-6">
        <x-slot name="heading">
            <span class="flex items-center gap-2 text-primary-600">
                <x-heroicon-o-information-circle class="w-5 h-5" />
                Cara Kerja
            </span>
        </x-slot>
        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
            <p><strong>1.</strong> Atur mata pelajaran, CP, TP, jenis penilaian, dan aspek di halaman ini.</p>
            <p><strong>2.</strong> Klik <strong>"Simpan Konsep Penilaian"</strong>.</p>
            <p><strong>3.</strong> Buka menu <strong>Data Siswa</strong>, lalu klik tombol <strong>"Input
                    Nilai"</strong> pada siswa yang ingin dinilai.</p>
            <p><strong>4.</strong> Pengaturan penilaian akan otomatis terisi sesuai konsep yang sudah disimpan.</p>
        </div>
    </x-filament::section>

</x-filament-panels::page>