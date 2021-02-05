<?php

namespace App\Controllers;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use Core\Controller;
use Core\Libs\Images\Image;

class ImageManipulation extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function resizeImage(Image $image)
    {
        $file = PUBLIC_DIR . 'uploads/image.jpg';
        $newpath = 'images/thumbs';
        $img = $image->get($file);
        $x = $img->resize(350,350); header('Content-Type: image/jpeg'); echo $img->buffering();die;
        $img->resize(350,350)->withPreffix('thumb_')->move($newpath);
        $img->resize(450,450)->withPreffix('new_')->save('new/image.png');
        dump($img->imageinfo());

    }

    public function test()
    {
        
    }
}
