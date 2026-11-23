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
        Schema::create('eye_conditions', function (Blueprint $table) {
    $table->id('conditionID');

    // Tambah customer_id di sini supaya terus merujuk kepada pelanggan
    $table->foreignId('customer_id')->constrained('customers', 'id')->onDelete('cascade');

    $table->foreignId('appointmentID')->nullable()->constrained('appointments', 'appointmentID')->onDelete('cascade');
    $table->foreignId('salespersonID')->constrained('users')->onDelete('cascade');

    $table->decimal('rightPower', 5, 2)->nullable();
    $table->decimal('leftPower', 5, 2)->nullable();
    $table->decimal('rightCylinder', 5, 2)->nullable();
    $table->decimal('leftCylinder', 5, 2)->nullable();
    $table->integer('rightAxis')->nullable();
    $table->integer('leftAxis')->nullable();
    $table->decimal('pupilDistance', 5, 2)->nullable();
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
        Schema::dropIfExists('eye_conditions');
    }
};
