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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->string('product_name')->default('Supari Fali');
            $table->string('lot_number')->nullable();
            $table->decimal('rate', 10, 2);
            $table->decimal('discounted_bag_weight', 10, 2);
            $table->json('per_bag_weight')->nullable();
            $table->decimal('packaging_charge', 10, 2)->nullable();
            $table->decimal('hamali_charge', 10, 2)->nullable();
            $table->dateTime('order_date');
            $table->dateTime('due_date')->nullable();
            $table->integer('quantity');
            $table->decimal('total_weight', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('grand_amount', 10, 2);
            $table->timestamps();
            $table->softDeletes();
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
