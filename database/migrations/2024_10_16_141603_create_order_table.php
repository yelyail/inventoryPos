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
        Schema::create('order', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('inventory_id');
            $table->date('order_date');
            $table->string('total_amount');
            $table->integer('qtyOrder');
            $table->string('status');
            $table->foreign('customer_id')->references('customer_id')->on('customer');
            $table->foreign('payment_id')->references('payment_id')->on('paymentmethod');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('inventory_id')->references('inventory_id')->on('inventory');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
