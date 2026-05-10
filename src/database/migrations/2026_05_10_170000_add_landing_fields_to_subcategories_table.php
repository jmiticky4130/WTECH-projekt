<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->boolean('show_on_landing')->default(false)->after('sort_order');
            $table->string('landing_image', 255)->nullable()->after('show_on_landing');
        });
    }

    public function down(): void
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn(['show_on_landing', 'landing_image']);
        });
    }
};
