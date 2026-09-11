<?php

namespace App\Traits;

use Str;

trait ImageUpload
{
    protected $defaultAvatar = 'frontend/images/user.png';

    public function imageUploadTrait($query, $old = null): string // Taking input image as parameter
    {

        $allowExt = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
        $ext = strtolower($query->getClientOriginalExtension());

        if ($query->getSize() > 5100000) {
            abort('406', 'max file size:5MB ');
        }

        if (!in_array($ext, $allowExt)) {
            abort('406', 'only allow : jpeg, png, jpg, gif, svg');
        }

        if ($old != null && $old != $this->defaultAvatar) {
            self::delete($old);
        }
        $image_name = Str::random(20);
        $image_full_name = $image_name . '.' . $ext;
        $upload_path = 'assets/global/images/';    //Creating Sub directory in Assets folder to put image
        $image_url = $upload_path . $image_full_name;
        $success = $query->move($upload_path, $image_full_name);

        return str_replace('assets/', '', $image_url); // Just return image
    }

    /**
     * Generic file upload used for task proof submissions. Unlike
     * imageUploadTrait() this accepts documents as well as images.
     *
     * @return string path relative to the assets directory
     */
    public function fileUploadTrait($query, array $allowExt = null, $old = null): string
    {
        $allowExt = $allowExt ?: ['jpeg', 'png', 'jpg', 'gif', 'svg', 'pdf', 'doc', 'docx', 'txt', 'zip'];

        $ext = strtolower($query->getClientOriginalExtension());

        if ($query->getSize() > 5100000) {
            abort('406', 'max file size:5MB');
        }

        if (! in_array($ext, $allowExt)) {
            abort('406', 'only allow : ' . implode(', ', $allowExt));
        }

        if ($old != null && $old != $this->defaultAvatar) {
            self::delete($old);
        }

        $fileName = Str::random(20) . '.' . $ext;
        $uploadPath = 'assets/global/images/';
        $query->move($uploadPath, $fileName);

        return str_replace('assets/', '', $uploadPath . $fileName);
    }

    protected function delete($path)
    {
        if (file_exists('assets/' . $path)) {
            @unlink('assets/' . $path);
        }
    }
}
