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
        Schema::create('payment_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->foreignId('verified_by')->constrained('users');
            $table->enum('action', ['verified', 'rejected', 'pending'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('verification_method')->nullable(); // manual, auto, api
            $table->json('metadata')->nullable(); // extra data
            $table->timestamps();
            
            // Indexes
            $table->index('payment_id');
            $table->index('verified_by');
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_verifications');
    }
};
