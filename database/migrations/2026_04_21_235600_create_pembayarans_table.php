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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('id_pembayaran')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_pembayaran');
            $table->integer('nominal');
            $table->enum('tipe_pembayaran', ['lunas', 'cicilan'])->nullable();
            $table->integer('jumlah_cicilan')->default(1);
            $table->enum('status', ['belum_bayar', 'pending', 'lunas', 'failed'])->default('belum_bayar');
            $table->string('transaction_status')->nullable();
            $table->string('snap_token')->nullable(); // Token dari Midtrans
            $table->string('transaction_id')->nullable();
            $table->string('va_number')->nullable();
            $table->string('payment_type')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
