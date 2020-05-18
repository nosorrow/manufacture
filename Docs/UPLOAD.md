---
title: Upload files
layout: layout.hbs
---
Upload files
-------------

```php
<?php
use Core\Libs\Files\Upload;
use Core\Libs\Files\ResponseFactory;

$uploader = app(Upload::class);

$uploader->max_size = 2;
$uploader->max_files = 2;
$uploader->preffix = date('d_M_Y_', time());
$uploader->filename_length = 6;
$uploader->file_name = 'random';
$uploader->overwrite = false;

$init = [
    'max_files'=>1, 
    'directory'=>'uploads/',
    'max_size'=>2, // in MB
    'file_name'=>'random', // or string
    'filename_length'=>8,  // random name is 8 symbols
    'overwrite'=>false,
    'allowed_types' =>[] // All mime types is allowed

];

$uploader->init($init);

$upload = $uploader->upload('img');
$response = ResponseFactory::makeResponse($upload, 'html');

dump($response);

if ($response->countErrors() > 0) {
  //  ... do some error msg
    dump($upload->hasError());
    dump($response->errors());

} else {
   // ... do some
    dump($upload->response);
    echo ($upload->getResponse());
}

```
Пример:

```php

<?php

namespace App\Controllers;

use Core\Controller;
use Core\Libs\Request;

class PostController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }
    
    public function upoad(Request $request)
    {
        $opt = [
                'file_name'=>md5(time()),
                'preffix'=>'plamen_',
                'overwrite'=>false,
                'allowed_types' =>['gif', 'jpg', 'png']
            ];
            
        $upload = $request->file()->upload('file', $opt);
    
        if ($upload->failed()) {
            $request->validation()->errors->file = $upload->getError(true);
        }

        if ($request->validation()->run() == false) {

            redirect()->back();

        }
    }
    
}
```