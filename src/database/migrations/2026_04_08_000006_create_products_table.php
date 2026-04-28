<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('slug', 60)->unique();
            $table->text('description');
            $table->string('category', 10);
            $table->foreignId('subcategory_id')->constrained('subcategories');
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->foreignId('material_id')->nullable()->constrained('materials');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'subcategory_id']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE products ADD CONSTRAINT products_category_check CHECK (category IN ('Ženy', 'Muži', 'Deti'))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
