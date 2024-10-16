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
        Schema::create('repair', function (Blueprint $table) {
            $table->id('repair_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('inventory_id');
            $table->date('return_date');
            $table->string('return_reason');
            $table->string('return_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair');
    }
};
