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
       Schema::create('driver_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('driver_id')->nullable();

            $table->string('licence_no')->nullable();
            $table->string('licence_front_photo')->nullable();
            $table->string('licence_back_photo')->nullable();
            $table->string('licence_expire_date')->nullable();
            $table->string('selfie_with_driver_licence')->nullable();

            $table->string('aadhar_no')->nullable();
            $table->string('aadhar_front_photo')->nullable();
            $table->string('aadhar_back_photo')->nullable();

            $table->string('police_verification_certificate')->nullable();

            $table->tinyInteger('verification_status')->default(0)->comment('pending = 0 , approved = 1, rejected = 2');
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_verifications');
    }
};
