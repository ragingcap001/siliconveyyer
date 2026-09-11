<?php

namespace App\Enums;

enum TaskProofType: string
{
    case Text = 'text';
    case Link = 'link';
    case Screenshot = 'screenshot';
    case File = 'file';

    public function label(): string
    {
        return match ($this) {
            self::Text => 'Written answer',
            self::Link => 'URL / link',
            self::Screenshot => 'Screenshot upload',
            self::File => 'File upload',
        };
    }

    /**
     * Human hint shown to the worker on the submit form.
     */
    public function hint(): string
    {
        return match ($this) {
            self::Text => 'Describe what you did in the box provided.',
            self::Link => 'Paste the URL that proves you completed the task.',
            self::Screenshot => 'Upload a screenshot showing the completed task.',
            self::File => 'Upload a file proving the task was completed.',
        };
    }
}
