<?php

namespace App\Controllers;

defined('APPLICATION_DIR') or exit('No direct Accesss here !');

use Core\Controller;
use Core\Libs\Images\Image;
use Core\Libs\Request;
use Core\Libs\Support\Facades\Log;

class ImageManipulation extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function resizeImage(Request $request, Image $image)
    {
        $path = PUBLIC_DIR . 'uploads/thumbs';
        $file = $request->file();
        $tmp_name = $file->getFile('avatar')['tmp_name'];
        $img = $image->get($tmp_name);
        $img->resize(150)
            ->withPreffix('thumb_')
            ->save($path . "/image.jpg");

        dd($tmp_name);

    }

    public function uploadForm(Request $request)
    {
        Log::info('The Facade logging chanel');
        return view('blade.upload');
    }
}
