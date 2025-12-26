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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('user_customers');
            $table->foreignId('address_id')->constrained('addresses');
            $table->string('invoice_number')->unique();
            $table->decimal('total_price',12,2);
            $table->decimal('shipping_cost',12,2);
            $table->enum('order_status', ['new', 'processing', 'shipped', 'delivered', 'cancelled'])->default('new');
            $table->enum('payment_status',['Pending','Berhasil','Gagal','Expired','Refound'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
