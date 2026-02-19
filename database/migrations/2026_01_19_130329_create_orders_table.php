<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('table_no', 5);
            $table->date('order_date')->default(DB::raw('CURRENT_DATE'));
            $table->time('ordertime')->default(DB::raw('CURRENT_TIME'));
            $table->integer('total');

            $table->unsignedBigInteger('waiters_id')->nullable();
            $table->unsignedBigInteger('cashiers_id')->nullable();
            $table->unsignedBigInteger('meja_id');

            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_konfirmasi_kasir',
                'diproses',
                'siap_antar',
                'selesai',
                'dibatalkan'
            ]);

            $table->enum('metode_pembayaran', ['cash', 'online'])->nullable();
            $table->enum('payment_status', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->text('catatan_umum')->nullable();


            $table->timestamps();
            $table->softDeletes();
            $table->foreign('waiters_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cashiers_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('meja_id')->references('id')->on('mejas')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
