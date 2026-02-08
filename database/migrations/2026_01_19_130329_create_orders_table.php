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
            $table->string('status', 100);
            $table->integer('total');

            $table->unsignedBigInteger('waiters_id');
            $table->unsignedBigInteger('cashiers_id');

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('waiters_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cashiers_id')->references('id')->on('users')->onDelete('cascade');

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
