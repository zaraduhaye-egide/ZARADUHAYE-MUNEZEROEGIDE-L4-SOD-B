<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, let's make sure we have the correct columns
        if (!Schema::hasColumn('products', 'unit_price')) {
            Schema::table('products', function (Blueprint $table) {
                // Add unit_price if it doesn't exist
                $table->decimal('unit_price', 10, 2)->after('description')->default(0);
            });
        }

        // If there's an old price column, migrate the data and remove it
        if (Schema::hasColumn('products', 'price')) {
            // Copy data from price to unit_price
            DB::statement('UPDATE products SET unit_price = price WHERE unit_price = 0');
            
            // Remove the old price column
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('price');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add back the price column if needed
            if (!Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 10, 2)->after('description')->default(0);
            }

            // Copy data back if unit_price exists
            if (Schema::hasColumn('products', 'unit_price')) {
                DB::statement('UPDATE products SET price = unit_price');
                $table->dropColumn('unit_price');
            }
        });
    }
}; 