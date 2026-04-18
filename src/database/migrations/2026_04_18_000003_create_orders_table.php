<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('pending');
            $table->string('email', 255);
            $table->string('phone', 30)->nullable();
            $table->foreignId('shipping_method_id')->constrained('shipping_methods');
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->decimal('shipping_cost', 10, 2);
            $table->decimal('payment_fee', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('street', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('zip', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('pickup_point', 255)->nullable();
            $table->string('billing_first_name', 100)->nullable();
            $table->string('billing_last_name', 100)->nullable();
            $table->string('billing_street', 255)->nullable();
            $table->string('billing_city', 100)->nullable();
            $table->string('billing_zip', 20)->nullable();
            $table->string('billing_country', 100)->nullable();
            $table->boolean('billing_same_as_delivery')->default(true);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
