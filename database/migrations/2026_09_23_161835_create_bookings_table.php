<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricelist_id')->constrained('pricelists')->restrictOnDelete();
            $table->string('kode_booking')->unique();
            // -------- Identitas Pelanggan --------
            $table->string('nama_pelanggan', 50);
            $table->string('no_hp', 15);
            $table->string('email');
            // -------- Detail Acara --------
            $table->date('tanggal_acara');
            $table->time('waktu_acara');
            $table->string('lokasi_acara', 100);
            $table->text('catatan_tambahan')->nullable();
            // -------- Pembayaran --------
            $table->unsignedBigInteger('total_harga');
            $table->unsignedBigInteger('dp');
            // -------- Status Booking --------
            $table->string('status_booking')->default('pending');
            $table->string('status_pembayaran')->default('belum_dp');
            $table->string('metode_pembayaran')->nullable();
            $table->string('payment_qr')->nullable();
            // -------- Testimoni --------
            $table->string('token_testimoni')->nullable()->unique();
            $table->string('expired_testimoni')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
