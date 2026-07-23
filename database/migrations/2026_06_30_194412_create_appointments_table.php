<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointmentID');
            $table->foreignId('customerID')->constrained('customers', 'id')->onDelete('cascade'); // Hubungan ke jadual customers
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // Ditambah: Staf yang merekodkan temujanji
            $table->date('appointmentDate');
            $table->time('appointmentTime');
            $table->string('status')->default('Scheduled');
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
        Schema::dropIfExists('appointments');
    }
};
