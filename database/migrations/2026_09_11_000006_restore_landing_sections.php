<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The homepage is assembled from landing_pages rows, one per section, and the
     * admin edits each one through backend.page.section.{code}. Two sections
     * ("schema" and "calculation") were investment-specific and their partials
     * were removed during the task-platform conversion, so an earlier migration
     * deleted their rows.
     *
     * That took the sections out of the admin's Landing Page menu entirely, so
     * they could no longer be edited. Both are reinstated here as ordinary
     * content sections whose headings and images the admin controls; their
     * partials now render task content instead of investment content.
     *
     * Idempotent: only rows that are genuinely missing are created, so this is
     * safe whether or not the deletion migration ever ran.
     *
     * @return void
     */
    public function up()
    {
        $defaults = [
            'schema' => [
                'name' => 'Featured Tasks Section',
                'data' => [
                    'title_small' => 'Available Tasks',
                    'title_big' => 'Pick a task, complete it, and get paid.',
                    'left_top_img' => null,
                ],
                'short' => 3,
            ],
            'calculation' => [
                'name' => 'Intro Section',
                'data' => [
                    'calculation_title_small' => 'How It Works',
                    'calculation_title_big' => 'See how easy it is to earn with us.',
                    'intro_video' => null,
                    'calculation_left_img' => null,
                ],
                'short' => 4,
            ],
        ];

        // every locale already represented in landing_pages
        $locales = DB::table('landing_pages')->distinct()->pluck('locale');

        if ($locales->isEmpty()) {
            $locales = collect(['en']);
        }

        foreach ($defaults as $code => $section) {
            $existing = DB::table('landing_pages')
                ->where('code', $code)
                ->pluck('locale')
                ->all();

            foreach ($locales as $locale) {
                if (in_array($locale, $existing, true)) {
                    continue;
                }

                DB::table('landing_pages')->insert([
                    'name' => $section['name'],
                    'code' => $code,
                    'data' => json_encode($section['data']),
                    'status' => 0, // start hidden; the admin turns it on from the editor
                    'short' => $section['short'],
                    'locale' => $locale,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('landing_pages')->whereIn('code', ['schema', 'calculation'])->delete();
    }
};
