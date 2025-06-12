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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone_no')->nullable();
            $table->string('alternative_no')->nullable();
            $table->string('profile')->nullable();
            $table->date('dob')->nullable();
            $table->string('vehicle_brand')->nullable();
            $table->string('vehicle_plate_no')->nullable();
            $table->string('vehicle_photo')->nullable();
            $table->string('vehicle_rc_front_photo')->nullable();
            $table->string('vehicle_rc_back_photo')->nullable();
            $table->string('vehicle_capicty')->nullable();
            $table->string('vehicle_production_year')->nullable();
            $table->string('commercial_permit_year')->nullable();
            $table->tinyInteger('status')->default(1)->comment('InActive = 0 , Active = 1');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
