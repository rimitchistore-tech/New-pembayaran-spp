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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Bank Transfer, E-Wallet, Cash
            $table->string('slug')->unique(); // bank_transfer, e_wallet, cash
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // for UI
            $table->boolean('is_active')->default(true);
            $table->integer('processing_time')->default(0); // dalam jam
            $table->json('metadata')->nullable(); // extra config
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
