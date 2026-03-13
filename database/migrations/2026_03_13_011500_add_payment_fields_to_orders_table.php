<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('customer_payment', 10, 2)->default(0)->after('total_amount');
            $table->decimal('change_amount', 10, 2)->default(0)->after('customer_payment');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_payment', 'change_amount']);
        });
    }
};