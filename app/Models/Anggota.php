<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
 
class Anggota extends Model
{
    use HasFactory;
 
    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'anggota';
 
    /**
     * Kolom yang dapat diisi secara mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_anggota',
        'nama',
        'email',
        'telepon',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'tanggal_daftar',
        'status',
    ];
 
    /**
     * Tipe casting untuk atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_daftar' => 'date',
    ];
 
    /**
     * Accessor untuk menghitung umur.
     */
    public function getUmurAttribute(): int
    {
        return Carbon::parse($this->tanggal_lahir)->age;
    }
 
    /**
     * Accessor untuk lama menjadi anggota (dalam hari).
     */
    public function getLamaAnggotaAttribute(): int
    {
        return Carbon::parse($this->tanggal_daftar)->diffInDays(now());
    }
 
    /**
     * Scope untuk filter anggota aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }
 
    /**
     * Scope untuk filter berdasarkan jenis kelamin.
     */
    public function scopeJenisKelamin($query, $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }

    // Accessor: status_badge
    public function getStatusBadgeAttribute(): string
    {
        if (strtolower($this->status) === 'aktif') {
            return '<span class="badge bg-success">Aktif</span>';
        }
 
        return '<span class="badge bg-secondary">Nonaktif</span>';
    }
    // Accessor: kategori_usia
    public function getKategoriUsiaAttribute(): string
    {
        if (!$this->tanggal_lahir) {
            return 'Tidak Diketahui';
        }
 
        $umur = Carbon::parse($this->tanggal_lahir)->age;
 
        if ($umur < 20) {
            return 'Remaja';
        } elseif ($umur <= 50) {
            return 'Dewasa';
        } else {
            return 'Senior';
        }
    }

    // Scope: terdaftarBulanIni
    public function scopeTerdaftarBulanIni($query)
    {
        $kolom = $this->getConnection()
            ->getSchemaBuilder()
            ->hasColumn($this->getTable(), 'terdaftar_at')
                ? 'terdaftar_at'
                : 'created_at';
 
        return $query
            ->whereYear($kolom, Carbon::now()->year)
            ->whereMonth($kolom, Carbon::now()->month);
    }
}