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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('updatedQty');
            $table->date('next_restock_date');
            $table->date('date_arrived');
            $table->string('image_delivery');
            $table->string('status');
            $table->string('warranty_supplier');
            $table->foreign('product_id')->references('product_ID')->on('product');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
