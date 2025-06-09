<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopkeepers', function (Blueprint $table) {
            $table->id('ShopkeeperId');
            $table->string('UserName')->unique();
            $table->string('Password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopkeepers');
    }
}; 