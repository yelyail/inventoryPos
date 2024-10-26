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
        Schema::create('product', function (Blueprint $table) {
            $table->id('product_ID');
            $table->unsignedBigInteger('supplier_ID');
            $table->unsignedBigInteger('category_Id');
            $table->float('unitPrice');
            $table->string('product_name');
            $table->string('product_description');
            $table->date('added_date');
            $table->string('typeOfUnit');
            $table->string('product_image');
            $table->foreign('supplier_ID')->references('supplier_id')->on('supplier');
            $table->foreign('category_Id')->references('category_id')->on('category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
