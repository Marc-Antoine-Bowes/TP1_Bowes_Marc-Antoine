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
        Schema::create('equipment_sport', function (Blueprint $table) {
            $table->timestamps();

            $table->bigInteger('sport_id');
            $table->foreign('sport_id')->references('id')->on('sports');
            $table->bigInteger('equipment_id');
            $table->foreign('equipment_id')->references('id')->on('equipment');
            $table->primary(['sport_id', 'equipment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_sport');
    }
};
