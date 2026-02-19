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
        Schema::table('orders', function (Blueprint $table) {
            // Drop foreign key dulu
            $table->dropForeign(['cashiers_id']);

            // Ubah jadi nullable
            $table->unsignedBigInteger('cashiers_id')->nullable()->change();

            // Tambahkan kembali foreign key
            $table->foreign('cashiers_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cashiers_id']);

            $table->unsignedBigInteger('cashiers_id')->nullable(false)->change();

            $table->foreign('cashiers_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};
