<?php

namespace App\Imports;

use App\Models\Student;
use Filament\Facades\Filament;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip empty rows (jika nama_lengkap kosong, lewati)
        if (empty($row['nama_lengkap'])) {
            return null;
        }

        $schoolProfileId = Filament::getTenant()->id;

        // Cek jika NIPD ada, update data lama, jika tidak buat baru
        $student = null;
        if (!empty($row['nipd_nisn'])) {
            $student = Student::where('school_profile_id', $schoolProfileId)
                ->where('nipd', $row['nipd_nisn'])
                ->first();
        }

        if (!$student) {
            $student = new Student();
            $student->school_profile_id = $schoolProfileId;
        }

        $student->nipd = $row['nipd_nisn'] ?? null;
        $student->nama = $row['nama_lengkap'];

        // Jika yang import adalah guru (admin), paksa kelas/nama_kelas/fase dari profil guru
        $user = auth()->user();
        if ($user && $user->role === 'admin' && $user->kelas) {
            $student->kelas = $user->kelas;
            $student->nama_kelas = $user->nama_kelas;
            // Auto-hitung fase dari kelas guru
            $angkaKelas = (int) $user->kelas;
            $student->fase = match (true) {
                $angkaKelas >= 1 && $angkaKelas <= 2 => 'A',
                $angkaKelas >= 3 && $angkaKelas <= 4 => 'B',
                $angkaKelas >= 5 && $angkaKelas <= 6 => 'C',
                default => $row['fase'] ?? null,
            };
        } else {
            $student->kelas = $row['kelas'] ?? null;
            $student->nama_kelas = $row['nama_kelas_rombel'] ?? null;
            $student->fase = $row['fase'] ?? null;

            // Auto-hitung fase jika kelas diisi tapi fase kosong
            if (!empty($student->kelas) && empty($student->fase)) {
                $angkaKelas = (int) $student->kelas;
                $student->fase = match (true) {
                    $angkaKelas >= 1 && $angkaKelas <= 2 => 'A',
                    $angkaKelas >= 3 && $angkaKelas <= 4 => 'B',
                    $angkaKelas >= 5 && $angkaKelas <= 6 => 'C',
                    default => null,
                };
            }
        }

        $student->jenis_kelamin = $row['jenis_kelamin'] ?? null;
        $student->tempat_lahir = $row['tempat_lahir'] ?? null;
        
        // Parse tanggal lahir if exist
        if (!empty($row['tanggal_lahir'])) {
            try {
                // Di Excel kadang tanggal berupa angka serial atau string bentuk d/m/Y dll.
                // Pendekatan paling aman via phpspreadsheet Date::excelToDateTimeObject
                if (is_numeric($row['tanggal_lahir'])) {
                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir']);
                    $student->tanggal_lahir = $date->format('Y-m-d');
                } else {
                    $student->tanggal_lahir = date('Y-m-d', strtotime($row['tanggal_lahir']));
                }
            } catch (\Exception $e) {
                // Biarkan null jika gagal parse
            }
        }

        $student->agama = $row['agama'] ?? null;
        $student->pendidikan_sebelumnya = $row['pendidikan_sebelumnya'] ?? null;
        $student->nama_ayah = $row['nama_ayah'] ?? null;
        $student->pekerjaan_ayah = $row['pekerjaan_ayah'] ?? null;
        $student->nama_ibu = $row['nama_ibu'] ?? null;
        $student->pekerjaan_ibu = $row['pekerjaan_ibu'] ?? null;
        $student->jalan = $row['jalan_dusun'] ?? null;
        $student->desa = $row['desa_kelurahan'] ?? null;
        $student->kecamatan = $row['kecamatan'] ?? null;
        $student->kabupaten = $row['kabupaten'] ?? null;
        $student->provinsi = $row['provinsi'] ?? null;
        $student->nama_wali = $row['nama_wali'] ?? null;
        $student->pekerjaan_wali = $row['pekerjaan_wali'] ?? null;
        $student->alamat_wali = $row['alamat_wali'] ?? null;

        return $student;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
