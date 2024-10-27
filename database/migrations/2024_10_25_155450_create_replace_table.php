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
            $table->unsignedBigInteger('serial_id');
            $table->date('replace_date');
            $table->string('replace_reason');
            $table->foreign('serial_id')->references('serial_id')->on('serial')->onDelete('cascade')->onUpdate('cascade');
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
