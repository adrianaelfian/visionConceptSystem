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
        Schema::create('warranties', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->onDelete('cascade');
    $table->foreignId('customer_id')->constrained()->onDelete('cascade');
    $table->string('warranty_code')->unique(); // e.g., WAR-2026-001
    $table->date('start_date');
    $table->date('end_date'); // Ditetapkan automatik +2 tahun
    $table->string('status')->default('Active'); // Active, Expired, Claimed
    $table->text('coverage_details')->nullable();
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
        Schema::dropIfExists('warranties');
    }
};
