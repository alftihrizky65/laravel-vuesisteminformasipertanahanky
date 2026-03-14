<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->string('alamat_rumah');
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten_kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->string('email')->nullable();
            
            // Details about the land/certificate
            $table->string('jenis_sertifikat'); // SHM, SHGB, HGB, dll
            $table->string('nomor_sertifikat')->nullable();
            $table->decimal('luas_tanah', 12, 2)->nullable(); // dalam meter persegi
            $table->decimal('luas_bangunan', 12, 2)->nullable();
            $table->text('deskripsi_lokasi')->nullable();
            
            // Status tracking
            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_verifikasi_pembayaran',
                'diproses',
                'selesai',
                'ditolak'
            ])->default('menunggu_pembayaran');
            
            $table->enum('status_pengiriman', [
                'belum_dikirim',
                'sedang_dikirim',
                'terkirim'
            ])->default('belum_dikirim');
            
            $table->string('nomor_resi')->nullable();
            $table->timestamp('tanggal_pengiriman')->nullable();
            $table->timestamp('tanggal_terkirim')->nullable();
            
            // Admin/Staff yang memproses
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->text('catatan')->nullable();
            
            // Tanggal expired pajak
            $table->date('tanggal_expired_pajak')->nullable();
            
            // File uploads
            $table->string('foto_rumah')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->string('sertifikatjadi')->nullable();
            
            $table->timestamps();
            
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
