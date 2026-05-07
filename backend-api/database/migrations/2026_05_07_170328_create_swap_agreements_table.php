<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('swap_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('party_a_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('party_b_id')->constrained('users')->onDelete('cascade');
            $table->float('cash_top_up_amount')->default(0);
            $table->boolean('party_a_signed')->default(false);
            $table->boolean('party_b_signed')->default(false);
            $table->enum('status', ['pending', 'locked', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        Schema::create('swap_agreement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swap_agreement_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['swap_agreement_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swap_agreement_items');
        Schema::dropIfExists('swap_agreements');
    }
};