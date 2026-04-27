<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE payment_methods ADD CONSTRAINT payment_methods_type_check CHECK (type IN ('dobierka', 'karta', 'bankový prevod'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE payment_methods DROP CONSTRAINT payment_methods_type_check');
    }
};
