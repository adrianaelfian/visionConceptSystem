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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('generated_date')->nullable(); // Ditambah: Tarikh laporan dijana
            $table->foreignId('generated_by')->nullable()->constrained('users')->onDelete('set null'); // Ditambah: Staf yang menjana laporan
            $table->json('data_summary');
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
        Schema::dropIfExists('reports');
    }
};
