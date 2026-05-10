<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE subcategories ALTER COLUMN show_on_landing DROP DEFAULT');
        DB::statement('ALTER TABLE subcategories ALTER COLUMN show_on_landing TYPE smallint USING show_on_landing::int');
        DB::statement('ALTER TABLE subcategories ALTER COLUMN show_on_landing SET DEFAULT 0');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE subcategories ALTER COLUMN show_on_landing DROP DEFAULT');
        DB::statement('ALTER TABLE subcategories ALTER COLUMN show_on_landing TYPE boolean USING show_on_landing::boolean');
        DB::statement('ALTER TABLE subcategories ALTER COLUMN show_on_landing SET DEFAULT false');
    }
};
