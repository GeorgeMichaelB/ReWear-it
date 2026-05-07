<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('style_boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->integer('follower_count')->default(0);
            $table->timestamps();
        });

        Schema::create('style_board_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('style_board_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['style_board_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('style_board_items');
        Schema::dropIfExists('style_boards');
    }
};