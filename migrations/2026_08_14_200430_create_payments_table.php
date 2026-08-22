<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            // Primary Key
            $table->string('id', 64)->primary()->comment('Unique payment identifier with "pay_" prefix');

            // Payment Data
            $table->unsignedBigInteger('amount')->comment('Payment amount in cents (e.g., 2500 = 25.00)');
            $table->string('currency', 3)->comment('ISO 4217 currency code (BRL, USD, EUR, GBP)');
            $table->text('description')->comment('Payment description/reference');

            // Status
            $table->enum('status', ['pending', 'paid', 'failed', 'canceled'])
                ->default('pending')
                ->comment('Current payment status');

            // Timestamps
            $table->timestamp('created_at')->useCurrent()->comment('Payment creation timestamp');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('Last update timestamp');

            // Indexes for performance
            $table->index('status', 'idx_payments_status');
            $table->index('created_at', 'idx_payments_created_at');
            $table->index('currency', 'idx_payments_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
