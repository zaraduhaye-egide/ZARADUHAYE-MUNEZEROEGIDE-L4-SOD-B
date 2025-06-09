<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_outs', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('total_price');
        });
    }

    public function down()
    {
        Schema::table('product_outs', function (Blueprint $table) {
            $table->dropColumn('customer_name');
        });
    }
}; 