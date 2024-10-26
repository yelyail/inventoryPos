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
        Schema::create('replace', function (Blueprint $table) {
            $table->id('replace_id');
            $table->unsignedBigInteger('inventory_id');
            $table->date('replace_date');
            $table->string('replace_reason');
            $table->string('replace_status');
            $table->foreign('inventory_id')->references('inventory_id')->on('inventory');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replace');
    }
};
