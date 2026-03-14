<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certificate_id');
            $table->string('nik', 16);
            $table->string('nama_pemilik');
            
            // Payment details
            $table->decimal('jumlah_pembayaran', 12, 2);
            $table->string('metode_pembayaran'); // transfer_bank, e_wallet, dll
            $table->string('bank')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('atas_nama')->nullable();
            
            // Payment status
            $table->enum('status', [
                'pending',
                'verified',
                'rejected',
                'refunded'
            ])->default('pending');
            
            // Payment proof
            $table->string('bukti_transfer')->nullable();
            $table->string('screenshot_pembayaran')->nullable();
            
            // Verification info
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            
            // Digital receipt
            $table->string('nomor_transaksi')->unique();
            $table->timestamp('tanggal_pembayaran')->nullable();
            
            $table->timestamps();
            
            $table->foreign('certificate_id')->references('id')->on('certificates')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
