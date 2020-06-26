<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduledBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_bookings', function (Blueprint $table) {
            $table->id();

            $table->timestamp('date')->default(now());
            $table->integer('interval');
            $table->string('room');
            $table->string('message')->nullable()->default(null);
            $table->text('result')->nullable()->default(null);
            $table->foreignId('kronox_credentials_id')->references('id')->on('kronox_credentials');
            $table->boolean('recurring')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_bookings');
    }
}
