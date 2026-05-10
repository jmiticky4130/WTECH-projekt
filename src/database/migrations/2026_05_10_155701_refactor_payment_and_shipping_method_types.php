<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop type from payment_methods
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        // Normalize existing shipping type values to English slugs in the old string column
        DB::statement("UPDATE shipping_methods SET type = 'address' WHERE type IN ('doručenie na adresu', 'address')");
        DB::statement("UPDATE shipping_methods SET type = 'pickup_point' WHERE type IN ('výdajné miesto', 'pickup_point')");
        DB::statement("UPDATE shipping_methods SET type = 'personal_pickup' WHERE type IN ('osobný odber', 'personal_pickup')");

        // Add new enum column as nullable, copy data, then enforce NOT NULL + constraint
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->enum('type_new', ['address', 'pickup_point', 'personal_pickup'])->nullable()->after('type');
        });

        DB::statement("UPDATE shipping_methods SET type_new = type");

        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->renameColumn('type_new', 'type');
        });

        DB::statement("ALTER TABLE shipping_methods ALTER COLUMN type SET NOT NULL");
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('type', 30)->nullable()->after('name');
        });

        DB::table('payment_methods')->where('name', 'Platba kartou online')->update(['type' => 'karta']);
        DB::table('payment_methods')->where('name', 'Bankový prevod')->update(['type' => 'bankový prevod']);
        DB::table('payment_methods')->where('name', 'Dobierka')->update(['type' => 'dobierka']);

        DB::statement("ALTER TABLE shipping_methods RENAME COLUMN type TO type_enum");

        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->string('type', 30)->after('name');
        });

        DB::statement("UPDATE shipping_methods SET type = type_enum");

        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn('type_enum');
        });
    }
};
