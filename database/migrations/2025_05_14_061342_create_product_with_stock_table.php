<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_with_stock_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('product_list')->onDelete('cascade');
            $table->string('product_name');
            $table->integer('required_stock')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_with_stock_list');
    }
};