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
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('attempt')->default(1);

            // the proof, in whichever shape the task asked for
            $table->longText('proof_text')->nullable();
            $table->string('proof_link')->nullable();
            $table->string('proof_file')->nullable();

            // payout destination chosen at claim time
            $table->unsignedBigInteger('payout_method_id')->nullable();
            $table->unsignedBigInteger('withdraw_account_id')->nullable();

            // reward snapshotted at claim time so later edits cannot alter history
            $table->double('pay_amount', 16, 8)->default(0);

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->unique(['task_id', 'user_id', 'attempt'], 'task_user_attempt_unique');
            $table->index(['user_id', 'status']);
            $table->index(['task_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_submissions');
    }
};
