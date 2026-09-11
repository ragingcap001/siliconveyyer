<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->nullable();
            $table->longText('description');
            $table->longText('instructions')->nullable();

            // reward
            $table->double('pay_amount', 16, 8)->default(0);

            // how the worker proves completion
            $table->enum('proof_type', ['text', 'link', 'screenshot', 'file'])->default('text');
            $table->boolean('proof_required')->default(true);

            // capacity. total_slots = 0 means unlimited takers.
            $table->unsignedInteger('total_slots')->default(0);
            $table->unsignedInteger('filled_slots')->default(0);
            $table->unsignedInteger('per_user_limit')->default(1);

            // who the task is recommended for
            $table->unsignedInteger('min_level')->default(1);
            $table->boolean('require_kyc')->default(false);
            $table->double('min_balance', 16, 8)->default(0);

            // payout options: null = every enabled method
            $table->json('payout_method_ids')->nullable();

            $table->enum('status', ['draft', 'active', 'paused', 'closed'])->default('draft');
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
