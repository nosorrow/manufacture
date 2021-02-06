<?php

namespace App\Controllers;

defined('APPLICATION_DIR') or exit('No direct Accesss here !');

use Core\Controller;
use Core\Libs\Images\Image;
use Core\Libs\Request;

class ImageManipulation extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function resizeImage(Request $request, Image $image)
    {
        /*$file = PUBLIC_DIR . 'uploads/image.jpg';
        $newpath = 'images/thumbs';
        $img = $image->get($file);
        $x = $img->resize(350,350); header('Content-Type: image/jpeg');
        echo $img->buffering();
        die;
        $img->resize(350,350)->withPreffix('thumb_')->move($newpath);
        $img->resize(450,450)->withPreffix('new_')->save('new/image.png');*/

        $path = PUBLIC_DIR . 'uploads/thumbs';

        $file = $request->file();
        //  dd($file->getFile('avatar'));
        $tmp_name = $file->getFile('avatar')['tmp_name'];
        $img = $image->get($tmp_name);
        $img->resize(350, 350)->withPreffix('thumb_')->save($path . "/image.jpg");
        dd($tmp_name);

    }

    public function uploadForm()
    {
        return view('blade.upload');
    }
}
