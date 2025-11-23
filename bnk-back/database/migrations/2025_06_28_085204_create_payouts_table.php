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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('profiles')->cascadeOnDelete();
            $table->unsignedTinyInteger('status')->default(0); //0 - сбор денег на счету 1- подана заявка на вывод; 2-заявка выгружена в банк; 3 - заявка исполнена 4- заявка отменена 5- ошибка платежа
            $table->foreignId('payment_detail_id')->nullable()->constrained('payment_details');
            $table->decimal('amount',10,2)->default(0);
            $table->unsignedTinyInteger('vote_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
