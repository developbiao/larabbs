<?php


namespace App\Handlers;


class ImageUploadHandler
{
    protected $allow_ext = ['png', 'jpg', 'gif', 'jpeg'];

    public function save($file, $folder, $file_prefix)
    {
        // construct file folder rule, example: uploads/images/avatars/201904/30/
        $folder_name = "uploads/images/$folder/" . date("Ym", time()) . '/' . date("d", time())  . '/';

        // file physical save path, `pubic_path()` is `public` physical folder path
        $upload_path = public_path() . '/' . $folder_name;

        // get file extension
        $extension = strtolower( $file->getClientOriginalExtension() ) ?: 'png';

        // generate file name
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        // check images if not allow extension stop it
        if( !in_array($extension, $this->allow_ext) )
        {
            return false;
        }
        // move image to target path
        $file->move($upload_path, $filename);

        return [
            'path' => config('app_url') . "/$folder_name/$filename"
        ];

    }

}