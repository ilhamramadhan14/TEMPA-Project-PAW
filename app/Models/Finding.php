<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_kompetisi',
        'kategori',
        'judul_tim',
        'tahap_ide',
        'anggota_dibutuhkan',
        'anggota_terkumpul',
        'tenggat_pengajuan',
        'keterampilan',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'keterampilan' => 'array',
        'tenggat_pengajuan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}