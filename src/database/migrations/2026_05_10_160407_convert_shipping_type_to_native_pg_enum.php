<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Translate English slugs to Slovak enum values
        DB::statement("UPDATE shipping_methods SET type = 'doručenie na adresu' WHERE type = 'address'");
        DB::statement("UPDATE shipping_methods SET type = 'výdajné miesto' WHERE type = 'pickup_point'");
        DB::statement("UPDATE shipping_methods SET type = 'osobný odber' WHERE type = 'personal_pickup'");

        // Swap enum column: drop old, add new with Slovak values
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->enum('type_new', ['doručenie na adresu', 'výdajné miesto', 'osobný odber'])->nullable()->after('type');
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
        // Add a plain string column, copy data, drop the enum, rename back
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->string('type_new', 30)->nullable()->after('type');
        });

        DB::statement("UPDATE shipping_methods SET type_new = type::text");

        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->renameColumn('type_new', 'type');
        });

        DB::statement("ALTER TABLE shipping_methods ALTER COLUMN type SET NOT NULL");
    }
};
