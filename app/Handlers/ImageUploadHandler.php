<?php


namespace App\Handlers;

use Intervention\Image\ImageManagerStatic as Image;

class ImageUploadHandler
{
    protected $allow_ext = ['png', 'jpg', 'gif', 'jpeg'];

    public function save($file, $folder, $file_prefix, $max_with = false)
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

        // if limited image width cut it
        if( $max_with && $extension != 'gif' )
        {
            $this->reduceSize($upload_path . '/' . $filename, $max_with);
        }

        return [
            'path' => config('app_url') . "/$folder_name/$filename"
        ];

    }

    public function reduceSize($file_path, $max_width)
    {
        // first instance, parameter file physical path
        $image = Image::make($file_path);

        // resize operator
        $image->resize($max_width, null, function($constraint){
            // set max width is $max_width, height equal scaling
            $constraint->aspectRatio();

            // prevent cut image size change
            $constraint->upsize();

        });

        // save iamge
        $image->save();

    }

}