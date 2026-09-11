<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The homepage renders `@include('frontend::home.include.__'.$code)` for every
     * active landing section. The "schema" (investment plans) and "calculation"
     * (profit calculator) sections belong to the removed investment module, so both
     * the rows and their content are dropped to keep the homepage rendering.
     *
     * @return void
     */
    public function up()
    {
        $sectionIds = DB::table('landing_pages')
            ->whereIn('code', ['schema', 'calculation'])
            ->pluck('id');

        if ($sectionIds->isNotEmpty()) {
            DB::table('landing_contents')->whereIn('landing_page_id', $sectionIds)->delete();
        }

        DB::table('landing_pages')->whereIn('code', ['schema', 'calculation'])->delete();
    }

    /**
     * Reverse the migrations.
     *
     * The sections are recreated as disabled rows so the site keeps rendering;
     * their previous content is not recoverable.
     *
     * @return void
     */
    public function down()
    {
        foreach (['schema', 'calculation'] as $index => $code) {
            DB::table('landing_pages')->insert([
                'name' => ucfirst($code) . ' Section',
                'code' => $code,
                'data' => json_encode([]),
                'status' => 0,
                'short' => 100 + $index,
                'locale' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
