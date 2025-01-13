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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('phone')->nullable();
            $table->boolean('different_ledger')->default(false);
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('governorate_id')->nullable()->constrained()->nullOnDelete();
            $table->string('receipt_in')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('account_client', function (Blueprint $table) {
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->string('role')->nullable();
            $table->boolean('subscribe')->default(true);
            $table->timestamps();

            $table->primary(['client_id', 'account_id']);
        });



        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('phone')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('business_delegate', function (Blueprint $table) {
            $table->foreignId('delegate_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('role')->nullable();
            $table->unsignedInteger('fare')->nullable();
            $table->timestamps();

            $table->primary(['delegate_id', 'business_id']);
        });

        Schema::create('business_governorate', function (Blueprint $table) {
            $table->foreignId('governorate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->primary(['governorate_id', 'business_id']);
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
