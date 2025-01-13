<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table = config('audit.drivers.database.table', 'audits');

        Schema::connection($connection)->create('audit_creators', function (Blueprint $table) {
            $morphPrefix = config('audit.user.morph_prefix', 'user');
            $table->id();
            $table->string($morphPrefix . '_type')->nullable();
            $table->unsignedBigInteger($morphPrefix . '_id')->nullable();
            $table->text('url')->nullable();
            $table->ipAddress()->nullable();
            $table->string('user_agent', 1023)->nullable();
            $table->index([$morphPrefix . '_id', $morphPrefix . '_type']);
        });

        Schema::connection($connection)->create($table, function (Blueprint $table) {
            $table->id();
            $table->string('event');
            $table->morphs('auditable');
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
//            $table->string('tags')->nullable();

            $table->foreignId('creator_id')->constrained('audit_creators')->cascadeOnDelete();
//            $table->morphs('user');
//            $table->text('url')->nullable();
//            $table->ipAddress()->nullable();
//            $table->string('user_agent', 1023)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
        Schema::dropIfExists('audit_creators');
    }
};
