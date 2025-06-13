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
            $table->string('user_id')->nullable();
            $table->string('driver_id')->nullable();
            $table->string('pick_up_location')->nullable();
            $table->string('drop_location')->nullable();
            $table->decimal('final_amount', 10, 2);
            $table->enum('cancel_type', ['0', '1'])->nullable()->comment('user = 0 , driver = 1');
            $table->string('ride_type')->nullable();
            $table->integer('vehicle_type')->nullable();
            $table->integer('person_count')->nullable();
            $table->json('parsal_details')->nullable();
            $table->tinyInteger('status')->comment('failed = 0 , success = 1');
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
