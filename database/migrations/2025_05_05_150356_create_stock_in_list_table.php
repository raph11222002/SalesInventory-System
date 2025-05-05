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
        Schema::create('stock_in_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumable_id')->constrained('consumable_list')->onDelete('cascade');
            $table->integer('quantity_added');
            $table->date('date_received');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_in_list');
    }
};
