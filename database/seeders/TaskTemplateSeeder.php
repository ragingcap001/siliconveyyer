<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskTemplateSeeder extends Seeder
{
    /**
     * Mail / sms / push templates the task workflow notifies with.
     *
     * The notify helpers (NotifyTrait) look templates up by `code` and only send
     * when the row exists AND status is truthy, so these have to be seeded.
     */
    public function run()
    {
        $now = now();

        $shortCodes = json_encode([
            '[[full_name]]',
            '[[task_name]]',
            '[[pay_amount]]',
            '[[status]]',
            '[[message]]',
            '[[site_title]]',
            '[[site_url]]',
        ]);

        // ============================== email ==============================
        $emails = [
            'task_approved' => [
                'name' => 'Task Approved',
                'title' => 'Task Approved',
                'subject' => 'Your task submission was approved',
                'salutation' => 'Hi [[full_name]],',
                'message_body' => 'Great news! Your submission for <b>[[task_name]]</b> was approved and <b>[[pay_amount]]</b> has been credited to your wallet.<br /><br /><b>Note from the reviewer:</b> [[message]]',
                'button_level' => 'View My Tasks',
            ],
            'task_rejected' => [
                'name' => 'Task Rejected',
                'title' => 'Task Rejected',
                'subject' => 'Your task submission needs another look',
                'salutation' => 'Hi [[full_name]],',
                'message_body' => 'Unfortunately your submission for <b>[[task_name]]</b> was not accepted, so <b>[[pay_amount]]</b> has not been credited.<br /><br /><b>Reason:</b> [[message]]<br /><br />A rejection does not use up one of your attempts, so you can correct the work and submit again while slots remain.',
                'button_level' => 'View My Tasks',
            ],
        ];

        foreach ($emails as $code => $row) {
            DB::table('email_templates')->updateOrInsert(
                ['code' => $code],
                $row + [
                    'for' => 'User',
                    'banner' => null,
                    'button_link' => '[[site_url]]/user/task/history',
                    'footer_status' => 1,
                    'footer_body' => 'Regards,<br />[[site_title]]',
                    'bottom_status' => 0,
                    'bottom_title' => null,
                    'bottom_body' => null,
                    'short_codes' => $shortCodes,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        // ============================== sms ==============================
        $sms = [
            'task_approved' => 'Hi [[full_name]], your submission for [[task_name]] was approved. [[pay_amount]] has been credited to your [[site_title]] wallet.',
            'task_rejected' => 'Hi [[full_name]], your submission for [[task_name]] was not accepted. Reason: [[message]]. You may correct it and submit again.',
        ];

        foreach ($sms as $code => $body) {
            DB::table('sms_templates')->updateOrInsert(
                ['code' => $code],
                [
                    'name' => $code === 'task_approved' ? 'Task Approved' : 'Task Rejected',
                    'for' => 'User',
                    'message_body' => $body,
                    'short_codes' => $shortCodes,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        // ============================== push ==============================
        $pushes = [
            'task_approved' => [
                'for' => 'User',
                'name' => 'Task Approved',
                'title' => 'Task approved',
                'message_body' => 'Your submission for [[task_name]] was approved. [[pay_amount]] is now in your wallet.',
            ],
            'task_rejected' => [
                'for' => 'User',
                'name' => 'Task Rejected',
                'title' => 'Task rejected',
                'message_body' => 'Your submission for [[task_name]] was not accepted. Reason: [[message]]',
            ],
            // tells the admin a worker has handed in proof to review
            'task_submitted' => [
                'for' => 'Admin',
                'name' => 'New Task Proof',
                'title' => 'Proof awaiting review',
                'message_body' => '[[full_name]] submitted proof for [[task_name]] and is waiting for review.',
            ],
        ];

        foreach ($pushes as $code => $row) {
            DB::table('push_notification_templates')->updateOrInsert(
                ['code' => $code],
                $row + [
                    'icon' => null,
                    'short_codes' => $shortCodes,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
