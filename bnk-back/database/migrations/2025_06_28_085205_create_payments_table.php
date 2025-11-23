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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payout_id')->default(1)->constrained('payouts');
            $table->foreignUuid('initiative_id')->constrained('initiatives');
            $table->boolean('map_activated')->default(false);
            $table->unsignedTinyInteger('rental_time')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('provider')->default(0); //0-yoomoney
            $table->decimal('amount', 15, 2)->default(0);
            $table->foreignId('profile_id')->constrained('profiles');
            $table->timestamps();
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
