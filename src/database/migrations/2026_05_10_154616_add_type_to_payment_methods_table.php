<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('type', 30)->nullable()->after('name');
        });

        DB::table('payment_methods')->where('name', 'Platba kartou online')->update(['type' => 'karta']);
        DB::table('payment_methods')->where('name', 'Bankový prevod')->update(['type' => 'bankový prevod']);
        DB::table('payment_methods')->where('name', 'Dobierka')->update(['type' => 'dobierka']);
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
