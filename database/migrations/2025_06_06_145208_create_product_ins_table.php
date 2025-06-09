<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_ins', function (Blueprint $table) {
            $table->id();
            $table->string('ProductCode');
            $table->foreign('ProductCode')->references('ProductCode')->on('products')->onDelete('cascade');
            $table->date('Date');
            $table->integer('Quantity');
            $table->decimal('UniquePrice', 10, 2);
            $table->decimal('TotalPrice', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_ins');
    }
}; 