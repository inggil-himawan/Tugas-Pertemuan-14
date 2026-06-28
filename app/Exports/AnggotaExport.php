<?php

namespace App\Exports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnggotaExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Ambil data dari database
     */
    public function collection()
    {
        return Anggota::select([
            'kode_anggota', 'nama', 'email', 'telepon', 'alamat',
            'tanggal_lahir', 'jenis_kelamin', 'pekerjaan', 'status', 'tanggal_daftar',
        ])->get();
    }

    /**
     * Baris header di Excel (baris pertama)
     */
    public function headings(): array
    {
        return [
            'Kode Anggota', 'Nama', 'Email', 'Telepon', 'Alamat',
            'Tanggal Lahir', 'Jenis Kelamin', 'Pekerjaan', 'Status', 'Tanggal Daftar',
        ];
    }

    /**
     * Format setiap baris data (opsional tapi direkomendasikan untuk tanggal)
     */
    public function map($anggota): array
    {
        return [
            $anggota->kode_anggota,
            $anggota->nama,
            $anggota->email,
            $anggota->telepon,
            $anggota->alamat,
            $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d-m-Y') : '',
            $anggota->jenis_kelamin,
            $anggota->pekerjaan,
            $anggota->status,
            $anggota->tanggal_daftar ? $anggota->tanggal_daftar->format('d-m-Y') : '',
        ];
    }
}