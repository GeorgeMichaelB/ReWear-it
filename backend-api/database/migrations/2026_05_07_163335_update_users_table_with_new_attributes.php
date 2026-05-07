<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->float('trust_score')->default(0.0)->after('password');
            $table->integer('eco_credits')->default(0)->after('trust_score');
            $table->string('preferred_currency', 3)->default('USD')->after('eco_credits');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['trust_score', 'eco_credits', 'preferred_currency']);
        });
    }
};