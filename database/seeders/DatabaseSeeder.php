<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Finding;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $demos = [
            [
                'leader' => 'Rafi A.',
                'email' => 'rafi.demo@student.ub.ac.id',
                'kompetisi' => 'Gemastik',
                'kategori' => 'Kompetisi Teknologi',
                'tahap' => 'Prototipe',
                'judul' => 'Tim Ecovision - Solusi AI Pendeteksi Sampah Sungai',
                'terkumpul' => 2,
                'dibutuhkan' => 3,
                'hari' => 4,
                'skills' => [
                    ['nama' => 'UI/UX Designer', 'status' => 'Terpenuhi'],
                    ['nama' => 'Fullstack Dev', 'status' => 'Terpenuhi'],
                    ['nama' => 'Machine Learning Engineer', 'status' => 'Dibutuhkan'],
                ],
            ],
            [
                'leader' => 'Nadia P.',
                'email' => 'nadia.demo@student.ub.ac.id',
                'kompetisi' => 'Hackathon',
                'kategori' => 'Kompetisi Teknologi',
                'tahap' => 'Ide',
                'judul' => 'Smart Campus Navigator untuk Mahasiswa Baru',
                'terkumpul' => 1,
                'dibutuhkan' => 3,
                'hari' => 9,
                'skills' => [
                    ['nama' => 'UI/UX Designer', 'status' => 'Terpenuhi'],
                    ['nama' => 'Mobile Dev (Flutter)', 'status' => 'Dibutuhkan'],
                    ['nama' => 'Backend Developer', 'status' => 'Dibutuhkan'],
                ],
            ],
            [
                'leader' => 'Dimas S.',
                'email' => 'dimas.demo@student.ub.ac.id',
                'kompetisi' => 'Business Plan',
                'kategori' => 'Kompetisi Bisnis',
                'tahap' => 'Ide',
                'judul' => 'AgroLink: Marketplace Hasil Tani Langsung dari Petani',
                'terkumpul' => 1,
                'dibutuhkan' => 3,
                'hari' => 12,
                'skills' => [
                    ['nama' => 'Pitching & Presentasi', 'status' => 'Terpenuhi'],
                    ['nama' => 'Business Analyst', 'status' => 'Dibutuhkan'],
                    ['nama' => 'Desain Grafis', 'status' => 'Dibutuhkan'],
                ],
            ],
            [
                'leader' => 'Salsa R.',
                'email' => 'salsa.demo@student.ub.ac.id',
                'kompetisi' => 'Karya Tulis Ilmiah',
                'kategori' => 'Lomba Karya Tulis',
                'tahap' => 'Prototipe',
                'judul' => 'Pemanfaatan Limbah Kopi sebagai Bahan Biokomposit',
                'terkumpul' => 1,
                'dibutuhkan' => 2,
                'hari' => 6,
                'skills' => [
                    ['nama' => 'Analis Data', 'status' => 'Terpenuhi'],
                    ['nama' => 'Penulis Ilmiah', 'status' => 'Dibutuhkan'],
                ],
            ],
            [
                'leader' => 'Bima T.',
                'email' => 'bima.demo@student.ub.ac.id',
                'kompetisi' => 'UI/UX Design',
                'kategori' => 'Desain',
                'tahap' => 'Ide',
                'judul' => 'Aksesibilitas Layanan Kampus untuk Mahasiswa Disabilitas',
                'terkumpul' => 1,
                'dibutuhkan' => 3,
                'hari' => 15,
                'skills' => [
                    ['nama' => 'Visual Designer', 'status' => 'Terpenuhi'],
                    ['nama' => 'UX Researcher', 'status' => 'Dibutuhkan'],
                    ['nama' => 'Front-end Developer', 'status' => 'Dibutuhkan'],
                ],
            ],
            [
                'leader' => 'Karin W.',
                'email' => 'karin.demo@student.ub.ac.id',
                'kompetisi' => 'Data Science',
                'kategori' => 'Kompetisi Teknologi',
                'tahap' => 'Prototipe',
                'judul' => 'Prediksi Kualitas Udara Perkotaan Berbasis Time Series',
                'terkumpul' => 1,
                'dibutuhkan' => 3,
                'hari' => 3,
                'skills' => [
                    ['nama' => 'Analis Data', 'status' => 'Terpenuhi'],
                    ['nama' => 'Data Scientist', 'status' => 'Dibutuhkan'],
                    ['nama' => 'Data Engineer', 'status' => 'Dibutuhkan'],
                ],
            ],
        ];

        foreach ($demos as $d) {
            $user = User::firstOrCreate(
                ['email' => $d['email']],
                ['name' => $d['leader'], 'password' => Hash::make('password123')]
            );

            Finding::create([
                'user_id' => $user->id,
                'nama_kompetisi' => $d['kompetisi'],
                'kategori' => $d['kategori'],
                'judul_tim' => $d['judul'],
                'tahap_ide' => $d['tahap'],
                'anggota_dibutuhkan' => $d['dibutuhkan'],
                'anggota_terkumpul' => $d['terkumpul'],
                'tenggat_pengajuan' => now()->addDays($d['hari']),
                'keterampilan' => $d['skills'],
                'deskripsi' => 'Mencari anggota tim yang berkomitmen untuk berkolaborasi dalam kompetisi ' . $d['kompetisi'] . '.',
                'status' => 'published',
            ]);
        }
    }
}