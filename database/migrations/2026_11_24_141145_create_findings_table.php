<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_kompetisi');
            $table->string('kategori');
            $table->string('judul_tim');
            $table->string('tahap_ide');
            $table->unsignedInteger('anggota_dibutuhkan')->default(1);
            $table->unsignedInteger('anggota_terkumpul')->default(1);
            $table->date('tenggat_pengajuan');
            $table->json('keterampilan');
            $table->text('deskripsi');
            $table->enum('status', ['published', 'draft', 'closed'])->default('published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('findings');
    }
};