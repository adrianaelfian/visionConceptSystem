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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('salesperson_id')->nullable()->constrained('users')->onDelete('set null'); // Ditambah: Merujuk kepada staf/salesperson
            $table->decimal('total_price', 10, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);      // Ditambah: Jumlah yang telah dibayar
            $table->decimal('remaining_balance', 10, 2)->default(0); // Ditambah: Baki bayaran
            $table->string('payment_status')->default('Pending');    // Ditambah: Status bayaran (cth: Partial, Paid)
            $table->string('order_status')->default('Processing');   // Ditambah: Status pesanan
            $table->date('order_date')->nullable();                 // Ditambah: Tarikh pesanan
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
        Schema::dropIfExists('orders');
    }
};
