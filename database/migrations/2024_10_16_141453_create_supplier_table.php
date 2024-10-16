<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->id('supplier_ID'); 
            $table->string('supplier_name'); 
            $table->string('supplier_address'); 
            $table->string('supplier_email');
            $table->string('supplier_phone');
            $table->string('status'); // Add status if needed
            $table->timestamps(); 
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
