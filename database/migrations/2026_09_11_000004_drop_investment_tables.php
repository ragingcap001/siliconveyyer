<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The investment (HYIP) module is replaced by the task platform. Wallets,
     * deposits, withdrawals and payout methods are intentionally preserved.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('invests');
        Schema::dropIfExists('schemas');
        Schema::dropIfExists('schedules');
    }

    /**
     * Reverse the migrations.
     *
     * Recreates the tables so the migration is reversible; the removed Eloquent
     * models would need restoring to use them again.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('time');
            $table->timestamps();
        });

        Schema::create('schemas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->double('min_amount', 16, 8)->default(0);
            $table->double('max_amount', 16, 8)->default(0);
            $table->double('fixed_amount', 16, 8)->default(0);
            $table->boolean('capital_back')->default(false);
            $table->double('return_interest', 16, 8)->default(0);
            $table->string('interest_type')->default('percentage');
            $table->unsignedBigInteger('return_period')->nullable();
            $table->string('return_type')->default('period');
            $table->integer('number_of_period')->default(0);
            $table->json('off_days')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('invests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('schema_id');
            $table->double('invest_amount', 16, 8)->default(0);
            $table->double('interest', 16, 8)->default(0);
            $table->string('interest_type')->default('percentage');
            $table->string('return_type')->default('period');
            $table->integer('number_of_period')->default(0);
            $table->integer('period_hours')->default(0);
            $table->boolean('capital_back')->default(false);
            $table->timestamp('next_profit_time')->nullable();
            $table->timestamp('last_profit_time')->nullable();
            $table->integer('already_return_profit')->default(0);
            $table->double('total_profit_amount', 16, 8)->default(0);
            $table->string('status')->default('ongoing');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->timestamps();
        });
    }
};
