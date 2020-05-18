---
title: Image Manipulation
layout: layout.hbs
---
Image Manipulation
--------

Оразмеряване на изображения

```php
<?php
    
    $file = 'uploads/pizza.jpg';
    $newpath = 'images/thumbs';
    $image = new \Core\Libs\Images\Image();
    
    // Файлът който ще манипулираме
    $img = $image->get($file);
    
    // Оразмерява и презаписва изображението с resize(int width, int $height)
    $img->resize(450,450)->save();
    
    // Оразмерява пропорционално
    $img->resize(null,450)->save();
    $img->resize(450, null)->save();

    // Записва с ново име
    $img->resize(450,450)->save('new/image.jpg');
    
    // Записва манипулирания файл с префикс в името
    $img->resize(450,450)->withPreffix('new_')->save();
     
    /* 
     * Създава оразмерено изображение в нова директория
     * със същото име 
     */
    $img->resize(350,350)->move($newpath);
    $img->resize(450,450)->withPreffix('thumd_')->move($newpath);
    
    // Качество (quality) на изобр. quality(int percent) !!! САМО image/jpg
    $img->quality(50)->withPreffix('quality_')->save();
    
    // Pixel ефект
    $img->pixelate(10)->save();
    
    // Chain
    $image->get($file)->resize(300,300)
          ->pixelate(10)
          ->withPreffix('pixelate_')
          ->save();
```

Как да го направим с контролера на мaнифактурата:

```php
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

    public function Resize(Image $image)
    {
        $file = PUBLIC_DIR . 'uploads/pizza.png';
        $image->get($file)->resize(300,300)
                  ->pixelate(10)
                  ->withPreffix('pix_')
                  ->save();
        dump($image->imageinfo());        
    }
}
```
