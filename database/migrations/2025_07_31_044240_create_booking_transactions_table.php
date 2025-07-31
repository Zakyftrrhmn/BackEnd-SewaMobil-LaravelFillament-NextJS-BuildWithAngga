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
        Schema::create('booking_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('proof');
            $table->string('booking_trx_id');

            $table->date('started_at');

            $table->unsignedBigInteger('total_amount');
            $table->unsignedBigInteger('duration');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('insurance');
            $table->unsignedBigInteger('total_max_amount');

            $table->boolean('is_paid');

            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('alpina_store_id')->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_transactions');
    }
};
