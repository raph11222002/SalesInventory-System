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
        Schema::create('product_consumable_needed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('product_list')->onDelete('cascade');
            $table->string('product_name', 100);
            $table->foreignId('consumable_id')->constrained('consumable_list')->onDelete('cascade');
            $table->string('consumable_name', 100);
            $table->integer('quantity_needed');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_consumable_needed');
    }
};