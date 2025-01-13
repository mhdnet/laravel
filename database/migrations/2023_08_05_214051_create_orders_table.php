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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();

            $table->unsignedBigInteger('no')->nullable();
            $table->unsignedBigInteger('governorate_id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->string('address')->nullable();
            $table->string('name')->nullable(); // customer name
            $table->string('notes', 500)->nullable();


            /* value */
            $table->unsignedMediumInteger('value')->nullable();
            $table->unsignedSmallInteger('fare');
            $table->unsignedSmallInteger('tax')->default(0);
            $table->unsignedSmallInteger('cost')->default(0);

            /* Status */
            $table->string('status')->default(\App\Constants\OrderStatus::Created);
            $table->unsignedTinyInteger('delay_count')->default(0);
            $table->string('temp_status')->nullable();

            $table->foreignId('roster_id')->nullable()->constrained('statements')->nullOnDelete();
            $table->foreignId('statement_id')->nullable()->constrained('statements')->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('statements')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
