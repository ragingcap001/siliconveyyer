<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ranks double as the worker "level" used for task eligibility. Existing ranks
     * keep their relative order by seeding level from the primary key.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rankings', function (Blueprint $table) {
            $table->unsignedInteger('level')->default(1)->after('id');
            $table->unsignedInteger('minimum_tasks')->default(0);
            $table->double('minimum_task_earning', 16, 8)->default(0);

            // the investment thresholds the task ladder replaced
            if (Schema::hasColumn('rankings', 'minimum_invest')) {
                $table->dropColumn('minimum_invest');
            }

            if (Schema::hasColumn('rankings', 'minimum_referral_invest')) {
                $table->dropColumn('minimum_referral_invest');
            }
        });

        DB::statement('UPDATE `rankings` SET `level` = `id` WHERE `level` IS NULL OR `level` = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rankings', function (Blueprint $table) {
            $table->dropColumn(['level', 'minimum_tasks', 'minimum_task_earning']);
            $table->integer('minimum_invest')->default(0);
            $table->integer('minimum_referral_invest')->default(0);
        });
    }
};
