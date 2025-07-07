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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->string('request_id')->unique()->nullable();
            // Location
            $table->string('pick_up_location')->nullable();
            $table->string('drop_location')->nullable();
            $table->decimal('pickup_lat', 10, 7)->nullable();
            $table->decimal('pickup_lng', 10, 7)->nullable();
            $table->decimal('drop_lat', 10, 7)->nullable();
            $table->decimal('drop_lng', 10, 7)->nullable();
            // Vehicle + Fare
            $table->tinyInteger('vehicle_type')->nullable()->comment('1 = Bike, 2 = Car, 3 = Auto');
            $table->decimal('final_amount', 10, 2)->default(0);
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->string('estimated_time')->nullable();
            // Ride Type Logic
            $table->tinyInteger('ride_type')->default(0)->comment('0 = Passenger, 1 = Parcel');
            $table->unsignedTinyInteger('number_of_passenger')->nullable();

            // Parcel Dimensions (nullable, required only if ride_type = parcel)
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->float('length')->nullable();
            $table->float('breadth')->nullable();

            $table->tinyInteger('cancel_type')->nullable()->comment('0 = User, 1 = Driver');
            $table->tinyInteger('status')->default(0)->comment('0 = Pending, 1 = Booked, 2 = Started, 3 = Completed, 4 = Cancelled, 5 = Failed');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
