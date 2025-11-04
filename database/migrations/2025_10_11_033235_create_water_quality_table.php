<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('water_quality_data', function (Blueprint $table) {
            $table->id();
            $table->decimal('conductivity', 8, 2)->nullable();
            $table->decimal('ph', 8, 2)->nullable();
            $table->decimal('oxygen', 8, 2)->nullable();
            $table->decimal('temperature', 8, 2)->nullable();
            $table->timestamp('measured_at');
            $table->timestamps();
            
            $table->index('measured_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('water_quality_data');
    }
};